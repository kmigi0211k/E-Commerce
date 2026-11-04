<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class InventoryDashboardAndAdminPasswordTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create(['description' => 'Admin', 'status' => 1]);
        $this->admin = User::factory()->create(['role_id' => $role->id]);
    }

    public function test_dashboard_displays_database_inventory_and_category_totals_and_charts(): void
    {
        $category = Category::create(['name' => 'Accesorios']);
        Product::factory()->create(['name' => 'Disponible', 'stock' => 12, 'category_id' => $category->id]);
        Product::factory()->create(['name' => 'Bajo inventario', 'stock' => 3]);
        Product::factory()->create(['name' => 'Agotado', 'stock' => 0]);

        $response = $this->actingAs($this->admin)->get(route('dashboard'));

        $response->assertOk()
            ->assertSee('Total productos')
            ->assertSee('Categorías')
            ->assertSee('Productos agotados')
            ->assertSee('Bajo inventario')
            ->assertSee('Movimientos registrados')
            ->assertSee('id="inventory-chart"', false)
            ->assertSee('id="movement-chart"', false)
            ->assertSee('3 productos')
            ->assertSee('1 productos');
    }

    public function test_administrator_can_manage_categories_without_deleting_associated_products(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.categories'))
            ->assertOk()
            ->assertSee('Nueva categoría');

        $this->post(route('admin.categories.store'), ['name' => 'Papelería'])
            ->assertRedirect(route('admin.categories'));

        $category = Category::where('name', 'Papelería')->firstOrFail();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $this->delete(route('admin.categories.destroy', $category))
            ->assertRedirect(route('admin.categories'));

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'category_id' => null]);
    }

    public function test_administrator_can_reset_another_users_password_and_the_user_must_change_it(): void
    {
        $target = User::factory()->create();

        $response = $this->actingAs($this->admin)
            ->post(route('admin.users.reset-password', $target));

        $response->assertRedirect(route('admin.users'))
            ->assertSessionHas('temporary_password');
        $temporaryPassword = session('temporary_password');
        $target->refresh();

        $this->assertIsString($temporaryPassword);
        $this->assertSame(32, strlen($temporaryPassword));
        $this->assertTrue(Hash::check($temporaryPassword, $target->password));
        $this->assertTrue($target->password_needs_reset);
        $this->assertNotSame($temporaryPassword, $target->password);

        $this->actingAs($target)
            ->get(route('products.index'))
            ->assertRedirect(route('profile.edit'));

        $this->get(route('profile.edit'))->assertOk();
        $this->put(route('password.update'), [
            'current_password' => $temporaryPassword,
            'password' => 'A-new-secure-password-2026',
            'password_confirmation' => 'A-new-secure-password-2026',
        ])->assertRedirect();

        $this->assertFalse($target->fresh()->password_needs_reset);
        $this->assertTrue(Hash::check('A-new-secure-password-2026', $target->fresh()->password));
    }

    public function test_non_administrator_cannot_reset_another_users_password(): void
    {
        $user = User::factory()->create();
        $target = User::factory()->create();
        $originalPassword = $target->password;

        $this->actingAs($user)
            ->post(route('admin.users.reset-password', $target))
            ->assertForbidden();

        $this->assertSame($originalPassword, $target->fresh()->password);
        $this->assertFalse($target->fresh()->password_needs_reset);
    }

    public function test_dashboard_movement_chart_uses_existing_purchase_and_order_item_records(): void
    {
        $product = Product::factory()->create();
        $order = Order::create([
            'user_id' => $this->admin->id,
            'total' => 25,
            'status' => 'pending_payment',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 4,
            'price' => 25,
        ]);
        Purchase::create([
            'article' => $product->name,
            'quantity' => 7,
            'unit_price' => 10,
            'total' => 70,
        ]);

        $response = $this->actingAs($this->admin)->get(route('dashboard'));

        $response->assertOk()
            ->assertSee('"entries":[0,0,0,0,0,7]', false)
            ->assertSee('"exits":[0,0,0,0,0,4]', false);
    }
}
