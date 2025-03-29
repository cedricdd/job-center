<x-card>
    <div class="flex items-center w-full">
        <div class="w-[100px] h-[100px] flex justify-center items-center mr-6">
            <img src="{{ $employer->logo }}" alt="{{ $employer->name }}-logo">
        </div>
        <div class="flex-1">
            <h1 class="text-bold text-3xl">{{ $employer->name }}</h1>
        </div>
        @if ($employer->user->is(Auth::user()))
        <div class="self-stretch flex flex-col justify-around">
            <x-link-button-blue href="{{ route('employers.edit', $employer->id) }}">Edit</x-link-button-blue>

            <form action="{{ route('employers.destroy', $employer->id) }}" method="POST">
                @csrf
                @method("DELETE")
                <x-forms.button-red>Delete</x-forms.button-red>
            </form>
        </div>
        @endif
    </div>
</x-card>