@props([
    'title' => null,
    'subtitle' => null
])

<div {{ $attributes->merge(['class' => 'bg-white border border-slate-100 shadow-sm rounded-2xl p-6 md:p-8']) }}>
    @if($title || $subtitle)
        <div class="mb-6 flex flex-col items-center">
            @if($title)
                <h2 class="text-2xl font-bold text-slate-900 leading-tight">
                    {{ $title }}
                </h2>
            @endif
            @if($subtitle)
                <p class="mt-1.5 text-sm text-slate-500">
                    {{ $subtitle }}
                </p>
            @endif
        </div>
    @endif
    {{ $slot }}
</div>
