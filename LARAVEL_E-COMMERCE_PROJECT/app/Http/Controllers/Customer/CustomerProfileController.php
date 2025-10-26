<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CustomerProfileController extends Controller
{
    /**
     * Display the customer profile page
     */
    public function index()
    {
        return view('customer.profile.index', [
            'customer' => Auth::user(),
        ]);
    }

    /**
     * Update customer profile information (name and email)
     */
    public function update(Request $request)
    {
        $customer = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($customer->id)],
        ]);

        $customer->forceFill([
            'name' => $request->name,
            'email' => $request->email,
        ])->save();

        return redirect()->route('customer.profile')->with('success', 'Profile information successfully updated.');
    }
    
    /**
     * Update customer password
     */
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

        return redirect()->route('customer.profile')->with('password_success', 'Password successfully updated.');
    }

    /**
     * Delete customer account
     */
    public function delete(Request $request)
    {
        $request->validate([
            'password_to_delete' => ['required', 'string'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password_to_delete, $user->password)) {
            return back()->withErrors(['password_to_delete' => 'The provided password does not match your current password.']);
        }

        // Log out the user first
        Auth::logout();
        
        // Use transaction for safety
        DB::transaction(function () use ($user) {
            // If you have foreign keys, related records will be deleted automatically
            // If not, manually delete related records here (e.g., $user->orders()->delete();)
            $user->delete();
        });

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Your account has been successfully deleted.');
    }
}