<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @vite(['resources/js/app.js'])

    <title>@yield('title')</title>

    @stack('header')
</head>

<body class="min-h-full bg-dark text-white pb-20">
    <div class="p-6">
        <nav class="flex justify-between items-center border-b-2 border-white/25 py-2">
            <a href="{{ route('index') }}">
                <img loading="lazy" style="width:50px;" src="{{ Vite::asset("resources/images/logo.jpg") }}" alt="logo" />
            </a>
            <div>
                <x-nav-link name="jobs.index">Jobs</x-nav-link>
                <x-nav-link name="jobs.featured">Featured</x-nav-link>
                <x-nav-link name="tags.index">Tags</x-nav-link>
                <x-nav-link name="employers.index">Companies</x-nav-link>
            </div>
            <div class="flex items-center gap-1">
                @auth
                    <x-nav-link name="users.profile">Profile</x-nav-link>
                    <form action="{{ route("sessions.destroy") }}" method="POST" class="inline-block">
                        @csrf
                        @method("DELETE")
                        <x-forms.button-white>Logout</x-forms.button-white>
                    </form>
                @endauth
                @guest
                <x-nav-link name="users.create">Register</x-nav-link>
                <x-nav-link name="sessions.create">Login</x-nav-link>
                @endguest
            </div>
        </nav>

        <x-flash-message name="failure" />
        <x-flash-message name="success" />

        <main class="mt-10">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                @yield('content')
            </div>
        </main>
    </div>

    @stack('footer')
</body>

</html>
