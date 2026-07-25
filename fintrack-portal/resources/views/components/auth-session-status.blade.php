@props(['status'])

@if ($status)
    <div id="toast" {{ $attributes->merge(['class' => 'fixed top-4 right-4 font-medium text-sm text-green-600 dark:text-green-400']) }}>
        {{ $status }}
    </div>


     <script>
        setTimeout(() => {
            document.getElementById('toast')?.remove();
        }, 3000);
    </script>
@endif
