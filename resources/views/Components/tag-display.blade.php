@props(["tag", "size" => "base"])

@php
    $classes = "bg-white/15 rounded hover:bg-white/25 cursor-pointer transition-colors duration-300";

    $classes .= $size == "base" ? " px-4 py-1" : " px-2 py-1 text-sm";
@endphp

<a href="#" {{ $attributes->merge(['class' => $classes]) }}>{{ $tag->name }}</a>