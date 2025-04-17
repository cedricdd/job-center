<?php

use App\Constants;
use App\Models\User;

test('employer_index', function () {
    //Create a number of employers with 1 job (we don't show employers without jobs)
    $employers = $this->createEmployers(count: Constants::EMPLOYERS_PER_PAGE * 2);

    //Sort the employers by the default sorting
    $employersSorted = $employers->sortBy($this->sortingEmployers);

    $this->get(route('employers.index'))
        ->assertStatus(200)
        ->assertSeeText('Company List')
        ->assertViewHas('employers', fn($viewEmployers) => $viewEmployers->first()->is($employersSorted->first()))
        ->assertViewHas('employers', fn($viewEmployers) => !$viewEmployers->contains($employersSorted->last()));

    $this->get(route('employers.index', ['page' => 2]))
        ->assertViewHas('employers', fn($viewEmployers) => !$viewEmployers->contains($employersSorted->first()))
        ->assertViewHas('employers', fn($viewEmployers) => $viewEmployers->last()->is($employersSorted->last()));
});

test('employer_sorting', function () {
    $employers = collect();

    //We want random job counts for the sorting
    for ($i = 0; $i < Constants::EMPLOYERS_PER_PAGE * 2; $i++) {
        $rand = rand(1, 10);

        $employer = $this->createEmployers(count: 1, jobsCount: $rand);
        $employer->jobs_count = $rand;

        $employers->push($employer);
    }

    //Test all the sortings
    foreach (Constants::EMPLOYER_SORTING as $key => $infos) {
        $sorted = $employers->sortBy(array_map(
            fn($sort) => explode(" ", $sort),
            explode(', ', $infos["order"])
        ));

        $this->withSession(['employer-sorting' => $key])
            ->get(route('employers.index'))
            ->assertStatus(200)
            ->assertViewHas('employers', fn($viewEmployers) => $viewEmployers->first()->is($sorted->first()))
            ->assertViewHas('employers', fn($viewEmployers) => !$viewEmployers->contains($sorted->last()));
    }
});

test('employer_index_hides_empty_employer', function () {
    //Create a number of employers with no jobs
    $this->createEmployers(count: 5, jobsCount: 0);

    $this->get(route('employers.index'))
        ->assertStatus(200)
        ->assertViewHas('employers', fn($employers) => $employers->count() == 0);
});


test('employer_index_show_empty_employer_for_owner', function () {
    //We already have an empty employer belonging to the user
    $this->actingAs($this->user)
        ->get(route('employers.index'))
        ->assertStatus(200)
        ->assertViewHas('employers', fn($employers) => $employers->contains($this->employer))
        ->assertViewHas('employers', fn($employers) => $employers->count() == 1);
});

test('employer_owner_see_options', function () {
    $employer = $this->createEmployers(count: 1, jobsCount: 1, user: $this->user);

    foreach ([route('employers.index'), route('employers.show', $employer->id)] as $route) {
        $this->actingAs($this->user)->get($route)
            ->assertStatus(200)
            ->assertSeeTextInOrder(['Add Job', 'Edit', 'Delete'])
            ->assertSee(route('jobs.create', $employer->id))
            ->assertSee(route('employers.edit', $employer->id))
            ->assertSee('action="' . route('employers.destroy', $employer->id) . '"', false);
    }
});

test('employer_non_owner_dont_see_options', function () {
    $employer = $this->createEmployers(count: 1, jobsCount: 1);

    foreach ([route('employers.index'), route('employers.show', $employer->id)] as $route) {
        $this->actingAs(User::factory()->create())->get($route)
            ->assertStatus(200)
            ->assertDontSee(route('jobs.create', $employer->id))
            ->assertDontSee(route('employers.edit', $employer->id))
            ->assertDontSee('action="' . route('employers.destroy', $employer->id) . '"', false);
    }
});

test('employer_show', function () {
    $employer = $this->createEmployers(count: 1, jobsCount: Constants::JOBS_PER_PAGE * 2);

    $sortedJobs = $employer->jobs->sortBy($this->sortingJobs);

    $this->get(route('employers.show', $employer->id))
        ->assertStatus(200)
        ->assertSeeText($employer->name)
        ->assertViewHas('employer', fn($viewEmployer) => $viewEmployer->is($employer))
        ->assertViewHas('jobs', fn($jobs) => $jobs->first()->is($sortedJobs->first()))
        ->assertViewHas('jobs', fn($jobs) => !$jobs->contains($sortedJobs->last()));

    $this->get(route('employers.show', [$employer->id, 'page' => 2]))
        ->assertViewHas('jobs', fn($jobs) => !$jobs->contains($sortedJobs->first()))
        ->assertViewHas('jobs', fn($jobs) => $jobs->last()->is($sortedJobs->last()));
});

test('employer_create', function () {
    $this->actingAs($this->user)
        ->get(route('employers.create'))
        ->assertStatus(200)
        ->assertSeeTextInOrder(['Create a Company', 'Name', 'Description', 'URL', 'Logo', 'Create']);
});

test('employer_create_cant_be_accessed_by_guest_user', function () {
    $this->get(route('employers.create'))
        ->assertRedirectToRoute('sessions.create');
});

test('employer_store_cant_be_accessed_by_guest_user', function () {
    $this->post(route('employers.store'))
        ->assertRedirectToRoute('sessions.create');
});


test('employer_edit', function () {
    $this->actingAs($this->user)
        ->get(route('employers.edit', $this->employer->id))
        ->assertStatus(200)
        ->assertSeeInOrder([
            $this->employer->name,
            'Name', 'value="' . $this->employer->name . '"',
            'Description', $this->employer->description,
            'URL', 'value="' . $this->employer->url . '"',
            'Logo', 
            'Edit'
        ], false)
        ->assertViewHas('employer', $this->employer);
});

test('employer_edit_cant_be_accessed_by_guest_user', function () {
    $this->get(route('employers.edit', $this->employer->id))
        ->assertRedirectToRoute('sessions.create');
});

test('employer_edit_check_right_user', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('employers.edit', $this->employer->id))
        ->assertStatus(403);
});

test('employer_update_cant_be_accessed_by_guest_user', function () {
    $this->put(route('employers.update', $this->employer->id))
        ->assertRedirectToRoute('sessions.create');
});

test('employer_update_check_right_user', function () {
    $this->actingAs(User::factory()->create())
        ->put(route('employers.update', $this->employer->id))
        ->assertStatus(403);
});

test('employer_destroy', function () {
    $this->actingAs($this->user)
        ->delete(route('employers.destroy', $this->employer->id))
        ->assertRedirectToRoute('users.profile', $this->user->id);

    $this->assertDatabaseMissing('employers', $this->employer->toArray())
        ->assertDatabaseCount('employers', 0);
});
