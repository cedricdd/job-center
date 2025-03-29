<?php

namespace Database\Seeders;

use App\Models\Employer;
use App\Models\Job;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tagIDs = Tag::select("id")->get();

        // $users = User::factory()->count(10)
        //     ->has(Employer::factory()->has(Job::factory()->count(100), "jobs"), "employers")
        //     ->create();

        // foreach($users as $user) {
        //     foreach($user->employers as $employer) {
        //         foreach($employer->jobs as $job) {
        //             $tagIDs->shuffle();

        //             $job->tags()->attach($tagIDs->slice(0, random_int(2, 5)));
        //         }
        //     }
        // }
        
        for($i = 0; $i < 10; ++$i) {
            Employer::factory()->count(random_int(1, 4))->for(User::factory(), "user")->create()->each(function ($employer) use ($tagIDs) {
                $employer->jobs()->saveMany(Job::factory()->count(random_int(10, max: 20))->make())
                    ->each(function ($job) use ($tagIDs) {
                        $tagIDs = $tagIDs->shuffle();
    
                        $job->tags()->attach($tagIDs->slice(0, random_int(1, 4)));
                    });
            });
        }
    }
}
