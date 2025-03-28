@php
    $active = 'bg-gray-900 text-white';
    $incative = 'text-gray-300 hover:bg-gray-700 hover:text-white';
@endphp

<a {{ $attributes }} href="{{ route($name) }}" class="rounded-md px-3 py-2 {{ Route::currentRouteName() == $name ? $active : $incative }}">{{ $slot }}</a>
                                