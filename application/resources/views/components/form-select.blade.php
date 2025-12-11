@props([
    'label' => '',
    'name',
    'focusLabel' => null,
    'options' => [],
    'value' => null,
    'placeholder' => null,
    'errorBag' => null,
    'required' => false,
])

@php
    $bag = $errorBag ? $errors->getBag($errorBag) : $errors;
    $hasError = $bag->has($name);
    $selectClasses = 'w-full border p-2 rounded mt-1 border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition-shadow duration-150 shadow-sm';
    if ($hasError) {
        $selectClasses .= ' border-red-500';
    }
    $selected = old($name, $value);
    $id = $focusLabel ?? $name;
@endphp

<div class="mb-2">
    <label for="{{ $id }}" class="block text-gray-700 cursor-pointer">{{ $label }}</label>

    <select name="{{ $name }}" id="{{ $id }}" @if ($required) required @endif
        {{ $attributes->merge(['class' => $selectClasses]) }}>

        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach ($options as $optKey => $opt)
            @php
                $optValue = $optKey;
                $optLabel = $opt;

                if (is_array($opt)) {
                    $optValue = $opt['id'] ?? ($opt['value'] ?? $optKey);
                    $optLabel = $opt['label'] ?? ($opt['name'] ?? $optValue);
                }

                if (is_object($opt)) {
                    $optValue = $opt->id ?? ($opt->value ?? $optKey);

                    $optLabel = $opt->name ?? ($opt->label ?? $optValue);

                    if (isset($opt->email) && isset($opt->name)) {
                        $optLabel = $opt->name . ' (' . $opt->email . ')';
                    }
                }
            @endphp

            <option value="{{ $optValue }}" {{ (string) $optValue === (string) $selected ? 'selected' : '' }}>
                {{ $optLabel }}
            </option>
        @endforeach
    </select>

    @if ($hasError)
        <p class="text-red-500 text-sm mt-1">{{ $bag->first($name) }}</p>
    @endif
</div>
