<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\EmployerController;

Route::get('/', [SiteController::class, 'index'])->name("index");

Route::get("jobs/create/{employer_id?}", [JobController::class, "create"])->name("jobs.create")->where('employer_id', '[0-9]+');
Route::get("jobs/featured", [JobController::class, "index"])->name("jobs.featured");
Route::resource("jobs", controller: JobController::class)->except('create');

Route::resource("employers", controller: EmployerController::class);

Route::get("register", [UserController::class, "create"])->name("users.create")->middleware('guest');
Route::post("register", [UserController::class, "store"])->name("users.store")->middleware('guest');

Route::get("login", [SessionController::class, "create"])->name("sessions.create")->middleware('guest');
Route::post("login", [SessionController::class, "store"])->name("sessions.store")->middleware('guest');
Route::delete("logout", [SessionController::class, "destroy"])->name("sessions.destroy")->middleware('auth');

Route::get("profile", [UserController::class, "profile"])->name("users.profile")->middleware("auth");

Route::get("search", [SiteController::class, "search"])->name("search");

Route::post("sorting/{type}", [SiteController::class, "sorting"])->name("sorting")->where('type', '[a-z]+');

Route::get("tags", [TagController::class, "index"])->name("tags.index");
Route::get("tags/{tag:name}", [TagController::class, "show"])->name("tags.show")->middleware('jobSorting');
Route::post("tags/autocomplete", [TagController::class, "autocomplete"])->name("tags.autocomplete")->middleware('auth');

Route::fallback(fn() => redirect()->route("index"));