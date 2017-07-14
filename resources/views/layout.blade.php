<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel 5 for Barchart</title>

        <!-- Fonts -->
        <link rel="stylesheet" type="text/css" href="/css/app.css">
        <!-- Styles -->

        <script type="text/javascript" src="/js/jquery-latest.js"></script> 
        <script type="text/javascript" src="/js/jquery.tablesorter.js"></script> 
        
        
    </head>
    <body>
        <div class="container">
            @yield('content')
        </div>
    </body>
</html>
