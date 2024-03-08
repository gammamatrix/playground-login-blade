<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Playground\Login\Blade\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Playground\Login\Blade\Http\Requests\NewPasswordRequest;
use Playground\Login\Blade\Http\Requests\StoreNewPasswordRequest;

/**
 * \Playground\Login\Blade\Http\Controllers\NewPasswordController
 */
class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     *
     * @route GET /reset-password/{token} password.reset
     */
    public function create(NewPasswordRequest $request): View
    {
        /**
         * @var array<string, mixed> $validated
         */
        $validated = $request->validated();

        $package_config_login_blade = config('playground-login-blade');

        return view($this->getPackageViewPathFromConfig(
            $package_config_login_blade,
            'reset-password'
        ), [
            'package_config_login_blade' => $package_config_login_blade,
            'token' => $request->route('token'),
        ]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @route POST /reset-password password.update
     *
     * @throws ValidationException
     */
    public function store(StoreNewPasswordRequest $request): RedirectResponse
    {
        /**
         * @var array<string, mixed> $validated
         */
        $validated = $request->validated();

        $password = ! empty($validated['password']) && is_string($validated['password']) ? $validated['password'] : '';

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        /**
         * @var string $status
         */
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', __($status));
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
