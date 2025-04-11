@extends('layouts.main')

@section('title', $employer->name)

@section('content')
    <div class="flex gap-4 justify-center items-center">
        <div class="w-[125px] h-[125px] rounded-full">
            <img src="{{ $employer->logoUrl }}" alt="{{ $employer->name }}-logo" loading="lazy" />
        </div>
        <x-header-title>{{ $employer->name }}</x-header-title>
    </div>
    <div>
        <div class="my-4">
            {{ $employer->description }}
        </div>
        <div class="flex gap-2 items-center justify-between flex-wrap">
            <p class="bg-white/10 px-4 py-2.5 rounded font-bold">Currently: {{ $employer->jobs_count }} Jobs @if ($employer->jobs_featured_count)
                    -- {{ $employer->jobs_featured_count }} Featured
                @endif
            </p>
            <div class="flex-1 flex gap-2 justify-end">
                <x-link-button-white href="{{ $employer->url }}" target='_blank'>Website</x-link-button-white>
                @can('update', $employer)
                    <x-link-button-blue href="{{ route('employers.edit', $employer->id) }}">Edit</x-link-button-blue>
                    <x-link-button-green href="{{ route('jobs.create', $employer->id) }}">Add Job</x-link-button-green>
                @endcan
                @can('destroy', $employer)
                    <form action="{{ route('employers.destroy', $employer->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <x-forms.button-red>Delete</x-forms.button-red>
                    </form>
                @endcan
            </div>
        </div>
    </div>

    <div class="mt-20">
        <h2 class="text-3xl font-bold text-center mb-4">Jobs List</h2>

        @if ($jobs->count() == 0)
            <p class="text-center text-gray-500 text-4xl mt-10">No jobs available</p>
        @else
            <x-nav-sorting type="jobs" />

            <div class="mt-4 space-y-4">
                @foreach ($jobs as $job)
                    <x-jobs.display-big :$job :hideLogo="true" />
                @endforeach
            </div>

            <div class="mt-4">
                {{ $jobs->links() }}
            </div>
        @endif
    @endsection
