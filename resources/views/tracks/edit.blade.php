@extends('layouts.app')
@section('content')
    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
        <form method="POST" action="/tracks">
            <div class="flex flex-wrap -mx-3 mt-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="record_id" value="{{ $track->record_id }}">
                <label for="track_no">Track #</label>
                <input type="text" name="track_no" value="{{ $track->track_no }}">
                <label for="title">Title</label>
                <input type="text" name="title" value="{{ $track->title }}">
                <select name="mix">
                    <option value="studio">Studio</option>
                </select>
                <div class="flex w-full justify-end">
                    <input type="submit" value="Save" class="bg-blue-500  hover:bg-blue-700 text-white font-bold mx-3 mb-2 py-2 px-4 rounded">
                </div>
            </div>
        </form>
    </div>˛
@endsection
˛˛˛
