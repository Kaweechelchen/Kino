<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Tezza (https://twitter.com/FAQ)">
    <meta name="description" content="Movie Showtimes by Morroni Studios">
    <meta property="og:title" content="Movie Showtimes by Morroni Studios" />
    <meta property="og:description" content="Upcoming movie screenings in theatres of Luxembourg" />
    <meta property="og:image" content="{{ URL::to('/') }}/img/logo.svg" />
    <link rel="shortcut icon" href="{{ URL::to('/') }}/img/logo.svg" />
    <title>Movies &middot; Morroni Studios</title>
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
</head>

<body class="@yield('bodyClass')">
    <div class="container-fluid" id="app">
        @yield('content')
    </div>
    @include('footer')
    <script src="{{ mix('/js/app.js') }}"></script>
</body>

</html>
