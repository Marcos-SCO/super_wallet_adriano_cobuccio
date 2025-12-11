@props([
    'label' => '',
    'name',
    'focusLabel' => null,
    'type' => 'text',
    'value' => null,
    'step' => null,
    'errorBag' => null,
    'required' => false,
])

@php
    $bag = $errorBag ? $errors->getBag($errorBag) : $errors;
    $hasError = $bag->has($name);
    $inputClasses = 'mt-1 w-full px-3 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition-shadow duration-150 shadow-sm';

    if ($hasError) {
        $inputClasses .= ' border-red-500';
    }
    $id = $focusLabel ?? $name;
@endphp

<div class="mb-2">
    <label for="{{ $id }}" class="block text-gray-700 cursor-pointer">{{ $label }}</label>

    @if ($type == 'textarea')
        <textarea name="{{ $name }}" id="{{ $id }}" @if ($required) required @endif
            {{ $attributes->merge(['class' => $inputClasses]) }}>{{ old($name, $value) }}
        </textarea>
    @endif

    @if ($type !== 'textarea')
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}"
            @if ($type !== 'password') value="{{ old($name, $value) }}" @endif
            @if ($step) step="{{ $step }}" @endif
            @if ($required) required @endif {{ $attributes->merge(['class' => $inputClasses]) }}>
    @endif

    @if ($hasError)
        <p class="text-red-500 text-sm mt-1">{{ $bag->first($name) }}</p>
    @endif
</div>
