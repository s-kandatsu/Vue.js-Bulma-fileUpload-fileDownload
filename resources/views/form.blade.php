<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>フォームテスト</title>

    </head>
    <body>
        <div id="app">
            <index></index>
        </div>

        <script src="{{ asset('/js/app.js') }}"></script>
    </body>
</html>
