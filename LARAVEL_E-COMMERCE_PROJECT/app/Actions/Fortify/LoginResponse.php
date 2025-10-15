<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        // 🔥 Role-based redirect
        if ($user->role === 'admin') {
            $redirectUrl = '/admin/dashboard';
        } elseif ($user->role === 'seller') {
            $redirectUrl = '/seller/dashboard';
        } else {
            // Customer -> Store page
            $redirectUrl = route('products.index');
        }

        return redirect()->intended($redirectUrl);
    }
}
