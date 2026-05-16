<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Blocks suspended / banned non-admin accounts from using authenticated routes.
 */
class EnsureUserAccountActive
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        $status = $user->account_status ?? 'active';

        if ($user->role === 'admin') {
            return $next($request);
        }

        if ($status === 'banned') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => __('This account has been banned. Contact support if you believe this is a mistake.'),
            ]);
        }

        if ($status === 'suspended') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => __('This account is suspended. Contact support for more information.'),
            ]);
        }

        return $next($request);
    }
}
