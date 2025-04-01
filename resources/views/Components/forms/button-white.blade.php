<button type="submit"
    {{ $attributes->merge(['class' => 'min-w-[100px] text-center text-black bg-white/90 hover:bg-white focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center cursor-pointer font-bold']) }}>{{ $slot }}</button>
