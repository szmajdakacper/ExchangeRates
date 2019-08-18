<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.3.0/css/flag-icon.css" integrity="sha256-jrNAqq4Gy0Gg2b6G6l0n57dPr6N1twCn+JMqY8x3l88=" crossorigin="anonymous" />
    <link rel="stylesheet" href="/css/app.css" >
    <title>Exchange Rates</title>
</head>

<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark mb-3">
        <div class="container">
            <a class="navbar-brand" href="/">ExchangeRates</a>
            <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarNav"><span
                    class="navbar-toggler-icon"></span></button>
            <div id="navbarNav" class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item mr-2">
                        <a class="nav-link" href="/">Rates</a>
                    </li>
                    @include('inc.user_dropdownMenu')
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
    @if(session('success'))
    <p class="alert alert-success text-center">{{session('success')}}</p>
    @elseif(session('error'))
    <p class="alert alert-danger text-center">{{session('error')}}</p>
    @endif
    @yield('content')    
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"
        integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous">
    </script>
    @if(Route::is('offers.show'))
    <script>
    $( document ).ready(function() {
        var ihave = $("#ihave_amount").text();
        var iwant_code = $("#iwant_code").text();
        var rate = $("#rate").text();
        var amount_iwant = ihave * rate;
        $("#iwant_amount").text(amount_iwant.toFixed(2));

        $( "#amount" ).keyup(function(){
            if($( "#amount" ).val() > 0) {
                $( "#youreceiveparagraph" ).removeClass( "d-none" );
                var youreceive = rate * $( "#amount" ).val();
                $( "#youreceive" ).text(youreceive.toFixed(2));
                $( "#youreceive_code" ).text(iwant_code);
                
            } else if ($( "#amount" ).val() <= 0) {
                $( "#youreceiveparagraph" ).addClass( "d-none" );
            }         
        });
    });
    </script>
    @endif
    @if(Route::is('offers.create') || Route::is('offers.edit'))
    <script>
    $( document ).ready(function() {

        var receive = $( "#ihave_amount" ).val() * $( "#myrate" ).val();
        $( "#ireceive_amount" ).val(receive.toFixed(2));

        $( "select" ).change(function() {

            var ihaveValue = $( "select#ihavecurrency" ).val();
            var iwantValue = $( "select#iwantcurrency" ).val();
            var latestrate = iwantValue/ihaveValue;
            var startValue = 100;

            $( "#ihave_amount" ).val(startValue.toFixed(2));
            
            $( "#latestrate, #myrate" ).val(latestrate.toFixed(4));

            var receive = $( "#ihave_amount" ).val() * $( "#myrate" ).val();
            $( "#ireceive_amount" ).val(receive.toFixed(2));

            var ihave_code = $( "select#ihavecurrency option:selected" ).text().substring(0, 3);
            var iwant_code = $( "select#iwantcurrency option:selected" ).text().substring(0, 3);

            $('input[name="ihave_code"]').attr('value', ihave_code);
            $('input[name="iwant_code"]').attr('value', iwant_code);
        })
        .trigger( "change" );  

        $( "#ihave_amount" ).keyup(function() {
            var receive = $( "#ihave_amount" ).val() * $( "#myrate" ).val();
            $( "#ireceive_amount" ).val(receive.toFixed(2));
        });

        $( "#myrate" ).keyup(function() {
            var receive = $( "#ihave_amount" ).val() * $( "#myrate" ).val();
            $( "#ireceive_amount" ).val(receive.toFixed(2));
        });

        $( "#ireceive_amount" ).keyup(function() {
            var ihave = $( "#ireceive_amount" ).val() / $( "#myrate" ).val();
            $( "#ihave_amount" ).val(ihave.toFixed(2));
        });       
    });
    </script>
    @endif
</body>

</html>