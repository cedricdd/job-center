<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function create(): View {
        return view("users.create");
    }

    public function store(RegisterRequest $request): RedirectResponse {
        $user = new User();
        $user->name = $request->input("name");
        $user->email = $request->input("email");
        $user->password = $request->input("password");
        $user->save();

        Auth::login($user);

        return redirect()->route("index")->with("success", "The user has been successfully created!");
    }

    public function profile() {
        return view("users.profile");
    }
}
