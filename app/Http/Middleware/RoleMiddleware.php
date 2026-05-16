<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        if ($user->role !== $role) {
            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'business' => redirect()->route('business.dashboard'),
                'customer' => redirect()->route('customer.dashboard'),
                default => redirect('/dashboard'),
            };
        }

        return $next($request);
    }
}
