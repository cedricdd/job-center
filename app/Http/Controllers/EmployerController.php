<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployerRequest;
use App\Models\Employer;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EmployerController extends Controller implements HasMiddleware
{
    public static function middleware(): array {
        return [
            new Middleware('auth', except: ['index', 'show']),
            new Middleware('can:edit,employer', only: ['edit', 'update']),
            new Middleware('can:destroy,employer', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $employers = Employer::with("user")->withCount("jobs")->orderBy("name", "ASC")->paginate(20);

        return view("employers.index", compact('employers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("employers.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployerRequest $request)
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
    public function show(string $id)
    {
        //
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
    public function update(EmployerRequest $request, Employer $employer)
    {
        $employer->name = $request->input("name");
        $employer->url = $request->input("url");
        $employer->description = $request->input("description");
        if($request->has('logo')) $employer->logo = $request->file('logo')->store("logos");
        $employer->save();

        return redirect()->route("users.profile", $request->user()->id)->with("success", "The employer {$employer->name} was successfully updated!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
