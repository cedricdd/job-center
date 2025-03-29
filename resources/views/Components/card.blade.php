@props(["size" => "base"])

@php
    $classes = "bg-white/15 py-4 px-2 rounded border border-transparent hover:border-blue-600 hover:bg-white/25 transition-colors duration-300 group";

    $classes .= $size == "base" ? " flex gap-4" : " flex flex-col text-center min-w-[400px]";
@endphp


<div class="{{ $classes }}">{{ $slot }}</div>