<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.bootstrap_admin.username' => null,
            'services.bootstrap_admin.password' => null,
            'services.bootstrap_admin.email' => null,
            'services.bootstrap_admin.name' => null,
        ]);
    }

    public function test_el_seeder_no_crea_una_cuenta_administrativa_con_credenciales_predeterminadas(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('products', 10);
    }

    public function test_el_seeder_crea_un_administrador_solo_con_credenciales_configuradas(): void
    {
        config([
            'services.bootstrap_admin.username' => 'admin-seguro',
            'services.bootstrap_admin.password' => 'UnaClaveSegura-2026!',
            'services.bootstrap_admin.email' => 'admin@example.test',
            'services.bootstrap_admin.name' => 'Administrador',
        ]);

        $this->seed(DatabaseSeeder::class);

        $admin = User::where('user_name', 'admin-seguro')->firstOrFail();

        $this->assertTrue($admin->isAdmin());
        $this->assertTrue(Hash::check('UnaClaveSegura-2026!', $admin->password));
    }

    public function test_el_seeder_no_modifica_productos_existentes(): void
    {
        $product = \App\Models\Product::factory()->create([
            'id' => 1,
            'name' => 'Producto propio',
            'price' => 123,
            'stock' => 7,
        ]);

        $this->seed(DatabaseSeeder::class);

        $this->assertSame('Producto propio', $product->fresh()->name);
        $this->assertSame(123, $product->fresh()->price);
        $this->assertSame(7, $product->fresh()->stock);
    }

    public function test_el_seeder_rechaza_una_configuracion_parcial_o_debil_del_administrador(): void
    {
        config([
            'services.bootstrap_admin.username' => 'admin',
            'services.bootstrap_admin.password' => 'short',
            'services.bootstrap_admin.email' => 'admin@example.test',
            'services.bootstrap_admin.name' => 'Administrador',
        ]);

        $this->expectException(\RuntimeException::class);

        $this->seed(DatabaseSeeder::class);
    }
}
