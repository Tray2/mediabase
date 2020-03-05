@csrf
<label for="first_name" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">First name</label>  
<input type="text" name="first_name" value="{{ isset($author) ? old('first_name', $author->first_name): old('first_name') }}"
class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
required
>
<label for="last_name" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Last name</label>
<input type="text" name="last_name" value="{{ isset($author) ? old('last_name', $author->last_name): old('last_name') }}"
class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
required
>
