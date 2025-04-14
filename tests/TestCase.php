<?php

namespace Tests;

use App\Constants;
use App\Models\Job;
use App\Models\User;
use App\Models\Employer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected User $user;
    protected Employer $employer;
    protected array $sortingJobs;
    protected array $sortingEmployers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->employer = Employer::factory()->create();
        $this->sortingJobs = array_map(
            fn($sort) => explode(" ", $sort),
            explode(', ', Constants::JOB_SORTING[Constants::JOB_SORTING_DEFAULT]["order"])
        );
        $this->sortingEmployers = array_map(
            fn($sort) => explode(" ", $sort),
            explode(', ', Constants::EMPLOYER_SORTING[Constants::EMPLOYER_SORTING_DEFAULT]["order"])
        );
    }

    /**
     * Create one or multiple Job instances with optional associations and parameters.
     *
     * @param int $count The number of Job instances to create. Defaults to 10.
     * @param Employer|null $employer An optional Employer instance to associate with the Job(s). If null, a new Employer instance will be created.
     * @param User|null $user An optional User instance to associate with the Employer. If null, a new User instance will be created.
     * @param array $params Additional attributes to override during Job creation.
     * @return Collection|Job A single Job instance if $count is 1, otherwise a Collection of Job instances.
     */
    protected function createJobs(int $count = 10, Employer|null $employer = null, User|null $user = null, array $params = []): Collection|Job
    {
        $jobs = Job::factory()
            ->for($employer ?? Employer::factory()->for($user ?? User::factory(), 'user'), 'employer')
            ->count($count)
            ->create($params);

        if ($count === 1)
            return $jobs->first();

        return $jobs;
    }

    /**
     * Create one or more Employer instances with associated jobs and user.
     *
     * @param int $count The number of Employer instances to create. Defaults to 10.
     * @param int $jobsCount The number of Job instances to associate with each Employer. Defaults to 1.
     * @param User|null $user An optional User instance to associate with the Employer(s). If null, a new User will be created.
     * @param array $params Additional attributes to override when creating the Employer(s).
     * @return Collection|Employer A single Employer instance if $count is 1, otherwise a Collection of Employer instances.
     */
    protected function createEmployers(int $count = 10, int $jobsCount = 1, User|null $user = null, array $params = []): Collection|Employer
    {
        $employers = Employer::factory()
            ->for($user ?? User::factory(), 'user')
            ->has(Job::factory()->count($jobsCount), 'jobs')
            ->count($count)
            ->create($params);

        if ($count === 1)
            return $employers->first();

        return $employers;
    }
}
