<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobRequest;
use App\Models\Employer;
use App\Models\Job;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class JobController extends Controller
{
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
        $employers = Employer::all();

        return view("jobs.create", compact("employers"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JobRequest $request): RedirectResponse
    {
        $job = new Job();
        $job->title = $request->input("title");
        $job->salary = $request->input("salary");
        $job->employer()->associate($request->input("employer_id"));
        $job->save();

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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
