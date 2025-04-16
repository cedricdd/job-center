<?php

namespace Tests;

use App\Constants;
use App\Models\Job;
use App\Models\User;
use App\Models\Employer;
use Illuminate\Http\UploadedFile;
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
        $this->employer = Employer::factory()->for($this->user, 'user')->create();
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

    /**
     * Provides default form data for an employer, with the ability to override specific fields by passing an associative array.
     * @param array $infos Optional associative array to override default form data or add more data.
     *                     Example: ['name' => 'Custom Name', 'url' => 'https://customurl.com']
     * @return array The resulting array containing employer form data.
     */
    protected function getEmployerFormData(array $infos = []): array {
        $size = (Constants::MIN_RES_EMPLOYER_LOGO + Constants::MAX_RES_EMPLOYER_LOGO) / 2;

        return $infos + [
            'name' => 'Test Company',
            'description' => 'Test Description',
            'url' => 'https://testcompany.com',
            'logo' => UploadedFile::fake()->image('avatar.jpg', $size, $size)->size(Constants::MAX_WEIGHT_EMPLOYER_LOGO / 2),
        ];
    }

    /**
     * Provides default form data for a job, with the ability to override specific fields by passing an associative array.
     * @param array $infos An associative array of custom values to override the default job form data.
     *                     Example: ['title' => 'Custom Job Title', 'salary' => '60,000 USD']
     * @return array The resulting array containing job form data.
     */
    protected function getJobFormData(array $infos = []): array {
        return $infos + [
            'title' => 'Test Job',
            'location' => 'Test Location',
            'schedule' => 'Full Time',
            'salary' => "$50.000 USD",
            'url' => 'https://testjob.com',
            'tags' => 'tag1,tag2',
            'employer_id' => $this->employer->id,
            'featured' => false,
        ];
    }
}
