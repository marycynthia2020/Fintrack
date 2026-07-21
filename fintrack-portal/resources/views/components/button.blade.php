@props([
    'type' =>'button',
    'variant' => 'primary'
])

@php
    $baseClass = 'w-full inline-flex justify-center items-center px-4 py-3 border border-transparent rounded-lg text-sm font-semibold shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-150 ease-in-out cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed';
    $variants = [
        'primary' => 'bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-500 dark:bg-blue-600 dark:hover:bg-blue-700',
        'secondary' => 'bg-gray-100 hover:bg-gray-200 text-gray-800 focus:ring-gray-500 dark:bg-zinc-800 dark:hover:bg-zinc-750 dark:text-zinc-200',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500 dark:bg-red-600 dark:hover:bg-red-700',
    ];
    $class = $baseClass . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $class]) }}>
    {{ $slot }}
</button>
