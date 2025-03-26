@extends('layouts.main')

@section('title', 'Login')

@section('content')
    <form class="max-w-sm mx-auto" accept-charset="UTF-8" method="POST" action="{{ route('sessions.store') }}">
        @csrf
        <div class="mb-5">
            <x-form-input input-name="email" label="Email" value="{{ old('email') }}" type="email" required />
        </div>
        <div class="mb-5">
            <x-form-input input-name="password" label="Password" type="password" required />
        </div>
        <div class="flex justify-end gap-2">
            <x-link-button-white href="/">Cancel</x-link-button-white>
            <x-button-blue>Login</x-button-blue>
        </div>
    </form>
@endsection