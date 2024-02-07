<?php
/**
 * Playground
 */
namespace Playground\Login\Blade\Http\Controllers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Validation\Rules;
use Playground\Login\Blade\Http\Requests\RegisterUserRequest;

/**
 * \Playground\Login\Blade\Http\Controllers\RegisteredUserController
 */
class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $package_config_login_blade = config('playground-login-blade');

        return view($this->getPackageViewPathFromConfig(
            $package_config_login_blade,
            'register'
        ), [
            'package_config_login_blade' => $package_config_login_blade,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterUserRequest $request)
    {
        /**
         * @var array<string, mixed> $validated
         */
        $validated = $request->validated();
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|string|email|max:255|unique:users',
        //     'password' => ['required', 'confirmed', Rules\Password::defaults()],
        // ]);

        /**
         * @var class-string<\Illuminate\Database\Eloquent\Model> $c
         */
        $c = config('auth.providers.users.model', '\\App\\Models\\User');

        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable
         */
        $user = $c::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make(
                is_string($validated['password']) ? $validated['password'] : ''
            ),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect($this->getRedirectUrl());
    }
}
