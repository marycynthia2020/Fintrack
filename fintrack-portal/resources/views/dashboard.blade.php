<x-app-layout>
<form action="{{route('logout')}}" method="post">
    <button>logout</button>
@csrf
</form>
</x-app-layout>
