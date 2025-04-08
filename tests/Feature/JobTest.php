<?php

use App\Models\Job; 
use App\Models\User;
use App\Models\Employer;

test('job_index_loads', function () {
    $response = $this->get(route('jobs.index'));

    $response->assertStatus(200);
});

test('job_featured_loads', function () {
    $response = $this->get(route('jobs.featured'));

    $response->assertStatus(200);
});

test('job_show_loads', function () {
    $job = Job::factory()->for(Employer::factory()->for(User::factory(), 'user'), 'employer')->create();

    $response = $this->get(route('jobs.show', $job->id));

    $response->assertStatus(200);
});

test('job_create_loads', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('jobs.create'));

    $response->assertStatus(200);
});

test('job_edit_loads', function () {
    $user = User::factory()->create();
    $job = Job::factory()->for(Employer::factory()->for($user, 'user'), 'employer')->create();

    $response = $this->actingAs($user)->get(route('jobs.edit', $job->id));

    $response->assertStatus(200);
});