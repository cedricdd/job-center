<?php

use App\Models\Employer;
use App\Models\User;

test('employer_index_loads', function () {
    $response = $this->get(route('employers.index'));

    $response->assertStatus(200);
    $response->assertSeeText('Company List');
});

test('employer_show_loads', function () {
    $employer = Employer::factory()->for(User::factory(), 'user')->create();

    $response = $this->get(route('employers.show', $employer->id));

    $response->assertStatus(200);
    $response->assertSeeTextInOrder([$employer->name, "Jobs List"]);
});

test('employer_create_loads', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('employers.create'));

    $response->assertStatus(200);
    $response->assertSeeText('Create a Company');
});

test('employer_edit_loads', function () {
    $user = User::factory()->create();
    $employer = Employer::factory()->for($user, 'user')->create();

    $response = $this->actingAs($user)->get(route('employers.edit', $employer->id));

    $response->assertStatus(200);
    $response->assertSeeText($employer->name);
});

test('employer_edit_check_right_user', function () {
    $user = User::factory()->create();
    $employer = Employer::factory()->for(User::factory(), 'user')->create();

    $response = $this->actingAs($user)->get(route('employers.edit', $employer->id));

    $response->assertStatus(403);
});
