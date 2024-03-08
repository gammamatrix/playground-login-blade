<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Tests\Feature\Playground\Login\Blade\Http\Controllers\Playground;

use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Playground\Test\Models\AppPlaygroundUser as User;
use Tests\Feature\Playground\Login\Blade\TestCase;

/**
 * \Tests\Feature\Playground\Login\Blade\Http\Controllers\Playground\EmailVerificationRouteTest
 */
class EmailVerificationRouteTest extends TestCase
{
    use TestTrait;

    protected bool $load_migrations_playground = true;

    public function test_email_verification_screen_can_be_rendered(): void
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get('/verify-email');

        $response->assertStatus(200);
    }

    public function test_email_verification_screen_is_not_rendered_if_already_verified(): void
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/verify-email');

        $response->assertStatus(302);
    }

    public function test_json_send_email_verification_notification_as_guest(): void
    {
        Notification::fake();

        $response = $this->json('post', '/verify-email');
        $response->assertStatus(401);

        // $response->dump();

        $response->assertJsonStructure([
            'message',
        ]);

        $response->assertExactJson([
            'message' => 'Unauthenticated.',
        ]);

        Notification::assertNothingSent();
    }

    public function test_send_email_verification_notification_as_guest_and_redirect(): void
    {
        Notification::fake();

        $response = $this->post('/verify-email');
        $response->assertStatus(302);
        $response->assertredirect('/login');

        // $response->dump();

        Notification::assertNothingSent();
    }

    public function test_send_email_verification_notification_as_user(): void
    {
        Notification::fake();

        /**
         * @var User $user
         */
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->post('/verify-email');

        // $response->dump();

        $response->assertStatus(302);

        $response->assertSessionHas('status', 'verification-link-sent');

        // Notification::assertNothingSent();
        Notification::assertSentTo(
            [$user],
            VerifyEmail::class
        );
    }

    /**
     * EmailVerificationController::send().
     */
    public function test_send_email_verification_notification_when_already_verified(): void
    {
        Notification::fake();

        /**
         * @var User $user
         */
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->post('/verify-email');

        // $response->dump();

        $response->assertStatus(302);

        Notification::assertNothingSent();
    }

    public function test_email_can_be_verified(): void
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        Event::fake();

        /**
         * @var int $expire
         */
        $expire = config('auth.verification.expire', 60);

        /**
         * @var string $email
         */
        $email = $user->getAttributeValue('email');

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes($expire),
            [
                'id' => $user->getAttributeValue('id'),
                'hash' => sha1($email),
            ]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        Event::assertDispatched(Verified::class);

        $this->assertTrue($user->fresh()?->hasVerifiedEmail());
        $response->assertRedirect('/?verified=1');
    }

    public function test_email_is_not_verified_with_invalid_hash(): void
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        /**
         * @var int $expire
         */
        $expire = config('auth.verification.expire', 60);

        /**
         * @var string $email
         */
        $email = $user->getAttributeValue('email');

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes($expire),
            [
                'id' => $user->getAttributeValue('id'),
                'hash' => sha1($email.'make-this-invalid'),
            ]
        );

        $this->actingAs($user)->get($verificationUrl);

        $this->assertFalse($user->fresh()?->hasVerifiedEmail());
    }

    public function test_email_is_not_verified_with_invalid_user_id(): void
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        /**
         * @var int $expire
         */
        $expire = config('auth.verification.expire', 60);

        /**
         * @var string $email
         */
        $email = $user->getAttributeValue('email');

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes($expire),
            [
                'id' => $user->getAttributeValue('id').'make-this-invalid',
                'hash' => sha1($email),
            ]
        );

        $this->actingAs($user)->get($verificationUrl);

        $this->assertFalse($user->fresh()?->hasVerifiedEmail());
    }

    public function test_email_verified_with_already_verified(): void
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        Event::fake();

        /**
         * @var int $expire
         */
        $expire = config('auth.verification.expire', 60);

        /**
         * @var string $email
         */
        $email = $user->getAttributeValue('email');

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes($expire),
            [
                'id' => $user->getAttributeValue('id'),
                'hash' => sha1($email),
            ]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        Event::assertNotDispatched(Verified::class);
        $this->assertTrue($user->fresh()?->hasVerifiedEmail());
        $response->assertRedirect('/?verified=1');
    }
}
