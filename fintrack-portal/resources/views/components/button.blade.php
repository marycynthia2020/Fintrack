@props([
    'type' =>'button',
    'variant' => 'primary'
])

@php
    $baseClass = 'w-full inline-flex justify-center items-center px-4 py-3 border border-transparent rounded-lg text-sm font-semibold shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-150 ease-in-out cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed';
    $variants = [
        'primary' => 'bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-500',
        'secondary' => 'bg-slate-100 hover:bg-slate-200 text-slate-700 focus:ring-slate-400',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500',
    ];
    $class = $baseClass . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $class]) }}>
    {{ $slot }}
</button>
