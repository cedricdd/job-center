@extends('layouts.main')

@section('title', $title ?? "Jobs List")

@section('content')
    <x-header-title>{{ $title ?? "Jobs List" }}</x-header-title>

    <x-nav-sorting type="jobs" />

    <div class="space-y-6 mt-4">
        @foreach ($jobs as $job)
            <x-jobs.display-big :$job />
        @endforeach

        {{ $jobs->links() }}
    </div>
@endsection