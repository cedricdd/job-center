<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobRequest;
use App\Jobs\JobCreated;
use App\Models\Job;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth', except: ['index', 'show']),
            new Middleware('can:edit,job', only: ['edit', 'update']),
            new Middleware('can:destroy,job', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $jobs = Job::with(["employer", "tags" => fn($query) => $query->orderBy('name', 'ASC')])->latest()->paginate(10);

        return view("jobs.index", ["jobs" => $jobs]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view("jobs.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JobRequest $request): RedirectResponse
    {
        $job = new Job();
        $job->title = $request->input("title");
        $job->salary = $request->input("salary");
        $job->employer()->associate($request->input("employer_id"))->save();

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
        $job->employer()->associate($request->input("employer_id"))->save();

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
