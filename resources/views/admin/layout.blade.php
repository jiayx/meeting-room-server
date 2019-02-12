<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <title>会议室预订后台管理 - @yield('title')</title>
</head>
<body>
<div id="app">
    @yield('content')
</div>
<script src="{{ asset('js/app.js', config('app.env') == 'production') }}"></script>
</body>
</html>
