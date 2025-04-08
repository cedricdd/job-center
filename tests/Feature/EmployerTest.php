<?php

use App\Models\Employer;
use App\Models\User;

test('employer_index_loads', function () {
    $response = $this->get(route('employers.index'));

    $response->assertStatus(200);
});

test('employer_show_loads', function () {
    $employer = Employer::factory()->for(User::factory(), 'user')->create();

    $response = $this->get(route('employers.show', $employer->id));

    $response->assertStatus(200);
});

test('employer_create_loads', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('employers.create'));

    $response->assertStatus(200);
});

test('employer_edit_loads', function () {
    $user = User::factory()->create();
    $employer = Employer::factory()->for($user, 'user')->create();

    $response = $this->actingAs($user)->get(route('employers.edit', $employer->id));

    $response->assertStatus(200);
});
