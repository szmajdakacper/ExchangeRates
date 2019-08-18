@extends('layouts.app')

@section('content')
<h1>Wallet</h1>
<ul class="list-group">
@foreach($currencies as $currency)
    <li class="list-group-item">{{$code = $currency->code}} : {{$myWallet->$code}}</li>
@endforeach
</ul>
<hr>
<h3>Top Up Your Wallet</h3>

    {{ Form::open(['action' => 'WalletsController@top_up', 'method' => 'post', 'class' => 'form-inline mt-4 justify-content-center align-items-start']) }}
    {{ Form::bsSelectCurrency('currency', 'Currency', $currencies, 'currency', 'EUR', ['nooptgroup' => 1]) }}
    {{ Form::bsTextPreApp('amount', 'Amount', 'amount') }}
    {{ Form::hidden('_method', 'PUT') }}
    {{ Form::bsButton('Send', ['class' => 'btn btn-outline-success btn-block']) }}
    {{ Form::close() }}


 @endsection