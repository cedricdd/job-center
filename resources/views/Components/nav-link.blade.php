@props(["name" => "index"])

@php
    $active = 'bg-white/20 text-white';
    $incative = 'text-gray-300 hover:bg-white/30 hover:text-white';
@endphp

<a {{ $attributes }} href="{{ route($name) }}" class="rounded-md px-3 py-2 font-bold inline-block min-w-[110px] text-center {{ Route::currentRouteName() == $name ? $active : $incative }}">{{ $slot }}</a>
                                