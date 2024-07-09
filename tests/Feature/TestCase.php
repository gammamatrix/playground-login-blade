<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Tests\Feature\Playground\Login\Blade;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Playground\Test\OrchestraTestCase;

/**
 * \Tests\Feature\Playground\Login\Blade\TestCase
 */
class TestCase extends OrchestraTestCase
{
    use DatabaseTransactions;
    use InteractsWithViews;
    use PackageProviders;

    protected bool $hasMigrations = true;

    protected bool $setUpUserForAdmin = false;

    protected bool $setUpUserForLaravel = false;

    protected bool $setUpUserForLaravelSanctum = false;

    protected bool $setUpUserForPlayground = false;

    protected bool $setUpUserForPlaygroundSanctum = false;

    protected bool $setUpUserForPolicy = false;

    protected bool $setUpUserForPrivileges = false;

    protected bool $setUpUserForRoles = false;
}
