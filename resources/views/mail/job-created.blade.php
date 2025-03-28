<div>
    <p>Congrats! Your job <strong><i>{{ $job->title }}</i></strong> is now live on our website.</p>
    <a href="{{ route("jobs.show", $job->id) }}">View the job</a>
</div>