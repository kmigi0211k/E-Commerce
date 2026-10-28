<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function un_usuario_pueden_procesar_un_pedido_y_el_stock_se_descuenta()
    {
        $product = Product::factory()->create([
            'price' => 100,
            'stock' => 10,
        ]);

        // Mock cart in session
        $cart = [
            $product->id => [
                'name' => 'Nombre alterado en la sesión',
                'quantity' => 2,
                'price' => 0.01,
                'image' => $product->image,
            ],
        ];

        $this->actingAs($this->user)
            ->withSession(['cart' => $cart])
            ->get(route('orders.checkout'))
            ->assertOk()
            ->assertSee('Finaliza tu pedido')
            ->assertSee('Información de envío')
            ->assertSee('método de pago')
            ->assertSee('Bancolombia')
            ->assertSee('Mastercard')
            ->assertSee('Daviplata')
            ->assertDontSee('El pedido se registra sin cobro')
            ->assertDontSee('esta página no procesa tarjetas ni pagos en línea')
            ->assertSee('payment-choice-brand bancolombia', false)
            ->assertSee('payment-choice-brand mastercard', false)
            ->assertSee('payment-choice-brand daviplata', false)
            ->assertSee('align-items: stretch', false)
            ->assertSee('margin-top: auto', false)
            ->assertSee('Confirmar pedido')
            ->assertSee('data-step-indicator="1"', false)
            ->assertSee('data-step-indicator="2"', false)
            ->assertSee('data-checkout-next', false)
            ->assertSee('data-checkout-back', false)
            ->assertSee('name="payment_method"', false);
        $checkoutToken = session('checkout_token');

        $response = $this->post(route('orders.process'), [
            'checkout_token' => $checkoutToken,
            'payment_method' => 'mastercard',
        ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
            'total' => 200,
            'status' => 'pending_payment',
            'payment_method' => 'mastercard',
        ]);
        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 100,
        ]);

        // Check stock deduction
        $this->assertEquals(8, $product->fresh()->stock);

        // Check cart is empty
        $response->assertSessionMissing('cart');
    }

    /** @test */
    public function un_pedido_no_se_puede_procesar_si_el_stock_es_insuficiente()
    {
        $product = Product::factory()->create([
            'stock' => 2,
        ]);

        $cart = [
            $product->id => [
                'name' => $product->name,
                'quantity' => 2,
                'price' => $product->price,
                'image' => $product->image,
            ],
        ];

        $this->actingAs($this->user)
            ->withSession(['cart' => $cart])
            ->get(route('orders.checkout'))
            ->assertOk();
        $checkoutToken = session('checkout_token');
        $product->update(['stock' => 1]);

        $response = $this->post(route('orders.process'), [
            'checkout_token' => $checkoutToken,
            'payment_method' => 'cash',
        ]);
        $response->assertSessionHas('error');
        $this->assertEquals(1, $product->fresh()->stock); // Stock remains the same
        $this->assertDatabaseCount('orders', 0);
    }

    /** @test */
    public function un_pedido_rechaza_cantidades_invalidas_sin_modificar_el_inventario()
    {
        $product = Product::factory()->create(['stock' => 10]);
        $cart = [
            $product->id => [
                'quantity' => 0,
                'price' => $product->price,
            ],
        ];

        $this->actingAs($this->user)
            ->withSession(['cart' => $cart])
            ->post(route('orders.process'))
            ->assertSessionHas('error');

        $this->assertSame(10, $product->fresh()->stock);
        $this->assertDatabaseCount('orders', 0);
    }

    /** @test */
    public function un_cliente_no_puede_consultar_pedidos_de_otra_cuenta()
    {
        $otherUser = User::factory()->create();
        $order = Order::create([
            'user_id' => $otherUser->id,
            'total' => 80,
            'status' => 'completed',
        ]);

        $this->actingAs($this->user)
            ->get(route('orders.success', $order))
            ->assertForbidden();

        $this->get(route('orders.index'))
            ->assertOk()
            ->assertDontSee('PEDIDO #'.str_pad($order->id, 6, '0', STR_PAD_LEFT));
    }

    /** @test */
    public function repetir_la_confirmacion_no_crea_un_pedido_ni_descuenta_inventario_dos_veces()
    {
        $product = Product::factory()->create(['stock' => 5, 'price' => 40]);
        $cart = [
            $product->id => [
                'quantity' => 2,
                'price' => 40,
            ],
        ];

        $this->actingAs($this->user)
            ->withSession(['cart' => $cart])
            ->get(route('orders.checkout'))
            ->assertOk();

        $checkoutToken = session('checkout_token');
        $checkoutFingerprint = session('checkout_fingerprint');
        $this->assertNotEmpty($checkoutToken);
        $this->assertNotEmpty($checkoutFingerprint);

        $this->post(route('orders.process'), [
            'checkout_token' => $checkoutToken,
            'payment_method' => 'cash',
        ])
            ->assertRedirect();

        $order = Order::firstOrFail();
        $this->withSession([
            'cart' => $cart,
            'checkout_token' => $checkoutToken,
            'checkout_fingerprint' => $checkoutFingerprint,
        ])->post(route('orders.process'), [
            'checkout_token' => $checkoutToken,
            'payment_method' => 'cash',
        ])
            ->assertRedirect(route('orders.success', $order));

        $this->assertDatabaseCount('orders', 1);
        $this->assertSame($checkoutToken, $order->checkout_token);
        $this->assertSame('cash', $order->payment_method);
        $this->assertSame(3, $product->fresh()->stock);
    }

    /** @test */
    public function un_pedido_rechaza_metodos_de_pago_no_admitidos(): void
    {
        $product = Product::factory()->create(['stock' => 3]);
        $this->actingAs($this->user)
            ->withSession(['cart' => [$product->id => ['quantity' => 1]]])
            ->get(route('orders.checkout'))
            ->assertOk();

        $this->post(route('orders.process'), [
            'checkout_token' => session('checkout_token'),
            'payment_method' => 'bitcoin',
        ])->assertSessionHasErrors('payment_method');

        $this->assertDatabaseCount('orders', 0);
        $this->assertSame(3, $product->fresh()->stock);
    }

    /** @test */
    public function un_administrador_puede_confirmar_manualmente_el_pago_de_un_pedido_pendiente(): void
    {
        $adminRole = \App\Models\Role::create(['description' => 'Admin', 'status' => 1]);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);
        $order = Order::create([
            'user_id' => $this->user->id,
            'total' => 100,
            'status' => 'pending_payment',
            'payment_method' => 'bancolombia',
        ]);

        $this->actingAs($admin)
            ->patch(route('orders.confirmPayment', $order))
            ->assertRedirect(route('orders.admin'))
            ->assertSessionHas('success');

        $this->assertSame('completed', $order->fresh()->status);
    }
}
