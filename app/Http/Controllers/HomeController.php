<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // return view('home');
        $role = Auth::user()->role;
        switch($role)
        {
            case "super_admin":
                return Redirect::route('super_admin.index');
                break;
            case "admin":
                return Redirect::route('admin.index');
                break;
            case "guest":
                return Redirect::route('guest.index');
                break;
        }
    }
}
