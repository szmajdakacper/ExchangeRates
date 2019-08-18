@extends('layouts.app')

@section('content')
<h1>Dashboard</h1>
<a href="/offers">Go to Currency Market</a>
<form id="logout-form" action="{{ route('logout') }}" method="POST">
    @csrf
    <input type="submit" value="Logout">
 </form>
 <ul class="list-group">
 @foreach($myOffers as $offer)
    <a style="text-decoration: none; color: black;" href="/offers/{{$offer->id}}">
        <li class="list-group-item">{{$offer->ihave_code}} TO {{$offer->iwant_code}} || Rate: {{$offer->rate}} || Amount: {{$offer->ihave_amount}}
            <br>
            <small>Added at: {{ $offer->created_at }}</small>
        </li>
    </a>
 @endforeach
 </ul>
 @endsection