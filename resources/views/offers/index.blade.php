@extends('layouts.exchange')

@section('content')
<h1>Latest Offers:</h1>
@if(count($offers) <= 0)
<p class="lead">There is no offers...</p>
@else
<table class="table">
  <thead>
    <tr>
      <th scope="col">From</th>
      <th scope="col">To</th>
      <th scope="col">Amount</th>
      <th scope="col">Rate</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
    @foreach($offers as $offer)
        <tr>
          <td><a style="text-decoration: none; color: black;" href="/offers/{{$offer->id}}">{{$offer->ihave_code}}</a></td>
          <td><a style="text-decoration: none; color: black;" href="/offers/{{$offer->id}}">{{$offer->iwant_code}}</a></td>
          <td><a style="text-decoration: none; color: black;" href="/offers/{{$offer->id}}">{{$offer->ihave_amount}}</a></td>
          <td><a style="text-decoration: none; color: black;" href="/offers/{{$offer->id}}">{{$offer->rate}}</a></td>
          <td><a class="btn btn-outline-info btn-sm py-0 px-2" href="/offers/{{$offer->id}}">Go To Offer</a></td>
        </tr>    
    @endforeach
    </tbody>
</table>
@endif
@endsection