<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    protected string $projectId;
    protected string $credentialsPath;

    public function __construct()
    {
        $this->projectId = config('services.firebase.project_id', 'al-quran-7a6a7');
        $this->credentialsPath = storage_path('app/firebase-service-account.json');
    }

    /**
     * Send push notification via FCM v1 HTTP API.
     */
    public function sendPush(string $target, string $title, string $body, array $extraData = []): array
    {
        try {
            $accessToken = $this->getAccessToken();
            if (!$accessToken) {
                return [
                    'success' => false,
                    'error' => 'Firebase Service Account credentials file missing or invalid. Please place firebase-service-account.json in storage/app/ directory.'
                ];
            }

            $endpoint = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";
            $isTopic = !str_starts_with($target, 'fcm_') && strlen($target) < 100;

            $messageData = array_map('strval', array_merge([
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'title' => $title,
                'body' => $body,
                'timestamp' => now()->toIso8601String(),
            ], $extraData));

            $bannerUrl = !empty($extraData['banner_image']) ? $extraData['banner_image'] : (!empty($extraData['image']) ? $extraData['image'] : asset('favicon.png'));

            $message = [
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                    'image' => $bannerUrl,
                ],
                'data' => $messageData,
                'android' => [
                    'priority' => 'HIGH',
                    'notification' => [
                        'channel_id' => 'quran_notification_channel',
                        'sound' => 'default',
                        'default_vibrate_timings' => true,
                        'image' => $bannerUrl,
                    ],
                ],
            ];

            if ($isTopic) {
                $message['topic'] = $target;
            } else {
                $message['token'] = $target;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post($endpoint, ['message' => $message]);

            if ($response->successful()) {
                Log::info('FCM v1 Push Sent Successfully', ['target' => $target, 'response' => $response->json()]);
                return ['success' => true, 'data' => $response->json()];
            } else {
                Log::error('FCM v1 Push Failed', ['status' => $response->status(), 'body' => $response->body()]);
                return ['success' => false, 'error' => 'FCM v1 Error (' . $response->status() . '): ' . $response->body()];
            }
        } catch (\Exception $e) {
            Log::error('FCM Service Exception: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Generate Google OAuth 2.0 Access Token using Service Account JSON.
     */
    protected function getAccessToken(): ?string
    {
        if (!file_exists($this->credentialsPath)) {
            return null;
        }

        $json = json_decode(file_get_contents($this->credentialsPath), true);
        if (!$json || !isset($json['private_key'], $json['client_email'])) {
            return null;
        }

        $now = time();
        $header = ['alg' => 'RS256', 'typ' => 'JWT'];
        $claims = [
            'iss' => $json['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now,
        ];

        $encodedHeader = $this->base64UrlEncode(json_encode($header));
        $encodedClaims = $this->base64UrlEncode(json_encode($claims));

        $signatureInput = $encodedHeader . '.' . $encodedClaims;
        $binarySignature = '';

        $privateKey = openssl_pkey_get_private($json['private_key']);
        if (!$privateKey) {
            return null;
        }

        openssl_sign($signatureInput, $binarySignature, $privateKey, 'SHA256');
        $encodedSignature = $this->base64UrlEncode($binarySignature);

        $jwt = $signatureInput . '.' . $encodedSignature;

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]);

        if ($response->successful()) {
            return $response->json('access_token');
        }

        return null;
    }

    private function base64UrlEncode(string $data): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }
}
