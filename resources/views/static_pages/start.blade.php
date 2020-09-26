@extends('layouts.app')
@section('content')
    <div>
        <h2 class="text-6xl">Welcome to Mediabase.</h2>
        <div class="self-end">
            <ul class="flex">
               <li class="text-gray-600 text-m">Over {{ $bookCounter }} books,</li>
               <li class="ml-2 text-gray-600 text-m">{{ $recordCounter }} records,</li>
               <li class="ml-2 text-gray-600 text-m">0 movies</li>
               <li class="ml-2 text-gray-600 text-m">and 0 games.</li>
            </ul>
        </div>
        <div class="mt-6">
            <h4 class="text-3xl">Some info about the site goes here.</h4>
            <p class="mt-2 text-m">
                Some more info here.
            </p>
        </div>
    </div>
@endsection
