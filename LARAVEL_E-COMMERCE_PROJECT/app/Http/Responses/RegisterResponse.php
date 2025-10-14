<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        // 1. I-log out ang user na awtomatikong na-log in.
        auth()->guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // 2. I-redirect sa Welcome Page (e.g., '/').
        return $request->wantsJson()
            ? new JsonResponse('', 201)
            // Palitan ang '/' ng route name/path ng iyong Welcome Page
            : redirect('/'); 
    }
}