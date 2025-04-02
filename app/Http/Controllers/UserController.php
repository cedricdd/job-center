<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\RegisterRequest;

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

    public function profile(Request $request): View {
        $request->user()->load(["employers" => fn($query) => $query->withCount("jobs")->orderBy("name") ]);

        //We need the user info of each employers to check if it belongs to the uesr, we already know that they all do, skip a query
        $request->user()->employers->transform(fn($employer) => $employer->setRelation('user', $request->user()));

        return view("users.profile");
    }
}
