@props(["action" => "Create", "job" => null, 'employerID' => null])

<form class="max-w-xl mx-auto" accept-charset="UTF-8" method="POST" action="<?= $action == "Create" ? route('jobs.store') : route('jobs.update', $job->id) ?>">
    @csrf
    @if($action == "Edit") 
        @method("PUT")
    @endif
    <div class="mb-5">
        <x-forms.input input-name="title" label="Job Title" placeholder="Enter job title" value="{{ old('title', $job?->title) }}" required />
    </div>
    <div class="mb-5">
        <x-forms.input input-name="salary" label="Salary" placeholder="Enter Yearly Salary" value="{{ old('salary', $job?->salary) }}" required />
    </div>
    <div class="mb-5">
        <x-forms.input input-name="location" label="Location" placeholder="Remote or City Name" value="{{ old('location', $job?->location) }}" required />
    </div>
    <div class="mb-5">
        <x-forms.select select-name="schedule" label="Select Schedule" :items="array_combine(Constants::SCHEDULES, Constants::SCHEDULES)" :current="$job?->schedule" />
    </div>
    <div class="mb-5">
        <x-forms.input input-name="url" label="URL" placeholder="Enter link to the job" value="{{ old('url', $job?->url) }}" required />
    </div>
    <div class="mb-5">
        <x-forms.select select-name="employer_id" label="Select Employer" :items="Auth::user()->employers->pluck('name', 'id')" :current="$job?->employer->id ?? $employerID" />
    </div>
    <div class="mb-5">
        <x-forms.input input-name="tags" label="Tags" placeholder="Tags are comma separated" value="{{ old('tags', $job?->tags()->implode('name', ', ')) }}" required />
    </div>
    <div class="flex justify-between gap-2">
        <x-link-button-white href="{{ route('jobs.index') }}">Cancel</x-link-button-white>
        <x-forms.button-blue>{{ $action }}</x-forms.button-blue>
    </div>

    {{-- $table->boolean("featured")->default(false); --}}
</form>