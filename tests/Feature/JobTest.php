<?php

use App\Constants;

use App\Models\User;

test('job_index', function () {
    $jobs = $this->createJobs(count: Constants::JOBS_PER_PAGE + 1);

    $sortedJobs = $jobs->sortBy($this->sortingJobs);

    $this->get(route('jobs.index'))
        ->assertStatus(200)
        ->assertSee('action="' . route('search') . '"', false)
        ->assertViewHas('jobs', fn($viewJobs) => $viewJobs->contains($sortedJobs->first()))
        ->assertViewHas('jobs', fn($viewJobs) => !$viewJobs->contains($sortedJobs->last()));

    $this->get(route('jobs.index', ['page' => 2]))
        ->assertViewHas('jobs', fn($viewJobs) => !$viewJobs->contains($sortedJobs->first()))
        ->assertViewHas('jobs', fn($viewJobs) => $viewJobs->contains($sortedJobs->last()));
});

test('job_index_sorting', function() {
    $jobs = $this->createJobs(count: Constants::JOBS_PER_PAGE * 2);

    foreach(Constants::JOB_SORTING as $sorting => $sortingData) {
        $sortedJobs = $jobs->sortBy(array_map(
            fn($sort) => explode(" ", $sort),
            explode(', ', $sortingData["order"])
        ));

        $this->withSession(['job-sorting' => $sorting])
            ->get(route('jobs.index'))
            ->assertStatus(200)
            ->assertViewHas('jobs', fn($viewJobs) => $viewJobs->contains($sortedJobs->first()))
            ->assertViewHas('jobs', fn($viewJobs) => !$viewJobs->contains($sortedJobs->last()));
    }
});

test('job_index_owner_see_job_options', function () {
    $job = $this->createJobs(count: 1, user: $this->user);

    //User should see edit and delete options
    $this->actingAs($this->user)
        ->get(route('jobs.index'))
        ->assertViewHas('jobs', fn($viewJobs) => $viewJobs->contains($job))
        ->assertSeeInOrder(['Edit', 'Delete']);
});

test('job_index_non_owner_does_not_see_job_options', function () {
    $this->createJobs(count: 1);

    //User shouldn't see edit and delete options
    $this->actingAs($this->user)
        ->get(route('jobs.index'))
        ->assertDontSee(['Edit', 'Delete']);
});

test('job_index_guest_does_not_see_job_options', function () {
    $this->createJobs(count: 1);

    //User shouldn't see edit and delete options
    $this->actingAs($this->user)
        ->get(route('jobs.index'))
        ->assertDontSee(['Edit', 'Delete']);
});


test('job_featured', function () {
    $jobs = $this->createJobs(count: Constants::JOBS_PER_PAGE + 1, params: ['featured' => true]);

    $sortedJobs = $jobs->sortBy($this->sortingJobs);

    $this->get(route('jobs.featured'))
        ->assertStatus(200)
        ->assertSee('action="' . route('search') . '"', false)
        ->assertViewHas('jobs', fn($viewJobs) => $viewJobs->contains($sortedJobs->first()))
        ->assertViewHas('jobs', fn($viewJobs) => !$viewJobs->contains($sortedJobs->last()));

    $this->get(route('jobs.index', ['page' => 2]))
        ->assertViewHas('jobs', fn($viewJobs) => !$viewJobs->contains($sortedJobs->first()))
        ->assertViewHas('jobs', fn($viewJobs) => $viewJobs->contains($sortedJobs->last()));
});

test('job_featured_shows_only_featured', function () {
    $this->createJobs(count: Constants::JOBS_PER_PAGE, params: ['featured' => false]);

    $this->get(route('jobs.featured'))
        ->assertStatus(200)
        ->assertViewHas('jobs', fn($jobs) => $jobs->isEmpty());
});

test('job_show', function () {
    $job = $this->createJobs(count: 1);

    $this->get(route('jobs.show', $job->id))
        ->assertStatus(200)
        ->assertSeeText([$job->title, $job->schedule, $job->salary, $job->location, $job->employer->name, ...$job->tags->pluck('name')])
        ->assertViewHas('job', fn ($viewJob) => $viewJob->is($job));
});

test('job_show_owner_see_options', function() {
    $job = $this->createJobs(count: 1, user: $this->user);

    $this->actingAs($this->user)
        ->get(route('jobs.show', $job->id))
        ->assertSeeText(['Delete Job', 'Edit Job']);
});

test('job_show_non_owner_does_not_see_options', function() {
    $job = $this->createJobs(count: 1);

    $this->actingAs($this->user)
        ->get(route('jobs.show', $job->id))
        ->assertDontSeeText(['Delete Job', 'Edit Job']);
});

test('job_show_guest_does_not_see_options', function() {
    $job = $this->createJobs(count: 1);

    $this->actingAs($this->user)
        ->get(route('jobs.show', $job->id))
        ->assertDontSeeText(['Delete Job', 'Edit Job']);
});

test('job_create', function () {
    $this->actingAs($this->user)
        ->get(route('jobs.create'))
        ->assertStatus(200)
        ->assertSeeTextInOrder(['Job Title', 'Salary', 'Location', 'Schedule', 'URL', 'Tags', 'Employer', 'Featured Job', 'Create']);
});

test('job_create_cant_be_accessed_by_guest_user', function () {
    $this->get(route('jobs.create'))->assertRedirect(route('sessions.create'));
});

test('job_edit', function () {
    $job = $this->createJobs(count: 1, user: $this->user);

    $this->actingAs($this->user)
        ->get(route('jobs.edit', $job->id))
        ->assertStatus(200)
        ->assertSee([$job->title, $job->salary, $job->location, $job->schedule, $job->url, $job->employer->name, ...$job->tags->pluck('name')])
        ->assertViewHas('job', fn ($viewJob) => $viewJob->is($job));
});

test('job_edit_cant_be_accessed_by_guest_user', function () {
    $job = $this->createJobs(count: 1);

    $this->get(route('jobs.edit', $job->id))
        ->assertRedirect(route('sessions.create'));
});

test('job_edit_check_right_user', function () {
    $job = $this->createJobs(count: 1);

    $this->actingAs($this->user)
        ->get(route('jobs.edit', $job->id))
        ->assertStatus(403);
});

test('job_destroy', function () {
    $job = $this->createJobs(count: 1, user: $this->user);

    $this->actingAs($this->user)
        ->delete(route('jobs.destroy', $job->id))
        ->assertRedirectToRoute('users.profile', $this->user->id);

    $this->assertDatabaseMissing('jobs', $job->toArray())
        ->assertDatabaseCount('jobs', 0);
});