<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"    content="width=device-width, initial-scale=1">
    <meta name="keyword"     content="Nclusive, profiles, celebrities, fame">
    <meta name="description" content="A site to post your profile">
    <meta name="author"      content="Jason Monroe">

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/css/datepicker.css') }}">
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/js/jgrowl/jquery.jgrowl.min.css') }}">
    <link rel="stylesheet" type="text/css" media="screen" href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" type="text/css" media="screen" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" type="text/css" media="screen" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/webfont/1.5.0/webfont.js"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jgrowl/jquery.jgrowl.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/bootstrap-datepicker.js') }}"></script>

    <!-- Latest compiled and minified JavaScript -->
    <script type="text/javascript" src="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

    <title>Nclusive: {{ $title }}</title>
</head>
<body>
    <div id="banner"> NCLUSIVE  </div>

    @include('jgrowl')
    <nav id="nav-bar">
        <ul>
            <li>{{ link_to('/', 'Home') }}</li>
            <li>{{ link_to('create', 'Post') }}</li>
            <li>{{ link_to('search', 'Search') }}</li>
            <li>Powered by {{ link_to('http://www.laravel.com', 'Laravel') }}</li>
        </ul>
    </nav>
    <div class="container">

        <hr>
        @yield('content')
        <hr>
    </div>


    <footer>

        <span>Copyright 2014 &copy; | Nclusive | Property of Jason Monroe | All Rights Reserved</span>
    </footer>
</body>
</html>
