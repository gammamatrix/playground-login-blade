<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Tests\Feature\Playground\Login\Blade;

/**
 * \Tests\Feature\Playground\Login\Blade\PackageProviders
 */
trait PackageProviders
{
    protected function getPackageProviders($app): array
    {
        return [
            \Playground\ServiceProvider::class,
            \Playground\Test\ServiceProvider::class,
            \Playground\Auth\ServiceProvider::class,
            \Playground\Blade\ServiceProvider::class,
            \Playground\Login\Blade\ServiceProvider::class,
        ];
    }
}
