<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Offer;
use App\LatestRate;
use App\User;
use App\Wallet;
use App\Transaction;

class OffersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $offers = Offer::orderBy('created_at', 'desc')->get();
        return view('offers/index')->with('offers', $offers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $latestrates = LatestRate::where('code', 'AUD')
            ->orWhere('code', 'CHF')
            ->orWhere('code', 'EUR')
            ->orWhere('code', 'GBP')
            ->orWhere('code', 'JPY')
            ->orWhere('code', 'USD')
            ->get();
        return view('offers/create')->with('latestrates', $latestrates);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'ihave_code' => 'required',
            'ihave_amount' => 'required',
            'myrate' => 'required',
            'iwant_code' => 'required'
        ]);
        //if user has enough money in wallet
        $user = User::find(\Auth::id());
        $ihave_code = $request->input('ihave_code');
        $ihave_amount = $request->input('ihave_amount');
        $amountInWallet = $user->wallet->$ihave_code;
        if ($amountInWallet < $ihave_amount) {
            return redirect('/offers/create')->with('error', 'You Dont Have Enough Money In Your Wallet');
        }

        \DB::transaction(function () use ($request, $user) {
            $user_id = $user->id;

            $ihave_code = $request->input('ihave_code');
            $ihave_amount = $request->input('ihave_amount');
            $iwant_code = $request->input('iwant_code');
            $rate = $request->input('myrate');

            $wallet = Wallet::where('user_id', $user->id)->first();
            $amountInWallet = $wallet->$ihave_code;
            $newAmountInWallet = $amountInWallet - $ihave_amount;
        
            $offer = new Offer;

            $offer->user_id = $user->id;
            $offer->ihave_code = $ihave_code;
            $offer->ihave_amount = $ihave_amount;
            $offer->iwant_code = $iwant_code;
            $offer->rate = $rate;

            $offer->save();

            $wallet->where('user_id', $user->id)->update([$ihave_code => $newAmountInWallet]);

            $transaction = new Transaction;
            $transaction->offer_id = $offer->id;
            $transaction->flow = 'payIn';
            $transaction->user_id = $user_id;
            $transaction->amount = $ihave_amount;

            $transaction->save();
        });

        return redirect('offers')->with('success', 'Offer Added Correct!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $offer = Offer::find($id);
        $latestrate['ihave'] = LatestRate::where('code', $offer->ihave_code)->first();
        $latestrate['iwant'] = LatestRate::where('code', $offer->iwant_code)->first();
        $actualRate = round(($latestrate['iwant']->rate / $latestrate['ihave']->rate), 4);
        return view('offers.show')->with('offer', $offer)->with('actualRate', $actualRate);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $offer = Offer::find($id);
        if (\Auth::id() !== $offer->user_id) {
            return redirect('offers')->with('error', 'No authorization for this action');
        }
        $latestrate['ihave'] = LatestRate::where('code', $offer->ihave_code)->first();
        $latestrate['iwant'] = LatestRate::where('code', $offer->iwant_code)->first();
        $actualRate = round(($latestrate['iwant']->rate / $latestrate['ihave']->rate), 4);
        return view('offers.edit')->with('offer', $offer)->with('actualRate', $actualRate);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'myrate' => 'required'
        ]);

        $offer = Offer::find($id);

        $offer->rate = $request->input('myrate');

        $offer->save();

        return redirect('offers')->with('success', 'Offer Updated Correct!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        \DB::transaction(function () use ($id) {
            $offer = Offer::find($id);
            
            if (\Auth::id() !== $offer->user_id) {
                return redirect('offers')->with('error', 'No authorization for this action');
            }

            $user_id = $offer->user_id;
            $offered_code = $offer->ihave_code;
            $leftover_amount = $offer->ihave_amount;

            $wallet = Wallet::where('user_id', $user_id)->first();
            $newStatus = $wallet->$offered_code + $leftover_amount;

            $wallet->where('user_id', $user_id)->update([$offered_code => $newStatus]);

            $transaction = new Transaction;
            $transaction->offer_id = $offer->id;
            $transaction->flow = 'OfferRemoved';
            $transaction->user_id = $user_id;
            $transaction->amount = $leftover_amount;

            $transaction->save();

            $offer->delete();
        });
        return redirect('/offers')->with('success', 'Offer Removed!');
    }

    public function buy(Request $request, $id)
    {
        $this->validate($request, [
            'amount' => 'required|numeric'
        ]);

        $offer = Offer::find($id);
        $buyersWallet = Wallet::where('user_id', \Auth::id())->first();
        $sold_amount = $request->input('amount');
        $bought_code = $offer->iwant_code;
        $bought_amount = $sold_amount * $offer->rate;

        //Validation
        if (\Auth::id() === $offer->user_id) {
            return redirect('offers/'.$id)->with('error', 'You Can Only Remove Your Offer');
        } elseif ($offer->ihave_amount < $sold_amount) {
            return redirect('offers/'.$id)->with('error', 'Offer Has Not Enough Funds, You Can Buy Only '.$offer->ihave_amount.' '.$offer->ihave_code);
        } elseif ($buyersWallet->$bought_code < $bought_amount) {
            return redirect('offers/'.$id)->with('error', 'You Dont Have Enough Money In Your Wallet');
        }
        
        \DB::transaction(function () use ($offer, $sold_amount) {
            $sold_code = $offer->ihave_code;
            $bought_code = $offer->iwant_code;
            $offer_amount = $offer->ihave_amount;
            $offer_amount -= $sold_amount;
            $bought_amount = $sold_amount * $offer->rate;

            $sellerWallet = Wallet::where('user_id', $offer->user_id)->first();
            $seller_newStatus_bought = $sellerWallet->$bought_code + $bought_amount;
            $sellerWallet->where('user_id', $offer->user_id)->update([$bought_code => $seller_newStatus_bought]);

            $buyersWallet = Wallet::where('user_id', \Auth::id())->first();

            $buyers_newStatus_bought = $buyersWallet->$bought_code - $bought_amount;
            $buyersWallet->where('user_id', \Auth::id())->update([$bought_code => $buyers_newStatus_bought]);
            
            $buyers_newStatus_sold = $buyersWallet->$sold_code + $sold_amount;
            $buyersWallet->where('user_id', \Auth::id())->update([$sold_code => $buyers_newStatus_sold]);

            $transaction = new Transaction;
            $transaction->offer_id = $offer->id;
            $transaction->flow = 'PayOut';
            $transaction->user_id = \Auth::id();
            $transaction->amount = $sold_amount;
            $transaction->save();

            if ($offer_amount) {
                $offer->ihave_amount = $offer_amount;
                $offer->save();
            } else {
                $offer->delete();
            }
        });
        return redirect('/offers')->with('success', 'the transaction was successful');
    }
}