<?php

use App\Constants;
use App\Models\User;

test('login', function () {
    $this->get(route('sessions.create'))
        ->assertStatus(200)
        ->assertSeeTextInOrder(['Email', 'Password', 'Login']);
});

test('login_cant_be_accessed_by_authenticated_user', function () {
    $this->actingAs($this->user)
        ->get(route('sessions.create'))
        ->assertRedirectToRoute('index');
});

test('login_successful', function () {
    $this->post(route('sessions.store'), [
        'email' => $this->user->email,
        'password' => 'password'
    ])->assertSessionHasNoErrors()
      ->assertRedirectToRoute('users.profile', $this->user->id);

    $this->assertAuthenticatedAs($this->user);
});

test('login_failed', function () {
    $this->fromRoute('sessions.create')
        ->post(route('sessions.store'), $this->getLoginFormData(["password" => "wrong-password"]))
        ->assertSessionHasErrors(['email'])
        ->assertRedirectToRoute('sessions.create');

    $this->assertGuest();
    $this->assertTrue(session()->hasOldInput('email'));
    $this->assertFalse(session()->hasOldInput('password'));
});

test('login_form_validation', function() {
    $this->checkForm(route('sessions.store'), $this->getLoginFormData(), [
        [['email', 'password'], 'required', ''],
        ['email', 'email', 'invalid-email'],
    ]);
});

test('logout', function () {
    $this->be($this->user); // login 

    $this->actingAs($this->user)
        ->delete(route('sessions.destroy'))
        ->assertSessionHasNoErrors()
        ->assertRedirectToRoute('index');

    $this->assertGuest();
}); 



test('register', function () {
    $this->get(route('users.create'))
        ->assertStatus(200)
        ->assertSeeTextInOrder(['Name', 'Email', 'Password', 'Confirm Password', 'Register']);
});

test('register_cant_be_accessed_by_authenticated_user', function () {
    $this->actingAs($this->user)
        ->get(route('users.create'))
        ->assertRedirectToRoute('index');
});

test('register_successful', function () {
    $data = $this->getRegisterFormData();

    $this->post(route('users.store'), $data)
        ->assertSessionHasNoErrors()
        ->assertRedirectToRoute('index');

    $this->assertAuthenticatedAs(User::where('email', $data['email'])->first());
});

test('register_form_validation', function() {
    $this->checkForm(route('users.store'), $this->getRegisterFormData(), [
        [['name', 'email', 'password'], 'required', ''],
        [['name', 'password'], 'string', false],
        ['name', 'min.string', 'a', ['min' => Constants::MIN_STRING_LENGTH]],
        ['name', 'max.string', str_repeat('a', Constants::MAX_STRING_LENGTH + 1), ['max' => Constants::MAX_STRING_LENGTH]],
        ['email', 'email', 'invalid-email'],
        ['email', 'unique', $this->user->email],
        ['password', 'min.string', 'a', ['min' => Constants::MIN_PASSWORD_LENGTH]],
        ['password', 'max.string', str_repeat('a', Constants::MAX_PASSWORD_LENGTH + 1), ['max' => Constants::MAX_PASSWORD_LENGTH]],
        ['password', 'confirmed', 'invalid-password'],
    ]);
});



test('profile', function () {
    $this->actingAs($this->user)
        ->get(route('users.profile'))
        ->assertStatus(200)
        ->assertSeeTextInOrder(['Your Profile', 'Your Companies', 'Add A Company', 'Add A Job']);
});

test('profile_cant_be_accessed_by_guest_user', function () {
    $this->get(route('users.profile'))
        ->assertRedirectToRoute('sessions.create');
});