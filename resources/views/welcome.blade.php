@extends("layouts.main")

@section("title", "Home Page")

@section("content")
    <h1>Hello From the Home Page</h1>
    <div class="flex gap-2">
        <x-link-button-blue href="{{ route('jobs.index') }}">Show All Jobs</x-link-button-blue>
        @auth
            <x-link-button-blue href="{{ route('jobs.create') }}">Create A Job</x-link-button-blue>
        @endauth
    </div>
@endsection