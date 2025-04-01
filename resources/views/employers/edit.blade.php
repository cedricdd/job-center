@extends("layouts.main")

@section("title", "Edit {$employer->name}")

@section("content")
    <x-header-title>{{ $employer->name }}</x-header-title>

    <x-employers.form action="edit" :$employer />
@endsection