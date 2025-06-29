<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('client')) {
            return redirect()->route('client.dashboard');
        }

        if ($user->hasRole('provider')) {
            return redirect()->route('provider.dashboard');
        }

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        // Default dashboard for users without specific roles
        return view('dashboard');
    }
}
