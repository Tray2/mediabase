@extends('layouts.app')
@section('content')
@auth
    <a href="/genres/create" class="bg-blue-500  hover:bg-blue-700 text-white font-bold mb-2 py-2 px-4 rounded">Add genre</a>
@endauth
@if(count($genres) == 0)
    <h3>No genres found</h3>
@else
    <table class="mt-6 w-1/3">
        <tr class="text-left bg-gray-500 text-xl"><th class="py-2 pl-2">Genre</th><th>Items</th></tr>
        @foreach($genres as $genre)
            <tr class="border-b-2 text-lg text-gray-800">
                <td class="pl-2 py-2">
                    <a href="{{ route('genres.show', $genre->id)}}" class="hover:underline">{{ $genre->genre }}</a>
                </td>
                <td>{{ $genre->count }}</td>
            </tr>
        @endforeach
    </table>
@endif
@endsection
