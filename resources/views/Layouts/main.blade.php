<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    @vite(['resources/js/app.js'])

    <title>@yield('title')</title>

    @yield('header')
</head>

<body class="min-h-full bg-dark text-white">
    <div class="p-6">
        <nav class="flex justify-between items-center border-b-2 border-white/25 py-2">
            <div>
                <img loading="lazy" style="width:50px;" src="{{ Vite::asset("resources/images/logo.jpg") }}" alt="logo" />
            </div>
            <div>
                <x-nav-link name="index">Jobs</x-nav-link>
                <x-nav-link name="jobs.index">Careers</x-nav-link>
                <x-nav-link name="contact">Salaries</x-nav-link>
                <x-nav-link name="about">Companies</x-nav-link>
            </div>
            <div class="flex items-center gap-1">
                @auth
                    <x-nav-link name="users.profile">Profile</x-nav-link>
                    <form action="{{ route("sessions.destroy") }}" method="POST" class="inline-block">
                        @csrf
                        @method("DELETE")
                        <x-forms.button-red>Logout</x-forms.button-red>
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

    @yield('footer')
</body>

</html>
