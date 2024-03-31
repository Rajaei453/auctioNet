<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="{{URL::asset('css/cstyles.css')}}" rel="stylesheet" />
    <!-- Styles -->
    <style>
    </style>

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
    <!-- firebase integration started -->




</head>
<body class="container">


<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="{{route('showUsers',$jwt_token)}}">RESERVATION.com</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link active" href="{{route('showUsers',$jwt_token)}}">All Users</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link active" href="{{route('showAllProviders',$jwt_token)}}">All Providers <span class="sr-only"></span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{route('providerInfo',$jwt_token)}}">Your Inforamtion</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{route('allReservations',$jwt_token)}}">Reservations</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link active alert-danger" href="{{route('logout',$jwt_token)}}">Logout<span class="sr-only"></span></a>
            </li>
        </ul>
    </div>
</nav>

@yield('content')
</body>

</html>
