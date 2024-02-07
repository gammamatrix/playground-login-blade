<?php
/**
 * Playground
 */
namespace Playground\Login\Blade\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * \Playground\Login\Blade\Http\Controllers\ConfirmablePasswordController
 */
class ConfirmablePasswordController extends Controller
{
    /**
     * Show the confirm password view.
     *
     * @route GET /confirm-password password.confirm
     */
    public function show(): View
    {
        $package_config_login_blade = config('playground-login-blade');

        return view($this->getPackageViewPathFromConfig(
            $package_config_login_blade,
            'confirm-password'
        ), [
            'package_config_login_blade' => $package_config_login_blade,
        ]);
    }

    /**
     * Confirm the user's password.
     *
     * @route POST /confirm-password
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        if (! Auth::guard('web')->validate([
            'email' => $request->user()?->getAttributeValue('email'),
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended($this->getRedirectUrl());
    }
}
