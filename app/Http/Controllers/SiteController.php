<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Models\Job;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class SiteController extends Controller
{
    public function index(): View
    {
        $tags = Tag::mostUsed()->limit(20)->get();

        $jobs = Job::with([
            "employer" => fn($query) => $query->with("user"), 
            "tags" => fn($query) => $query->orderBy('name', 'ASC')
        ])->limit(10)->latest()->get();

        $jobsFeatured = Job::with([
            "employer" => fn($query) => $query->with("user"), 
            "tags" => fn($query) => $query->orderBy('name', 'ASC')
        ])->whereFeatured(true)->limit(6)->latest()->get();

        return view("index", compact("jobs", "jobsFeatured", "tags"));
    }

    public function search(Request $request, string $jobSorting): View
    {
        $term = $request->input("q", "");
        
        $jobs = Job::with([
            "employer" => fn($query) => $query->with("user"), 
            "tags" => fn($query) => $query->orderBy('name', 'ASC')
        ])->whereFullText(['title', 'location', 'schedule'], $term)
        ->orderByRaw(Constants::JOB_SORTING[$jobSorting]['order'])
        ->paginate(Constants::JOBS_PER_PAGE);

        $jobs->appends(["q" => $term]);

        $title = "Jobs Listed for " . $term;

        return view("jobs.index", compact("jobs", "title"));
    }

    public function sorting(Request $request, string $type): RedirectResponse
    {
        if($type == "job") {
            $sorting = $request->input("sorting", Constants::JOB_SORTING_DEFAULT);

            if (isset(Constants::JOB_SORTING[$sorting])) {
                $request->session()->put("job-sorting", $sorting);
            } else {
                $request->session()->forget("job-sorting");
            }
        } elseif ($type == "employer") {
            $sorting = $request->input("sorting", Constants::EMPLOYER_SORTING_DEFAULT);

            if (isset(Constants::EMPLOYER_SORTING[$sorting])) {
                $request->session()->put("employer-sorting", $sorting);
            } else {
                $request->session()->forget("employer-sorting");
            }
        }
        else abort(404);

        return redirect()->back();
    }
}
