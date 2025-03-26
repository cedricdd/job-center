<?php

namespace App\Http\Controllers;

use App\Http\Requests\SessionRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;


class SessionController extends Controller
{
    
    public function create(): View {
        return view("sessions.create");
    }

    public function store(SessionRequest $request): RedirectResponse {
        if(! RateLimiter::attempt('login-attempt:' . $request->ip(), 5, function() {})) {
            throw ValidationException::withMessages([
                'email' => "To many failled login attempts, try again later!",
            ]);
        }

        if(! Auth::attempt($request->only(["email", "password"]))) {
            throw ValidationException::withMessages([
                'email' => "The email/password you provided are invalid!",
            ]);
        }

        RateLimiter::clear('login-attempt:' . $request->ip());

        Session::regenerate();

        return redirect()->route("jobs.index")->with("success", "You have been successfully logged in!");
    }

    public function destroy(): RedirectResponse {
        Auth::logout();

        Session::invalidate();
        Session::regenerateToken();

        return redirect()->route("index")->with("success", "You have been successfully logged out!");
    }
}
