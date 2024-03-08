<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Tests\Feature\Playground\Login\Blade\Http\Controllers\Sanctum;

use Playground\Test\Models\UserWithSanctum;
use Tests\Feature\Playground\Login\Blade\TestCase;

/**
 * \Tests\Feature\Playground\Login\Blade\Http\Controllers\Sanctum\LogoutRouteTest
 */
class LogoutRouteTest extends TestCase
{
    use TestTrait;

    protected bool $load_migrations_laravel = true;

    // /**
    //  * Set up the environment.
    //  *
    //  * @param  \Illuminate\Foundation\Application  $app
    //  */
    // protected function getEnvironmentSetUp($app)
    // {
    //     $app['config']->set('auth.providers.users.model', UserWithSanctum::class);
    //     $app['config']->set('auth.testing.password', 'password');
    //     $app['config']->set('auth.testing.hashed', false);
    //     $app['config']->set('playground-auth.token.sanctum', true);
    // }

    public function test_sanctum_users_can_authenticate_and_logout_and_delete_token(): void
    {
        /**
         * @var UserWithSanctum $user
         */
        $user = UserWithSanctum::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->getAttributeValue('email'),
            'password' => config('auth.testing.password'),
        ]);

        // $response->dump();

        // $response->dumpHeaders();

        // $response->dumpSession();

        $response->assertStatus(302);

        $this->assertAuthenticated();
        $response->assertRedirect('/');

        $response = $this->actingAs($user)->json('get', '/logout');
        $this->assertGuest();
    }

    public function test_sanctum_users_can_logout_without_token_using_json(): void
    {
        /**
         * @var UserWithSanctum $user
         */
        $user = UserWithSanctum::factory()->create();
        // dd([
        //     'playground' => config('playground'),
        //     'playground-login-blade' => config('playground-login-blade'),
        // ]);
        $response = $this->actingAs($user)->json('get', '/logout');

        // $response->dump();
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'message',
            'session_token',
        ]);

        $this->assertGuest();
    }

    public function test_sanctum_users_can_logout_all_tokens_without_token_using_json(): void
    {
        /**
         * @var UserWithSanctum $user
         */
        $user = UserWithSanctum::factory()->create();
        // dd([
        //     'playground' => config('playground'),
        //     'playground-login-blade' => config('playground-login-blade'),
        // ]);
        $response = $this->actingAs($user)->json('get', '/logout?all');

        // $response->dump();
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'message',
            'session_token',
        ]);

        $this->assertGuest();
    }

    public function test_sanctum_users_can_logout_everywhere_tokens_without_token(): void
    {
        /**
         * @var UserWithSanctum $user
         */
        $user = UserWithSanctum::factory()->create();
        // dd([
        //     'playground' => config('playground'),
        //     'playground-login-blade' => config('playground-login-blade'),
        // ]);
        $response = $this->actingAs($user)->get('/logout?everywhere=1');

        // $response->dump();
        $response->assertStatus(302);

        $this->assertGuest();
    }

    public function test_sanctum_users_can_authenticate_and_logout_and_delete_token_with_sessions(): void
    {
        config([
            'playground-auth.token.session' => true,
            'playground-login-blade.session' => true,
        ]);
        /**
         * @var UserWithSanctum $user
         */
        $user = UserWithSanctum::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->getAttributeValue('email'),
            'password' => config('auth.testing.password'),
        ]);

        // $response->dump();

        // $response->dumpHeaders();

        // $response->dumpSession();

        $response->assertStatus(302);

        $this->assertAuthenticated();
        $response->assertRedirect('/');

        $response = $this->actingAs($user)->json('get', '/logout');
        $this->assertGuest();
    }
}
