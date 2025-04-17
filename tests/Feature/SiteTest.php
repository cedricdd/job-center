<?php

use App\Constants;

use Illuminate\Support\Facades\DB;

test('index', function () {
    $this->get(route('index'))
        ->assertStatus(200)
        ->assertSeeText('Find Your Next Job', 'Featured Jobs', 'Tags', 'Jobs');
});

test('search', function () {
    $this->get(route('search', ['q' => '']))
        ->assertStatus(200)
        ->assertSeeText("No jobs matching your search have been found!");
});

test('search_with_query', function () {
    $jobs = $this->createJobs(count: Constants::JOBS_PER_PAGE * 2, params: ['title' => 'Test Job' . rand(1, 100)]);

    DB::commit(); //Fulltext search requires a commit to be effective, it doesn't work inside a transaction

    $sortedJobs = $jobs->sortBy($this->sortingJobs);

    $this->get(route('search', ['q' => 'Test Job']))
        ->assertStatus(200)
        ->assertDontSeeText("No jobs matching your search have been found!")
        ->assertViewHas('jobs', fn($viewJobs) => $viewJobs->first()->is($sortedJobs->first()))
        ->assertViewHas('jobs', fn($viewJobs) => !$viewJobs->contains($sortedJobs->last()));

    $this->get(route('search', ['q' => 'Test Job', 'page' => 2]))
        ->assertViewHas('jobs', fn($viewJobs) => !$viewJobs->contains($sortedJobs->first()))
        ->assertViewHas('jobs', fn($viewJobs) => $viewJobs->last()->is($sortedJobs->last()));
});

test('search_sorting', function() {
    $jobs = $this->createJobs(count: Constants::JOBS_PER_PAGE * 2, params: ['location' => 'Remote']);

    DB::commit(); //Fulltext search requires a commit to be effective, it doesn't work inside a transaction

    foreach(Constants::JOB_SORTING as $sorting => $sortingData) {
        $sortedJobs = $jobs->sortBy(array_map(
            fn($sort) => explode(" ", $sort),
            explode(', ', $sortingData["order"])
        ));

        $this->withSession(['job-sorting' => $sorting])
            ->get(route('search', ['q' => 'Remote']))
            ->assertStatus(200)
            ->assertViewHas('jobs', fn($viewJobs) => $viewJobs->first()->is($sortedJobs->first()))
            ->assertViewHas('jobs', fn($viewJobs) => !$viewJobs->contains($sortedJobs->last()));
    }
});


test('set_sorting_jobs', function() {
    foreach(Constants::JOB_SORTING as $sorting => $sortingData) {
        $this->post(route('sorting', 'job'), ['sorting' => $sorting])
            ->assertSessionHas('job-sorting', $sorting)
            ->assertStatus(302);
    }
});

test('set_sorting_jobs_invalid_valid_ignored', function() {
    $this->post(route('sorting', 'job'), ['sorting' => 'invalid-sorting'])
        ->assertSessionMissing('job-sorting')
        ->assertStatus(302);
});

test('set_sorting_employers', function() {
    foreach(Constants::EMPLOYER_SORTING as $sorting => $sortingData) {
        $this->post(route('sorting', 'employer'), ['sorting' => $sorting])
            ->assertSessionHas('employer-sorting', $sorting)
            ->assertStatus(302);
    }
});

test('set_sorting_employers_invalid_valid_ignored', function() {
    $this->post(route('sorting', 'employer'), ['sorting' => 'invalid-sorting'])
        ->assertSessionMissing('employer-sorting')
        ->assertStatus(302);
});

test('set_sorting_invalid_type', function() {
    $this->post(route('sorting', 'invalid'), ['sorting' => 'random'])->assertStatus(404);
});

test('check_redirect_fallback', function () {
    $this->get('/non-existent-route')->assertRedirectToRoute('index');
});