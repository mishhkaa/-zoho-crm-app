<?php
namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZohoAuthService
{
    public function getAccessToken()
    {
        $token = Cache::get('zoho_access_token');

        if (!$token) {
            Log::warning('🔄 Токен Zoho закінчився! Оновлюємо...');
            $token = $this->refreshAccessToken();
            Cache::put('zoho_access_token', $token, now()->addSeconds(3500));
        }

        Log::info('🔑 Використовується токен Zoho: ' . $token);
        return $token;
    }

    public function refreshAccessToken()
    {
        Log::warning('Запит нового токена Zoho!');

        $response = Http::asForm()->post(env('ZOHO_ACCOUNTS_URL') . '/oauth/v2/token', [
            'refresh_token' => env('ZOHO_REFRESH_TOKEN'),
            'client_id' => env('ZOHO_CLIENT_ID'),
            'client_secret' => env('ZOHO_CLIENT_SECRET'),
            'grant_type' => 'refresh_token',
        ])->json();

        if (isset($response['access_token'])) {
            Log::info('✅ Новий токен отримано: ' . $response['access_token']);
            return $response['access_token'];
        }

        Log::error('❌ ПОМИЛКА отримання нового токена Zoho: ' . json_encode($response));
        throw new \Exception('Не вдалося отримати токен доступу Zoho.');
    }
}
