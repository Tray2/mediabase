@extends('layouts.master')
@section('main')

    <form action="{{ route('records.update', $record) }}" method="post">
        @csrf
        @method('PUT')
        <x-text-input-mb field="title"
                         placeholder="Title..."
                         value="{{ $record->title }}"/>
        <x-text-input-mb field="release_year"
                         placeholder="YYYY"
                         value="{{ $record->release_year }}"/>
        <x-text-input-mb field="barcode"
                         placeholder="Barcode..."
                         value="{{ $record->barcode }}"/>
        <x-text-input-mb field="spine_code"
                         placeholder="Spine Code..."
                         value="{{ $record->spine_code }}"/>
        <x-datalist-mb field="format"
                       placeholder="Format..."
                       listname="formats"
                       :data="$formats"
                       value="{{ $record->format }}"/>
        <x-datalist-mb field="genre"
                       placeholder="Genre..."
                       listname="genres"
                       :data="$genres"
                       value="{{ $record->genre }}"/>
        <x-datalist-mb field="country"
                       placeholder="Country..."
                       listname="countries"
                       :data="$countries"
                       value="{{ $record->country }}"/>
        <x-datalist-mb field="artist"
                       placeholder="Artist..."
                       listname="artists"
                       :data="$artists"
                       value="{{ $record->artist }}"
                       suffix=""/>
        <x-datalist-mb field="record_label"
                       placeholder="Record Label..."
                       listname="record_labels"
                       :data="$recordLabels"
                       value="{{ $record->record_label }}"/>
        @forelse($tracks as $track)
            <x-track-field
                position="{{ old('track_positions.' . $loop->index, $track->position) }}"
                artist="{{ old('track_artists.' . $loop->index, $track->artist) }}"
                title="{{ old('track_titles.' . $loop->index, $track->title) }}"
                duration="{{ old('track_durations.' . $loop->index, $track->duration) }}"
                mix="{{ old('track_mixes.' . $loop->index, $track->mix) }}"/>
        @empty
            <x-track-field/>
        @endforelse
        <x-submit-mb/>
    </form>
    <x-validation_errors></x-validation_errors>
@endsection
