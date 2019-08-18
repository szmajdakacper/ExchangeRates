@extends('layouts.frontend')

@section('content')
<div class="jumbotron py-1 px-0 mb-0">
{{ Form::open(['action' => 'HistoricalRatesController@index', 'method' => 'post', 'class' => 'form-inline mt-4 justify-content-center']) }}
{{ Form::bsSelectPeriod('period', 'periodselect', $_SESSION['period']) }}
{{ Form::bsSelectCurrency('currency1', 'I Have', $currencies, 'currency1', $_SESSION['currency1']) }}
{{ Form::bsSelectCurrency('currency2', 'I Want', $currencies, 'currency2', $_SESSION['currency2']) }}
{{ Form::bsButton('Check Out', ['class' => 'btn btn-block btn-outline-dark ml-auto']) }}
{{ Form::close() }}
</div>
<img class="img-fluid w-100 mt-3" src="/storage/hist_rates/period{{$_SESSION['period']}}{{$_SESSION['currency1']}}to{{$_SESSION['currency2']}}.png" alt="">
@endsection