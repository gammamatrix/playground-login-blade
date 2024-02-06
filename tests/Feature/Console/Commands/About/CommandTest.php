<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Tests\Feature\Playground\Login\Blade\Console\Commands\About;

use PHPUnit\Framework\Attributes\CoversClass;
use Playground\Test\ServiceProvider;
use Tests\Feature\Playground\Login\Blade\TestCase;

/**
 * \Tests\Feature\Playground\Login\Blade\Console\Commands\About
 */
#[CoversClass(ServiceProvider::class)]
class CommandTest extends TestCase
{
    public function test_command_about_displays_package_information_and_succeed_with_code_0(): void
    {
        /**
         * @var \Illuminate\Testing\PendingCommand $result
         */
        $result = $this->artisan('about');
        $result->assertExitCode(0);
        $result->expectsOutputToContain('Playground: Login Blade');
    }
}
