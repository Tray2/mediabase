@extends('layouts.app')
@section('content')
    @include('common.records_subnav')
    @if (session('error'))
        <div class="flex justify-center">
            <div class="m-5 w-1/3 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                {{ session('error') }}
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
        </span>
            </div>
        </div>
    @endif
    @auth
        <a href="/artists/create" class="bg-blue-500  hover:bg-blue-700 text-white font-bold mb-2 py-2 px-4 rounded">Add artist</a>
    @endauth
    <ul>
    @foreach($artists as $artist)
            <tr class="border-b-2 text-lg text-gray-800"><td class="pl-2 py-2"><a href="{{ route('artists.show', $artist->id )}}" class="hover:underline">{{ $artist->name }}</a></td><td>{{ $artist->records->count() }}</td></tr>
    @endforeach
    </ul>
    @if(count($artists) === 0)
        No artists to display
    @endif
@endsection
