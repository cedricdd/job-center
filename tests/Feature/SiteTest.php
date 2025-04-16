<?php

use App\Constants;

use Illuminate\Support\Facades\DB;

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

test('login_cant_be_accessed_by_authenticated_user', function () {
    $response = $this->actingAs($this->user)->get(route('sessions.create'));

    $response->assertRedirect(route('index'));
});

test('register', function () {
    $response = $this->get(route('users.create'));

    $response->assertStatus(200);
    $response->assertSeeTextInOrder(['Name', 'Email', 'Password']);
});

test('register_cant_be_accessed_by_authenticated_user', function () {
    $response = $this->actingAs($this->user)->get(route('users.create'));

    $response->assertRedirect(route('index'));
});

test('profile', function () {
    $response = $this->actingAs($this->user)->get(route('users.profile'));

    $response->assertStatus(200);
    $response->assertSeeTextInOrder(['Your Profile', 'Your Profile']);
});

test('profile_cant_be_accessed_by_guest_user', function () {
    $response = $this->get(route('users.profile'));

    $response->assertRedirect(route('sessions.create'));
});

test('search', function () {
    $response = $this->get(route('search'));

    $response->assertStatus(200);
    $response->assertSeeText("No jobs matching your search have been found");
});

test('search_with_query', function () {
    $jobs = $this->createJobs(count: Constants::JOBS_PER_PAGE + 1, params: ['title' => 'Test Job' . rand(1, 100)]);

    DB::commit(); //Fulltext search requires a commit to be effective, it doesn't work inside a transaction

    $response = $this->get(route('search', ['q' => 'Test Job']));

    $response->assertStatus(200);
    $response->assertDontSeeText("No jobs matching your search have been found");

    $sortedJobs = $jobs->sortBy($this->sortingJobs);

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