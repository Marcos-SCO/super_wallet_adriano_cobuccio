<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.app_title') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <!-- include htmx -->
    {{-- <script src="https://unpkg.com/htmx.org@1.9.3"></script> --}}

    <x-navbar />

    <main class="container mx-auto p-6">
        @yield('content')
    </main>

    <x-footer />

    @stack('scripts')
</body>

</html>
