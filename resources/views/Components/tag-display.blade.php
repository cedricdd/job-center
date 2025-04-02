@props(["tag", "size" => "base"])

@php
    $classes = "bg-white/15 rounded hover:bg-white/25 cursor-pointer transition-colors duration-300 hover:text-gray-300 flex items-center gap-x-1";

    $classes .= $size == "base" ? " px-4 py-1" : " px-2 py-1 text-sm";

    $mark = "bg-white rounded" . ($size == "base" ? " w-[8px] h-[8px] mt-[4px]" : " w-[5px] h-[5px] mt-[4px]");
@endphp

<a href="{{ route("tags.show", $tag->name) }}" {{ $attributes->merge(['class' => $classes]) }}>
    <div class="{{ $mark }}"></div>
    <div>{{ $tag->name }}</div>
</a>