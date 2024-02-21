<?php
/**
 * Playground
 */
namespace Tests\Feature\Playground\Login\Blade\Http\Controllers;

use Playground\Test\Models\User;
use Tests\Feature\Playground\Login\Blade\TestCase;

/**
 * \Tests\Feature\Playground\Login\Blade\Http\Controllers\PasswordConfirmationRouteTest
 */
class PasswordConfirmationRouteTest extends TestCase
{
    public function test_confirm_password_screen_can_be_rendered(): void
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable
         */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/confirm-password');

        $response->assertStatus(200);
    }

    public function test_password_can_be_confirmed(): void
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable
         */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/confirm-password', [
            'password' => config('auth.testing.password'),
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    public function test_password_is_not_confirmed_with_invalid_password(): void
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable
         */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/confirm-password', [
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
    }
}
