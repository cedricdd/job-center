@extends('layouts.main')

@section('title', $title ?? 'Jobs List')

@section('content')
    <form action="{{ route('search') }}" method="GET" class="mb-10 max-w-xl flex items-center justify-center gap-x-1 m-auto">
        <x-forms.input input-name="q" placeholder="Search for jobs..." required></x-forms.input>
    </form>

    @if ($jobs->count() == 0)
        <x-header-title>We currently don't have any jobs matching your search!</x-header-title>
    @else
        <x-header-title>{{ $title ?? 'Jobs List' }}</x-header-title>

        <x-nav-sorting type="jobs" />

        <div class="space-y-6 mt-4">
            @foreach ($jobs as $job)
                <x-jobs.display-big :$job />
            @endforeach

            {{ $jobs->links() }}
        </div>
    @endif
@endsection
