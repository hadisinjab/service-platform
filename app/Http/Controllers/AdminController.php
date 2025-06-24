<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function users()
    {
        $users = \App\Models\User::with('roles')->get();
        return view('admin.users', compact('users'));
    }

    public function providers()
    {
        $providers = \App\Models\User::role('provider')->get();
        return view('admin.providers', compact('providers'));
    }

    public function clients()
    {
        $clients = \App\Models\User::role('client')->get();
        return view('admin.clients', compact('clients'));
    }
}
