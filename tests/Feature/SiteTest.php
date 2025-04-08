<?php

use App\Models\User;

test('index_loads', function () {
    $response = $this->get(route('index'));

    $response->assertStatus(200);
});

test('login_loads', function () {
    $response = $this->get(route('sessions.create'));

    $response->assertStatus(200);
});

test('register_loads', function () {
    $response = $this->get(route('users.create'));

    $response->assertStatus(200);
});

test('profile_loads', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->get(route('users.profile'));

    $response->assertStatus(200);
});

test('search_loads', function () {
    $response = $this->get(route('search'));

    $response->assertStatus(200);
});

test('check_redirect_fallback', function () {
    $response = $this->get('/non-existent-route');

    $response->assertRedirectToRoute('index');
});