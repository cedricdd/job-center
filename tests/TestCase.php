<?php

namespace Tests;

use App\Constants;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected User $user;
    protected array $sortingJobs;
    protected array $sortingEmployers;

    protected function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->sortingJobs = array_map(
            fn($sort) => explode(" ", $sort),
            explode(', ', Constants::JOB_SORTING[Constants::JOB_SORTING_DEFAULT]["order"])
        );
        $this->sortingEmployers = array_map(
            fn($sort) => explode(" ", $sort),
            explode(', ', Constants::EMPLOYER_SORTING[Constants::EMPLOYER_SORTING_DEFAULT]["order"])
        );
    }
}
