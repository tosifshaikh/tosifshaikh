<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" value="{{ csrf_token() }}"/>

        <title>Laravel Full stack</title>

        <!-- Styles -->
        <link rel="stylesheet" href='css/app.css'>
        <script>
        (function(){
            window.Laravel = {
                csrfToken : '{{ csrf_token() }}'
            };
        })();
        </script>
    </head>
    <body>
        <div id="app">
            @if(Auth::check())
            <App :user="{{Auth::user()}}"></App>
            @else
            <App :user="false" ></App>
            @endif
        </div>
        <script src="{{mix('js/app.js')}}"  ></script>
        <script src="{{asset('js/vendor-all.min.js')}}"  ></script>
        <script src="{{asset('js/bootstrap.min.js')}}"  ></script>

        <script src="{{asset('js/pcoded.min.js')}}" ></script>
    </body>
</html>
