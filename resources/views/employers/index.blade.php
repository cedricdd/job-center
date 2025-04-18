@extends('layouts.main')

@section('title', 'Companies')

@section('content')
    <x-header-title>Company List</x-header-title>

    <x-nav-sorting type="employers" />

    <div class="space-y-6 mt-4">
        @foreach ($employers as $employer)
            <x-employers.card :$employer />
        @endforeach
    </div>

    <div class="my-6">
        {{ $employers->links() }}
    </div>
@endsection
