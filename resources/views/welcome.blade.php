@extends("Layouts.main")

@section("title", "Home Page")

@section("content")
    <h1>Hello From the Home Page</h1>
    <div class="flex gap-2">
        <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" href="{{ route("jobs.index") }}">View All Jobs</a>
        <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" href="{{ route("jobs.create") }}">Create A Job</a>
    </div>
@endsection