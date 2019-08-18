<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HistoricalRate;
use App\Currency;

class HistoricalRatesController extends Controller
{
    private $access_key;

    public function __construct()
    {
        $this->access_key = 'f264fa6eab9c8de13be9cac497a820c5';
        $from = new \DateTime();
        $to = new \DateTime();
        $from->modify('- 31 day');
        $this->sendRequest($from, $to);
    }

    public function setDefault()
    {
        $period = new \DateTime();
        $period->modify('- 31 day');
        
        $rates1 = HistoricalRate::where('code', "EUR")->where('date', '>=', $period)->orderBy('date')->get();
        $rates2 = HistoricalRate::where('code', "USD")->where('date', '>=', $period)->orderBy('date')->get();
        $comparedRates = array();

        $day = $period;
        for ($i = 0; $i < 31; $i++) {
            $comparedRates['rate'][$day->format('d-m')] = $rates2[$i]->rate/$rates1[$i]->rate;
            $day->modify('+ 1 day');
        }

        $this->createFigure("EUR", "USD", $comparedRates);

        $_SESSION['currency1'] = "EUR";
        $_SESSION['currency2'] = "USD";
        $_SESSION['period'] = 1;
        $currencies = Currency::all();
        return view('historicalrates.index')->with('currencies', $currencies);
    }

    public function index(Request $request)
    {
        $this->validate($request, [
            'currency1' => 'required',
            'currency2' => 'required',
            'period' => 'required'
        ]);

        $currency1 = $request['currency1'];
        $currency2 = $request['currency2'];

        switch ($request['period']) {
            case 1:
                $periodDays = 31;
                break;
            case 2:
                $periodDays = 93;
                break;
            case 3:
                $periodDays = 365;
                break;
        }

        $period = new \DateTime();
        $period->modify('- '.$periodDays.' day');

        $rates1 = HistoricalRate::where('code', $currency1)->where('date', '>=', $period)->orderBy('date')->get();
        $rates2 = HistoricalRate::where('code', $currency2)->where('date', '>=', $period)->orderBy('date')->get();
        $comparedRates = array();

        $day = $period;
        for ($i = 0; $i < $periodDays; $i++) {
            $comparedRates['rate'][$day->format('d-m')] = $rates2[$i]->rate/$rates1[$i]->rate;
            $day->modify('+ 1 day');
        }

        $this->createFigure($currency1, $currency2, $comparedRates);

        $_SESSION['currency1'] = $currency1;
        $_SESSION['currency2'] = $currency2;
        $_SESSION['period'] = $request['period'];
        $currencies = Currency::all();
        return view('historicalrates.index')->with('currencies', $currencies);
    }

