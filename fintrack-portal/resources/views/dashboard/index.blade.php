@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    {{-- <div class="bg-white dark:bg-zinc-950 border border-gray-100 dark:border-zinc-800 shadow-md rounded-2xl p-6 md:p-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
            Welcome to the dashboard
        </h1>
        <p class="text-gray-600 dark:text-zinc-400 mb-6">
            You are successfully authenticated! You are signed in as 
            <span class="font-semibold text-gray-900 dark:text-zinc-200">{{ auth()->user()->name }}</span> 
            (<span class="italic">{{ auth()->user()->email }}</span>)
            @if(auth()->user()->organization)
                of <span class="font-semibold text-gray-900 dark:text-zinc-200">{{ auth()->user()->organization->name }}</span>.
            @endif
        </p>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="inline-flex justify-center items-center px-4 py-2.5 border border-transparent rounded-lg text-sm font-semibold shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-150 ease-in-out cursor-pointer bg-red-600 hover:bg-red-750 text-white focus:ring-red-500">
                Logout
            </button>
        </form>
    </div> --}}

    Thiss is the dashboard
</div>
@endsection
