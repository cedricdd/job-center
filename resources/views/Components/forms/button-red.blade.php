<button type="submit"
    {{ $attributes->merge(['class' => 'text-white bg-red-500 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center cursor-pointer font-bold']) }}>{{ $slot }}</button>
