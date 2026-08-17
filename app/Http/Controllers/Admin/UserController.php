<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->latest()->paginate(20);

        $totalUsers = User::count();

        $activeUsers = User::whereNotNull('last_login_at')
            ->where('last_login_at', '>=', now()->subDays(30))
            ->count();

        $adminUsers = User::whereHas('roles', function($q) {
            $q->where('name', 'admin');
        })->count();

        return view('admin.users.index', compact('users', 'totalUsers', 'activeUsers', 'adminUsers'));
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot change the status of your own account.');
        }

        $user->update([
            'is_active' => !($user->is_active ?? true)
        ]);

        $statusText = ($user->is_active ?? true) ? 'activated' : 'deactivated';
        return back()->with('success', 'User ' . $statusText . ' successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->hasRole('admin')) {
            $adminCount = User::whereHas('roles', function($q) {
                $q->where('name', 'admin');
            })->count();

            if ($adminCount <= 1) {
                return back()->with('error', 'You cannot delete the last administrator in the system.');
            }
        }

        $userName = $user->name;
        $user->delete();

        return back()->with('success', 'User "' . $userName . '" deleted successfully.');
    }
}