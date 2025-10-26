<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSeller
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check kung naka-login
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to access seller panel.');
        }

        // Check kung seller ang role (adjust depende sa structure ng users table mo)
        if (auth()->user()->role !== 'seller') {
            abort(403, 'Unauthorized. Seller access only.');
        }

        return $next($request);
    }
}