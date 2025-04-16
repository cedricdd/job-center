<?php

use App\Constants;
use App\Models\Employer;
use Illuminate\Support\Arr;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('employer_create_successful', function () {
    Storage::fake('public'); // Create a fake storage disk for testing

    $this->actingAs($this->user)
        ->post(route('employers.store'), $this->getEmployerFormData())
        ->assertValid()
        ->assertRedirectToRoute('users.profile', $this->user->id);

    //Check if the employer was created in the database
    $this->assertDatabaseHas('employers', Arr::except($this->getEmployerFormData(['user_id' => $this->user->id]), 'logo'));

    //Check if the logo was uploaded
    Storage::assertExists(Employer::latest('id')->first()->logo);
});

test('employer_validate_employer_form_request', function ($field, $list) {
    Storage::fake('public'); // Create a fake storage disk in case the validation doesn't fail

    foreach($list as [$value, $error]) {
        $this->actingAs($this->user)
            ->post(route('employers.store'), $this->getEmployerFormData([$field => $value]))
            ->assertStatus(302)
            ->assertInvalid([$field => $error]); // Assert validation errors
    }
})->with([
    // Test cases: [field, [
    //     [value, error message],
    //     ...
    // ]]
    ['name',  [
            ['', 'The name field is required.'],
            [str_repeat('a', Constants::MAX_STRING_LENGTH + 1), 'The name field must not be greater than ' . Constants::MAX_STRING_LENGTH . ' characters.'],
        ]
    ],
    ['description', [
            ['', 'The description field is required.'],
            [str_repeat('a', Constants::MIN_DESCRIPTION_EMPLOYER_LENGTH - 1), 'The description field must be at least ' . Constants::MIN_DESCRIPTION_EMPLOYER_LENGTH . ' characters.'],
        ],
    ],
    ['url', [
            ['', 'The url field is required.'],
            ['invalid-url', 'The url field must be a valid URL.'],
        ],
    ],
]);

test('employer_create_validate_logo', function () {
    Storage::fake('public'); // Create a fake storage disk for testing

    $size = (Constants::MIN_RES_EMPLOYER_LOGO + Constants::MAX_RES_EMPLOYER_LOGO) / 2;

    //No logo
    $this->actingAs($this->user)
        ->post(route('employers.store'), $this->getEmployerFormData(['logo' => null]))
        ->assertStatus(302)
        ->assertInvalid(['logo' => 'The logo field is required.']);

    //Too small
    $this->actingAs($this->user)
        ->post(route('employers.store'), $this->getEmployerFormData(['logo' => UploadedFile::fake()->image('avatar.jpg', 10, 10)->size(Constants::MAX_WEIGHT_EMPLOYER_LOGO / 2)]))
        ->assertStatus(302)
        ->assertInvalid(['logo' => 'The logo needs to be at least 100x100 and at max 500x500']);

    //Too big
    $this->actingAs($this->user)
        ->post(route('employers.store'), $this->getEmployerFormData(['logo' => UploadedFile::fake()->image('avatar.jpg', 1000, 1000)->size(Constants::MAX_WEIGHT_EMPLOYER_LOGO / 2)]))
        ->assertStatus(302)
        ->assertInvalid(['logo' => 'The logo needs to be at least 100x100 and at max 500x500']);

    //Too heavy
    $this->actingAs($this->user)
        ->post(route('employers.store'), $this->getEmployerFormData(['logo' => UploadedFile::fake()->image('avatar.jpg', $size, $size)->size(Constants::MAX_WEIGHT_EMPLOYER_LOGO + 1)]))
        ->assertStatus(302)
        ->assertInvalid(['logo' => 'The logo field must not be greater than ' . Constants::MAX_WEIGHT_EMPLOYER_LOGO . ' kilobytes.']);

    //Not an image
    $this->actingAs($this->user)
        ->post(route('employers.store'), $this->getEmployerFormData(['logo' => UploadedFile::fake()->create('avatar.pdf', Constants::MAX_WEIGHT_EMPLOYER_LOGO / 2)]))
        ->assertStatus(302)
        ->assertInvalid(['logo' => 'The logo field must be an image.']);

    //Wrong type
    $this->actingAs($this->user)
        ->post(route('employers.store'), $this->getEmployerFormData(['logo' => UploadedFile::fake()->image('avatar.gif', $size, $size)->size(Constants::MAX_WEIGHT_EMPLOYER_LOGO / 2)]))
        ->assertStatus(302)
        ->assertInvalid(['logo' => ['The logo field must be a file of type: jpg, png, webp.']]);
});

test('employer_update_successful', function () {
    Storage::fake('public'); // Create a fake storage disk for testing

    $data = $this->getEmployerFormData();

    $this->actingAs($this->user)
        ->put(route('employers.update', $this->employer->id), $data)
        ->assertValid()
        ->assertRedirectToRoute('users.profile', $this->user->id);

    //Check if the employer was updated in the database
    $employer = Employer::find($this->employer->id);

    foreach($data as $key => $value) {
        if ($key !== 'logo') {
            expect($employer->$key)->toBe($value);
        }
    }

    //Check if the logo was uploaded
    Storage::assertExists($employer->logo);
});


test('employer_update_logo_optional', function () {
    Storage::fake('public'); // Create a fake storage disk for testing

    $this->actingAs($this->user)
        ->put(route('employers.update', $this->employer->id), Arr::except($this->getEmployerFormData(), 'logo'))
        ->assertValid()
        ->assertRedirectToRoute('users.profile', $this->user->id);

    expect($this->employer->logo)->toBe(Employer::find($this->employer->id)->logo); //Logo info should not change
});
