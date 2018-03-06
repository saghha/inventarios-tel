<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('home');
    }

    public function get_token(){
        $api_token = Auth::user()->api_token;
        return response()->json(['api_token' => $api_token]);
    }
    public function get_username(){
        return Auth::user()->name;
    }
}
