<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    public function index()
    {
        $userId = session('auth_user_id');
        $data   = $this->dashboardService->getData($userId);

        return view('dashboard', $data);
    }
}