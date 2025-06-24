<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:provider');
    }

    public function dashboard()
    {
        return view('provider.dashboard');
    }

    public function services()
    {
        return view('provider.services');
    }

    public function orders()
    {
        return view('provider.orders');
    }

    public function profile()
    {
        return view('provider.profile');
    }
}
