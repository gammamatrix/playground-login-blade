<?php
/**
 * Playground
 */
namespace Tests\Feature\Playground\Login\Blade\Http\Controllers;

use Playground\Test\Models\User;
use Tests\Feature\Playground\Login\Blade\TestCase;

/**
 * \Tests\Feature\Playground\Login\Blade\Http\Controllers\AuthenticationRouteTest
 */
class AuthenticationRouteTest extends TestCase
{
    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->getAttributeValue('email'),
            'password' => 'password',
        ]);
        $response->assertStatus(302);

        $this->assertAuthenticated();
        $response->assertRedirect('/');
    }

    public function test_users_cannot_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->getAttributeValue('email'),
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(302);

        $this->assertGuest();
    }

    public function test_users_can_logout_on_get_request(): void
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable
         */
        $user = User::factory()->create();
        // dd([
        //     'playground' => config('playground'),
        //     'playground-login-blade' => config('playground-login-blade'),
        // ]);
        $response = $this->actingAs($user)->get('/logout');

        $response->assertStatus(302);

        $this->assertGuest();
    }

    public function test_users_can_logout_on_post_request(): void
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable
         */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertStatus(302);

        $this->assertGuest();
    }

    public function test_guests_can_logout_on_get_request(): void
    {
        $response = $this->get('/logout');

        $response->assertStatus(302);

        $this->assertGuest();
    }

    public function tesst_guests_can_logout_on_post_request(): void
    {
        $response = $this->get('/logout');

        $response->assertStatus(302);

        $this->assertGuest();
    }

    public function test_csfr_token_request(): void
    {
        $response = $this->get('/token');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'meta' => [
                'token',
            ],
        ]);
    }

    public function test_login_repeat_under_rate_limit_and_clear(): void
    {
        $user = User::factory()->create();

        $limit = 2;

        for ($i = 0; $i < $limit; $i++) {
            $response = $this->post('/login', [
                'email' => $user->getAttributeValue('email'),
                'password' => 'wrong-password',
            ]);
            $response->assertStatus(302);
        }

        $response = $this->post('/login', [
            'email' => $user->getAttributeValue('email'),
            'password' => 'password',
        ]);

        $response->assertStatus(302);

        $this->assertAuthenticated();
        $response->assertRedirect('/');
    }

    public function test_login_repeat_and_hit_rate_limit(): void
    {
        $user = User::factory()->create();

        $limit = 6;

        for ($i = 0; $i < $limit; $i++) {
            $response = $this->post('/login', [
                'email' => $user->getAttributeValue('email'),
                'password' => 'wrong-password',
            ]);
            $response->assertStatus(302);
        }

        // 'Too many login attempts. Please try again in 59 seconds.'
        $response->assertSessionHasErrors([
            'email',
        ]);
        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();
    }
}