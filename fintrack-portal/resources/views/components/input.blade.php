@props([
    'label' => null,
    'name',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false
])

<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    <input 
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge([
            'class' => 'w-full px-4 py-2.5 border rounded-lg shadow-sm transition-all duration-150 ease-in-out focus:outline-none focus:ring-2 ' . 
            ($errors->has($name) 
                ? 'border-red-500 placeholder-red-300 focus:ring-red-500 focus:border-red-500' 
                : 'border-gray-300 dark:border-zinc-700 text-gray-900 dark:text-zinc-100 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-900')
        ]) }}
    />
        <x-error :name="$name" />
</div>
