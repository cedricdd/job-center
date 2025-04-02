@props(['job', 'hideLogo' => false])

<x-card>
    @unless ($hideLogo)
        <div class="flex justify-center w-[125px] h-[125px]">
            <a href="{{ route('employers.show', $job->employer->id) }}">
                <img loading="lazy" src="{{ $job->employer->logoUrl }}" alt="{{ $job->employer->name }}-logo">
            </a>
        </div>
    @endunless
    <div class="flex-1">
        <p class="text-gray-100">
            <a href="{{ route('employers.show', $job->employer->id) }}">{{ $job->employer->name }}</a>
        </p>
        <h1 class="mt-3 font-bold text-2xl group-hover:text-blue-600 transition-colors duration-300">
            <a href="{{ $job->url }}" target="_blank">{{ $job->title }}</a>
        </h1>
        <p class="mt-8">{{ $job->salary }}</p>
    </div>
    <div class="flex flex-col justify-between items-end">
        <div class="flex gap-2">
            <div class="rounded border border-2 border-white/30 px-2 hover:bg-white/10 hover:boder-white">
                <a href="{{ route('search') . '/?q=' . urlencode($job->schedule) }}">{{ ucwords($job->schedule) }}</a>
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
    <div class="flex flex-col justify-center gap-y-2">
        @can('update', $job)
            <x-link-button-blue href="{{ route('jobs.edit', $job->id) }}">Edit</x-link-button-blue>
        @endcan
        @can('destroy', $job)
            <form action="{{ route('jobs.destroy', $job->id) }}" method="POST">
                @csrf
                @method("DELETE")
                <x-forms.button-red>Delete</x-forms.button-red>
            </form>
        @endcan
    </div>
</x-card>
