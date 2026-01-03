@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-bold text-xs text-gray-500 uppercase tracking-wider mb-2 ml-1']) }}>
    {{ $value ?? $slot }}
</label>
