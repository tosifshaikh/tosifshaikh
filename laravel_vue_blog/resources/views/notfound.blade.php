<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" value="{{ csrf_token() }}"/>

        <title>Page has no permission</title>

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
            <h1>You don't have enough permission.</h1>
    </body>
</html>
