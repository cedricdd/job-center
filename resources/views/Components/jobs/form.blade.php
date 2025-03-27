<form class="max-w-sm mx-auto" accept-charset="UTF-8" method="POST" action="<?= $action == "Create" ? route('jobs.store') : route('jobs.update', $job->id) ?>">
    @csrf
    @if($action == "Edit") 
        @method("PUT")
    @endif
    <div class="mb-5">
        <x-form-input input-name="title" label="Job Title" placeholder="Enter job title" value="{{ old('title', $job?->title) }}" required />
    </div>
    <div class="mb-5">
        <x-form-input input-name="salary" label="Salary" placeholder="Enter Yearly Salary" value="{{ old('salary', $job?->salary) }}" required />
    </div>
    <div class="mb-5">
        <x-form-select select-name="employer_id" label="Select Employer" :items="Auth::user()->employers" :current="$job?->employer->id" />
    </div>
    <div class="flex justify-end gap-2">
        <x-link-button-white href="{{ route('jobs.index') }}">Cancel</x-link-button-white>
        <x-button-blue>{{ $action }}</x-button-blue>
    </div>
</form>