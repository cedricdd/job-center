<x-card>
    <div class="flex items-center gap-2 w-full">
        <div class="w-[125px] h-[125px] flex justify-center items-center mr-6">
            <a href="{{ route('employers.show', $employer->id) }}">
                <img loading="lazy" src="{{ $employer->logoUrl }}" alt="{{ $employer->name }}-logo">
            </a>
        </div>
        <div class="flex-1">
            <h1 class="text-bold text-3xl group-hover:text-blue-600 transition-colors duration-300">
                <a href="{{ route('employers.show', $employer->id) }}">{{ $employer->name }}</a>
            </h1>
            <div class="my-4">
                {{ $employer->description }}
            </div>
            <p>Currently: {{ $employer->jobs_count }} Jobs</p>
        </div>
        <div class="self-stretch flex flex-col justify-around gap-1">
            <x-link-button-white href="{{ $employer->url }}" target='_blank'>Website</x-link-button-white>
            @can('update', $employer)
                <x-link-button-green href="{{ route('jobs.create', $employer->id) }}">Add Job</x-link-button-green>
                <x-link-button-blue href="{{ route('employers.edit', $employer->id) }}">Edit</x-link-button-blue>
            @endcan
            @can('destroy', $employer)
                <form action="{{ route('employers.destroy', $employer->id) }}" method="POST">
                    @csrf
                    @method("DELETE")
                    <x-forms.button-red>Delete</x-forms.button-red>
                </form>
            @endcan
        </div>
    </div>
</x-card>