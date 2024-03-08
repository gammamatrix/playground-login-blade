<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Tests\Feature\Playground\Login\Blade\Http\Controllers\Playground;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Playground\Test\Models\AppPlaygroundUser as User;
use Tests\Feature\Playground\Login\Blade\TestCase;

/**
 * \Tests\Feature\Playground\Login\Blade\Http\Controllers\Playground\PasswordResetRouteTest
 */
class PasswordResetRouteTest extends TestCase
{
    use TestTrait;

    protected bool $load_migrations_playground = true;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        Notification::fake();

        /**
         * @var User $user
         */
        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->getAttributeValue('email')]);

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        Notification::fake();

        /**
         * @var User $user
         */
        $user = User::factory()->create();

        $response = $this->post('/forgot-password', ['email' => $user->getAttributeValue('email')]);
        // $response->dump();
        $response->assertRedirect();
        Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
            $response = $this->get('/reset-password/'.$notification->token);

            // $response->dump();
            $response->assertStatus(200);

            return true;
        });
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        Notification::fake();

        /**
         * @var User $user
         */
        $user = User::factory()->create();

        $response = $this->post('/forgot-password', ['email' => $user->getAttributeValue('email')]);
        $response->assertRedirect();

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $response = $this->post('/reset-password', [
                'token' => $notification->token,
                'email' => $user->getAttributeValue('email'),
                'password' => config('auth.testing.password'),
                'password_confirmation' => config('auth.testing.password'),
            ]);

            $response->assertSessionHasNoErrors();

            return true;
        });
    }

    public function test_password_reset_with_invalid_token(): void
    {
        Notification::fake();

        /**
         * @var User $user
         */
        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->getAttributeValue('email')]);

        Notification::assertNotSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $response = $this->post('/reset-password', [
                'token' => 'not the valid token',
                'email' => $user->getAttributeValue('email'),
                'password' => config('auth.testing.password'),
                'password_confirmation' => config('auth.testing.password'),
            ]);

            $response->assertSessionHasErrors();

            return false;
        });
    }

    public function test_password_reset_with_invalid_user(): void
    {
        Notification::fake();

        /**
         * @var User $user
         */
        $user = User::factory()->make();

        $this->post('/forgot-password', ['email' => $user->getAttributeValue('email')]);

        Notification::assertNotSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $response = $this->post('/reset-password', [
                'token' => 'not the valid token',
                'email' => $user->getAttributeValue('email'),
                'password' => config('auth.testing.password'),
                'password_confirmation' => config('auth.testing.password'),
            ]);

            $response->assertSessionHasErrors();

            return false;
        });
    }
}
