@props(['job', 'hideLogo' => false])

<x-card>
    @unless ($hideLogo)
    <div class="flex justify-center w-[125px] h-[125px]">
        <img loading="lazy" src="{{ $job->employer->logoUrl }}" alt="{{ $job->employer->name }}-logo">
    </div>
    @endunless
    <div class="flex-1">
        <p class="text-gray-100">{{ $job->employer->name }}</p>
        <h1 class="mt-3 font-bold text-2xl group-hover:text-blue-600 transition-colors duration-300">
            <a href="{{ $job->url }}" target="_blank">{{ $job->title }}</a>
        </h1>
        <p class="mt-8">{{ $job->salary }}</p>
    </div>
    <div class="flex flex-col justify-between items-end">
        <div class="flex gap-2">
            <div class="rounded border border-2 border-white/30 px-2 hover:bg-white/10 hover:boder-white">
                <a href="{{ route('search') . "/?q=" . urlencode($job->schedule) }}">{{ $job->schedule }}</a>
            </div>
            <div class="rounded border border-white/30 px-2">{{ $job->location }}</div>
            <div class="rounded border border-white/30 px-2">{{ $job->updated_at->diffForHumans() }}</div>
        </div>
        <div class="flex gap-2">
            @foreach ($job->tags as $tag)
                <x-tag-display size="small" :$tag />
            @endforeach    
        </div>
    </div>
</x-card>