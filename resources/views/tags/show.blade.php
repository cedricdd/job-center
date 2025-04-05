@extends('layouts.main')

@section('title', $tag->name)

@section("content")
    <x-header-title>{{ ucwords($tag->name) }} ({{ $tag->jobs_count }} Jobs)</x-header-title>

    <x-nav-jobs-sorting />

    <div class="mt-4 space-y-4">
        @foreach($jobs as $job)
            <x-jobs.display-big :$job />
        @endforeach

        <div class="mt-6">
            {{ $jobs->links() }}
        </div>
    </div>
@endsection
