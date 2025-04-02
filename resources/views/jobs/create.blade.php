@extends('layouts.main')

@section('title', 'Create a Job')

@section('content')

    <x-jobs.form action="Create" :$employerID />

@endsection
