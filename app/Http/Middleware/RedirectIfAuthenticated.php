<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();

                
                $currentRoute = $request->route()->getName();

                $authRoutes = ['login', 'register', 'password.request', 'password.reset'];

                if (in_array($currentRoute, $authRoutes)) {
                    if ($user->hasRole('admin')) {
                        return redirect()->route('admin.dashboard');
                    } elseif ($user->hasRole('business_owner')) {
                        return redirect()->route('business-owner.dashboard');
                    } elseif ($user->hasRole('customer')) {
                        return redirect()->route('customer.dashboard');
                    }
                }

                return $next($request);
            }
        }

        return $next($request);
    }
}
