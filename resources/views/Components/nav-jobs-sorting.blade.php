<div class="mt-4 p-6 bg-white/10 rounded flex justify-center items-center gap-x-2">
    @foreach(Constants::JOB_SORTING as $name => ["label" => $sorting])
        <x-jobs.sorting :$name>{{ $sorting }}</x-jobs.sorting>
    @endforeach
</div>