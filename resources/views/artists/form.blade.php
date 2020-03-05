@csrf
<label for="name" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Name</label>  
<input type="text" name="name" value="{{ isset($artist) ? old('name', $artist->name): old('name') }}"
class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
required
>
