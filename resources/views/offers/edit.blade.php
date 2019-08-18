@extends('layouts.exchange')

@section('content')
    <div class="card mb-3">
    <div class="card-header">
        <h4 class="card-title display-4 text-center">Your Offer</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-6">
                {{ Form::open(['action' => ['OffersController@update', $offer->id], 'method' => 'post']) }}
                {{ Form::bsText('ihave_code', 'Currency I Have', 'ihave_code', $offer->ihave_code, ['readonly' => 'readonly']) }}
                {{ Form::bsText('ihave_amount', 'Amount You Want Exchange', 'ihave_amount', $offer->ihave_amount, ['readonly' => 'readonly']) }}
                {{ Form::bsText('myrate', 'My Rate', 'myrate', $offer->rate) }}
                
            </div>
            <div class="col-6">
                {{ Form::bsText('iwant_code', 'Currency I Want', 'iwant_code', $offer->iwant_code, ['readonly' => 'readonly']) }}
                {{ Form::bsText('ireceive_amount', 'Amount You Will Receive', 'ireceive_amount', ' ', ['readonly' => 'readonly']) }}
                {{ Form::bsText('latestRate', 'Actual Rate', 'latestrate', $actualRate, ['readonly' => 'readonly']) }}
                
            </div>
            {{ Form::hidden('_method', 'PUT') }}
            {{ Form::bsButton('Update Offer', ['class' => 'btn btn-outline-dark btn-block'])}}
            {{ Form::close() }}
        </div>
    </div>
    <div class="card-footer">
    <small>Added at: {{ $offer->created_at }}</small>
    </div>
</div>
@endsection