<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.firebase.api_key' => 'test-api-key',
            'services.firebase.auth_domain' => 'example.test',
            'services.firebase.project_id' => 'productospro-test',
            'services.recaptcha.site_key' => 'test-site-key',
            'services.recaptcha.secret_key' => 'test-secret-key',
        ]);
    }

    public function test_la_pantalla_de_login_se_puede_visualizar(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200)
            ->assertSee('btn-sso-google')
            ->assertDontSee('microsoft');
    }

    public function test_el_login_puede_guardar_sesiones_en_la_base_de_datos(): void
    {
        config(['session.driver' => 'database']);

        $this->get('/login')->assertOk();

        $this->assertTrue(DB::table('sessions')->exists());
    }

    public function test_el_acceso_con_google_no_se_muestra_sin_configuracion_de_firebase(): void
    {
        config([
            'services.firebase.api_key' => null,
            'services.firebase.auth_domain' => null,
            'services.firebase.project_id' => null,
        ]);

        $this->get('/login')
            ->assertOk()
            ->assertDontSee('btn-sso-google');
    }

    public function test_la_pantalla_de_registro_ofrece_google_sin_microsoft(): void
    {
        $this->get('/register')
            ->assertOk()
            ->assertSee('btn-sso-google')
            ->assertSee('email-verification-hint')
            ->assertSee('password-hint')
            ->assertDontSee('password-rules-box')
            ->assertDontSee('security-notice')
            ->assertDontSee('microsoft');
    }

    public function test_los_usuarios_pueden_autenticarse_usando_la_pantalla_de_login(): void
    {
        Http::fake([
            'www.google.com/recaptcha/api/siteverify' => Http::response(['success' => true]),
        ]);

        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'user_name' => $user->user_name,
            'password' => 'password',
            'g-recaptcha-response' => 'test-token',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_los_usuarios_no_pueden_autenticarse_con_una_contrasena_invalida(): void
    {
        Http::fake([
            'www.google.com/recaptcha/api/siteverify' => Http::response(['success' => true]),
        ]);

        $user = User::factory()->create();

        $this->post('/login', [
            'user_name' => $user->user_name,
            'password' => 'wrong-password',
            'g-recaptcha-response' => 'test-token',
        ]);

        $this->assertGuest();
    }

    public function test_los_usuarios_inactivos_no_pueden_autenticarse(): void
    {
        Http::fake([
            'www.google.com/recaptcha/api/siteverify' => Http::response(['success' => true]),
        ]);
        $user = User::factory()->create([
            'password' => bcrypt('password'),
            'status' => false,
        ]);

        $this->post('/login', [
            'user_name' => $user->user_name,
            'password' => 'password',
            'g-recaptcha-response' => 'test-token',
        ])->assertSessionHasErrors('user_name');

        $this->assertGuest();
    }

    public function test_el_login_rechaza_un_recaptcha_invalido(): void
    {
        Http::fake([
            'www.google.com/recaptcha/api/siteverify' => Http::response(['success' => false], 200),
        ]);
        $user = User::factory()->create(['password' => bcrypt('password')]);

        $this->post('/login', [
            'user_name' => $user->user_name,
            'password' => 'password',
            'g-recaptcha-response' => 'invalid-token',
        ])->assertSessionHasErrors('g-recaptcha-response');

        $this->assertGuest();
    }

    public function test_el_login_falla_de_forma_segura_si_recaptcha_no_responde(): void
    {
        Http::fake(function () {
            throw new ConnectionException('Connection refused');
        });
        $user = User::factory()->create(['password' => bcrypt('password')]);

        $this->post('/login', [
            'user_name' => $user->user_name,
            'password' => 'password',
            'g-recaptcha-response' => 'test-token',
        ])->assertSessionHasErrors('g-recaptcha-response');

        $this->assertGuest();
    }

    public function test_el_login_rechaza_el_acceso_si_faltan_claves_de_recaptcha(): void
    {
        config([
            'services.recaptcha.site_key' => null,
            'services.recaptcha.secret_key' => null,
        ]);
        $user = User::factory()->create(['password' => bcrypt('password')]);

        $this->post('/login', [
            'user_name' => $user->user_name,
            'password' => 'password',
            'g-recaptcha-response' => 'test-token',
        ])->assertSessionHasErrors('g-recaptcha-response');

        $this->assertGuest();
    }

    public function test_los_usuarios_pueden_cerrar_sesion(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
