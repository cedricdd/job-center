@props(['job', 'hideLogo' => false])

<x-card :class="$job->featured ? '!border-yellow-600' : ''">
    @unless ($hideLogo)
        <div class="flex justify-center w-[125px] h-[125px] hidden sm:flex">
            <a href="{{ route('employers.show', $job->employer->id) }}">
                <img loading="lazy" src="{{ $job->employer->logoUrl }}" alt="{{ $job->employer->name }}-logo">
            </a>
        </div>
    @endunless
    <div class="flex-1">
        <div class="flex gap-x-2 items-center">
            @unless ($hideLogo)
                <div class="sm:hidden flex justify-center w-[50px] h-[50px]">
                    <a href="{{ route('employers.show', $job->employer->id) }}">
                        <img loading="lazy" src="{{ $job->employer->logoUrl }}" alt="{{ $job->employer->name }}-logo">
                    </a>
                </div>
            @endunless
            <p class="text-gray-100 text-center flex-1 sm:flex-none">
                <a href="{{ route('employers.show', $job->employer->id) }}">{{ $job->employer->name }}</a>
            </p>
        </div>
        <h1 class="mt-3 font-bold text-2xl group-hover:text-blue-600 transition-colors duration-300">
            <a href="{{ route('jobs.show', $job->id) }}">{{ $job->title }}</a>
        </h1>
        <p class="mt-8">{{ $job->salary }}</p>
    </div>
    <div class="flex flex-col justify-between sm:items-end gap-y-2">
        <div class="flex flex-wrap gap-2 justify-end">
            <x-span-info>
                <a href="{{ route('search', ['q' => urlencode($job->schedule)]) }}">{{ $job->schedule }}</a>
            </x-span-info>
            <x-span-info>
                <a href="{{ route('search', ['q' => urlencode($job->location)]) }}">{{ $job->location }}</a>
            </x-span-info>
            <x-span-info>{{ $job->updated_at->diffForHumans() }}</x-span-info>
        </div>
        <div class="flex flex-wrap gap-2">
            @foreach ($job->tags as $tag)
                <x-tag-display size="small" :$tag />
            @endforeach
        </div>
    </div>
    @canany(['update', 'destroy'], $job)
        <div class="flex flex-col justify-center gap-y-2">
            @can('update', $job)
                <x-link-button color='blue' href="{{ route('jobs.edit', $job->id) }}">Edit</x-link-button>
            @endcan
            @can('destroy', $job)
                <form action="{{ route('jobs.destroy', $job->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <x-forms.button color='red'>Delete</x-forms.button>
                </form>
            @endcan
        </div>
    @endcanany
</x-card>
