<?php

use App\Constants;
use App\Models\Job;
use Illuminate\Support\Arr;

test('job_create_successful', function () {
    $data = $this->getJobFormData();

    $this->actingAs($this->user)
        ->post(route('jobs.store'), $data)
        ->assertValid()
        ->assertRedirectToRoute('jobs.index');

    //Check if the employer was created in the database
    $this->assertDatabaseHas('jobs', Arr::except($data, 'tags'));

    //Check if the tags were created in the database
    expect(Job::first()->tags->pluck('name')->toArray())->toBe(array_map('ucwords', explode(",", $data['tags'])));
});

test('job_validate_form_request', function () {
    $this->checkForm(route('jobs.store'), $this->getJobFormData(), [
        [['title', 'salary', 'location', 'url', 'schedule', 'employer_id'], 'required', ''],
        [['title', 'salary', 'location', 'schedule', 'tags'], 'string', false],
        [['title', 'salary', 'location'], 'max.string', str_repeat('a', Constants::MAX_STRING_LENGTH + 1), ['max' => Constants::MAX_STRING_LENGTH]],
        [['title', 'salary', 'location'], 'min.string', 'a', ['min' => Constants::MIN_STRING_LENGTH]],
        ['url', 'active_url', 'invalid-url'],
        ['schedule', 'in', 'invalid_schedule'],
        ['employer_id', 'integer', 'invalid'],
        ['employer_id', 'owner_employer', 0],
        ['featured', 'boolean', 'invalid'],
    ], $this->user);
});

test('job_update_successful', function () {
    $job = $this->createJobs(count: 1, user: $this->user);

    $data = $this->getJobFormData();

    $this->actingAs($this->user)
        ->put(route('jobs.update', $job->id), $data)
        ->assertValid()
        ->assertRedirectToRoute('jobs.show', $job->id);

    $this->assertDatabaseHas('jobs', Arr::except($data, 'tags') + ['id' => $job->id]);

    //Check if the tags were update in the database
    expect(Job::find($job->id)->tags->pluck('name')->toArray())->toBe(array_map('ucwords', explode(",", $data['tags'])));
});
