<?php

namespace App;

class Constants
{
    const SCHEDULES = ["Full Time", "Part Time", "Freelance", "Contract", "Internship", "Temporary"];
    const JOB_SORTING = [
        "newest" => ["label" => "Newest", "order" => "created_at DESC"],
        "oldest" => ["label" => "Oldest", "order" => "created_at ASC"],
        "salary_desc" => ["label" => "Salary Desc", "order" => "salary DESC, title ASC"],
        "salary_asc" => ["label" => "Salary Asc", "order" => "salary ASC, title ASC"],
        "alpha_asc" => ["label" => "Alphabetical A-Z", "order" => "title ASC"],
        "alpha_desc" => ["label" => "Alphabetical Z-A", "order" => "title DESC"],
    ];
    const JOB_SORTING_DEFAULT = "newest";
    const EMPLOYER_SORTING = [
        "alpha_asc" => ["label" => "Alphabetical A-Z", "order" => "name ASC"],
        "alpha_desc" => ["label" => "Alphabetical Z-A", "order" => "name DESC"],
        "count_desc" => ["label" => "#Jobs Desc", "order" => "jobs_count DESC, name ASC"],
        "count_asc" => ["label" => "#Jobs Asc", "order" => "jobs_count ASC, name ASC"],
    ];
    const EMPLOYER_SORTING_DEFAULT = "alpha_asc";
}