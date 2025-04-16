<?php

use App\Constants;
use App\Models\Job;

use App\Models\Tag;

test('tags_index', function () {
    $tagCount = 10;
    //Create a number of tags with 1 job (we don't show tags without jobs)
    $tags = Tag::factory()->count($tagCount)->create()->each(function ($tag) {
        $tag->jobs()->attach(Job::factory()->create());
    });

    $this->get(route('tags.index'))
        ->assertStatus(200)
        ->assertSeeText($tags->first()->name)
        ->assertSeeText($tags->last()->name)
        ->assertViewHas('tags', fn ($viewTags) => $viewTags->contains($tags->first()))
        ->assertViewHas('tags', fn ($viewTags) => $viewTags->contains($tags->last()))
        ->assertViewHas('tags', fn ($viewTags) => $viewTags->count() === $tagCount);
});

test('tags_show', function () {
    $tag = Tag::factory()->has(Job::factory()->count(Constants::JOBS_PER_PAGE + 1), 'jobs')->create();

    $sortedJobs = $tag->jobs->sortBy($this->sortingJobs);

    $this->get(route('tags.show', $tag->name))
        ->assertStatus(200)
        ->assertSeeText($tag->name)
        ->assertViewHas('tag', fn ($viewTag) => $viewTag->is($tag))
        ->assertViewHas('jobs', fn ($jobs) => $jobs->contains($sortedJobs->first()))
        ->assertViewHas('jobs', fn ($jobs) => !$jobs->contains($sortedJobs->last()));

    $this->get(route('tags.show', [$tag->name, 'page' => 2]))
        ->assertViewHas('jobs', fn ($jobs) => !$jobs->contains($sortedJobs->first()))
        ->assertViewHas('jobs', fn ($jobs) => $jobs->contains($sortedJobs->last()));
});

test('tag_show_sorting_jobs', function() {
    $tag = Tag::factory()->has(Job::factory()->count(Constants::JOBS_PER_PAGE * 2), 'jobs')->create();

    foreach(Constants::JOB_SORTING as $sorting => $sortingData) {
        $sortedJobs = $tag->jobs->sortBy(array_map(
            fn($sort) => explode(" ", $sort),
            explode(', ', $sortingData["order"])
        ));

        $this->withSession(['job-sorting' => $sorting])
            ->get(route('tags.show', $tag->name))
            ->assertStatus(200)
            ->assertViewHas('jobs', fn($jobs) => $jobs->contains($sortedJobs->first()))
            ->assertViewHas('jobs', fn($jobs) => !$jobs->contains($sortedJobs->last()));
    }
});