<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:client');
    }

    public function dashboard()
    {
        return view('client.dashboard');
    }

    public function services()
    {
        return view('client.services');
    }

    public function orders()
    {
        return view('client.orders');
    }

    public function profile()
    {
        return view('client.profile');
    }
}
