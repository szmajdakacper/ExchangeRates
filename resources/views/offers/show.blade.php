@extends('layouts.exchange')

@section('content')
    <div class="card mb-3">
    <div class="card-header">
        <h4 class="card-title display-4 text-center">Offer {{$offer->ihave_code}} To {{$offer->iwant_code}}</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-6">
                <p class="lead border">To Buy: <span id="ihave_code">{{$offer->ihave_code}}</span></p>
                <p class="lead">Amount To Buy: <div class="lead" id="ihave_amount">{{$offer->ihave_amount}}</div></p>
                <hr>
                <p class="lead">Offered Rate: <div class="lead" id="rate">{{$offer->rate}}</div></p>                
            </div>
            <div class="col-6">
                <p class="lead border">You Sell: <span id="iwant_code">{{$offer->iwant_code}}</span></p>
                <p class="lead">Amount You Sell: <div class="lead" id="iwant_amount"></div></p>
                <hr>
                <p class="lead">Actual Rate: <div class="lead" id="actualRate">{{$actualRate}}</div></p>                
            </div>
        </div>
        {{ Form::open(['action' => ['OffersController@buy', $offer->id], 'method' => 'post', 'class' => 'form-inline mt-4 justify-content-center']) }}
        {{ Form::bsTextWithButton('amount', 'BUY', 'how much?') }}
        {{ Form::close() }}
        <div id="youreceiveparagraph" class="d-none text-center">
        <hr>
        <p class="lead"> You Sell: <span id="youreceive">1</span> <span id="youreceive_code">X</span></p>
        </div>
        @if(\Auth::id() == $offer->user_id)
            <hr>
            <a href="/offers/{{$offer->id}}/edit" class="btn btn-outline-info btn-block mt-4">CHANGE RATE</a>
            {{ Form::open(['action' => ['OffersController@destroy', $offer->id], 'method' => 'post']) }}
            {{ Form::hidden('_method', 'DELETE') }}
            {{ Form::bsButton('Remove Offer', ['class' => 'btn btn-outline-danger btn-block']) }}
            {{ Form::close() }}
        @endif
    </div>
    <div class="card-footer">
    <small>Added at: {{ $offer->created_at }}</small>
    </div>
</div>
@endsection