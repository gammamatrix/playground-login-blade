<?php
/**
 * Playground
 */
namespace Playground\Login\Blade\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Playground\Login\Blade\Http\Requests\PasswordResetLinkRequest;

/**
 * \Playground\Login\Blade\Http\Controllers\PasswordResetLinkController
 */
class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        $package_config_login_blade = config('playground-login-blade');

        return view($this->getPackageViewPathFromConfig(
            $package_config_login_blade,
            'forgot-password'
        ), [
            'package_config_login_blade' => $package_config_login_blade,
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(PasswordResetLinkRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $status = Password::sendResetLink($validated);

        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
