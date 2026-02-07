<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\SocialPostController;
use App\Services\InstagramService;

class SyncSocialPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'social:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync recent social media posts to the database';

    /**
     * Execute the console command.
     */
    public function handle(InstagramService $instagram)
    {
        $this->info('Starting social media sync...');
        
        $controller = new SocialPostController();
        $response = $controller->sync($instagram);
        
        $data = $response->getData(true);
        
        if (isset($data['error'])) {
            $this->error('Sync failed: ' . $data['error']);
            return Command::FAILURE;
        }

        $this->info($data['message']);
        return Command::SUCCESS;
    }
}
