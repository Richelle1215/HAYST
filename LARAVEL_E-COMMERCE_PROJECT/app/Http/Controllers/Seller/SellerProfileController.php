<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerProfileController extends Controller
{
    public function index()
    {
        // get logged-in seller info
        $seller = Auth::user();

        return view('seller.profile.index', compact('seller'));
    }

    public function update(Request $request)
    {
        $seller = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        $seller->update($request->only('name', 'email'));

        return redirect()->route('seller.profile.index')->with('success', 'Profile updated successfully!');
    }
}
