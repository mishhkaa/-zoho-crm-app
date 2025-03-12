<?php

namespace App\Http\Controllers;

use App\Services\ZohoAuthService;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    protected $zohoAuthService;

    public function __construct(ZohoAuthService $zohoAuthService)
    {
        $this->zohoAuthService = $zohoAuthService;
    }

    public function showDashboard()
    {
        try {
            $token = $this->zohoAuthService->getAccessToken();
            return view('dashboard', compact('token'));
        } catch (\Exception $e) {
            return redirect()->route('error')->with('error', 'Не вдалося отримати токен');
        }
    }
}
