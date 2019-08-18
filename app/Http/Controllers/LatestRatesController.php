<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LatestRate;

class LatestRatesController extends Controller
{
    private $endpoint;
    private $access_key;

    public function __construct()
    {
        $this->endpoint = 'latest';
        $this->access_key = 'f264fa6eab9c8de13be9cac497a820c5';
    }

    public function index()
    {
        if ($this->ratesAvaible()) {
            $latestRates = LatestRate::all();
            return view('latestrates.index')->with('latestrates', $latestRates);
        } else {
            $this->sendRequest();
            $latestRates = LatestRate::all();
            return view('latestrates.index')->with('latestrates', $latestRates);
        }
    }

    public function fromTo($from, $to)
    {
        $_SESSION['activeLink'] = $from.$to;
        if ($this->ratesAvaible()) {
            $latestRates = $this->fromTo_fetchRates($from, $to);
            return view('latestrates.fromto')->with('latestrates', $latestRates);
        } else {
            $this->sendRequest();
            $latestRates = $this->fromTo_fetchRates($from, $to);
            return view('latestrates.fromto')->with('latestrates', $latestRates);
        }
    }

    public function fromTo_fetchRates($from, $to)
    {
        $latestRates = [];
        $letters = range($from, $to);
        $all = LatestRate::all();
        foreach ($letters as $letter) {
            foreach ($all as $latestRate) {
                if ($letter == $latestRate->code[0]) {
                    $latestRates[] = $latestRate;
                }
            }
        }
        return $latestRates;
    }
    
    public function sendRequest()
    {
        // Initialize CURL:
        $ch = curl_init('http://data.fixer.io/api/'.$this->endpoint.'?access_key='.$this->access_key.'');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Store the data:
        $json = curl_exec($ch);
        curl_close($ch);

        // Decode JSON response:
        // Access the exchange rate values. For example $exchangeRates[rates][PLN]:
        $exchangeRates = json_decode($json, true);

        $this->saveInDB($exchangeRates);
    }

    private function saveInDB($exchangeRates)
    {
        $this->truncateTable();
        foreach ($exchangeRates['rates'] as $code => $value) {
            $latestRate = new LatestRate;
            $latestRate->code = $code;
            $latestRate->rate = $value;

            $latestRate->save();
        }
    }

    private function truncateTable()
    {
        \DB::table('latest_rates')->truncate();
    }

    public function ratesAvaible()
    {
        $first = LatestRate::first();
        if (is_object($first)) {
            $date = new \DateTime($first->created_at);
            $dateStr = $date->format('d-m-y.H');
            $now = new \DateTime();
            $nowStr = $now->format('d-m-y.H');
            if ($nowStr == $dateStr) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
}