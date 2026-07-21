@props([
    'title' => null,
    'subtitle' => null
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-zinc-950 border border-gray-100 dark:border-zinc-850 shadow-md rounded-2xl p-6 md:p-8']) }}>
    @if($title || $subtitle)
        <div class="mb-6 flex flex-col items-center">
            @if($title)
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white leading-tight">
                    {{ $title }}
                </h2>
            @endif
            @if($subtitle)
                <p class="mt-1.5 text-sm text-gray-500 dark:text-zinc-400">
                    {{ $subtitle }}
                </p>
            @endif
        </div>
    @endif
    {{ $slot }}
</div>
