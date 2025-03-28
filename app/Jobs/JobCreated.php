<?php

namespace App\Jobs;

use App\Models\Job;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\JobCreated as MailJobCreated;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class JobCreated implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public User $user, public Job $jobCreated)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("User {$this->user->name} ({$this->user->email}) has just created a new job: {$this->jobCreated->title} ({$this->jobCreated->url()})");

        Mail::to($this->jobCreated->employer->user)->send(
            new MailJobCreated($this->jobCreated)
        );
    }
}
