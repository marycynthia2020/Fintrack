@props([
    'name'
])

@error($name)
<p class="mt-1.5 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
@enderror