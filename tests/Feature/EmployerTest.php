<?php

use App\Constants;
use App\Models\Job;
use App\Models\User;
use App\Models\Employer;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('employer_index', function () {
    //Create a number of employers with 1 job (we don't show employers without jobs)
    $employers = Employer::factory()->count(Constants::EMPLOYERS_PER_PAGE + 1)->create()->each(function ($employer) {
        $employer->jobs()->save(Job::factory()->make());
    });

    //Find the last employer in the list with the default sorting
    $employer = $employers->sortBy($this->sortingEmployers);

    $response = $this->get(route('employers.index'));

    $response->assertStatus(200);
    $response->assertSeeText('Company List');
    $response->assertViewHas('employers', fn($viewEmployers) => $viewEmployers->contains($employer->first()));
    $response->assertViewHas('employers', fn($viewEmployers) => !$viewEmployers->contains($employer->last()));

    $response = $this->get(route('employers.index', ['page' => 2]));

    $response->assertViewHas('employers', fn($viewEmployers) => !$viewEmployers->contains($employer->first()));
    $response->assertViewHas('employers', fn($viewEmployers) => $viewEmployers->contains($employer->last()));
});

test('employer_index_hides_empty_employer', function () {
    //Create a number of employers with no jobs
    Employer::factory()->count(10)->create();

    $response = $this->get(route('employers.index'));

    $response->assertStatus(200);
    $response->assertSeeText('Company List');
    $response->assertViewHas('employers', fn($employers) => $employers->count() == 0);
});

test('employer_show', function () {
    $employer = Employer::factory()->create();
    $employer->jobs()->saveMany(Job::factory()->count(Constants::JOBS_PER_PAGE + 1)->make());

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

    $response->assertRedirect(route('sessions.create'));
});

test('employer_edit', function () {
    $employer = Employer::factory()->for($this->user, 'user')->create();

    $response = $this->actingAs($this->user)->get(route('employers.edit', $employer->id));

    $response->assertStatus(200);
    $response->assertSeeText($employer->name);
    $response->assertViewHas('employer', fn($viewEmployer) => $viewEmployer->is($employer));
});

test('employer_edit_cant_be_accessed_by_guest_user', function () {
    $employer = Employer::factory()->create();

    $response = $this->get(route('employers.edit', $employer->id));

    $response->assertRedirect(route('sessions.create'));
});

test('employer_edit_check_right_user', function () {
    $user = User::factory()->create();
    $employer = Employer::factory()->create();

    $response = $this->actingAs($user)->get(route('employers.edit', $employer->id));

    $response->assertStatus(403);
});