    public function createFigure($currency1, $currency2, $comparedRates)
    {
        //Image Template
        $img = imagecreate(850, 500);
        $grey = imagecolorallocate($img, 233, 236, 239);

        imagefill($img, 1, 1, $grey);

        $black = imagecolorallocate($img, 0, 0, 0);
        $red = imagecolorallocate($img, 255, 0, 0);

        imagerectangle($img, 1, 1, 848, 498, $black);
        imageline($img, 40, 20, 40, 460, $black);
        imageline($img, 40, 460, 640, 460, $black);
        imagedashedline($img, 40, 80, 640, 80, $black);
        imagedashedline($img, 40, 400, 640, 400, $black);

        //arrows
        imageline($img, 40, 20, 35, 30, $black);
        imageline($img, 40, 20, 45, 30, $black);
        imageline($img, 640, 460, 630, 465, $black);
        imageline($img, 640, 460, 630, 455, $black);

        //Figure title
        $font = $_ENV['REAL_PATH'].'public/storage/fonts/arial.ttf';
        $title = 'Recent Trends '.$currency1.'/'.$currency2.' average daily prices';
        imagettftext($img, 20, 0, 50, 25, $black, $font, $title);

        //left arrow describe
        $left_arrow = 'Rates '.$currency2.', where '.$currency1.' = 1';
        imagettftext($img, 15, 90, 23, 350, $black, $font, $left_arrow);

        //Figure
        $max = max($comparedRates['rate']);
        $max_101pr = round($max * 1.01, 2);
        imagettftext($img, 10, 0, 42, 78, $black, $font, $max_101pr);
        $min = min($comparedRates['rate']);
        $min_99pr = round($min * 0.99, 2);
        imagettftext($img, 10, 0, 42, 398, $black, $font, $min_99pr);

        $i = 0;
        $x = 40;
        foreach ($comparedRates['rate'] as $date => $rate) {
            switch (count($comparedRates['rate'])) {
                case 31:
                    $modulo = 5;
                    $x_move = 19;
                    $filename = 'period1'.$currency1.'to'.$currency2.'.png';
                    break;
                case 93:
                    $modulo = 15;
                    $x_move = 6.4;
                    $filename = 'period2'.$currency1.'to'.$currency2.'.png';
                    break;
                case 365:
                    $modulo = 60;
                    $x_move = 1.6;
                    $filename = 'period3'.$currency1.'to'.$currency2.'.png';
                    break;
            }
            $y_rate = 320 / (($max_101pr - $min_99pr) / ($rate - $min_99pr));
            $y = 400 - $y_rate;

            if (!isset($y_previous)) {
                $y_previous = $y;
            }
            
            imageline($img, $x, $y_previous, ($x + $x_move), $y, $red);

            if ($i % $modulo == 0) {
                imagettftext($img, 10, 0, ($x - 10), 480, $black, $font, $date);
                imagettftext($img, 10, 45, ($x + 8), ($y - 5), $black, $font, round($rate, 4));
                imagedashedline($img, $x, 60, $x, 460, $black);
            }
            
            $y_previous = $y;
            $x += $x_move;
            $i++;
        }

        //legend
        imagettftext($img, 10, 0, 700, 80, $black, $font, 'MAX : '.round($max, 4));
        imagettftext($img, 10, 0, 700, 100, $black, $font, 'MIN : '.round($min, 4));
        $avg = (array_sum($comparedRates['rate'])/count($comparedRates['rate']));
        imagettftext($img, 10, 0, 700, 120, $black, $font, 'AVG : '.round($avg, 4));
        $stnd_dev = $this->stnd_dev($comparedRates['rate']);
        imagettftext($img, 10, 0, 700, 140, $black, $font, 'SD : '.round($stnd_dev, 4));
        imagettftext($img, 10, 0, 700, 160, $black, $font, 'Today\'s Rate  : '.round($rate, 4));
        
        imagepng($img, '../storage/app/public/hist_rates/'.$filename);
        imagedestroy($img);
    }
    
    public function sendRequest($from, $to)
    {
        for ($i = $from; $i < $to; $i->modify('+ 1 day')) {
            if (HistoricalRate::where('date', $i->format('Y-m-d'))->count()) {
                continue;
            }
            // Initialize CURL:
            $ch = curl_init('http://data.fixer.io/api/'.$i->format('Y-m-d').'?access_key='.$this->access_key.'');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Store the data:
            $json = curl_exec($ch);
            curl_close($ch);

            // Decode JSON response:
            // Access the exchange rate values. For example $exchangeRates[rates][PLN]:
            $exchangeRates = json_decode($json, true);

            $this->saveInDB($exchangeRates);
        }
    }

    private function saveInDB($exchangeRates)
    {
        $date = $exchangeRates['date'];
        foreach ($exchangeRates['rates'] as $code => $value) {
            $historicalRate = new HistoricalRate;
            $historicalRate->code = $code;
            $historicalRate->rate = $value;
            $historicalRate->date = $date;

            $historicalRate->save();
        }
    }

    private function stnd_dev(array $a, $sample = false)
    {
        $n = count($a);
        if ($n === 0) {
            trigger_error("The array has zero elements", E_USER_WARNING);
            return false;
        }
        if ($sample && $n === 1) {
            trigger_error("The array has only 1 element", E_USER_WARNING);
            return false;
        }
        $mean = array_sum($a) / $n;
        $carry = 0.0;
        foreach ($a as $val) {
            $d = ((double) $val) - $mean;
            $carry += $d * $d;
        };
        if ($sample) {
            --$n;
        }
        return sqrt($carry / $n);
    }
}