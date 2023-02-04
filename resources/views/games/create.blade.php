<form action="{{ route('games.store') }}" method="post">
    @csrf
    <x-text-input-mb field="title"
                     placeholder="Title..." />
    <x-text-input-mb field="release_year"
                     placeholder="YYYY" />
    <x-textarea-mb field="blurb" />
    <x-datalist-mb field="platform"
                           placeholder="Platform..."
                           listname="platforms"
                           :data="$platforms" />
    <x-datalist-mb field="format"
                   placeholder="Format..."
                   listname="formats"
                   :data="$formats"/>
    <x-datalist-mb field="genre"
                   placeholder="Genre..."
                   listname="genres"
                   :data="$genres"/>
    <x-submit-mb />
</form>
<x-validation_errors></x-validation_errors>
