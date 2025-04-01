<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class SiteController extends Controller
{
    public function index(): View {
        $tags = Tag::mostUsed()->limit(20)->get();
        $jobs = Job::with(["employer", "tags" => fn($query) => $query->orderBy('name', 'ASC')])->limit(10)->latest()->get();
        $jobsFeatured = Job::with(["employer", "tags" => fn($query) => $query->orderBy('name', 'ASC')])->whereFeatured(true)->limit(6)->latest()->get();

        return view("index", compact("jobs", "jobsFeatured", "tags"));
    }
}
