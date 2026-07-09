<?php

// Bootstrap Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\FcmService;
use App\Models\UserFcmToken;

$userId = 256; // Check user 256
$title = "ทดสอบการแจ้งเตือน";
$body = "ยินดีด้วย! ระบบแจ้งเตือน FCM ทำงานได้อย่างถูกต้อง";

echo "Retrieving tokens for user {$userId}...\n";
$tokens = UserFcmToken::where('user_id', $userId)->pluck('token')->toArray();
print_r($tokens);

if (empty($tokens)) {
    echo "No tokens found for user {$userId}!\n";
    exit;
}

echo "Sending push notification via FcmService...\n";
$credentialsPath = config('services.firebase.credentials_path');
$projectId = config('services.firebase.project_id');

echo "Credentials Path: {$credentialsPath}\n";
echo "Project ID: {$projectId}\n";

// Run the sending logic and capture details
$response = FcmService::sendPushNotification($userId, $title, $body, ['ticket' => '1']);

if ($response) {
    echo "FCM Send reported success!\n";
} else {
    echo "FCM Send reported failure.\n";
}
