@extends('layouts.exchange')

@section('content')
    <div class="card mb-3">
    <div class="card-header">
        <h4 class="card-title display-4 text-center">Add New Offer</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-6">
                <h5 class="display-5">Currency I Have</h5>
                {{ Form::open(['action' => 'OffersController@store', 'method' => 'post']) }}
                {{ Form::bsSelectCurrencyWithRates('ihavecurrency', 'I Have', $latestrates, 'ihavecurrency', 'EUR', ['nooptgroup' => 1]) }}
                {{ Form::hidden('ihave_code', 'EUR') }}
                {{ Form::bsText('ihave_amount', 'Amount You Want Exchange', 'ihave_amount') }}
                {{ Form::bsText('myrate', 'My Rate', 'myrate') }}
                
            </div>
            <div class="col-6">
                <h5 class="display-5">Currency I Want</h5>
                
                {{ Form::bsSelectCurrencyWithRates('iwantcurrency', 'I Want', $latestrates, 'iwantcurrency', 'USD', ['nooptgroup' => 1]) }}
                {{ Form::hidden('iwant_code', 'USD') }}
                {{ Form::bsText('ireceive_amount', 'Amount You Will Receive', 'ireceive_amount') }}
                {{ Form::bsText('latestRate', 'Latest Rate', 'latestrate', '', ['disabled' => 'disabled']) }}
                
            </div>
            {{ Form::bsButton('Add Offer', ['class' => 'btn btn-outline-dark btn-block'])}}
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection