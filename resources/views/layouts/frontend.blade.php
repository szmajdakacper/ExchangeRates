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
                        <a class="nav-link @if(Route::is('latest')) active @endif" href="/">Latest Rates</a>
                    </li>
                    <li class="nav-item mr-2">
                        <a class="nav-link @if(Route::is('historical')) active @endif" href="/historical">Historical</a>
                    </li>
                    @auth
                    @include('inc.user_dropdownMenu')
                    @endauth
                    @guest
                    <li class="nav-item mr-2">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    @endguest
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
    <script>
    $( document ).ready(function() {
        $( "select" ).change(function() {
            var ihaveValue = $( "select#ihaveselect" ).val();
            var iwantValue = $( "select#iwantselect" ).val();
            var iwantAmount = iwantValue/ihaveValue;
            var startValue = 1;
            $( "#ihavetext" ).val(startValue.toFixed(2));
            $( "#iwanttext" ).val(iwantAmount.toFixed(2));
        })
        .trigger( "change" );

        $( "#ihavetext" ).keyup(function() {
            var ihaveValue = $( "select#ihaveselect" ).val();
            var iwantValue = $( "select#iwantselect" ).val();
            var iwantAmount = (iwantValue * $( "#ihavetext" ).val())/ihaveValue;
            $( "#iwanttext" ).val(iwantAmount.toFixed(2));
        });

        $( "#iwanttext" ).keyup(function() {
            var ihaveValue = $( "select#ihaveselect" ).val();
            var iwantValue = $( "select#iwantselect" ).val();
            var ihaveAmount = (ihaveValue * $( "#iwanttext" ).val())/iwantValue;
            $( "#ihavetext" ).val(ihaveAmount.toFixed(2));
        });
    });
</script>
</body>

</html>