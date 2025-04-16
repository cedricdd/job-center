<?php

use App\Constants;
use App\Models\Job;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Lang;

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

test('job_validate_employer_form_request', function ($fields, $infos) {
    foreach($fields as $field) {

        //Get the error message based on the rule used
        $attribute = Lang::has("validation.attributes.{$field}") ? Lang::get("validation.attributes.{$field}") : $field;
        $error = Lang::get('validation.' . $infos[1], compact('attribute') + ($infos[2] ?? []));

        $this->actingAs($this->user)
            ->post(route('jobs.store'), $this->getJobFormData([$field => $infos[0]]))
            ->assertStatus(302)
            ->assertInvalid([$field => $error]); // Assert validation errors
    }
})->with([
    [['title', 'salary', 'location', 'url', 'schedule', 'employer_id'], ['', 'required']],
    [['title', 'salary', 'location', 'schedule', 'tags'], [true, 'string']],
    [['title', 'salary', 'location'], [str_repeat('a', Constants::MAX_STRING_LENGTH + 1), 'max.string', ['max' => Constants::MAX_STRING_LENGTH]]],
    [['title', 'salary', 'location'], ['a', 'min.string', ['min' => Constants::MIN_STRING_LENGTH]]],
    [['url'], ['invalid-url', 'active_url']],
    [['schedule'], ['invalid_schedule', 'in']],
    [['employer_id'], ['invalid', 'integer']],
    [['employer_id'], [0, 'owner_employer']],
    [['featured'], ['invalid', 'boolean']],
]);

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
