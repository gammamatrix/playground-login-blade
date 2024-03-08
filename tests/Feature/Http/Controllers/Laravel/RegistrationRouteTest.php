<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Tests\Feature\Playground\Login\Blade\Http\Controllers\Laravel;

use Tests\Feature\Playground\Login\Blade\TestCase;

/**
 * \Tests\Feature\Playground\Login\Blade\Http\Controllers\Laravel\RegistrationRouteTest
 */
class RegistrationRouteTest extends TestCase
{
    use TestTrait;

    protected bool $load_migrations_laravel = true;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => $this->faker()->email,
            'password' => config('auth.testing.password'),
            'password_confirmation' => config('auth.testing.password'),
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/');
    }
}
