<?php

namespace App\Http\Controllers\Administrator\Auth;

use App\Http\Controllers\Controller;
use App\Models\Administrator;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class RegisteredAdministratorController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return Inertia::render('Administrator/Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:administrators',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $administrator = Administrator::create([
            'name' => $request->name,
            'lastname' => 'algun valor',
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($administrator));

        Auth::guard('administrator')->login($administrator);

        //return redirect(RouteServiceProvider::HOME);
        return redirect('/admin/dashboard');
    }
}
