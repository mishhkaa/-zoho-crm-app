<?php

namespace App\Http\Controllers;

use App\Services\ZohoAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ZohoController extends Controller
{
    protected $zohoAuthService;

    public function __construct(ZohoAuthService $zohoAuthService)
    {
        $this->zohoAuthService = $zohoAuthService;
    }

    
    public function getAccounts()
    {
        $token = $this->zohoAuthService->getAccessToken();
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->get(config('zoho.api_base_url') . '/crm/v2/Accounts');

        return response()->json($response->json());
    }

   
    public function createAccount(Request $request)
    {
        $token = $this->zohoAuthService->getAccessToken();
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->post(config('zoho.api_base_url') . '/crm/v2/Accounts', [
            'data' => [[
                'Account_Name' => $request->Account_Name,
                'Website' => $request->Website,
                'Phone' => $request->Phone
            ]]
        ]);

        return response()->json($response->json());
    }

   
    public function updateAccount(Request $request, $id)
    {
        $token = $this->zohoAuthService->getAccessToken();
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->put(config('zoho.api_base_url') . '/crm/v2/Accounts/' . $id, [
            'data' => [[
                'Account_Name' => $request->Account_Name,
                'Website' => $request->Website,
                'Phone' => $request->Phone
            ]]
        ]);

        return response()->json($response->json());
    }

    
    public function deleteAccount($id)
    {
        $token = $this->zohoAuthService->getAccessToken();
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->delete(config('zoho.api_base_url') . '/crm/v2/Accounts/' . $id);

        return response()->json($response->json());
    }

    
    public function getDeals()
    {
        $token = $this->zohoAuthService->getAccessToken();
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->get(config('zoho.api_base_url') . '/crm/v2/Deals');

        return response()->json($response->json());
    }

   
    public function updateDeal(Request $request, $id)
    {
        $token = $this->zohoAuthService->getAccessToken();
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->put(config('zoho.api_base_url') . '/crm/v2/Deals/' . $id, [
            'data' => [[
                'Deal_Name' => $request->Deal_Name,
                'Stage' => $request->Stage,
                'Account_Id' => $request->Account_Id
            ]]
        ]);

        return response()->json($response->json());
    }

    
    public function deleteDeal($id)
    {
        $token = $this->zohoAuthService->getAccessToken();
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->delete(config('zoho.api_base_url') . '/crm/v2/Deals/' . $id);

        return response()->json($response->json());
    }
    public function createDeal(Request $request)
    {
        $token = $this->zohoAuthService->getAccessToken();
    
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->post(config('zoho.api_base_url') . '/crm/v2/Deals', [
            'data' => [[
                'Deal_Name' => $request->Deal_Name,
                'Stage' => $request->Stage,
                'Account_Id' => $request->Account_Id,
            ]]
        ]);
    
        return response()->json($response->json());
    }
    

}
