@extends('Layouts.main')

@section('title', "Edit Job: $job->title")

@section('content')

    <x-jobs-form action="Edit" :$job />

@endsection