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

        $useSession = ! empty(config('playground-login-blade.session'));

        if ($useSession) {
            $request->session()->regenerate();
        }

        /**
         * @var array<string, mixed> $config
         */
        $config = config('playground-auth');

        $session_name = '';
        $token_name = '';

        if (is_array($config)
            && ! empty($config['sanctum'])
            && is_array($config['token'])
            && ! empty($config['token']['sanctum'])
        ) {
            if (! empty($config['token']['session_name'])
                && is_string($config['token']['session_name'])
            ) {
                $session_name = $config['token']['session_name'];
            }
            if (! empty($config['token']['name'])
                && is_string($config['token']['name'])
            ) {
                $token_name = $config['token']['name'];
            }
        }

        /**
         * @var Authenticatable $user
         */
        $user = $request->user();

        $issuer = app(Issuer::class);

        $payload = [
            'message' => __('authenticated'),
            'tokens' => $issuer->authorize($user),
        ];

        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '$config' => $config,
        //     '$useSession' => $useSession,
        //     '$session_name' => $session_name,
        //     '$token_name' => $token_name,
        //     '$user' => $user,
        //     '$issuer' => $issuer,
        //     '$payload' => $payload,
        // ]);

        if ($useSession) {

            $payload['tokens']['session'] = $request->session()->token();

            if ($token_name && $session_name) {

                if (! empty($payload['tokens'][$token_name])
                    && is_string($payload['tokens'][$token_name])
                ) {
                    $request->session()->put(
                        $session_name,
                        $payload['tokens'][$token_name]
                    );
                }
            }
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

        $useSession = ! empty(config('playground-login-blade.session'));

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
                $this->destroyTokens($user, $request, $config);
            }
        }

        Auth::guard('web')->logout();

        if ($useSession) {
            $request->session()->invalidate();

            $request->session()->regenerateToken();
        }

        if ($request->expectsJson()) {
            $data = [
                'message' => __('logout'),
            ];
            if ($useSession) {
                $data['session_token'] = $request->session()->token();
            }

            return response()->json($data);
        }

        return redirect('/');
    }

    /**
     * @param array<string, mixed> $config
     */
    protected function destroyTokens(
        Authenticatable $user,
        Request $request,
        array $config
    ): void {
        $all = $request->has('all') || $request->has('everywhere');
        $useSession = ! empty(config('playground-login-blade.session'));

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

            if ($useSession) {
                $session_name = '';
                if (! empty($config['sanctum'])
                    && ! empty($config['token'])
                    && is_array($config['token'])
                    && ! empty($config['token']['sanctum'])
                    && ! empty($config['token']['session_name'])
                    && is_string($config['token']['session_name'])
                ) {
                    $session_name = $config['token']['session_name'];
                }
                $hash = $session_name ? $request->session()->get($session_name) : null;
                if (! $token && $hash && is_string($hash)) {
                    /**
                     * @var PersonalAccessToken $token
                     */
                    $token = PersonalAccessToken::findToken($hash);
                }
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
