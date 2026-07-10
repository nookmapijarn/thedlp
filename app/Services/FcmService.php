<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FcmService
{
    private static function getAccessToken($credentialsPath)
    {
        if (!file_exists($credentialsPath)) {
            logger()->error("FCM Service Account file not found at: {$credentialsPath}");
            return null;
        }

        // Cache the token to prevent redundant requests
        $cacheKey = 'fcm_access_token';
        if (cache()->has($cacheKey)) {
            return cache()->get($cacheKey);
        }

        $credentials = json_decode(file_get_contents($credentialsPath), true);
        if (!$credentials) {
            logger()->error("Invalid Service Account file format.");
            return null;
        }

        $privateKey = $credentials['private_key'];
        $clientEmail = $credentials['client_email'];

        $header = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
        $now = time();
        $payload = json_encode([
            'iss' => $clientEmail,
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $now,
            'exp' => $now + 3600
        ]);

        $base64UrlHeader = self::base64UrlEncode($header);
        $base64UrlPayload = self::base64UrlEncode($payload);

        $signature = '';
        $success = openssl_sign(
            $base64UrlHeader . "." . $base64UrlPayload,
            $signature,
            $privateKey,
            OPENSSL_ALGO_SHA256
        );

        if (!$success) {
            logger()->error("FCM OAuth token generation failed: openssl_sign failed.");
            return null;
        }

        $base64UrlSignature = self::base64UrlEncode($signature);
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        $response = Http::withoutVerifying()->asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt
        ]);

        if ($response->failed()) {
            logger()->error("FCM Google OAuth2 request failed: " . $response->body());
            return null;
        }

        $accessToken = $response->json()['access_token'];
        
        // Cache token for 55 minutes (3300 seconds)
        cache()->put($cacheKey, $accessToken, 3300);

        return $accessToken;
    }

    private static function base64UrlEncode($data)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    /**
     * Send Web Push notification to user devices using Google FCM v1.
     */
    public static function sendPushNotification($userId, $title, $body, $data = [])
    {
        $credentialsPath = config('services.firebase.credentials_path');
        
        if ($credentialsPath && !file_exists($credentialsPath)) {
            $resolvedPath = base_path($credentialsPath);
            if (file_exists($resolvedPath)) {
                $credentialsPath = $resolvedPath;
            }
        }

        if (empty($credentialsPath) || !file_exists($credentialsPath)) {
            $credentialsPath = base_path('olis-b1bc2-firebase-adminsdk-fbsvc-057ba63d59.json');
        }
        
        $projectId = config('services.firebase.project_id');

        if (empty($projectId)) {
            logger()->info("FCM Project ID is not configured. Skipping push notification.");
            return false;
        }

        $accessToken = self::getAccessToken($credentialsPath);
        if (!$accessToken) {
            return false;
        }

        // Get all active FCM tokens for this user
        $tokens = \App\Models\UserFcmToken::where('user_id', $userId)->pluck('token')->toArray();
        if (empty($tokens)) {
            return false;
        }

        $successCount = 0;
        foreach ($tokens as $token) {
            $payload = [
                'message' => [
                    'token' => $token,
                    'notification' => [
                        'title' => $title,
                        'body' => $body
                    ],
                    'data' => array_map('strval', $data) // FCM requires data values to be strings
                ]
            ];

            $response = Http::withoutVerifying()->withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json'
            ])->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", $payload);

            if ($response->successful()) {
                $successCount++;
            } else {
                logger()->warning("FCM failed for token: {$token}. Error: " . $response->body());
                
                // If token is invalid/expired, clean it up from DB
                $resJson = $response->json();
                if (isset($resJson['error']['status']) && 
                    ($resJson['error']['status'] === 'UNREGISTERED' || $resJson['error']['message'] === 'The registration token is not a valid FCM registration token')) {
                    \App\Models\UserFcmToken::where('token', $token)->delete();
                }
            }
        }

        return $successCount > 0;
    }
}
