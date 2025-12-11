@extends('layouts.app')

@section('content')
    <form method="POST" action="{{ route('register') }}"
        class="max-w-md mx-auto mt-12 space-y-6 bg-white p-6 rounded-lg shadow-sm border border-l-4 border-gray-200 hover:shadow transition"

        {{-- hx-post="{{ route('register') }}" hx-target="body" hx-swap="outerHTML" --}}
        >

        @csrf
        <h1 class="text-2xl font-bold">{{ __('messages.register') }}</h1>
        <p class="text-sm text-gray-500">{{ __('messages.register_sub') }}</p>

        <x-form-input label="{{ __('messages.name') }}" name="name" focusLabel="register_name" :value="old('name')"
            required />

        <x-form-input label="{{ __('messages.email') }}" name="email" focusLabel="register_email" type="email"
            :value="old('email')" required />

        <x-form-input label="{{ __('messages.password') }}" name="password" focusLabel="register_password" type="password"
            required />

        <x-form-input label="{{ __('messages.confirm_password') }}" name="password_confirmation"
            focusLabel="register_password_confirmation" type="password" required />

        <button data-loading-message="{{ __('messages.loading') }}" data-regular-message="{{ __('messages.register') }}"
            type="submit"
            class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition cursor-pointer">{{ __('messages.register') }}</button>

        <p class="text-sm text-gray-600 mt-4 text-center">
            {!! __('messages.already_have_account', [
                'link' => '<a href="' . route('login') . '" class="text-blue-600 font-medium">' . __('messages.login') . '</a>',
            ]) !!}
        </p>
    </form>
@endsection
