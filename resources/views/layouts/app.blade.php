<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- CSRF Token -->
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- script src="{{ asset('js/app.js') }}" defer></script --> <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script> <!-- Scripts -->
    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"> <!-- Styles -->
    @yield('style')
</head>

<body>
    <div id="app">
        @include('layouts.partial.navigation')
        {{-- @if(session()->has('flash_message'))
            <div class="alert alert-info" role="alert">
                {{ session('flash_message') }}
            </div>
        @endif --}}
        @include('flash::message')
        <main class="py-4">
            @yield('content')
            @yield('script')
        </main>
    </div>
    @include('layouts.partial.footer')
</body>
</html>
