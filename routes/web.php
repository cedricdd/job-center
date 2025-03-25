<?php

use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name("index");

Route::get('/about', action: function () {
    return view('about');
})->name("about");

Route::get('/contact', function () {
    return view('contact');
})->name("contact");


Route::resource("jobs", controller: JobController::class);

Route::fallback(fn() => redirect()->route("index"));