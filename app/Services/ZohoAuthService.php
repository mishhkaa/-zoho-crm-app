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
            Cache::put('zoho_access_token', $token, now()->addMinutes(55)); 
        }

        Log::info('🔑 Використовується токен Zoho: ' . $token);
        return $token;
    }

    public function refreshAccessToken()
    {
        Log::warning('Запит нового токена Zoho!');

        $response = Http::asForm()->post(config('zoho.accounts_url') . '/oauth/v2/token', [
            'refresh_token' => config('zoho.refresh_token'),
            'client_id' => config('zoho.client_id'),
            'client_secret' => config('zoho.client_secret'),
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
