<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Models\Job;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class SiteController extends Controller
{
    public function index(): View
    {
        $tags = Tag::mostUsed()->limit(20)->get();
        $jobs = Job::with(["employer", "tags" => fn($query) => $query->orderBy('name', 'ASC')])->limit(10)->latest()->get();
        $jobsFeatured = Job::with(["employer", "tags" => fn($query) => $query->orderBy('name', 'ASC')])->whereFeatured(true)->limit(6)->latest()->get();

        return view("index", compact("jobs", "jobsFeatured", "tags"));
    }

    public function search(): View
    {
        $term = request("q");
        $jobs = Job::with([
            "employer" => fn($query) => $query->with("user"), 
            "tags" => fn($query) => $query->orderBy('name', 'ASC')
        ]);

        if (in_array(strtolower($term), array_map('strtolower', Constants::SCHEDULES))) {
            $jobs = $jobs->where("schedule", "=", $term);
        } else {
            $jobs = $jobs->where("title", "LIKE", "%{$term}%")
                ->orWhereHas("tags", fn($query) => $query->where("name", "LIKE", "%{$term}%"));
        }

        $jobs = $jobs->latest()->paginate(15);
        $jobs->appends(["q" => $term]);

        return view("jobs.index", compact("jobs"));
    }
}
