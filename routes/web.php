<?php

use App\Http\Controllers\JobController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name("index");
Route::view('/about', 'about')->name("about");
Route::view('/contact', 'contact')->name("contact");

Route::resource("jobs", controller: JobController::class);

Route::get("register", [UserController::class, "create"])->name("users.create")->middleware('guest');
Route::post("register", [UserController::class, "store"])->name("users.store")->middleware('guest');

Route::get("login", [SessionController::class, "create"])->name("sessions.create")->middleware('guest');
Route::post("login", [SessionController::class, "store"])->name("sessions.store")->middleware('guest');
Route::delete("logout", [SessionController::class, "destroy"])->name("sessions.destroy")->middleware('auth');

Route::fallback(fn() => redirect()->route("index"));