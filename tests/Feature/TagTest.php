<?php

use App\Constants;
use App\Models\Job;

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('tags_index', function () {
    //Create a number of tags with 1 job (we don't show tags without jobs)
    $tags = Tag::factory()->count(10)->create()->each(function ($tag) {
        $tag->jobs()->attach(Job::factory()->create());
    });

    $response = $this->get(route('tags.index'));

    $response->assertStatus(200);
    $response->assertSeeText('Tags Center');
    $response->assertSeeText($tags->first()->name);
    $response->assertViewHas('tags', fn ($viewTags) => $viewTags->contains($tags->first()));
    $response->assertViewHas('tags', fn ($viewTags) => $viewTags->count() === 10 );
});

test('tags_show', function () {
    $tag = Tag::factory()->create();
    $tag->jobs()->attach(Job::factory()->count(Constants::JOBS_PER_PAGE + 1)->create());

    $response = $this->get(route('tags.show', $tag->name));

    $response->assertStatus(200);
    $response->assertSeeText($tag->name);
    $response->assertViewHas('tag', fn ($viewTag) => $viewTag->is($tag));

    $sortedJobs = $tag->jobs->sortBy($this->getJobSorting());

    $response->assertViewHas('jobs', fn ($jobs) => $jobs->contains($sortedJobs->first()));
    $response->assertViewHas('jobs', fn ($jobs) => !$jobs->contains($sortedJobs->last()));

    $response = $this->get(route('tags.show', [$tag->name, 'page' => 2]));

    $response->assertViewHas('jobs', fn ($jobs) => !$jobs->contains($sortedJobs->first()));
    $response->assertViewHas('jobs', fn ($jobs) => $jobs->contains($sortedJobs->last()));
});

