<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ZohoService
{
    private $accessToken;

    public function __construct()
    {
        $this->accessToken = $this->getAccessToken();
    }

    private function getAccessToken()
    {
        $response = Http::asForm()->post(env('ZOHO_ACCOUNTS_URL') . '/oauth/v2/token', [
            'refresh_token' => env('ZOHO_REFRESH_TOKEN'),
            'client_id' => env('ZOHO_CLIENT_ID'),
            'client_secret' => env('ZOHO_CLIENT_SECRET'),
            'grant_type' => 'refresh_token',
        ]);

        $data = $response->json();

        return $data['access_token'] ?? null;
    }

    public function createAccount($data)
    {
        $response = Http::withHeaders([
            'Authorization' => "Zoho-oauthtoken {$this->accessToken}",
            'Content-Type' => 'application/json'
        ])->post(env('ZOHO_API_BASE_URL') . "/crm/v2/Accounts", [
            'data' => [$data]
        ]);

        return $response->json();
    }

    public function createDeal($data)
    {
        $response = Http::withHeaders([
            'Authorization' => "Zoho-oauthtoken {$this->accessToken}",
            'Content-Type' => 'application/json'
        ])->post(env('ZOHO_API_BASE_URL') . "/crm/v2/Deals", [
            'data' => [$data]
        ]);

        return $response->json();
    }
}
