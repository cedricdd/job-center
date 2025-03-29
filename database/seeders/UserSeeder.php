<?php

namespace Database\Seeders;

use App\Models\Employer;
use App\Models\Job;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tagIDs = Tag::select("id")->get();

        //Create some jobs that we own
        $john = User::factory()->johnDoe()->has(Employer::factory()->count(random_int(2, 5)), "employers")->create();

        $john->employers()->each(function($employer) use ($tagIDs) {
            $employer->jobs()->saveMany(Job::factory()->count(random_int(10, max: 25))->make())->each(function ($job) use ($tagIDs) {
                $tagIDs = $tagIDs->shuffle();
    
                $job->tags()->attach($tagIDs->slice(0, random_int(2, 4)));
            });
        });
    }
}
