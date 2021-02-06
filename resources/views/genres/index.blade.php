@extends('layouts.app')
@if(isset($type))
    @include('common.' . strtolower($type) . '_subnav')
@endif
@section('content')
@auth
   @if(isset($type))
     <a href="/genres/create?type={{ $type }}" class="bg-blue-500  hover:bg-blue-700 text-white font-bold mb-2 py-2 px-4 rounded">Add genre</a>
   @else
     <a href="/genres/create" class="bg-blue-500  hover:bg-blue-700 text-white font-bold mb-2 py-2 px-4 rounded">Add genre</a>
   @endif
@endauth
@if(count($genres) == 0)
    <h3>No genres found</h3>
@else
    <table class="mt-6 w-1/3">
        <tr class="text-left bg-gray-500 text-xl"><th class="py-2 pl-2">Genre</th><th>{{ ucfirst(strtolower($type)) }}</th></tr>
        @foreach($genres as $genre)
            <tr class="border-b-2 text-lg text-gray-800">
                <td class="pl-2 py-2">
                    <a href="{{ route('genres.show', $genre->id )}}" class="hover:underline">{{ $genre->genre }}</a>
                </td>
                @if($genre->media_type_id == env('BOOKS'))
                    <td>{{ $genre->books_count }}</td>
                @elseif($genre->media_type_id == env('RECORDS'))
                    <td>{{ $genre->records_count }}</td>
                @endif
            </tr>
        @endforeach
    </table>
@endif
@endsection
