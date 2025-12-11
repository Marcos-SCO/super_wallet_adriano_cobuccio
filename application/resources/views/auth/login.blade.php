@extends('layouts.app')

@section('content')
    <form method="POST" action="{{ route('login') }}"
        class="max-w-md mx-auto mt-12 space-y-6 bg-white p-6 rounded-lg shadow-sm border border-l-4 border-gray-200 hover:shadow transition"

        {{-- hx-post="{{ route('login') }}" hx-target="body" hx-swap="outerHTML" --}}
        >

        @csrf

        <h1 class="text-2xl font-bold">{{ __('messages.login') }}</h1>
        <p class="text-sm text-gray-500">
            {{ __('messages.login_sub') ?? 'Access your account to manage your wallet and transactions.' }}
        </p>

        <x-form-input label="{{ __('messages.email') }}" name="email" type="email" focusLabel="login_email"
            :value="old('email')" required />


        <x-form-input label="{{ __('messages.password') }}" name="password" type="password" focusLabel="login_password"
            required />


        <div class="flex items-center">
            <label class="inline-flex items-center cursor-pointer">
                <input type="checkbox" name="remember" class="rounded border-gray-300">
                <span class="ml-2 text-sm text-gray-600">{{ __('messages.remember_me') }}</span>
            </label>
        </div>

        <button data-loading-message="{{ __('messages.loading') }}" data-regular-message="{{ __('messages.login') }}"
            type="submit"
            class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition cursor-pointer">
            {{ __('messages.login') }}
        </button>

        <p class="text-sm text-gray-600 mt-4 text-center">
            {!! __('messages.dont_have_account', [
                'link' =>
                    '<a href="' . route('register') . '" class="text-blue-600 font-medium">' . __('messages.register') . '</a>',
            ]) !!}
        </p>

    </form>
@endsection
