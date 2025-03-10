<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function showDashboard()
    {
        $accessToken = env('ZOHO_ACCESS_TOKEN');

        // Отримуємо список акаунтів
        $accountsResponse = Http::withHeaders([
            'Authorization' => "Bearer $accessToken",
            'Content-Type' => 'application/json',
        ])->get(env('ZOHO_API_BASE_URL') . "/crm/v2/Accounts");

        // Отримуємо список угод
        $dealsResponse = Http::withHeaders([
            'Authorization' => "Bearer $accessToken",
            'Content-Type' => 'application/json',
        ])->get(env('ZOHO_API_BASE_URL') . "/crm/v2/Deals");

        return view('dashboard', [
            'accounts' => $accountsResponse->json()['data'] ?? [],
            'deals' => $dealsResponse->json()['data'] ?? []
        ]);
    }

}
