<?php

namespace App;

class Constants
{
    const SCHEDULES = ["Full Time", "Part Time", "Freelance", "Contract", "Internship", "Temporary"];
    const JOB_SORTING = [
        "newest" => ["label" => "Newest", "order" => "created_at DESC"],
        "oldest" => ["label" => "Oldest", "order" => "created_at ASC"],
        "salary_desc" => ["label" => "Salary Desc", "order" => "salary DESC"],
        "salary_asc" => ["label" => "Salary Asc", "order" => "salary ASC"],
        "alpha_asc" => ["label" => "Alphabetical A-Z", "order" => "title ASC"],
        "alpha_desc" => ["label" => "Alphabetical Z-A", "order" => "title DESC"],
    ];
    const JOB_SORTING_DEFAULT = "newest";
}