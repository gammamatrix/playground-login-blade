<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Tests\Feature\Playground\Login\Blade;

use Playground\Auth\ServiceProvider as PlaygroundAuthServiceProvider;
use Playground\Blade\ServiceProvider as PlaygroundBladeServiceProvider;
use Playground\Login\Blade\ServiceProvider as PlaygroundLoginBladeServiceProvider;
use Playground\ServiceProvider as PlaygroundServiceProvider;

// use Playground\Login\Blade\ServiceProvider;
// use Playground\Site\Blade\ServiceProvider as PlaygroundSiteBladeServiceProvider;

/**
 * \Tests\Feature\Playground\Site\Blade\TestTrait
 */
trait TestTrait
{
    protected function getPackageProviders($app)
    {
        return [
            PlaygroundAuthServiceProvider::class,
            PlaygroundBladeServiceProvider::class,
            PlaygroundLoginBladeServiceProvider::class,
            // PlaygroundSiteBladeServiceProvider::class,
            PlaygroundServiceProvider::class,
            // ServiceProvider::class,
        ];
    }
}
