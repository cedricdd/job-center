<?php

namespace Tests;

use App\Constants;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getEmployerSorting() {
        return array_map(
            fn($sort) => explode(" ", $sort),
            explode(', ', Constants::EMPLOYER_SORTING[Constants::EMPLOYER_SORTING_DEFAULT]["order"])
        );
    }

    protected function getJobSorting() {
        return array_map(
            fn($sort) => explode(" ", $sort),
            explode(', ', Constants::JOB_SORTING[Constants::JOB_SORTING_DEFAULT]["order"])
        );
    }
}
