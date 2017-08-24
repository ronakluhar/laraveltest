<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Redirect;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
    
    /*
     * Check if user is login or not 
     */
    public function checkLogin()
    {
        if(Auth::check())
        {
            return Redirect::to("/admin/users/");
            exit;
        }
        return view('auth.login');
    }
}
