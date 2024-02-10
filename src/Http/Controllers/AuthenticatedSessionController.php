<?php
/**
 * Playground
 */
namespace Playground\Login\Blade\Http\Controllers;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use Laravel\Sanctum\Contracts\HasApiTokens;
use Playground\Login\Blade\Http\Requests\LoginRequest;
use Playground\Auth\Issuer;

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
     * TODO This should work with any kind of authentication system. Identify what is supported.
     *
     * Types:
     * - User::$priviliges
     * - User::hasPrivilige()
     * - User::$roles
     * - User::hasRole() - with string or array?
     * - User::hasRoles()
     * - Auth::user()?->currentAccessToken()?->can('app:*')
     * - Auth::user()?->currentAccessToken()?->can($withPrivilege.':create')
     *
     * @experimental Subject to change
     *
     * @return array<int, string>
     */
    protected function privileges(Authenticatable $user): array
    {
        $privileges = [];

        $hasRoles = ! empty(config('playground-auth.token.roles'));

        $isAdmin = $hasRoles && is_callable([$user, 'hasRole']) && $user->hasRole(['admin', 'wheel', 'root']);
        $isManager = $hasRoles && is_callable([$user, 'hasRole']) && $user->hasRole(['amanager']);

        $managers = config('playground-login-blade.managers');

        /**
         * @var string $email
         */
        $email = $user->getAttributeValue('email');

        if (is_array($managers)) {
            if ($email && in_array($email, $managers)) {
                $isAdmin = false;
                $isManager = true;
            }
        }

        $admins = config('playground-auth.admins');
        if (is_array($admins)) {
            if ($email && in_array($email, $admins)) {
                $isAdmin = true;
                $isManager = false;
            }
        }

        if ($isAdmin) {
            $privileges_admin = config('playground-auth.privileges.admin');
            if (is_array($privileges_admin)) {
                foreach ($privileges_admin as $privilege) {
                    if (is_string($privilege)
                        && $privilege
                        && ! in_array($privilege, $privileges)
                    ) {
                        $privileges[] = $privilege;
                    }
                }
            }
        } elseif ($isManager) {
            $privileges_manager = config('playground-auth.privileges.manager');
            if (is_array($privileges_manager)) {
                foreach ($privileges_manager as $privilege) {
                    if (is_string($privilege)
                        && $privilege
                        && ! in_array($privilege, $privileges)
                    ) {
                        $privileges[] = $privilege;
                    }
                }
            }
        } else {
            $privileges_user = config('playground-auth.privileges.user');
            if (is_array($privileges_user)) {
                foreach ($privileges_user as $privilege) {
                    if (is_string($privilege)
                        && $privilege
                        && ! in_array($privilege, $privileges)
                    ) {
                        $privileges[] = $privilege;
                    }
                }
            }
        }

        return $privileges;
    }

    /**
     * NOTE: Creates multiple keys. Not sure if it is ok to reuse a token?
     * TODO: This needs the device_name handling for Sanctum
     *
     * @param Authenticatable&HasApiTokens $user
     * @return array<string, string>
     */
    protected function issue(Authenticatable $user): array
    {
        $tokens = [];

        /**
         * @var string $name
         */
        $name = config('playground-auth.token.name');

        $privileges = $this->privileges($user);

        /**
         * @var string $expire
         */
        $expire = config('playground-auth.token.expires');

        // $token = PersonalAccessToken::findToken($hashedTooken);

        $tokens[$name] = $user->createToken(
            $name,
            $privileges
        )->plainTextToken;

        return $tokens;
    }

    /**
     * Authenticated the user.
     *
     * @route POST /login
     */
    public function store(LoginRequest $request): JsonResponse|RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $payload = [
            'message' => __('authenticated'),
            'tokens' => [],
        ];

        if (! empty(config('playground-auth.token.sanctum'))) {
            /**
             * @var Authenticatable&HasApiTokens $user
             */
            $user = $request->user();
            if ($user) {
                $payload['tokens'] = $this->issue($user);
                // $payload['tokens'] = app(Issuer::class)->sanctum($user);
            }
        }

        if (! empty(config('playground-login-blade.session'))) {
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
        $all = $request->has('all') || $request->has('everywhere');

        if (! empty(config('playground-auth.token.sanctum'))) {
            /**
             * @var Authenticatable&HasApiTokens $user
             */
            $user = $request->user();

            if ($user) {
                if ($all) {
                    $user->tokens()->delete();
                } else {
                    /**
                     * @var \Laravel\Sanctum\PersonalAccessToken $token
                     */
                    $token = $user->currentAccessToken();
                    if ($token) {
                        $token->delete();
                    }
                }
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

    /**
     * Return a CSRF token.
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
