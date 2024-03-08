<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Tests\Feature\Playground\Login\Blade\Http\Controllers\Sanctum;

/**
 * \Tests\Feature\Playground\Login\Blade\Http\Controllers\Sanctum\TestTrait
 */
trait TestTrait
{
    /**
     * Set up the environment.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.debug', false);

        $app['config']->set('auth.providers.users.model', '\\Playground\\Test\\Models\\UserWithSanctum');
        $app['config']->set('auth.testing.password', 'password');
        $app['config']->set('auth.testing.hashed', false);

        $app['config']->set('playground-auth.debug', false);
        $app['config']->set('playground-auth.sanctum', true);
        $app['config']->set('playground-auth.verify', 'sanctum');
        $app['config']->set('playground-auth.token.sanctum', true);

        $app['config']->set('playground-auth.hasPrivilege', false);
        $app['config']->set('playground-auth.userPrivileges', false);

        $app['config']->set('playground-auth.hasRole', false);
        $app['config']->set('playground-auth.userRole', false);
        $app['config']->set('playground-auth.userRoles', false);
    }
}
