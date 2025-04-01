@extends("layouts.main")

@section("title", "Your Profile")

@section("content")
    <x-header-title>Your Profile</x-header-title>

    <div class="mb-5">
        <x-forms.input-disabled label="Your Name" input-name="name" disabled>{{ Auth::user()->name }}</x-forms.input-disabled>
    </div>    
    <div class="mb-5">
        <x-forms.input-disabled label="Email" input-name="email" disabled>{{ Auth::user()->email }}</x-forms.input-disabled>
    </div>

    <x-header-title>Your Companies</x-header-title>

    <div class="text-center mb-6">
        <x-link-button-blue href="{{ route('employers.create') }}">Add A Company</x-link-button-blue>
    </div>

    <div class="space-y-4">
        @foreach (Auth::user()->employers as $employer)
            <x-employers.card :$employer />
        @endforeach
    </div>
@endsection