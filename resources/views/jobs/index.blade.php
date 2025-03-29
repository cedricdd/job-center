@extends('layouts.main')

@section('title', 'Jobs Center')

@section('content')
    <section class="text-center mb-30">
        <h1 class="text-3xl mb-4">Find Your Next Job</h1>
        <form action="" class="mp-6">
            <input type="text" placeholder="Web Developper" class="bg-white/10 border border-white/20 px-6 py-3 rounded-xl w-full max-w-2xl" />
        </form>
    </section>

    <section>
        <x-title-header>Featured Jobs</x-title-header>

        <div class="mt-6 flex flex-wrap gap-2 justify-center">
            @foreach ($jobsFeatured as $job)
                <x-jobs.display-small :$job />
            @endforeach
        </div>
    </section>

    <section class="my-10">
        <x-title-header>Tags</x-title-header>

        <div class="flex flex-wrap gap-2 mt-4 text-center">
            @foreach ($tags as $tag)
                <x-tag-display :$tag class="flex-1" />
            @endforeach
        </div>
    </section>

    <section>
        <x-title-header>Jobs</x-title-header>

        <div class="space-y-6 mt-4">
            @foreach ($jobs as $job)
                <x-jobs.display-big :$job />
            @endforeach
        </div>
    </section>
@endsection
