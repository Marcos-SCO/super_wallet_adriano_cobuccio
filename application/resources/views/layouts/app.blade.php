<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.app_title') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <!-- include htmx -->
    {{-- <script src="https://unpkg.com/htmx.org@1.9.3"></script> --}}

    <nav class="bg-white shadow-sm">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('wallet.index') }}" class="text-lg font-bold text-indigo-600">{{ __('messages.brand') }}</a>
                </div>

                <div class="flex items-center space-x-4">

                    @auth
                        <a href="{{ route('wallet.index') }}" class="text-sm text-gray-600 hover:text-gray-800">{{ __('messages.account') }}</a>

                        <span class="text-sm text-gray-700">{{ __('messages.hello', ['name' => auth()->user()->name]) }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-red-600 hover:text-red-800">{{ __('messages.logout') }}</button>
                        </form>
                    @endauth

                    @guest
                        <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-800">{{ __('messages.login') }}</a>
                    @endguest
                    
                    {{-- Language switcher --}}
                    <div class="ml-4">
                        <a href="{{ route('locale.switch', 'en') }}" class="text-sm px-2 {{ app()->getLocale() === 'en' ? 'font-semibold' : '' }}">EN</a>
                        <a href="{{ route('locale.switch', 'pt') }}" class="text-sm px-2 {{ app()->getLocale() === 'pt' ? 'font-semibold' : '' }}">PT</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="container mx-auto p-6">
        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>
