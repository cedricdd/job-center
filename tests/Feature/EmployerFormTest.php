<?php

use App\Constants;
use App\Models\Employer;
use Illuminate\Support\Arr;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('employer_create_successful', function () {
    Storage::fake('public'); // Create a fake storage disk for testing

    $response = $this->actingAs($this->user)->post(route('employers.store'), $this->getEmployerFormData());

    $response->assertStatus(302);
    $response->assertValid();
    $response->assertRedirect(route('users.profile', $this->user->id));

    //Check if the employer was created in the database
    $this->assertDatabaseHas('employers', Arr::except($this->getEmployerFormData(['user_id' => $this->user->id]), 'logo'));

    $employer = Employer::latest('id')->first();
 
    //Check if the logo was uploaded
    Storage::assertExists($employer->logo);
});

test('employer_validate_employer_form_request', function ($field, $list) {
    Storage::fake('public'); // Create a fake storage disk in case the validation doesn't fail

    foreach($list as [$value, $error]) {
        $response = $this->actingAs($this->user)->post(route('employers.store'), $this->getEmployerFormData([$field => $value]));

        $response->assertStatus(302); // Redirect back to the form
        $response->assertInvalid([$field => $error]); // Assert validation errors
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
    $response = $this->actingAs($this->user)->post(route('employers.store'), $this->getEmployerFormData(['logo' => null]));
    
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

    //Not an image
    $response = $this->actingAs($this->user)->post(route('employers.store'), $this->getEmployerFormData(['logo' => UploadedFile::fake()->create('avatar.pdf', Constants::MAX_WEIGHT_EMPLOYER_LOGO / 2)]));

    $response->assertStatus(302);
    $response->assertInvalid(['logo' => 'The logo field must be an image.']);

    //Wrong type
    $response = $this->actingAs($this->user)->post(route('employers.store'), $this->getEmployerFormData(['logo' => UploadedFile::fake()->image('avatar.gif', $size, $size)->size(Constants::MAX_WEIGHT_EMPLOYER_LOGO / 2)]));
    
    $response->assertStatus(302);
    $response->assertInvalid(['logo' => ['The logo field must be a file of type: jpg, png, webp.']]);
});

test('employer_update_successful', function () {
    Storage::fake('public'); // Create a fake storage disk for testing

    $data = $this->getEmployerFormData();

    $response = $this->actingAs($this->user)->put(route('employers.update', $this->employer->id), $data);

    $response->assertStatus(302);
    $response->assertValid();
    $response->assertRedirect(route('users.profile', $this->user->id));

    //Check if the employer was updated in the database
    $employer = Employer::find($this->employer->id);

    foreach($data as $key => $value) {
        if ($key !== 'logo') {
            $this->assertEquals($value, $employer->$key);
        }
    }

    //Check if the logo was uploaded
    Storage::assertExists($employer->logo);
});


test('employer_update_logo_optional', function () {
    Storage::fake('public'); // Create a fake storage disk for testing

    $response = $this->actingAs($this->user)->put(route('employers.update', $this->employer->id), Arr::except($this->getEmployerFormData(), 'logo'));

    $response->assertStatus(302);
    $response->assertValid();
    $this->assertEquals($this->employer->logo, Employer::find($this->employer->id)->logo);
});
