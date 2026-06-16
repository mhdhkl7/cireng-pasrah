<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeout
{
    /**
     * Batas idle time dalam menit sebelum sesi dihapus.
     */
    private const IDLE_MINUTES = 15;

    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $lastActivity = session('last_activity');

        if ($lastActivity !== null) {
            $idleSeconds = time() - $lastActivity;

            if ($idleSeconds > self::IDLE_MINUTES * 60) {
                // Logout otomatis
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('error', '⏰ Sesi Anda telah berakhir karena tidak ada aktivitas selama ' . self::IDLE_MINUTES . ' menit. Silakan login kembali.');
            }
        }

        // Perbarui timestamp aktivitas terakhir
        session(['last_activity' => time()]);

        return $next($request);
    }
}
