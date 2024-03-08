<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Playground\Login\Blade\Http\Controllers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use Laravel\Sanctum\PersonalAccessToken;
use Playground\Auth\Issuer;
use Playground\Login\Blade\Http\Requests\LoginRequest;

/**
 * \Playground\Login\Blade\Http\Controllers\AuthenticatedSessionController
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @route GET /login login
     */
    public function create(): View
    {
        $package_config_login_blade = config('playground-login-blade');

        return view($this->getPackageViewPathFromConfig(
            $package_config_login_blade,
            'login'
        ), [
            'package_config_login_blade' => $package_config_login_blade,
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Authenticate the user.
     *
     * @route POST /login login.post
     */
    public function store(LoginRequest $request): JsonResponse|RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $useSession = ! empty(config('playground-login-blade.session'));

        /**
         * @var Authenticatable $user
         */
        $user = $request->user();

        $issuer = app(Issuer::class);

        $payload = [
            'message' => __('authenticated'),
            'tokens' => $issuer->authorize($user),
        ];

        /**
         * @var array<string, mixed> $config_token
         */
        $config_token = config('playground-auth.token');

        $token_name = '';
        if (! empty($config_token['name'])
            && is_string($config_token['name'])
        ) {
            $token_name = $config_token['name'];
        }
        // dd([
        //     '__METHOD__' => __METHOD__,
        //     '$issuer' => $issuer,
        // ]);
        if (! empty($config_token['sanctum'])) {
            if ($user) {
                // $payload['tokens'] = app(Issuer::class)->sanctum($user);

                if ($useSession
                    && ! empty($payload['tokens'][$token_name])
                    && is_string($payload['tokens'][$token_name])
                ) {
                    $request->session()->put(
                        'sanctum',
                        $payload['tokens'][$token_name]
                    );
                }
            }
        }

        if ($useSession) {
            $payload['tokens']['session'] = $request->session()->token();
        }

        if ($request->expectsJson()) {
            return response()->json($payload);
        }

        return redirect()->intended($this->getRedirectUrl());
    }

    /**
     * Destroy an authenticated session.
     *
     * @route GET /logout logout
     * @route POST /logout
     */
    public function destroy(Request $request): JsonResponse|RedirectResponse
    {
        $config = config('playground-auth');
        $config = is_array($config) ? $config : [];

        if (! empty($config['sanctum'])
            && ! empty($config['token'])
            && is_array($config['token'])
            && ! empty($config['token']['sanctum'])
        ) {
            /**
             * @var Authenticatable $user
             */
            $user = $request->user();

            if ($user) {
                $this->destroyTokens($user, $request);
            }
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => __('logout'),
                'session_token' => $request->session()->token(),
            ]);
        }

        return redirect('/');
    }

    protected function destroyTokens(
        Authenticatable $user,
        Request $request
    ): void {
        $all = $request->has('all') || $request->has('everywhere');

        if ($all) {
            if (is_callable([$user, 'tokens'])) {
                $user->tokens()->delete();
            }
        } else {
            /**
             * @var ?PersonalAccessToken $token
             */
            $token = null;
            if (is_callable([$user, 'currentAccessToken'])) {
                $token = $user->currentAccessToken();
            }

            $hash = $request->session()->get('sanctum');
            if (! $token && $hash && is_string($hash)) {
                /**
                 * @var PersonalAccessToken $token
                 */
                $token = PersonalAccessToken::findToken($hash);
            }

            if ($token) {
                $token->delete();
            }
        }
    }

    /**
     * Return a CSRF token.
     *
     * @route GET /token token
     */
    public function token(Request $request): JsonResponse
    {
        return response()->json([
            'meta' => [
                'token' => csrf_token(),
            ],
        ]);
    }
}
