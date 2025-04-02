@props(['job'])

<x-card size="small">
    <div class="self-start">
        <a href="{{ route('employers.show', $job->employer->id) }}">{{ $job->employer->name }}</a>
    </div>
    <div class="my-8">
        <h3 class="text-xl group-hover:text-blue-600">
            <a href="{{ route('jobs.show', $job->id) }}">{{ $job->title }}</a>
        </h3>
        <p class="text-sm mt-4">{{ $job->salary }}</p>
    </div>
    <div class="inline-flex justify-between items-center mt-auto">
        <div class="flex gap-1 flex-wrap">
            @foreach ($job->tags as $tag)
                <x-tag-display size="small" :$tag/>
            @endforeach
        </div>
        <div class="w-[60px] h-[60px]">
            <img loading="lazy" src="{{ $job->employer->logoUrl }}" alt="{{ $job->employer->name }}-logo">
        </div>
    </div>
</x-card>