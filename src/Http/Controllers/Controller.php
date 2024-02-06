<?php
/**
 * Playground
 */
namespace Playground\Login\Blade\Http\Controllers;

use Playground\Http\Controllers\Controller as BaseController;

/**
 * \Playground\Login\Blade\Http\Controllers\Controller
 */
abstract class Controller extends BaseController
{
    /**
     * Get the redirect path for authentication.
     */
    public function getRedirectUrl(): string
    {
        $path = config('playground-login-blade.redirect');

        return is_string($path) ? $path : '';
    }

    public function getPackageViewPathFromConfig(mixed $config, string $view = ''): string
    {
        $basePath = '';
        if (! empty($config)
            && is_array($config)
            && ! empty($config['view'])
            && is_string($config['view'])
        ) {
            $basePath = $config['view'];
        }

        return sprintf('%1$s%2$s', $basePath, $view);
    }
}