@extends("Layouts.main")

@section("title", $job->title)

@section("content")
    <div class="block px-4 py-6 border border-gray-400 rounded-lg">
        <p>Employer: <strong>{{ $job->employer->name }}</strong></p>
        <p><strong>{{ $job->title }}:</strong> pays {{ $job->salary }} per year.</p>
        <div>
            <ul class="mt-2 flex flex-wrap gap-2 items-center justify-start text-gray-900 dark:text-white">
                @foreach ($job->tags as $tag)
                    <li>
                        <a href="#" class="p-2 bg-gray-300 rounded-lg hover:underline">{{ $tag->name }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection