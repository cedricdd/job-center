<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Models\Job;
use App\Models\Employer;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\EmployerRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class EmployerController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth', except: ['index', 'show', 'sorting']),
            new Middleware('can:update,employer', only: ['edit', 'update']),
            new Middleware('can:destroy,employer', only: ['destroy']),
            new Middleware('jobSorting', only: ['show']),
            new Middleware('employerSorting', only: ['index']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(string $employerSorting): View
    {
        $employers = Employer::with("user")
            ->withCount("jobs")
            ->orderByRaw(Constants::EMPLOYER_SORTING[$employerSorting]['order'])
            ->having("jobs_count", ">", 0)
            ->paginate(Constants::EMPLOYERS_PER_PAGE);

        return view("employers.index", compact('employers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view("employers.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployerRequest $request): RedirectResponse
    {
        $path = $request->file('logo')->store('logos');

        $employer = new Employer();
        $employer->name = $request->input('name');
        $employer->url = $request->input('url');
        $employer->description = $request->input('description');
        $employer->logo = $path;
        $employer->user()->associate($request->user())->save();

        return redirect()->route("users.profile", $request->user()->id)->with("success", "The company {$employer->name} has been successfully created!");
    }

    /**
     * Display the specified resource.
     */
    public function show(Employer $employer, string $jobSorting): View
    {
        $employer->loadCount(["jobs", "jobs as jobs_featured_count" => fn($query) => $query->where('featured', true)])->load("user");

        $jobs = $employer->jobs()->with('tags')->orderByRaw(Constants::JOB_SORTING[$jobSorting]['order'])->paginate(Constants::JOBS_PER_PAGE, total: $employer->jobs_count);

        $jobs->transform(fn(Job $job): Job => $job->setRelation('employer', $employer));

        return view("employers.show", compact("employer", "jobs"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employer $employer): View
    {
        return view("employers.edit", compact("employer"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployerRequest $request, Employer $employer): RedirectResponse
    {
        $employer->name = $request->input("name");
        $employer->url = $request->input("url");
        $employer->description = $request->input("description");

        if ($request->has('logo')) {
            //Remove the old logo from storage
            Storage::delete($employer->logo);

            //Store the new logo
            $employer->logo = $request->file('logo')->storeAs("logos", $employer->id . "." . $request->file('logo')->extension());
        }

        $employer->save();

        return redirect()->route("users.profile", $request->user()->id)->with("success", "The employer {$employer->name} was successfully updated!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Employer $employer): RedirectResponse
    {
        //Remove the logo from storage
        Storage::delete($employer->logo);

        //Remove the employer from the database
        $employer->delete();

        return redirect()->route("users.profile", $request->user()->id)->with("success", "The employer {$employer->name} was successfully deleted!");
    }
}
