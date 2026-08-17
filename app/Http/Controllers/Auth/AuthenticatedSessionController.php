<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();

            $request->session()->regenerate();

            $user = Auth::user();

            if (! $user) {
                throw new \RuntimeException('Unable to resolve authenticated user after login.');
            }

            $role = $user->getRoleNames()->first();

            $request->session()->put('auth.user_id', $user->id);
            $request->session()->put('auth.user_email', $user->email);
            $request->session()->put('auth.role', $role);
            $request->session()->put('auth.login_at', now()->toDateTimeString());

            $user->update([
                'last_login_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            Log::info('User logged in successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
                'role' => $role,
            ]);

            if ($user->hasRole('admin')) {
                Log::info('Redirecting to admin dashboard');
                return redirect()->route('admin.dashboard');
            } elseif ($user->hasRole('business_owner')) {
                Log::info('Redirecting to business owner dashboard');
                return redirect()->route('business-owner.dashboard');
            } elseif ($user->hasRole('customer')) {
                Log::info('Redirecting to customer dashboard');
                return redirect()->route('customer.dashboard');
            }

            Log::warning('User has no role, redirecting to home');
            return redirect()->route('home');

        } catch (\Exception $e) {
            Log::error('Login failed', [
                'email' => $request->email,
                'ip' => $request->ip(),
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'email' => 'An error occurred while logging in. Please try again.',
            ]);
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();

        Log::info('User logged out', [
            'user_id' => $user?->id,
            'email' => $user?->email,
            'ip' => $request->ip(),
        ]);

        if ($request->hasSession()) {
            $request->session()->put('auth.logout_at', now()->toDateTimeString());
        }

        Auth::guard('web')->logout();

        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->flush();
            $request->session()->regenerateToken();
        }

        return redirect()->route('login')->with('status', 'You have been logged out successfully.');
    }
}
