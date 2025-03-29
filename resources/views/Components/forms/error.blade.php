@error($name)
    <p {{ $attributes->merge(['class' => "p-4 mt-2 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"]) }}>{{ $message }}</p>
@enderror