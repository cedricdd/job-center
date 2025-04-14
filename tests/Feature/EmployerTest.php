<?php

use App\Constants;
use App\Models\Employer;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

test('employer_index_hides_empty_employer', function () {
    //Create a number of employers with no jobs
    $this->createEmployers(count: 5, jobsCount: 0);

    $response = $this->get(route('employers.index'));

    $response->assertStatus(200);
    $response->assertSeeText('Company List');
    $response->assertViewHas('employers', fn($employers) => $employers->count() == 0);
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

    $response->assertRedirect(route('sessions.create'));
});

test('employer_create_successful', function () {
    Storage::fake('public'); // Create a fake storage disk for testing

    $size = (Constants::MIN_RES_EMPLOYER_LOGO + Constants::MAX_RES_EMPLOYER_LOGO) / 2;

    $response = $this->actingAs($this->user)->post(route('employers.store'), $this->getEmployerFormData(['logo' => UploadedFile::fake()->image('avatar.jpg', $size, $size)->size(Constants::MAX_WEIGHT_EMPLOYER_LOGO / 2)]));

    $response->assertStatus(302);
    $response->assertValid();
    $response->assertRedirect(route('users.profile', $this->user->id));

    $this->assertDatabaseHas('employers', $this->getEmployerFormData(['user_id' => $this->user->id]));

    $employer = Employer::latest('id')->first();
 
    Storage::assertExists($employer->logo);
});

test('employer_create_failed', function () {
    $response = $this->actingAs($this->user)->post(route('employers.store'), [
        'name' => '',
        'description' => '',
        'url' => '',
    ]);

    $response->assertStatus(302);
    $response->assertInvalid(['name', 'description', 'url']);
});


test('employer_create_valid_logo', function () {
    Storage::fake('public'); // Create a fake storage disk for testing

    $size = (Constants::MIN_RES_EMPLOYER_LOGO + Constants::MAX_RES_EMPLOYER_LOGO) / 2;

    //No logo
    $response = $this->actingAs($this->user)->post(route('employers.store'), $this->getEmployerFormData());
    
    $response->assertStatus(302);
    $response->assertInvalid(['logo' => 'The logo field is required.']);

    //Too small
    $response = $this->actingAs($this->user)->post(route('employers.store'), $this->getEmployerFormData(['logo' => UploadedFile::fake()->image('avatar.jpg', 10, 10)->size(Constants::MAX_WEIGHT_EMPLOYER_LOGO / 2)]));
    
    $response->assertStatus(302);
    $response->assertInvalid(['logo' => 'The logo needs to be at least 100x100 and at max 500x500']);

    //Too big
    $response = $this->actingAs($this->user)->post(route('employers.store'), $this->getEmployerFormData(['logo' => UploadedFile::fake()->image('avatar.jpg', 1000, 1000)->size(Constants::MAX_WEIGHT_EMPLOYER_LOGO / 2)]));
    
    $response->assertStatus(302);
    $response->assertInvalid(['logo' => 'The logo needs to be at least 100x100 and at max 500x500']);

    //Too heavy
    $response = $this->actingAs($this->user)->post(route('employers.store'), $this->getEmployerFormData(['logo' => UploadedFile::fake()->image('avatar.jpg', $size, $size)->size(Constants::MAX_WEIGHT_EMPLOYER_LOGO + 1)]));

    $response->assertStatus(302);
    $response->assertInvalid(['logo' => 'The logo field must not be greater than ' . Constants::MAX_WEIGHT_EMPLOYER_LOGO . ' kilobytes.']);
});

test('employer_edit', function () {
    $employer = $this->createEmployers(count: 1, user: $this->user);

    $response = $this->actingAs($this->user)->get(route('employers.edit', $employer->id));

    $response->assertStatus(200);
    $response->assertSeeText($employer->name);
    $response->assertViewHas('employer', fn($viewEmployer) => $viewEmployer->is($employer));
});

test('employer_edit_cant_be_accessed_by_guest_user', function () {
    $response = $this->get(route('employers.edit', $this->employer->id));

    $response->assertRedirect(route('sessions.create'));
});

test('employer_edit_check_right_user', function () {
    $response = $this->actingAs($this->user)->get(route('employers.edit', $this->employer->id));

    $response->assertStatus(403);
});
