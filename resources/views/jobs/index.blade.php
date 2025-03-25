@extends('Layouts.main')

@section('title', 'Jobs Lising')

@section('content')
    @foreach ($jobs as $job)
        <div class="block px-4 py-6 border border-gray-200 rounded-lg my-2">
            <a href="{{ route('jobs.show', $job->id) }}">
                <p>Employer: <strong>{{ $job->employer->name }}</strong></p>
                <p><strong>{{ $job->title }}:</strong> pays {{ $job->salary }} per year.</p>
            </a>
            <div class="mt-2 flex flex-wrap gap-2 items-center justify-start text-gray-900 dark:text-white">
                @foreach ($job->tags as $tag)
                    <a href="#" class="p-2 bg-gray-300 rounded-lg hover:underline">{{ $tag->name }}</a>
                @endforeach
            </div>
        </div>
    @endforeach

    {{ $jobs->links() }}
@endsection
