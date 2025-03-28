@extends('layouts.main')

@section('title', 'Jobs Lising')

@section('content')
    @auth
    <div class="mx-2 flex justify-center">
        <x-link-button-white href="{{ route('jobs.create') }}">Create A Job</x-link-button-white>
    </div>
    @endauth

    @foreach ($jobs as $job)
        <x-jobs-display :$job />
    @endforeach

    {{ $jobs->links() }}
@endsection
