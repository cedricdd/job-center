@props(["size" => "base"])

@php
    $classes = "bg-white/15 rounded hover:bg-white/25 cursor-pointer transition-colors duration-300";

    $classes .= $size == "base" ? " px-5 py-1" : " px-3 py-1 text-sm";
@endphp

<div class="{{ $classes }}">{{ $slot}}</div>