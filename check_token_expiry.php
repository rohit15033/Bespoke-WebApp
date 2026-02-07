<?php

$token = $argv[1] ?? null;

if (!$token) {
    // Read from .env
    $envContent = file_get_contents('.env');
    preg_match('/INSTAGRAM_ACCESS_TOKEN=(.+)/', $envContent, $matches);
    $token = trim($matches[1] ?? '');
}

if (!$token) {
    echo "No token found!\n";
    exit(1);
}

echo "Checking token type and expiration...\n\n";

$url = "https://graph.facebook.com/v19.0/debug_token?input_token={$token}&access_token={$token}";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

$output = curl_exec($ch);
curl_close($ch);

$json = json_decode($output, true);

if (isset($json['error'])) {
    echo "❌ Error: " . $json['error']['message'] . "\n";
    exit(1);
}

$data = $json['data'] ?? [];

echo "✅ Token Information:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Type: " . ($data['type'] ?? 'Unknown') . "\n";
echo "App: " . ($data['application'] ?? 'Unknown') . "\n";
echo "Valid: " . ($data['is_valid'] ? 'Yes ✅' : 'No ❌') . "\n";

if (isset($data['expires_at'])) {
    $expiresAt = $data['expires_at'];
    $expiryDate = date('Y-m-d H:i:s', $expiresAt);
    $daysRemaining = floor(($expiresAt - time()) / 86400);
    
    echo "Expires: {$expiryDate}\n";
    echo "Days Remaining: {$daysRemaining} days\n";
    
    if ($daysRemaining > 50) {
        echo "\n🎉 This is a LONG-LIVED token (~60 days)!\n";
    } else if ($daysRemaining > 1) {
        echo "\n✅ This is a long-lived token.\n";
    } else {
        echo "\n⚠️  This token expires very soon!\n";
    }
} else {
    echo "Expires: Never (No expiration) 🎉\n";
    echo "\n🎉 This is a PERMANENT token!\n";
}
