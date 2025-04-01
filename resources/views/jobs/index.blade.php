@extends('layouts.main')

@section('title', 'Jobs List')

@section('content')
    <x-header-title>Jobs List</x-header-title>

    <div class="space-y-6 mt-4">
        @foreach ($jobs as $job)
            <x-jobs.display-big :$job />
        @endforeach

        {{ $jobs->links() }}
    </div>
@endsection