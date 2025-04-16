<?php

use App\Constants;

use App\Models\User;

test('job_index', function () {
    $jobs = $this->createJobs(count: Constants::JOBS_PER_PAGE + 1);
 
    $response = $this->get(route('jobs.index'));

    $response->assertStatus(200);
    $response->assertSeeText('Jobs List');

    $sortedJobs = $jobs->sortBy($this->sortingJobs);

    $response->assertViewHas('jobs', fn($viewJobs) => $viewJobs->contains($sortedJobs->first()));
    $response->assertViewHas('jobs', fn($viewJobs) => !$viewJobs->contains($sortedJobs->last()));

    $response = $this->get(route('jobs.index', ['page' => 2]));

    $response->assertViewHas('jobs', fn($viewJobs) => !$viewJobs->contains($sortedJobs->first()));
    $response->assertViewHas('jobs', fn($viewJobs) => $viewJobs->contains($sortedJobs->last()));
});

test('job_index_see_job_options', function () {
    $this->createJobs(count: 10, user: $this->user);

    //Random user should not see edit and delete options
    $response = $this->actingAs(User::factory()->create())->get(route('jobs.index'));

    $response->assertDontSeeText(['Edit', 'Delete']);

    //User should see edit and delete options
    $response = $this->actingAs($this->user)->get(route('jobs.index'));

    $response->assertSeeText(['Edit', 'Delete']);
});

test('job_featured', function () {
    $jobs = $this->createJobs(count: Constants::JOBS_PER_PAGE + 1, params: ['featured' => true]);

    $response = $this->get(route('jobs.featured'));

    $response->assertStatus(200);
    $response->assertSeeText('Featured Jobs');

    $sortedJobs = $jobs->sortBy($this->sortingJobs);

    $response->assertViewHas('jobs', fn($viewJobs) => $viewJobs->contains($sortedJobs->first()));
    $response->assertViewHas('jobs', fn($viewJobs) => !$viewJobs->contains($sortedJobs->last()));

    $response = $this->get(route('jobs.index', ['page' => 2]));

    $response->assertViewHas('jobs', fn($viewJobs) => !$viewJobs->contains($sortedJobs->first()));
    $response->assertViewHas('jobs', fn($viewJobs) => $viewJobs->contains($sortedJobs->last()));
});

test('job_show', function () {
    $job = $this->createJobs(count: 1);

    $response = $this->get(route('jobs.show', $job->id));

    $response->assertStatus(200);
    $response->assertSeeText($job->title);
    $response->assertViewHas('job', fn ($viewJob) => $viewJob->is($job));
});

test('job_create', function () {
    $response = $this->actingAs($this->user)->get(route('jobs.create'));

    $response->assertStatus(200);
    $response->assertSeeTextInOrder(['Job Title', 'Schedule', 'Employer']);
});

test('job_create_cant_be_accessed_by_guest_user', function () {
    $response = $this->get(route('jobs.create'));

    $response->assertRedirect(route('sessions.create'));
});

test('job_edit', function () {
    $job = $this->createJobs(count: 1, user: $this->user);

    $response = $this->actingAs($this->user)->get(route('jobs.edit', $job->id));

    $response->assertStatus(200);
    $response->assertSeeTextInOrder([$job->title, $job->schedule, $job->employer->name]);
    $response->assertViewHas('job', fn ($viewJob) => $viewJob->is($job));
});

test('job_edit_cant_be_accessed_by_guest_user', function () {
    $job = $this->createJobs(count: 1);

    $response = $this->get(route('jobs.edit', $job->id));

    $response->assertRedirect(route('sessions.create'));
});

test('job_edit_check_right_user', function () {
    $job = $this->createJobs(count: 1);

    $response = $this->actingAs($this->user)->get(route('jobs.edit', $job->id));

    $response->assertStatus(403);
});