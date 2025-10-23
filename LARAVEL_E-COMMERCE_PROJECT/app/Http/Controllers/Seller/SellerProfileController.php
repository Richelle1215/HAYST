<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // Added for transaction

class SellerProfileController extends Controller
{
    public function index()
    {
        return view('seller.profile.index', [
            'seller' => Auth::user(),
        ]);
    }

    // ✅ EDIT INFO (Update Name at Email)
    public function update(Request $request)
    {
        $seller = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($seller->id)],
        ]);

        $seller->forceFill([
            'name' => $request->name,
            'email' => $request->email,
        ])->save();

        return redirect()->route('seller.profile.index')->with('success', 'Profile information successfully updated.');
    }
    
    // ✅ CHANGE PASSWORD
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'The provided password does not match your current password.']);
        }

        Auth::user()->forceFill([
            'password' => Hash::make($request->password),
        ])->save();

        return redirect()->route('seller.profile.index')->with('password_success', 'Password successfully updated.');
    }

    // ✅ DELETE ACCOUNT
    public function delete(Request $request)
    {
        $request->validate([
            'password_to_delete' => ['required', 'string'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password_to_delete, $user->password)) {
            return back()->withErrors(['password_to_delete' => 'The provided password does not match your current password.']);
        }

        // Tiyakin na nagla-log out muna bago mag-delete.
        Auth::logout();
        
        // Gamitin ang transaction para sa safety.
        DB::transaction(function () use ($user) {
            // Kung may foreign keys, automatic na madedelete ang products/orders.
            // Kung wala, idagdag dito ang manual deletion (e.g., $user->products()->delete();)
            $user->delete();
        });

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Your seller account has been successfully deleted.');
    }
}