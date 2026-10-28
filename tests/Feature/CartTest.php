<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_usuario_puede_agregar_actualizar_y_quitar_productos_del_carrito(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 5, 'price' => 120]);

        $this->actingAs($user)
            ->post(route('cart.add', $product))
            ->assertSessionHas("cart.{$product->id}.quantity", 1);

        $this->patch(route('cart.update'), [
            'id' => $product->id,
            'quantity' => 2,
        ])->assertOk();
        $this->assertSame(2, session("cart.{$product->id}.quantity"));

        $this->delete(route('cart.remove'), ['id' => $product->id])
            ->assertRedirect(route('cart.index'));
        $this->assertSame([], session('cart'));
    }

    public function test_el_carrito_muestra_el_precio_actual_de_la_base_de_datos(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 120, 'stock' => 5]);

        $this->actingAs($user)
            ->withSession([
                'cart' => [
                    $product->id => [
                        'name' => 'Nombre falso',
                        'price' => 0.01,
                        'quantity' => 2,
                    ],
                ],
            ])
            ->get(route('cart.index'))
            ->assertOk()
            ->assertSee($product->name)
            ->assertSee('120 COP')
            ->assertSee('action="'.route('cart.remove').'"', false)
            ->assertSee('¿Eliminar producto?', false)
            ->assertSee('¿Vaciar carrito?', false)
            ->assertSee('action="'.route('cart.clear').'"', false)
            ->assertSee('name="_method" value="DELETE"', false)
            ->assertSee('Vaciar Carrito')
            ->assertDontSee('0,01');
    }

    public function test_el_carrito_rechaza_cantidades_mayores_al_inventario(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 2]);

        $this->actingAs($user)
            ->withSession(['cart' => [$product->id => ['quantity' => 1]]])
            ->patch(route('cart.update'), [
                'id' => $product->id,
                'quantity' => 3,
            ])
            ->assertUnprocessable();
    }

    public function test_un_usuario_puede_vaciar_el_carrito_y_se_invalidan_los_tokens_de_compra(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user)
            ->withSession([
                'cart' => [$product->id => ['quantity' => 1]],
                'checkout_token' => 'token-anterior',
                'checkout_fingerprint' => 'huella-anterior',
            ])
            ->delete(route('cart.clear'))
            ->assertRedirect(route('cart.index'))
            ->assertSessionMissing('cart')
            ->assertSessionMissing('checkout_token')
            ->assertSessionMissing('checkout_fingerprint');
    }
}
