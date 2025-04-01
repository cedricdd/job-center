<?php

namespace App\Http\Controllers;

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

        if ($term == "Full Time" || $term == "Part Time" || $term == "Freelance") {
            $jobs = Job::with(["employer", "tags" => fn($query) => $query->orderBy('name', 'ASC')])
            ->where("schedule", "=", $term)
            ->latest()
            ->paginate(15);
        } else {
            $jobs = Job::with(["employer", "tags" => fn($query) => $query->orderBy('name', 'ASC')])
                ->where("title", "LIKE", "%{$term}%")
                ->orWhereHas("tags", fn($query) => $query->where("name", "LIKE", "%{$term}%"))
                ->latest()
                ->paginate(15);
        }

        $jobs->appends(["q" => $term]);

        return view("jobs.index", compact("jobs"));
    }
}
