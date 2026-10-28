<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_pagina_de_perfil_se_muestra(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk()
            ->assertSee('Mi Perfil')
            ->assertSee('Mis Pedidos')
            ->assertSee('Mi Carrito')
            ->assertSee('Administrar perfil')
            ->assertSee('Ver mis pedidos')
            ->assertSee('Ir al carrito');
    }

    public function test_la_informacion_del_perfil_se_puede_actualizar(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'user_name' => 'testuser',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('testuser', $user->user_name);
    }

    // Skipping email verification test as email was removed from users table

    public function test_el_usuario_puede_eliminar_su_cuenta(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_el_perfil_no_permite_cambiar_el_correo_verificado_por_uno_no_validado(): void
    {
        $person = Person::create([
            'names' => 'Usuario de prueba',
            'email' => 'original@example.test',
        ]);
        $user = User::factory()->create(['person_id' => $person->id]);

        $this->actingAs($user)->patch(route('profile.info.update'), [
            'user_name' => $user->user_name,
            'email' => 'victim@example.test',
            'names' => 'Usuario de prueba',
            'last_name' => 'Apellido',
            'phone' => '',
            'address' => '',
        ])->assertRedirect(route('profile.edit'));

        $this->assertSame('original@example.test', $person->fresh()->email);
    }

    public function test_se_debe_proporcionar_la_contrasena_correcta_para_eliminar_la_cuenta(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }
}
