<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ZohoAuthController extends Controller
{
    public function handleCallback(Request $request)
    {
        $authCode = $request->query('code');

        if (!$authCode) {
            return response()->json(['error' => 'Authorization code not found'], 400);
        }

        $response = Http::asForm()->post('https://accounts.zoho.eu/oauth/v2/token', [
            'client_id' => env('ZOHO_CLIENT_ID'),
            'client_secret' => env('ZOHO_CLIENT_SECRET'),
            'redirect_uri' => env('ZOHO_REDIRECT_URI'),
            'grant_type' => 'authorization_code',
            'code' => $authCode
        ]);

        $data = $response->json();

        if (isset($data['refresh_token'])) {
            // Збережемо refresh_token у .env (або у базу)
            file_put_contents(base_path('.env'), str_replace(
                'ZOHO_REFRESH_TOKEN=' . env('ZOHO_REFRESH_TOKEN'),
                'ZOHO_REFRESH_TOKEN=' . $data['refresh_token'],
                file_get_contents(base_path('.env'))
            ));

            return response()->json(['message' => 'Refresh token saved!', 'refresh_token' => $data['refresh_token']]);
        }

        return response()->json($data);
    }
    public static function getAccessToken()
    {
        // Перевіряємо, чи є токен у кеші
        if (Cache::has('zoho_access_token')) {
            return Cache::get('zoho_access_token');
        }

        // Якщо токена немає – оновлюємо через refresh_token
        $response = Http::asForm()->post(env('ZOHO_ACCOUNTS_URL') . '/oauth/v2/token', [
            'refresh_token' => env('ZOHO_REFRESH_TOKEN'),
            'client_id' => env('ZOHO_CLIENT_ID'),
            'client_secret' => env('ZOHO_CLIENT_SECRET'),
            'grant_type' => 'refresh_token',
        ]);

        $data = $response->json();

        if (isset($data['access_token'])) {
            // Зберігаємо новий токен у кеші (на 55 хвилин, щоб було запасних 5 хвилин перед спливанням)
            Cache::put('zoho_access_token', $data['access_token'], now()->addSeconds($data['expires_in'] - 300));
            return $data['access_token'];
        }

        throw new \Exception('Не вдалося отримати токен доступу Zoho.');
    }
}
