@props([
    'message' => session('status'),
])

@if ($message)
    <div {{ $attributes->merge([
        'class' => 'fixed top-4 right-4 mb-4 text-sm font-medium text-green-700 bg-green-50 p-3.5 rounded-lg border border-green-200'
    ]) }}>
        {{ $message }}
    </div>
@endif
