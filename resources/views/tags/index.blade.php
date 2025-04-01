@extends('layouts.main')

@section('title', 'Tag Center')

@section('content')
    <x-header-title>Tag Center</x-header-title>

    <div class="flex gap-4 flex-wrap justify-stretch">
        @foreach($tags as $tag)
            <x-tag-display :$tag class="min-w-[200px]" />
        @endforeach
    </div>
@endsection