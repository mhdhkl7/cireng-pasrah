<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (!auth()->user()->isCustomer()) {
            // Arahkan ke halaman yang sesuai dengan role, bukan abort
            if (auth()->user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            if (auth()->user()->isDriver()) {
                return redirect()->route('driver.dashboard');
            }
            return redirect()->route('login');
        }

        return $next($request);
    }
}
