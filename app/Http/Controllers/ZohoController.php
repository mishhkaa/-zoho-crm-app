<?php

namespace App\Http\Controllers;

use App\Services\ZohoAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZohoController extends Controller
{
    protected $zohoAuthService;

    public function __construct(ZohoAuthService $zohoAuthService)
    {
        $this->zohoAuthService = $zohoAuthService;
    }

    /**
     * Отримати список акаунтів
     */
    public function getAccounts()
    {
        $token = $this->zohoAuthService->getAccessToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->get(env('ZOHO_API_BASE_URL') . '/crm/v2/Accounts');

        return response()->json($response->json());
    }

    /**
     * Отримати список угод
     */
    public function getDeals()
    {
        $token = $this->zohoAuthService->getAccessToken();
    
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->get(env('ZOHO_API_BASE_URL') . '/crm/v2/Deals');
    
        return response()->json($response->json());
    }

    /**
     * Створити акаунт
     */
    public function createAccount(Request $request)
    {
        $token = $this->zohoAuthService->getAccessToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->post(env('ZOHO_API_BASE_URL') . '/crm/v2/Accounts', [
            'data' => [[
                'Account_Name' => $request->Account_Name,
                'Website' => $request->Website,
                'Phone' => $request->Phone
            ]]
        ]);

        return response()->json($response->json());
    }

    /**
     * Створити угоду
     */
    public function createDeal(Request $request)
    {
        $token = $this->zohoAuthService->getAccessToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->post(env('ZOHO_API_BASE_URL') . '/crm/v2/Deals', [
            'data' => [[
                'Deal_Name' => $request->Deal_Name,
                'Stage' => $request->Stage,
                'Account_Id' => $request->Account_Id
            ]]
        ]);

        return response()->json($response->json());
    }

    /**
     * Оновити акаунт
     */
    public function updateAccount(Request $request, $id)
    {
        $token = $this->zohoAuthService->getAccessToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->put(env('ZOHO_API_BASE_URL') . "/crm/v2/Accounts/{$id}", [
            'data' => [[
                'Account_Name' => $request->Account_Name,
                'Website' => $request->Website,
                'Phone' => $request->Phone
            ]]
        ]);

        return response()->json($response->json());
    }

    /**
     * Оновити угоду
     */
    public function updateDeal(Request $request, $id)
    {
        $token = $this->zohoAuthService->getAccessToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->put(env('ZOHO_API_BASE_URL') . "/crm/v2/Deals/{$id}", [
            'data' => [[
                'Deal_Name' => $request->Deal_Name,
                'Stage' => $request->Stage,
                'Account_Id' => $request->Account_Id
            ]]
        ]);

        return response()->json($response->json());
    }

    /**
     * Видалити акаунт
     */
    public function deleteAccount($id)
    {
        $token = $this->zohoAuthService->getAccessToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->delete(env('ZOHO_API_BASE_URL') . "/crm/v2/Accounts/{$id}");

        return response()->json($response->json());
    }

    /**
     * Видалити угоду
     */
    public function deleteDeal($id)
    {
        $token = $this->zohoAuthService->getAccessToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->delete(env('ZOHO_API_BASE_URL') . "/crm/v2/Deals/{$id}");

        return response()->json($response->json());
    }
}
