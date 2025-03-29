@extends('layouts.main')

@section('title', 'Register')

@section('content')
    <x-header-title>Register</x-header-title>

    <form class="max-w-xl mx-auto" accept-charset="UTF-8" method="POST" action="{{ route('users.store') }}">
        @csrf
        <div class="mb-5">
            <x-forms.input input-name="name" label="Your Name" value="{{ old('name') }}" required />
        </div>
        <div class="mb-5">
            <x-forms.input input-name="email" label="Email" value="{{ old('email') }}" type="email" required />
        </div>
        <div class="mb-5">
            <x-forms.input input-name="password" label="Password" type="password" required />
        </div>
        <div class="mb-5">
            <x-forms.input input-name="password_confirmation" label="Confirm Password" type="password" required />
        </div>
        <div class="flex justify-between">
            <x-link-button-white href="/">Cancel</x-link-button-white>
            <x-forms.button-blue>Register</x-forms.button-blue>
        </div>
    </form>
@endsection
