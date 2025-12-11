@extends('layouts.app')

@section('content')
    <form method="POST" action="{{ route('login') }}" class="max-w-md mx-auto mt-12 space-y-6 bg-white p-6 rounded-lg shadow-sm border border-gray-200 border-l-4 border-indigo-300 hover:shadow transition">
        @csrf
        <h1 class="text-2xl font-bold">{{ __('messages.login') }}</h1>
        <p class="text-sm text-gray-500">{{ __('messages.login_sub') ?? 'Access your account to manage your wallet and transactions.' }}</p>

        <div>
            <label for="email" class="block text-gray-700">{{ __('messages.email') }}</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}"
                class="mt-1 w-full px-3 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-200" required autofocus>
            @error('email')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-gray-700">{{ __('messages.password') }}</label>
            <input type="password" name="password" id="password" class="mt-1 w-full px-3 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-200" required>
            @error('password')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="inline-flex items-center">
                <input type="checkbox" name="remember" class="rounded border-gray-300">
                <span class="ml-2 text-sm text-gray-600">{{ __('messages.remember_me') }}</span>
            </label>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">{{ __('messages.login') }}</button>

        <p class="text-sm text-gray-600 mt-4 text-center">
            {!! __('messages.dont_have_account', ['link' => '<a href="' . route('register') . '" class="text-blue-600 font-medium">' . __('messages.register') . '</a>']) !!}
        </p>
    </form>
@endsection
