<?php
/**
 * Playground
 */
namespace Tests\Feature\Playground\Login\Blade;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Playground\Auth\ServiceProvider as PlaygroundAuthServiceProvider;
use Playground\Blade\ServiceProvider as PlaygroundBladeServiceProvider;
use Playground\Login\Blade\ServiceProvider;
use Playground\ServiceProvider as PlaygroundServiceProvider;
use Playground\Test\OrchestraTestCase;

/**
 * \Tests\Feature\Playground\Login\Blade\TestCase
 */
class TestCase extends OrchestraTestCase
{
    use DatabaseTransactions;
    use InteractsWithViews;

    protected function getPackageProviders($app)
    {
        return [
            PlaygroundAuthServiceProvider::class,
            PlaygroundServiceProvider::class,
            PlaygroundBladeServiceProvider::class,
            ServiceProvider::class,
        ];
    }

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        if (! empty(env('TEST_DB_MIGRATIONS'))) {
            // $this->loadLaravelMigrations();
            $this->loadMigrationsFrom(dirname(dirname(__DIR__)).'/database/migrations-laravel');
        }
    }

    /**
     * Set up the environment.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function getEnvironmentSetUp($app)
    {
        // dd(__METHOD__);
        $app['config']->set('auth.providers.users.model', 'Playground\\Test\\Models\\User');
        $app['config']->set('playground.auth.verify', 'user');
        $app['config']->set('auth.testing.password', 'password');
        $app['config']->set('auth.testing.hashed', false);

        // $app['config']->set('playground-login-blade.redirect', true);
        // $app['config']->set('playground-login-blade.session', true);

        // $app['config']->set('playground-login-blade.token.roles', false);
        // $app['config']->set('playground-login-blade.token.privileges', false);
        // $app['config']->set('playground-login-blade.token.name', 'app-testing');
        // $app['config']->set('playground-login-blade.token.sanctum', false);

        // $app['config']->set('playground-login-blade.load.commands', true);
        // $app['config']->set('playground-login-blade.load.routes', true);
        // $app['config']->set('playground-login-blade.load.views', true);

        // $app['config']->set('playground-login-blade.routes.confirm', true);
        // $app['config']->set('playground-login-blade.routes.forgot', true);
        // $app['config']->set('playground-login-blade.routes.logout', true);
        // $app['config']->set('playground-login-blade.routes.login', true);
        // $app['config']->set('playground-login-blade.routes.register', true);
        // $app['config']->set('playground-login-blade.routes.reset', true);
        // $app['config']->set('playground-login-blade.routes.token', true);
        // $app['config']->set('playground-login-blade.routes.verify', true);

        // $app['config']->set('playground-login-blade.sitemap.enable', true);
        // $app['config']->set('playground-login-blade.sitemap.guest', true);
        // $app['config']->set('playground-login-blade.sitemap.user', true);

        // $app['config']->set('playground-login-blade.admins', []);
        // $app['config']->set('playground-login-blade.managers', []);
    }
}
