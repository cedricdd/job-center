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
            <div>
                <x-nav-link name="about">Post A Job</x-nav-link>
            </div>
        </nav>

        <main class="mt-10">
            @if (session('failure'))
                <div class="flex items-center p-4 m-2 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800"
                    role="alert">
                    <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                    </svg>
                    <span class="sr-only">Info</span>
                    <div>
                        <span>{{ session('failure') }}</span>.
                    </div>
                </div>
            @endif
            @if (session('success'))
                <div class="flex items-center p-4 m-2 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 dark:border-green-800"
                    role="alert">
                    <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                    </svg>
                    <span class="sr-only">Info</span>
                    <div>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                @yield('content')
            </div>
        </main>
    </div>

    @yield('footer')
</body>

</html>
