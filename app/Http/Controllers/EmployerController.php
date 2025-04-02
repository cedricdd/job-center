<?php

namespace App\Http\Controllers;

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
            new Middleware('auth', except: ['index', 'show']),
            new Middleware('can:update,employer', only: ['edit', 'update']),
            new Middleware('can:destroy,employer', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $employers = Employer::with("user")->withCount("jobs")->orderBy("name", "ASC")->where("jobs_count", ">", 0)->paginate(20);

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
    public function show(Employer $employer): View
    {
        $employer->loadCount("jobs")->with("user");

        $jobs = $employer->jobs()->with(['tags'])->orderBy("created_at", "DESC")->paginate(10, total: $employer->jobs_count);

        $jobs->transform(fn (Job $job): Job => $job->setRelation('employer', $employer));

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
