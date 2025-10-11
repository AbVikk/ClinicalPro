<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the pharmacy dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        
        // You can add any data needed for the dashboard here
        $data = [
            'user' => $user,
            // Add other data as needed
        ];
        
        return view('pharmacy.dashboard', $data);
    }
}