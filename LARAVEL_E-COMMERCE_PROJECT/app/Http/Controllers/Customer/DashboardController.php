<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Check if user is customer
        if (Auth::user()->role !== 'customer') {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }
        
        // You can pass data to the view if needed
        $user = Auth::user();
        
        return view('customer.dashboard', compact('user'));
    }
}