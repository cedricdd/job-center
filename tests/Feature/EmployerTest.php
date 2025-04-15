<?php

use App\Constants;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('employer_index', function () {
    //Create a number of employers with 1 job (we don't show employers without jobs)
    $employers = $this->createEmployers(count: Constants::EMPLOYERS_PER_PAGE + 1);

    //Sort the employers by the default sorting
    $employersSorted = $employers->sortBy($this->sortingEmployers);

    $response = $this->get(route('employers.index'));

    $response->assertStatus(200);
    $response->assertSeeText('Company List');
    $response->assertViewHas('employers', fn($viewEmployers) => $viewEmployers->contains($employersSorted->first()));
    $response->assertViewHas('employers', fn($viewEmployers) => !$viewEmployers->contains($employersSorted->last()));

    $response = $this->get(route('employers.index', ['page' => 2]));

    $response->assertViewHas('employers', fn($viewEmployers) => !$viewEmployers->contains($employersSorted->first()));
    $response->assertViewHas('employers', fn($viewEmployers) => $viewEmployers->contains($employersSorted->last()));
});

test('employer_sorting', function() {
    $employers = collect();

    //We want random job count for the sorting
    for($i = 0; $i < Constants::EMPLOYERS_PER_PAGE * 2; $i++) {
        $rand = rand(1, 10);

        $employer = $this->createEmployers(count: 1, jobsCount: $rand);
        $employer->jobs_count = $rand;

        $employers->push($employer);
    }

    foreach (Constants::EMPLOYER_SORTING as $key => $infos) {
        $sorted = $employers->sortBy(array_map(
            fn($sort) => explode(" ", $sort),
            explode(', ', $infos["order"])
        ));

        $response = $this->withSession(['employer-sorting' => $key])->get(route('employers.index'));

        $response->assertStatus(200);
        $response->assertViewHas('employers', fn($viewEmployers) => $viewEmployers->first()->is($sorted->first()));
        $response->assertViewHas('employers', fn($viewEmployers) => !$viewEmployers->contains($sorted->last()));
    }
});

test('employer_index_hides_empty_employer', function () {
    //Create a number of employers with no jobs
    $this->createEmployers(count: 5, jobsCount: 0);

    $response = $this->get(route('employers.index'));

    $response->assertStatus(200);
    $response->assertViewHas('employers', fn($employers) => $employers->count() == 0);
});

test('employer_index_show_empty_employer_for_owner', function () {
    $response = $this->actingAs($this->user)->get(route('employers.index'));

    $response->assertStatus(200);
    $response->assertViewHas('employers', fn($employers) => $employers->contains($this->employer));
    $response->assertViewHas('employers', fn($employers) => $employers->count() == 1);
});

test('employer_owner_see_options', function() {
    $employer = $this->createEmployers(count: 1, jobsCount: 1, user: $this->user);

    foreach([route('employers.index'), route('employers.show', $employer->id)] as $route) { 
        $response = $this->actingAs($this->user)->get($route);

        $response->assertStatus(200);
        $response->assertSeeTextInOrder(['Add Job', 'Edit', 'Delete']);
        $response->assertSee(route('jobs.create', $employer->id));
        $response->assertSee(route('employers.edit', $employer->id));
        $response->assertSee('action="' . route('employers.destroy', $employer->id) . '"', false);
    }
});

test('employer_non_owner_dont_see_options', function() {
    $employer = $this->createEmployers(count: 1, jobsCount: 1);

    foreach([route('employers.index'), route('employers.show', $employer->id)] as $route) {
        $response = $this->actingAs(User::factory()->create())->get($route);

        $response->assertStatus(200);
        $response->assertDontSee(route('jobs.create', $employer->id));
        $response->assertDontSee(route('employers.edit', $employer->id));
        $response->assertDontSee('action="' . route('employers.destroy', $employer->id) . '"', false);
    }
});

test('employer_show', function () {
    $employer = $this->createEmployers(count: 1, jobsCount: Constants::JOBS_PER_PAGE + 1);

    $response = $this->get(route('employers.show', $employer->id));

    $response->assertStatus(200);
    $response->assertSeeText($employer->name);
    $response->assertViewHas('employer', fn($viewEmployer) => $viewEmployer->is($employer));

    $sortedJobs = $employer->jobs->sortBy($this->sortingJobs);

    $response->assertViewHas('jobs', fn($jobs) => $jobs->contains($sortedJobs->first()));
    $response->assertViewHas('jobs', fn($jobs) => !$jobs->contains($sortedJobs->last()));

    $response = $this->get(route('employers.show', [$employer->id, 'page' => 2]));

    $response->assertViewHas('jobs', fn($jobs) => $jobs->contains($sortedJobs->last()));
    $response->assertViewHas('jobs', fn($jobs) => !$jobs->contains($sortedJobs->first()));
});

test('employer_create', function () {
    $response = $this->actingAs($this->user)->get(route('employers.create'));

    $response->assertStatus(200);
    $response->assertSeeText('Create a Company');
});

test('employer_create_cant_be_accessed_by_guest_user', function () {
    $response = $this->get(route('employers.create'));

    $response->assertStatus(302);
    $response->assertRedirect(route('sessions.create'));
});

test('employer_store_cant_be_accessed_by_guest_user', function () {
    $response = $this->post(route('employers.store'));

    $response->assertStatus(302);
    $response->assertRedirect(route('sessions.create'));
});


test('employer_edit', function () {
    $response = $this->actingAs($this->user)->get(route('employers.edit', $this->employer->id));

    $response->assertStatus(200);
    $response->assertSee('value="' . $this->employer->name . '"', false);
    $response->assertViewHas('employer', $this->employer);
});

test('employer_edit_cant_be_accessed_by_guest_user', function () {
    $response = $this->get(route('employers.edit', $this->employer->id));

    $response->assertStatus(302);
    $response->assertRedirect(route('sessions.create'));
});

test('employer_edit_check_right_user', function () {
    $response = $this->actingAs(User::factory()->create())->get(route('employers.edit', $this->employer->id));

    $response->assertStatus(403);
});

test('employer_update_cant_be_accessed_by_guest_user', function () {
    $response = $this->put(route('employers.update', $this->employer->id));

    $response->assertStatus(302);
    $response->assertRedirect(route('sessions.create'));
});

test('employer_update_check_right_user', function () {
    $response = $this->actingAs(User::factory()->create())->put(route('employers.update', $this->employer->id));

    $response->assertStatus(403);
});

test('employer_destroy', function() {
    $response = $this->actingAs($this->user)->delete(route('employers.destroy', $this->employer->id));

    $response->assertStatus(302);
    $response->assertRedirect(route('users.profile', $this->user->id));
    $this->assertDatabaseMissing('employers', $this->employer->toArray());
    $this->assertDatabaseCount('employers', 0);
});
