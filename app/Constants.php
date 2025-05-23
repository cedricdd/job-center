<?php

namespace App;

class Constants
{
    const SCHEDULES = ["Full Time", "Part Time", "Freelance", "Contract", "Internship", "Temporary"];
    const JOB_SORTING = [
        "newest" => ["label" => "Newest", "order" => "created_at desc"],
        "oldest" => ["label" => "Oldest", "order" => "created_at asc"],
        "salary_desc" => ["label" => "Salary desc", "order" => "salary desc, title asc"],
        "salary_asc" => ["label" => "Salary asc", "order" => "salary asc, title asc"],
        "alpha_asc" => ["label" => "Alphabetical A-Z", "order" => "title asc"],
        "alpha_desc" => ["label" => "Alphabetical Z-A", "order" => "title desc"],
    ];
    const JOB_SORTING_DEFAULT = "newest";
    const EMPLOYER_SORTING = [
        "alpha_asc" => ["label" => "Alphabetical A-Z", "order" => "name asc"],
        "alpha_desc" => ["label" => "Alphabetical Z-A", "order" => "name desc"],
        "count_desc" => ["label" => "#Jobs desc", "order" => "jobs_count desc, name asc"],
        "count_asc" => ["label" => "#Jobs asc", "order" => "jobs_count asc, name asc"],
    ];
    const EMPLOYER_SORTING_DEFAULT = "alpha_asc";

    const JOBS_PER_PAGE = 14;
    const EMPLOYERS_PER_PAGE = 20;

    /** Forms */
    const MAX_STRING_LENGTH = 255;
    const MIN_STRING_LENGTH = 3;
    const MIN_DESCRIPTION_EMPLOYER_LENGTH = 10;
    const MAX_WEIGHT_EMPLOYER_LOGO = 4096;
    const MIN_RES_EMPLOYER_LOGO = 100;
    const MAX_RES_EMPLOYER_LOGO = 500;
    const IMAGE_EXTENSIONS_ALLOWED = ["jpg", "png", "webp"];
    const MIN_PASSWORD_LENGTH = 7;
    const MAX_PASSWORD_LENGTH = 100;
}