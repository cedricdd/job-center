@props(["action" => "create", "employer" => null])

<form action="{{ $action == "create" ? route('employers.store') : route('employers.update', $employer->id) }}" method="POST" accept-charset="UTF-8" enctype="multipart/form-data">
    @csrf
    @if($action == "edit") @method("PATCH") @endif

    <div class="mb-8">
        <x-forms.input input-name="name" label="Name" placeholder="Enter the name of the company" value="{{ old('name', $employer?->name) }}" />
    </div>
    <div class="mb-8">
        <x-forms.text input-name="description" label="Description" placeholder="Enter a short description of the company" rows=6>{{ old("description", $employer?->description) }}</x-forms.text>
    </div>
    <div class="mb-8">
        <x-forms.input input-name="url" label="URL" value="{{ old('url', $employer?->url) }}" placeholder="Enter an URL for the company" />
    </div>
    <div class="mb-8">
        <x-forms.input input-name="logo" label="Logo (Best 250*250 px)" type="file" value="{{ old('url') }}" />
        @if($action == "edit")
            <p class="italic">* Not providing a logo will not remove the current logo.</p>
        @endif
    </div>
    <div class="flex justify-between">
        <x-link-button-white href="{{ url()->previous() }}">Cancel</x-link-button-white>
        <x-forms.button-blue>{{ $action == "create" ? "Create" : "Edit" }}</x-forms.button-blue>
    </div>
</form>