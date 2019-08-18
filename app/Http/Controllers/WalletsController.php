<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Wallet;
use App\Currency;

class WalletsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $myWallet = Wallet::where('user_id', \Auth::id())->first();
        $currencies = Currency::where('code', 'AUD')
        ->orWhere('code', 'CHF')
        ->orWhere('code', 'EUR')
        ->orWhere('code', 'GBP')
        ->orWhere('code', 'JPY')
        ->orWhere('code', 'USD')
        ->get();
        return view('app.wallet')->with('currencies', $currencies)->with('myWallet', $myWallet);
    }
    
    public static function newWallet()
    {
        $wallet = new Wallet;

        $wallet->user_id = \Auth::id();
        $wallet->AUD = 0;
        $wallet->CHF = 0;
        $wallet->EUR = 0;
        $wallet->GBP = 0;
        $wallet->JPY = 0;
        $wallet->USD = 0;

        $wallet->save();
    }

    public function top_up(Request $request)
    {
        \DB::transaction(function () use ($request) {
            $wallet = Wallet::where('user_id', \Auth::id())->first();
            $code = $request->input('currency');
            $wallet->$code += $request->input('amount');

            \DB::table('wallets')->where('user_id', \Auth::id())->update([$request->input('currency') => $wallet->$code]);
        });

        return redirect('/wallet')->with('success', 'Money Added To Your Wallet');
    }
}