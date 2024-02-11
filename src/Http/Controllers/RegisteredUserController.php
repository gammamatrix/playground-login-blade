<?php
/**
 * Playground
 */
namespace Playground\Login\Blade\Http\Controllers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Playground\Login\Blade\Http\Requests\RegisterUserRequest;

/**
 * \Playground\Login\Blade\Http\Controllers\RegisteredUserController
 */
class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @route GET /register register
     */
    public function create(): View
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
     * @route POST /register register.post
     */
    public function store(RegisterUserRequest $request): RedirectResponse
    {
        /**
         * @var array<string, mixed> $validated
         */
        $validated = $request->validated();

        /**
         * @var class-string<\Illuminate\Database\Eloquent\Model> $u
         */
        $u = config('auth.providers.users.model', '\\App\\Models\\User');

        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user
         */
        $user = $u::create([
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
