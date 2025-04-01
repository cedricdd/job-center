@props(["size" => "base"])

@php
    $classes = "bg-white/15 p-4 rounded border border-transparent hover:border-blue-600 hover:bg-white/25 transition-colors duration-300 group";

    $classes .= $size == "base" ? " flex gap-4" : " flex flex-col text-center w-[400px]";
@endphp


<div class="{{ $classes }}">{{ $slot }}</div>