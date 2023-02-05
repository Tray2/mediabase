<form action="{{ route('records.store') }}" method="post">
    @csrf
    <x-text-input-mb field="title"
                     placeholder="Title..." />
    <x-text-input-mb field="release_year"
                     placeholder="YYYY" />
    <x-text-input-mb field="barcode"
                     placeholder="Barcode..." />
    <x-text-input-mb field="spine_code"
                     placeholder="Spine Code..." />
    <x-datalist-mb field="format"
                   placeholder="Format..."
                   listname="formats"
                   :data="$formats"/>
    <x-datalist-mb field="genre"
                   placeholder="Genre..."
                   listname="genres"
                   :data="$genres"/>
    <x-datalist-mb field="country"
                   placeholder="Country..."
                   listname="countries"
                   :data="$countries"/>
    <x-datalist-mb field="artist"
                   placeholder="Artist..."
                   listname="artists"
                   :data="$artists"
                   suffix="" />
    <x-datalist-mb field="record_label"
                   placeholder="Record Label..."
                   listname="record_labels"
                   :data="$recordLabels"/>

    @forelse(old('track_positions', []) as $position)
        <x-track-field
            position="{{ old('track_positions.' . $loop->index) }}"
            artist="{{ old('track_artists.' . $loop->index, '') }}"
            title="{{ old('track_titles.' . $loop->index) }}"
            duration="{{ old('track_durations.' . $loop->index) }}"
            mix="{{ old('track_mixes.' . $loop->index) }}" />
    @empty
        <x-track-field />
    @endforelse
    <x-submit-mb />
</form>
<x-validation_errors></x-validation_errors>
