@props(['name'])

@php
    $active = Session::get('sort', 'newest') == $name;
@endphp

<form action="{{ route('jobs.sorting') }}" method="POST">
    @csrf
    <input type="hidden" name="sort" value="{{ $name }}" />  
    <x-forms.button-white :$active>
        @if($active)
        <svg class="h-5 w-5" viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round">  <polyline points="9 11 12 14 22 4" />  <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" /></svg>
        @else
        <svg class="h-5 w-5" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <rect x="4" y="4" width="16" height="16" rx="2" /></svg>
        @endif
        <span>{{ $slot }}</span>
    </x-forms.button-white>
</form>