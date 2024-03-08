<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Tests\Feature\Playground\Login\Blade;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Illuminate\Foundation\Testing\DatabaseTransactions;
// use Playground\Auth\ServiceProvider as PlaygroundAuthServiceProvider;
// use Playground\Blade\ServiceProvider as PlaygroundBladeServiceProvider;
// use Playground\Login\Blade\ServiceProvider;
// use Playground\ServiceProvider as PlaygroundServiceProvider;
use Illuminate\Support\Carbon;
use Playground\Test\OrchestraTestCase;

/**
 * \Tests\Feature\Playground\Login\Blade\TestCase
 */
class TestCase extends OrchestraTestCase
{
    use DatabaseTransactions;
    use InteractsWithViews;
    use TestTrait;

    protected bool $load_migrations_laravel = false;

    protected bool $load_migrations_playground = false;

    // protected function getPackageProviders($app)
    // {
    //     return [
    //         PlaygroundAuthServiceProvider::class,
    //         PlaygroundServiceProvider::class,
    //         PlaygroundBladeServiceProvider::class,
    //         ServiceProvider::class,
    //     ];
    // }

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::now());

        if (! empty(env('TEST_DB_MIGRATIONS'))) {
            if ($this->load_migrations_laravel) {
                $this->loadMigrationsFrom(dirname(dirname(__DIR__)).'/database/migrations-laravel');
            }
            if ($this->load_migrations_playground) {
                $this->loadMigrationsFrom(dirname(dirname(__DIR__)).'/database/migrations-playground');
            }
        }
    }

    /**
     * Set up the environment.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.debug', false);

        $app['config']->set('auth.providers.users.model', '\\Playground\\Test\\Models\\User');
        $app['config']->set('auth.testing.password', 'password');
        $app['config']->set('auth.testing.hashed', false);

        $app['config']->set('playground-auth.debug', false);
        $app['config']->set('playground-auth.sanctum', false);
        $app['config']->set('playground-auth.verify', 'user');

        $app['config']->set('playground-auth.hasPrivilege', false);
        $app['config']->set('playground-auth.userPrivileges', false);

        $app['config']->set('playground-auth.hasRole', false);
        $app['config']->set('playground-auth.userRole', false);
        $app['config']->set('playground-auth.userRoles', false);
    }
}
