@props(['job', 'hideLogo' => false])

<x-card :class="$job->featured ? '!border-yellow-600' : ''">
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
            <a href="{{ route('jobs.show', $job->id) }}">{{ $job->title }}</a>
        </h1>
        <p class="mt-8">{{ $job->salary }}</p>
    </div>
    <div class="flex flex-col justify-between items-end">
        <div class="flex gap-2">
            <x-span-info>
                <a href="{{ route('search', ['q' => urlencode($job->schedule)]) }}">{{ $job->schedule }}</a>
            </x-span-info>
            <x-span-info>
                <a href="{{ route('search', ['q' => urlencode($job->location)]) }}">{{ $job->location }}</a>
            </x-span-info>
            <x-span-info>{{ $job->updated_at->diffForHumans() }}</x-span-info>
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
                @method('DELETE')
                <x-forms.button-red>Delete</x-forms.button-red>
            </form>
        @endcan
    </div>
</x-card>
