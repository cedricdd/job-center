<?php

use App\Constants;
use App\Models\Job;
use App\Models\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('index', function () {
    $response = $this->get(route('index'));

    $response->assertStatus(200);
    $response->assertSeeText('Find Your Next Job', 'Featured Jobs', 'Tags', 'Jobs');
});

test('login', function () {
    $response = $this->get(route('sessions.create'));

    $response->assertStatus(200);
    $response->assertSeeTextInOrder(['Email', 'Password']);
});

test('register', function () {
    $response = $this->get(route('users.create'));

    $response->assertStatus(200);
    $response->assertSeeTextInOrder(['Name', 'Email', 'Password']);
});

test('profile', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->get(route('users.profile'));

    $response->assertStatus(200);
    $response->assertSeeTextInOrder(['Your Profile', 'Your Profile']);
});

test('search', function () {
    $response = $this->get(route('search'));

    $response->assertStatus(200);
    $response->assertSeeText("No jobs matching your search have been found");
});

test('search_with_query', function () {
    $jobs = Job::factory()->count(Constants::JOBS_PER_PAGE + 1)->create(['title' => 'Test Job' . rand(1, 100)]);

    DB::commit(); //Fulltext search requires a commit to be effective, it doesn't work inside a transaction

    $response = $this->get(route('search', ['q' => 'Test Job']));

    $response->assertStatus(200);
    $response->assertDontSeeText("No jobs matching your search have been found");

    $sortedJobs = $jobs->sortBy($this->getJobSorting());

    $response->assertViewHas('jobs', fn($viewJobs) => $viewJobs->contains($sortedJobs->first()));
    $response->assertViewHas('jobs', fn($viewJobs) => !$viewJobs->contains($sortedJobs->last()));

    $response = $this->get(route('search', ['q' => 'Test Job', 'page' => 2]));

    $response->assertViewHas('jobs', fn($viewJobs) => !$viewJobs->contains($sortedJobs->first()));
    $response->assertViewHas('jobs', fn($viewJobs) => $viewJobs->contains($sortedJobs->last()));
});

test('check_redirect_fallback', function () {
    $response = $this->get('/non-existent-route');

    $response->assertRedirectToRoute('index');
});