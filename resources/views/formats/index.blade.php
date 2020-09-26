@extends('layouts.app')
@include('common.book_subnav')
@section('content')
@auth
    <a href="/formats/create" class="bg-blue-500  hover:bg-blue-700 text-white font-bold mb-2 py-2 px-4 rounded">Add format</a>
@endauth
@if(count($formats) == 0)
    <h3>No formats found</h3>
@else
    <table class="mt-6 w-1/3">
        <tr class="text-left bg-gray-500 text-xl"><th class="py-2 pl-2">Format</th><th>Type</th><th>Items</th></tr>
        @foreach($formats as $format)
            <tr class="border-b-2 text-lg text-gray-800">
                <td class="pl-2 py-2">
                    <a href="{{ route('formats.show', $format->id )}}" class="hover:underline">{{ $format->format }}</a>
                </td>
                <td>{{ucfirst($format->media_types->media)}}</td>
                <td>{{ $format->counts }}</td>
            </tr>
        @endforeach
    </table>
@endif
@endsection
