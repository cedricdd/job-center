<form class="max-w-sm mx-auto" accept-charset="UTF-8" method="POST" action="<?= $action == "Create" ? route('jobs.store') : route('jobs.update', $job->id) ?>">
    @csrf
    @if($action == "Edit") 
        @method("PUT")
    @endif
    <div class="mb-5">
        <label for="title" class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">Job Title</label>
        <input type="text" id="title" name="title"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Enter job title" value="{{ old('title', $job?->title) }}" />
        @error('title')
            <p class="p-4 mt-2 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400">
                {{ $message }}</p>
        @enderror
    </div>
    <div class="mb-5">
        <label for="salary" class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">Salary</label>
        <input type="text" id="salary" name="salary"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Enter Yearly Salary" value="{{ old('salary', $job?->salary) }}" />
        @error('salary')
            <p class="p-4 mt-2 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400">
                {{ $message }}</p>
        @enderror
    </div>
    <div class="mb-5">
        <label for="employer_id" class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">Select
            Employer</label>
        <select id="employer_id" name="employer_id"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            @foreach ($employers as $employer)
                <option value="{{ $employer->id }}" @if($job?->employer->id == $employer->id) selected @endif>{{ $employer->name }}</option>
            @endforeach
        </select>
        @error('employer_id')
            <p class="p-4 mt-2 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400">
                {{ $message }}</p>
        @enderror
    </div>
    <div class="flex justify-end gap-2">
        <a href="{{ route('jobs.index') }}"
            class="text-black font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center border border-graw-300">Cancel</a>
        <button type="submit"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">{{ $action }}</button>
    </div>
</form>