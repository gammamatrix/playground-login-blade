<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Tests\Feature\Playground\Login\Blade;

use Playground\ServiceProvider;

/**
 * \Tests\Feature\Playground\Login\Blade\PackageProviders
 */
trait PackageProviders
{
    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
            \Playground\Test\ServiceProvider::class,
            \Playground\Auth\ServiceProvider::class,
            \Playground\Blade\ServiceProvider::class,
            \Playground\Login\Blade\ServiceProvider::class,
        ];
    }
}
