@props(['action' => 'Create', 'job' => null, 'employerID' => null])

<form class="max-w-xl mx-auto" accept-charset="UTF-8" method="POST"
    action="<?= $action == 'Create' ? route('jobs.store') : route('jobs.update', $job->id) ?>">
    @csrf
    @if ($action == 'Edit')
        @method('PUT')
    @endif
    <div class="mb-5">
        <x-forms.input input-name="title" label="Job Title" placeholder="Enter job title"
            value="{{ old('title', $job?->title) }}" required />
    </div>
    <div class="mb-5">
        <x-forms.input input-name="salary" label="Salary" placeholder="Enter Yearly Salary"
            value="{{ old('salary', $job?->salary) }}" required />
    </div>
    <div class="mb-5">
        <x-forms.input input-name="location" label="Location" placeholder="Remote or City Name"
            value="{{ old('location', $job?->location) }}" required />
    </div>
    <div class="mb-5">
        <x-forms.select select-name="schedule" label="Schedule" :items="array_combine(Constants::SCHEDULES, Constants::SCHEDULES)" :current="$job?->schedule" />
    </div>
    <div class="mb-5">
        <x-forms.input input-name="url" label="URL" placeholder="Enter link to the job"
            value="{{ old('url', $job?->url) }}" required />
    </div>
    <div class="mb-5">
        <x-forms.input input-name="tags" label="Tags" placeholder="Tags are comma separated"
            value="{{ old('tags', $job?->tags()->implode('name', ', ')) }}" />
    </div>
    <div class="mb-5">
        <x-forms.select select-name="employer_id" label="Employer" :items="Auth::user()->employers->pluck('name', 'id')" :current="$job?->employer->id ?? $employerID" />
    </div>
    <div class="mb-5">
        <x-forms.checkbox input-name="featured" label="Featured Job (You should pay for that but it's just a test project)" :checked="old('featured', $job?->featured)" />
    </div>
    <div class="flex justify-between gap-2">
        <x-link-button href="{{ route('jobs.index') }}">Cancel</x-link-button>
        <x-forms.button color='blue'>{{ $action }}</x-forms.button>
    </div>
</form>

@push('header')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/smoothness/jquery-ui.css">

    <style>
        .ui-autocomplete {
            position: absolute;
            max-height: 250px;
            overflow-y: auto;
            overflow-x: hidden;
            background: #eee;
            border: none;
            padding: 2px 0;
            border-radius: 0 0 4px 4px;
        }

        .ui-widget-content .ui-state-active {
            background-color: #ddd;
            color: #000;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            border-left: none;
            border-right: none;
        }
    </style>
@endpush

@push('footer')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.min.js"
        integrity="sha256-AlTido85uXPlSyyaZNsjJXeCs07eSv3r43kyCVc8ChI=" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            $("#tags").autocomplete({
                minLength: 2,
                delay: 250,
                source: function(request, response) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ route('tags.autocomplete') }}",
                        method: "POST",
                        data: {
                            term: request.term
                        },
                        success: function(data) {
                            response(data); // Expecting an array of strings
                        }
                    });
                }
            });
        });
    </script>
@endpush
