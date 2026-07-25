<x-app-layout>
    <ul  class="my-8">
        @forelse ($expenses as $expense)
            <li>{{$expense->title}}</li>
            @empty
            <p>No expenses yet</p>
        @endforelse
    </ul>
</x-app-layout>
