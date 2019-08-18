@extends('layouts.frontend')


@section('content')
@include('latestrates.conventer')
<!-- CARD WITH NAV -->
<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item">
                <a class="nav-link active" href="/">Most Popular</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/fromto/A/G">A-G</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/fromto/H/M">H-M</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/fromto/N/T">N-T</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/fromto/U/Z">U-Z</a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <h4 class="card-title text-center">Today's Rates, where EUR = 1</h4>
        <!--{{$i = 0}}-->
        @foreach($latestrates as $latestrate)
            @if($latestrate->code == 'USD' || $latestrate->code == 'CHF' || $latestrate->code == 'GBP' || $latestrate->code == 'JPY' || $latestrate->code == 'AUD')
            <ul class='list-group text-center'>
                <li class='list-group-item w-100'>
                    {{++$i}}. {{$latestrate->currency->name}} {!!$latestrate->currency->flag!!}  : {{$latestrate->rate}}[{{$latestrate->code}}]
                </li>
            </ul>
            @endif
        @endforeach
    </div>
</div>
    
@endsection