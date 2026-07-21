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
        <label for="{{ $name }}" class="block text-sm font-medium text-slate-700 mb-1">
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
                : 'border-slate-200 text-slate-900 placeholder-slate-400 focus:ring-blue-500 focus:border-blue-500 bg-white')
        ]) }}
    />
        <x-error :name="$name" />
</div>
