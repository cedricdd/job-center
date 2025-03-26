@extends("Layouts.main")

@section("title", $job->title)

@section("content")
    <x-jobs-display :$job />
@endsection