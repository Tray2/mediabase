@extends('layouts.app')
@section('content')
<div class="flex">
    <div class="mt-4 w-1/4">
        <h3 class="text-xl bg-gray-500 px-1 py-2 font-semibold rounded-t-lg">Stats:</h3>
        <ul class="bg-gray-300 py-1 text-lg px-2 rounded-b-lg">
            <li class="py-1 flex justify-between border-b-2 border-gray-400"><span>Books in collection:</span><a href="/bookcollections/{{ Auth::user()->id }}" class="hover:underline">{{ $bookCount }}</a></li>
            <li class="py-1 flex justify-between border-b-2 border-gray-400"><span>Games in collection:</span><a href="/gamecollections/{{ Auth::user()->id }}" class="hover:underline">0</a></li>
            <li class="py-1 flex justify-between border-b-2 border-gray-400"><span>Movies in collection:</span><a href="/moviecollections/{{ Auth::user()->id }}" class="hover:underline">0</a></li>
            <li class="py-1 flex justify-between"><span>Records in collection:</span><a href="/recordcollections/{{ Auth::user()->id }}" class="hover:underline">0</a></li>
        </ul>
    </div>
    <div class="mt-4 w-1/4">
        <ul class="bg-gray-300 py-1 text-lg px-2 rounded-b-lg">
            <li class="py-1 flex justify-between border-b-2 border-gray-400">Books Read: {{ $readCount }}</a></li>
        </ul>
    </div>
</div>
@endsection
