@extends('layouts.main')

@section('title', 'Jobs Lising')

@section('content')
    @foreach ($jobs as $job)
        <x-jobs-display :$job />
    @endforeach

    {{ $jobs->links() }}
@endsection
