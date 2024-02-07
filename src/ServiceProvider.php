<?php
/**
 * Playground
 */
namespace Playground\Login\Blade;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use Illuminate\Support\Str;

/**
 * \Playground\Login\Blade\ServiceProvider
 */
class ServiceProvider extends AuthServiceProvider
{
    protected string $package = 'playground-login-blade';

    public const VERSION = '73.0.0';

    public function boot(): void
    {
        /**
         * @var array<string, mixed> $config
         */
        $config = config($this->package);

        if (! empty($config['load']) && is_array($config['load'])) {

            if (! empty($config['load']['routes']) && is_array($config['routes'])) {
                $this->routes($config['routes']);
            }

            if (! empty($config['load']['views'])) {
                $this->loadViewsFrom(
                    dirname(__DIR__).'/resources/views',
                    'playground-login'
                );
            }

            if ($this->app->runningInConsole()) {
                // Publish configuration
                $this->publishes([
                    sprintf('%1$s/config/%2$s.php', dirname(__DIR__), $this->package) => config_path(sprintf('%1$s.php', $this->package)),
                ], 'playground-config');

                // Publish Blade Views
                $this->publishes([
                    dirname(__DIR__).'/resources/views' => resource_path(Str::of('vendor/'.$this->package)->beforeLast('-blade')),
                ], 'playground-blade');
            }

            $this->about();
        }
    }

    public function about(): void
    {
        $config = config($this->package);
        $config = is_array($config) ? $config : [];

        $load = ! empty($config['load']) && is_array($config['load']) ? $config['load'] : [];

        $middleware = ! empty($config['middleware']) && is_array($config['middleware']) ? $config['middleware'] : [];

        $routes = ! empty($config['routes']) && is_array($config['routes']) ? $config['routes'] : [];

        $sitemap = ! empty($config['sitemap']) && is_array($config['sitemap']) ? $config['sitemap'] : [];

        $version = $this->version();

        AboutCommand::add('Playground: Login Blade', fn () => [
            '<fg=yellow;options=bold>Load</> Routes' => ! empty($load['routes']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=yellow;options=bold>Load</> Views' => ! empty($load['views']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',

            '<fg=yellow;options=bold>Middleware</> auth' => sprintf('%s', json_encode($middleware['auth'])),
            '<fg=yellow;options=bold>Middleware</> default' => sprintf('%s', json_encode($middleware['default'])),
            '<fg=yellow;options=bold>Middleware</> guest' => sprintf('%s', json_encode($middleware['guest'])),

            '<fg=blue;options=bold>View</> [layout]' => sprintf('[%s]', $config['layout']),
            '<fg=blue;options=bold>View</> [prefix]' => sprintf('[%s]', $config['view']),

            '<fg=magenta;options=bold>Sitemap</> Views' => ! empty($sitemap['enable']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=magenta;options=bold>Sitemap</> Guest' => ! empty($sitemap['guest']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=magenta;options=bold>Sitemap</> User' => ! empty($sitemap['user']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=magenta;options=bold>Sitemap</> [view]' => sprintf('[%s]', $sitemap['view']),

            '<fg=red;options=bold>Route</> confirm' => ! empty($routes['confirm']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=red;options=bold>Route</> forgot' => ! empty($routes['forgot']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=red;options=bold>Route</> login' => ! empty($routes['login']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=red;options=bold>Route</> logout' => ! empty($routes['logout']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=red;options=bold>Route</> register' => ! empty($routes['home']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=red;options=bold>Route</> reset' => ! empty($routes['reset']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=red;options=bold>Route</> token' => ! empty($routes['token']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=red;options=bold>Route</> verify' => ! empty($routes['verify']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',

            'Package' => $this->package,
            'Version' => $version,
        ]);
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            sprintf('%1$s/config/%2$s.php', dirname(__DIR__), $this->package),
            $this->package
        );
    }

    /**
     * @param array<string, bool> $routes
     */
    public function routes(array $routes): void
    {
        if (! empty($routes['confirm'])) {
            $this->loadRoutesFrom(dirname(__DIR__).'/routes/confirm.php');
        }
        if (! empty($routes['forgot'])) {
            $this->loadRoutesFrom(dirname(__DIR__).'/routes/forgot.php');
        }
        if (! empty($routes['login'])) {
            $this->loadRoutesFrom(dirname(__DIR__).'/routes/login.php');
        }
        if (! empty($routes['logout'])) {
            $this->loadRoutesFrom(dirname(__DIR__).'/routes/logout.php');
        }
        if (! empty($routes['register'])) {
            $this->loadRoutesFrom(dirname(__DIR__).'/routes/register.php');
        }
        if (! empty($routes['reset'])) {
            $this->loadRoutesFrom(dirname(__DIR__).'/routes/reset.php');
        }
        if (! empty($routes['token'])) {
            $this->loadRoutesFrom(dirname(__DIR__).'/routes/token.php');
        }
        if (! empty($routes['verify'])) {
            $this->loadRoutesFrom(dirname(__DIR__).'/routes/verify.php');
        }
    }

    public function version(): string
    {
        return static::VERSION;
    }
}
