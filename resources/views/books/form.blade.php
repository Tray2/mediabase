@if(isset($book))
@foreach($book->author as $author)
<input type="hidden" name="author_id" value="{{ isset($author) ? old('author_id', $author->id): old('author_id') }}">
@endforeach
@else
<input type="hidden" name="author_id" value="{{ isset($author) ? old('author_id', $author->id): old('author_id') }}">
@endif
@csrf
<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
    <label for="title" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Title</label>
    <input type="text" name="title"
        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
        value="{{ isset($book) ? old('title', $book->title): old('title') }}"
        required>
</div>
<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
    <label for="series" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Series</label>
    <input type="text" name="series"
        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
        value="{{ isset($book) ? old('series', $book->series): old('series') }}">
</div>
<div class="w-full md:w-2/12 px-3 mb-6 md:mb-0">
    <label for="part" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Part</label>
    <input type="number" name="part"
        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
        value="{{ isset($book) ? old('part', $book->part): old('part') }}">
</div>
<div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
    <label for="isbn" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">isbn</label>
    <input type="text" name="isbn"
        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
        value="{{ isset($book) ? old('isbn', $book->isbn): old('isbn') }}"
        required>
</div>
<div class="w-full md:w-1/6 px-3 mb-6 md:mb-0">
    <label for="released" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Relesed</label>
    <input type="text" name="released"
        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
        value="{{ isset($book) ? old('released', $book->released): old('released') }}"
        required>
</div>
<div class="w-full md:w-1/6 px-3 mb-6 md:mb-0">
    <label for="reprinted" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Reprinted</label>
    <input type="text" name="reprinted"
        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
        value="{{ isset($book) ? old('reprinted', $book->reprinted): old('reprinted') }}">
</div>
<div class="w-full md:w-2/12 px-3 mb-6 md:mb-0">
    <label for="pages" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Pages</label>
    <input type="text" name="pages"
        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
        value="{{ isset($book) ? old('pages', $book->pages): old('pages') }}"
        required>
</div>
<div class="w-full md:w-2/4 px-3 mb-6 md:mb-0">
    <label for="genre_id" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Genre</label>
    <div class="relative">
        <select name="genre_id"
            class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
            required>
            @if(old('genre_id'))
            <option value="" disabled>Select your genre</option>
            @else
            <option value="" disabled {{ isset($book) ? '' : 'selected' }}>Select your genre</option>
            @endif
            @foreach($genres as $genre)
            <option value="{{ $genre->id }}" {{ isset($book) && $book->genre_id == $genre->id ? 'selected' : '' }}>{{ $genre->genre }}</option>
            @endforeach
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" /></svg>
        </div>
    </div>
</div>
<div class="w-full md:w-2/4 px-3 mb-6 md:mb-0">
    <label for="format_id" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Format</label>
    <div class="relative">
        <select name="format_id"
            class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
            required>
            @if(old('format_id'))
            <option value="" disabled>Select your format</option>
            @else
            <option value="" disabled {{ isset($book) ? '' : 'selected' }}>Select your format</option>
            @endif
            @foreach($formats as $format)
            <option value="{{ $format->id }}" {{ isset($book) && $book->format_id == $format->id ? 'selected' : '' }}>{{ $format->format }}</option>
            @endforeach
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" /></svg>
        </div>
    </div>
</div>
<div class="w-full md:w-2/4 px-3 mb-6 md:mb-0 mt-4">
    <label for="additional_authors" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Additional Authors</label>
    <select class="form-multiselect block w-full bg-gray-200 text-gray-700 focus:outline-none focus:bg-white focus:border-gray-500" name="additional_authors" multiple>
        @foreach($additional_authors as $author)
            <option value="{{ $author->id }}">{{ $author->name }}</option>
        @endforeach
    </select>
</div>
<div class="w-full px-3 mb-6 md:mb-0">
    <label for="blurb" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Blurb</label>
    <textarea name="blurb"
        class="appearance-none resize-none block w-full h-56 bg-gray-200 text-gray-700 border border-gray-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
        required>
            {{ isset($book) ? old('blurb', $book->blurb): old('blurb') }}
    </textarea>
</div>
