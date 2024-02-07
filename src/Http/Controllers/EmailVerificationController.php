<?php
/**
 * Playground
 */
namespace Playground\Login\Blade\Http\Controllers;

use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Playground\Login\Blade\Http\Requests\EmailVerificationRequest;

/**
 * \Playground\Login\Blade\Http\Controllers\EmailVerificationController
 */
class EmailVerificationController extends Controller
{
    /**
     * Display the email verification prompt.
     *
     * @route GET /verify-email verification.notice
     */
    public function show(Request $request): Response|JsonResponse|RedirectResponse|View
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable&\Illuminate\Contracts\Auth\MustVerifyEmail
         */
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended($this->getRedirectUrl());
        }

        $package_config_login_blade = config('playground-login-blade');

        return view($this->getPackageViewPathFromConfig(
            $package_config_login_blade,
            'verify-email'
        ), [
            'package_config_login_blade' => $package_config_login_blade,
        ]);
    }

    /**
     * Send a new email verification notification.
     *
     * @route POST /verify-email verification.send
     */
    public function send(Request $request): RedirectResponse|Response
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable&\Illuminate\Contracts\Auth\MustVerifyEmail
         */
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended($this->getRedirectUrl());
        }

        $user->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @route POST /verify-email/{id}/{hash} verification.verify
     */
    public function verify(
        EmailVerificationRequest $request
    ): Response|JsonResponse|RedirectResponse|View {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable&\Illuminate\Contracts\Auth\MustVerifyEmail
         */
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended($this->getRedirectUrl().'?verified=1');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->intended($this->getRedirectUrl().'?verified=1');
    }
}
