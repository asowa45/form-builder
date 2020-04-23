<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('form-builder/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    @stack('head-scripts')
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a href="{{route('home')}}" class="navbar-brand">Form Builder</a>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>
</div>

<!-- Scripts -->
{{--<script src="{{ asset('form-builder/js/jquery.js') }}"></script>--}}
{{--<script src="{{ asset('form-builder/js/bootstrap/bootstrap.js') }}"></script>--}}
<script src="{{ asset('js/app.js') }}" defer></script>
@stack('script')
</body>
</html>
