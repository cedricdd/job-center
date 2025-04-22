<div class="block px-2 sm:px-4 py-6 border border-gray-200 rounded-lg my-2">

    <div class="flex flex-col sm:flex-row items-center gap-x-4 p-2 border-b-2 border-white/25">
        <a href="{{ route('employers.show', $job->employer->id) }}" class="flex flex-col items-center gap-y-2 max-w-[175px]">
            <div class="text-2xl text-center">{{ $job->employer->name }}</div>
            <div class="w-[125px] h-[125px]">
                <img src="{{ $job->employer->logoUrl }}" alt="{{ $job->employer->name }}-logo">
            </div>
        </a>
        <div class="flex-1 text-center">
            <span class="text-5xl">{{ $job->title }}</span>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row justify-between my-4">
        <div class="mb-5 sm:mb-0 flex flex-wrap gap-1">
            <x-span-info>{{ $job->salary }}</x-span-info>
            <x-span-info>
                <a href="{{ route('search', ["q" => urlencode($job->schedule)]) }}">{{ $job->schedule }}</a>
            </x-span-info>
            <x-span-info>
                <a href="{{ route('search', ["q" => urlencode($job->location)]) }}">{{ $job->location }}</a>
            </x-span-info>
        </div>
        <x-link-button-white href="{{ $job->url }}">More Infos</x-link-button-white>
    </div>

    <div class="mt-2 inline-flex flex-wrap gap-2 items-center justify-start">
        @foreach ($job->tags as $tag)
            <x-tag-display :$tag />
        @endforeach
    </div>

    @canany(['update', 'destroy'], $job)
        <div class="flex flex-col sm:flex-row justify-end gap-2 mt-4">
            @can('destroy', $job)
                <form action="{{ route('jobs.destroy', $job->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <x-forms.button-red>Delete Job</x-forms.button-red>
                </form>
            @endcan
            @can('update', $job)
                <x-link-button-blue href="{{ route('jobs.edit', $job->id) }}">Edit Job</x-link-button-blue>
            @endcan
        </div>
    @endcanany
</div>
