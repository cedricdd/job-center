<x-card>
    <div class="flex items-center gap-2 w-full">
        <div class="w-[125px] h-[125px] flex justify-center items-center mr-6">
            <img loading="lazy" src="{{ asset($employer->logo) }}" alt="{{ $employer->name }}-logo">
        </div>
        <div class="flex-1">
            <h1 class="text-bold text-3xl">{{ $employer->name }}</h1>
            <div class="my-4">
                {{ $employer->description }}
            </div>
            <p>Currently: {{ $employer->jobs_count }} Jobs</p>
        </div>
        <div class="self-stretch flex flex-col justify-around gap-1">
            <x-link-button-white href="{{ $employer->url }}">Website</x-link-button-white>
            @if ($employer->user->is(Auth::user()))
            <x-link-button-blue href="{{ route('employers.edit', $employer->id) }}">Edit</x-link-button-blue>

            <form action="{{ route('employers.destroy', $employer->id) }}" method="POST">
                @csrf
                @method("DELETE")
                <x-forms.button-red>Delete</x-forms.button-red>
            </form>
            @endif
        </div>
    </div>
</x-card>