<x-app-layout>
    <ul  class="my-8">
        @forelse ($incomes as $income)
            <li>{{$income->title}}</li>
            @empty
            <p>No income yet</p>
        @endforelse
    </ul>
</x-app-layout>
