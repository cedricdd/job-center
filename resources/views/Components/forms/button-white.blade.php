@props(['active' => false])

@php
    $class = "flex items-center gap-x-2 min-w-[100px] bg-white/90 hover:bg-white text-center border border-3 text-black focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-sm w-full sm:w-auto px-5 py-1.5 sm:py-2.5 text-center cursor-pointer font-bold";

    if ($active) $class .= " border-blue-500";
    else $class .= " border-transparent";    
@endphp

<button type="submit"
    {{ $attributes->merge(['class' => $class]) }}>{{ $slot }}</button>
