<x-card size="small">
    <div class="self-start">{{ $job->employer->name }}</div>
    <div class="my-8">
        <h3 class="text-lg group-hover:text-blue-600">{{ $job->title }}</h3>
        <p class="text-sm mt-4">{{ $job->salary }}</p>
    </div>
    <div class="inline-flex justify-between items-center mt-auto">
        <div class="flex gap-1 flex-wrap">
            @foreach ($job->tags as $tag)
                <x-tag-display size="small" :$tag/>
            @endforeach
        </div>
        <x-placeholder-image size="42" />
    </div>
</x-card>