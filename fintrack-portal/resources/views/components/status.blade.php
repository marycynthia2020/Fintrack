@props([
    'message' => session('status'),
])

@if ($message)
    <div {{ $attributes->merge([
        'class' => 'mb-4 text-sm font-medium text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-950/20 p-3.5 rounded-lg border border-green-200 dark:border-green-900/30'
    ]) }}>
        {{ $message }}
    </div>
@endif