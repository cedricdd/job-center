<?php

namespace Database\Seeders;

use App\Models\Employer;
use App\Models\Job;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tagIDs = Tag::select("id")->get();

        Employer::factory()->count(10)->create()->each(function ($employer) use ($tagIDs) {
            $employer->jobs()->saveMany(Job::factory()->count(random_int(10, 100))->make())
                ->each(function ($job) use ($tagIDs) {
                    $tagIDs = $tagIDs->shuffle();

                    $job->tags()->attach($tagIDs->slice(0, random_int(1, 5)));
                });
        });
    }
}
