@extends("layouts.main")

@section("title", "Job Center")

@section("content")
    <section>
        <x-title-header>Top Jobs</x-title-header>

        <div class="mt-6 flex gap-2 justify-center">
            <x-jobs-display-small />
            <x-jobs-display-small />
            <x-jobs-display-small />
        </div>
    </section>

    <section class="my-10">
        <x-title-header>Tags</x-title-header>

        <div class="flex gap-2 mt-4">
            <x-tag>{{ fake()->word() }}</x-tag>
            <x-tag>{{ fake()->word() }}</x-tag>
            <x-tag>{{ fake()->word() }}</x-tag>
            <x-tag>{{ fake()->word() }}</x-tag>
            <x-tag>{{ fake()->word() }}</x-tag>
            <x-tag>{{ fake()->word() }}</x-tag>
            <x-tag>{{ fake()->word() }}</x-tag>
            <x-tag>{{ fake()->word() }}</x-tag>
            <x-tag>{{ fake()->word() }}</x-tag>
            <x-tag>{{ fake()->word() }}</x-tag>
        </div>
    </section>

    <section>
        <x-title-header>Jobs</x-title-header>

        <div class="space-y-6 mt-4">
            <x-jobs-display-big />
            <x-jobs-display-big />
            <x-jobs-display-big />
            <x-jobs-display-big />
            <x-jobs-display-big />
        </div>
    </section>
@endsection