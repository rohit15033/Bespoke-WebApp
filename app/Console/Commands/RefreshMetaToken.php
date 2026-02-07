<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

class RefreshMetaToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meta:refresh-token {short_token : The short-lived User Access Token from Graph API Explorer}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exchanges a short-lived User Token for a Long-Lived Page Access Token and updates .env';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $shortToken = $this->argument('short_token');
        $appId = config('services.instagram.app_id');
        $appSecret = config('services.instagram.app_secret');
        // $targetAccountId = config('services.instagram.account_id'); // Optional validation

        if (!$appId || !$appSecret) {
            $this->error('Missing FACEBOOK_APP_ID or FACEBOOK_APP_SECRET in .env');
            return 1;
        }

        $this->info('1. Exchanging Short-Lived User Token for Long-Lived User Token...');
        
        $exchangeUrl = "https://graph.facebook.com/v19.0/oauth/access_token";
        $response = Http::withoutVerifying()->get($exchangeUrl, [
            'grant_type' => 'fb_exchange_token',
            'client_id' => $appId,
            'client_secret' => $appSecret,
            'fb_exchange_token' => $shortToken,
        ]);

        if ($response->failed()) {
            $this->error('Failed to exchange token: ' . $response->body());
            return 1;
        }

        $longLivedUserToken = $response->json()['access_token'];
        $this->info('   Success! Long-Lived User Token obtained.');

        $this->info('2. Fetching Accounts (Pages) to get Page Access Token...');
        
        $accountsUrl = "https://graph.facebook.com/v19.0/me/accounts";
        $accountsResponse = Http::withoutVerifying()->get($accountsUrl, [
            'access_token' => $longLivedUserToken,
            'fields' => 'id,name,access_token,instagram_business_account',
        ]);

        if ($accountsResponse->failed()) {
            $this->error('Failed to fetch accounts: ' . $accountsResponse->body());
            return 1;
        }

        $accounts = $accountsResponse->json()['data'] ?? [];
        $targetPageToken = null;
        $targetPageName = null;

        $foundPages = [];
        foreach ($accounts as $account) {
            $foundPages[] = $account['name'];
            if (isset($account['instagram_business_account']['id'])) {
                $targetPageToken = $account['access_token'];
                $targetPageName = $account['name'];
                $igId = $account['instagram_business_account']['id'];
                
                $this->info("   Found Page: {$targetPageName} (IG ID: {$igId})");
                break;
            }
        }

        if (empty($accounts)) {
             $this->warn("   No Pages found. You likely didn't select your Facebook Page in the 'Generate Access Token' popup.");
        } else if (!$targetPageToken) {
             $this->warn("   Found Pages: " . implode(', ', $foundPages));
             $this->warn("   But none of them have an 'instagram_business_account' field visible to this token.");
        }

        if (!$targetPageToken) {
            $this->warn('No Page explicitly linked to an Instagram Business Account found in the response.');
            $this->info('Falling back to Long-Lived User Token (Valid for ~60 days).');
            
            // Fallback: Use the Long-Lived User Token
            $targetPageToken = $longLivedUserToken;
            $targetPageName = "User Token (Fallback)";
        }

        $this->info("3. updating .env with Token for '{$targetPageName}'...");

        $this->updateEnvFile('INSTAGRAM_ACCESS_TOKEN', $targetPageToken);

        $this->info('-------------------------------------------------------');
        $this->info('SUCCESS! Token updated. It should be valid indefinitely (unless password changes).');
        return 0;
    }

    protected function updateEnvFile($key, $value)
    {
        $path = base_path('.env');

        if (File::exists($path)) {
            $content = File::get($path);
            
            // Check if key exists
            if (strpos($content, "{$key}=") !== false) {
                // Replace existing
                $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
            } else {
                // Append
                $content .= "\n{$key}={$value}\n";
            }

            File::put($path, $content);
        }
    }
}
