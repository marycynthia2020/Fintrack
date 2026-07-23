@props(['value'])

<label {{ $attributes->merge(['class' => 'ft-label block font-medium']) }}>
    {{ $value ?? $slot }}
</label>
