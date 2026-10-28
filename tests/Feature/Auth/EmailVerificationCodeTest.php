<?php

namespace Tests\Feature\Auth;

use App\Mail\VerificationCodeMail;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailVerificationCodeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['description' => 'Admin']);
        Role::create(['description' => 'Customer']);
    }

    public function test_registration_sends_an_email_code_without_exposing_it_in_the_session_or_page(): void
    {
        Mail::fake();

        $this->post('/register', $this->registrationData())
            ->assertRedirect(route('verification.code.notice'))
            ->assertSessionMissing('demo_verification_code');

        $user = User::with('person')->firstOrFail();

        $this->assertFalse($user->status);
        $this->assertNotNull($user->verification_code);
        $this->assertSame(6, strlen($user->verification_code));
        $this->assertNotNull($user->verification_expires_at);

        Mail::assertSent(VerificationCodeMail::class, function (VerificationCodeMail $mail) use ($user) {
            return $mail->hasTo($user->person->email)
                && hash_equals($user->verification_code, $mail->code);
        });

        $this->get('/verify-code')->assertOk()->assertDontSee($user->verification_code);
    }

    public function test_a_valid_email_code_activates_the_account_and_cannot_be_reused(): void
    {
        Mail::fake();
        $this->post('/register', $this->registrationData());
        $user = User::firstOrFail();
        $code = $user->verification_code;

        $this->post('/verify-code', ['code' => $code])
            ->assertRedirect(route('products.index'));
        $this->assertAuthenticatedAs($user);

        $this->assertTrue($user->fresh()->status);
        $this->assertNotNull($user->fresh()->email_verified_at);
        $this->assertNull($user->fresh()->verification_code);
        $this->assertNull($user->fresh()->verification_expires_at);

        $this->post('/verify-code', ['code' => $code])
            ->assertSessionHas('error');
    }

    /**
     * @return array<string, string>
     */
    private function registrationData(): array
    {
        return [
            'names' => 'Persona de prueba',
            'email' => 'verificacion@example.com',
            'user_name' => 'usuario_prueba',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
        ];
    }
}
