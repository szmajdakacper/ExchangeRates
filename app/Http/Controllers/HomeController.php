<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Offer;

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
        $myOffers = Offer::where('user_id', \Auth::id())->orderBy('created_at', 'desc')->get();
        return view('app.dashboard')->with('myOffers', $myOffers);
    }
}
