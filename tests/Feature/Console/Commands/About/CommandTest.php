<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Tests\Feature\Playground\Login\Blade\Console\Commands\About;

use Illuminate\Testing\PendingCommand;
use Tests\Feature\Playground\Login\Blade\TestCase;

/**
 * \Tests\Feature\Playground\Login\Blade\Console\Commands\About
 */
class CommandTest extends TestCase
{
    public function test_command_about_displays_package_information_and_succeed_with_code_0(): void
    {
        /**
         * @var PendingCommand $result
         */
        $result = $this->artisan('about');
        $result->assertExitCode(0);
        $result->expectsOutputToContain('Playground: Login Blade');
    }
}
