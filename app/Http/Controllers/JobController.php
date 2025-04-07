<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Models\Job;
use App\Models\Tag;
use App\Jobs\JobCreated;
use Illuminate\Http\Request;
use App\Http\Requests\JobRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class JobController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth', except: ['index', 'show', 'sorting']),
            new Middleware('can:update,job', only: ['edit', 'update']),
            new Middleware('can:destroy,job', only: ['destroy']),
            new Middleware('jobSorting', only: ['index']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(string $jobSorting): View
    {
        $jobs = Job::with([
            "employer" => fn($query) => $query->with("user"), 
            "tags" => fn($query) => $query->orderBy('name', 'ASC')
        ])->orderByRaw(Constants::JOB_SORTING[$jobSorting]['order'])->paginate(15);

        return view("jobs.index", compact("jobs"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(?int $employerID = 0): View
    {
        return view("jobs.create", compact('employerID'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JobRequest $request): RedirectResponse
    {
        $job = new Job();
        $job->title = $request->input("title");
        $job->salary = $request->input("salary");
        $job->location = $request->input("location");
        $job->schedule = $request->input("schedule");
        $job->url = $request->input("url");
        $job->employer()->associate($request->input("employer_id"))->save();

        if(!empty($request->input("tags"))) {
            $tagIDs = [];

            foreach(explode(",", $request->input("tags")) as $tag) {
                $tag = Tag::firstOrCreate(["name" => ucwords(trim($tag))]);
                $tagIDs[] = $tag->id;
            }
    
            if($tagIDs) $job->tags()->attach($tagIDs); //Only run one query to attach all the tags
        }

        JobCreated::dispatch(Auth::user(), $job);

        return redirect()->route("jobs.index")->with("success", "The job was successfully created!");
    }

    /**
     * Display the specified resource.
     */
    public function show(Job $job): View
    {
        $job->load(["employer", "tags" => fn($query) => $query->orderBy('name', 'ASC')]);

        return view("jobs.show", ["job" => $job]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Job $job): View
    {
        return view("jobs.edit", compact('job'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JobRequest $request, Job $job): RedirectResponse
    {
        $job->title = $request->input("title");
        $job->salary = $request->input("salary");
        $job->location = $request->input("location");
        $job->schedule = $request->input("schedule");
        $job->url = $request->input("url");
        $job->employer()->associate($request->input("employer_id"))->save();

        $tagIDs = [];

        if(!empty($request->input("tags"))) {
            foreach(explode(",", $request->input("tags")) as $tag) {
                $tag = Tag::firstOrCreate(["name" => ucwords(trim($tag))]);
                $tagIDs[] = $tag->id;
            }
        }

        $job->tags()->sync($tagIDs); 

        return redirect()->route("jobs.show", $job->id)->with("success", "The job $job->title was successfully updated!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Job $job): RedirectResponse
    {
        $job->delete();

        return redirect()->route("jobs.index")->with("success", "The job $job->title was successfully deleted!");
    }
}
