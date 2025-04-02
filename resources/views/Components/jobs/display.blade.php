<div class="block px-4 py-6 border border-gray-200 rounded-lg my-2">

    <a href="{{ route('employers.show', $job->employer->id) }}" class="flex gap-x-2 items-center">
        <div class="w-[125px] h-[125px]">
            <img src="{{ $job->employer->logoUrl }}" alt="{{ $job->employer->name }}-logo">
        </div>
        <div class="text-4xl">{{ $job->employer->name }}</div>
    </a>

    <div class="flex justify-between my-4">
        <div>
            <span class="text-2xl">{{ $job->title }}</span>
        </div>
        <div>
            <x-span-info>{{ $job->salary }}</x-span-info>
            <x-span-info>
                <a href="{{ route('search') . "?=" . urlencode($job->schedule) }}">{{ $job->schedule }}</a>
            </x-span-info>
            <x-span-info>{{ $job->location }}</x-span-info>
        </div>
        <x-link-button-white href="{{ $job->url }}">More Infos</x-link-button-white>
    </div>

    <div class="mt-2 flex flex-wrap gap-2 items-center justify-start">
        @foreach ($job->tags as $tag)
            <x-tag-display :$tag />
        @endforeach
    </div>

    <div class="flex justify-end gap-2">
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
</div>
