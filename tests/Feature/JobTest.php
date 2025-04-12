<?php

use App\Constants;

use App\Models\Job; 
use App\Models\User;
use App\Models\Employer;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('job_index', function () {
    $jobs = Job::factory()->count(Constants::JOBS_PER_PAGE + 1)->create();

    $response = $this->get(route('jobs.index'));

    $response->assertStatus(200);
    $response->assertSeeText('Jobs List');

    $sortedJobs = $jobs->sortBy($this->getJobSorting());

    $response->assertViewHas('jobs', fn($viewJobs) => $viewJobs->contains($sortedJobs->first()));
    $response->assertViewHas('jobs', fn($viewJobs) => !$viewJobs->contains($sortedJobs->last()));

    $response = $this->get(route('jobs.index', ['page' => 2]));

    $response->assertViewHas('jobs', fn($viewJobs) => !$viewJobs->contains($sortedJobs->first()));
    $response->assertViewHas('jobs', fn($viewJobs) => $viewJobs->contains($sortedJobs->last()));
});

test('job_featured', function () {
    $jobs = Job::factory()->count(Constants::JOBS_PER_PAGE + 1)->create(['featured' => true]);

    $response = $this->get(route('jobs.featured'));

    $response->assertStatus(200);
    $response->assertSeeText('Featured Jobs');

    $sortedJobs = $jobs->sortBy($this->getJobSorting());

    $response->assertViewHas('jobs', fn($viewJobs) => $viewJobs->contains($sortedJobs->first()));
    $response->assertViewHas('jobs', fn($viewJobs) => !$viewJobs->contains($sortedJobs->last()));

    $response = $this->get(route('jobs.index', ['page' => 2]));

    $response->assertViewHas('jobs', fn($viewJobs) => !$viewJobs->contains($sortedJobs->first()));
    $response->assertViewHas('jobs', fn($viewJobs) => $viewJobs->contains($sortedJobs->last()));
});

test('job_show', function () {
    $job = Job::factory()->create();

    $response = $this->get(route('jobs.show', $job->id));

    $response->assertStatus(200);
    $response->assertSeeText($job->title);
    $response->assertViewHas('job', fn ($viewJob) => $viewJob->is($job));
});

test('job_create', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('jobs.create'));

    $response->assertStatus(200);
    $response->assertSeeTextInOrder(['Job Title', 'Schedule', 'Employer']);
});

test('job_edit', function () {
    $user = User::factory()->create();
    $job = Job::factory()->for(Employer::factory()->for($user, 'user'), 'employer')->create();

    $response = $this->actingAs($user)->get(route('jobs.edit', $job->id));

    $response->assertStatus(200);
    $response->assertSeeTextInOrder([$job->title, $job->schedule, $job->employer->name]);
    $response->assertViewHas('job', fn ($viewJob) => $viewJob->is($job));
});

test('job_edit_check_right_user', function () {
    $user = User::factory()->create();
    $job = Job::factory()->for(Employer::factory()->for(User::factory(), 'user'), 'employer')->create();

    $response = $this->actingAs($user)->get(route('jobs.edit', $job->id));

    $response->assertStatus(403);
});