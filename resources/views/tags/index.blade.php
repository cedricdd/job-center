@extends('layouts.main')

@section('title', 'Tags Center')

@section('content')
    <x-header-title>Tags Center</x-header-title>

    <div class="flex gap-4 flex-wrap justify-center">
        @foreach($tags as $tag)
            <x-tag-display :$tag />
        @endforeach
    </div>
@endsection