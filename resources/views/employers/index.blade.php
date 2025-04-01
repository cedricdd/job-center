@extends('layouts.main')

@section('title', 'Companies')

@section('content')
    <x-header-title>Every Companies</x-header-title>

    <div class="space-y-6">
        @foreach ($employers as $employer)
            <x-employers.card :$employer />
        @endforeach
    </div>

    <div class="my-6">
        {{ $employers->links() }}
    </div>
@endsection
