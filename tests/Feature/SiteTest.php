<?php

use App\Models\User;

test('index_loads', function () {
    $response = $this->get(route('index'));

    $response->assertStatus(200);
    $response->assertSeeText('Find Your Next Job', 'Featured Jobs', 'Tags', 'Jobs');
});

test('login_loads', function () {
    $response = $this->get(route('sessions.create'));

    $response->assertStatus(200);
    $response->assertSeeTextInOrder(['Email', 'Password']);
});

test('register_loads', function () {
    $response = $this->get(route('users.create'));

    $response->assertStatus(200);
    $response->assertSeeTextInOrder(['Name', 'Email', 'Password']);
});

test('profile_loads', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->get(route('users.profile'));

    $response->assertStatus(200);
    $response->assertSeeTextInOrder(['Your Profile', 'Your Profile']);
});

test('search_loads', function () {
    $response = $this->get(route('search'));

    $response->assertStatus(200);
    $response->assertSeeText("No jobs matching your search have been found");
});

test('check_redirect_fallback', function () {
    $response = $this->get('/non-existent-route');

    $response->assertRedirectToRoute('index');
});