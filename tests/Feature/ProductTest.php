<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        // Create the Admin role required by IsAdmin middleware
        $adminRole = \App\Models\Role::create(['description' => 'Admin', 'status' => 1]);
        $this->user = User::factory()->create(['role_id' => $adminRole->id]);
    }

    /** @test */
    public function un_usuario_autenticado_puede_ver_el_listado_de_productos()
    {
        $name = 'Producto de prueba con nombre extenso para pantallas pequeñas';
        $product = Product::factory()->create(['name' => $name]);

        $response = $this->actingAs($this->user)
            ->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertSee($name);
        $response->assertDontSee('Bienvenido de nuevo');
        $response->assertDontSee($this->user->user_name.'! 👋');
        $response->assertDontSee('Encuentra la mejor calidad y precios increíbles en un solo lugar.');
        $response->assertSee('Explora nuestros');
    }

    /** @test */
    public function la_portada_conserva_la_busqueda_y_no_muestra_filtros_ni_anuncios()
    {
        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee('id="catalog-search-hero"', false)
            ->assertSee('aria-label="Buscar productos"', false)
            ->assertDontSee('Precio mínimo')
            ->assertDontSee('Precio máximo')
            ->assertDontSee('Ordenar por')
            ->assertDontSee('catalog-filters')
            ->assertDontSee('Anuncios de plataformas')
            ->assertDontSee('Publicidad');
    }

    /** @test */
    public function la_portada_muestra_busqueda_y_carrusel_de_productos_destacados()
    {
        Product::factory()->create([
            'name' => 'Producto destacado uno',
            'image' => 'https://images.example.com/producto-uno.jpg',
            'price' => 300,
        ]);
        Product::factory()->create([
            'name' => 'Producto destacado dos',
            'image' => 'https://images.example.com/producto-dos.jpg',
            'price' => 500,
        ]);

        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee('id="catalog-search-hero"', false)
            ->assertSee('aria-label="Buscar productos"', false)
            ->assertSee('data-product-carousel', false)
            ->assertSee('Producto destacado uno')
            ->assertSee('Producto destacado dos')
            ->assertSee('data-carousel-next', false)
            ->assertSee('height: 350px', false)
            ->assertSee('object-fit: contain', false)
            ->assertSee('transform: none', false)
            ->assertSee('setInterval', false);
    }

    /** @test */
    public function el_catalogo_busca_por_nombre_o_descripcion_y_conserva_los_filtros()
    {
        $matching = Product::factory()->create([
            'name' => 'Teclado inalámbrico',
            'description' => 'Accesorio ergonómico',
            'price' => 80,
        ]);
        Product::factory()->create([
            'name' => 'Monitor',
            'description' => 'Teclado incluido como obsequio',
            'price' => 250,
        ]);

        $this->get(route('products.index', ['search' => 'ergonómico', 'max_price' => 100]))
            ->assertOk()
            ->assertSee($matching->name)
            ->assertDontSee('Monitor')
            ->assertSee('value="ergonómico"', false)
            ->assertSee('name="search"', false);
    }

    /** @test */
    public function el_catalogo_ordena_por_precio_y_pagina_los_resultados()
    {
        Product::factory()->create(['name' => 'Producto caro', 'price' => 200]);
        Product::factory()->create(['name' => 'Producto barato', 'price' => 20]);
        Product::factory()->count(11)->create(['price' => 500]);

        $response = $this->get(route('products.index', ['sort' => 'price_asc']));

        $response->assertOk()
            ->assertSee('Producto barato')
            ->assertSee('Producto caro')
            ->assertSee('page=2');
    }

    /** @test */
    public function la_imagen_del_producto_se_muestra_completa_al_abrir_su_tarjeta()
    {
        $product = Product::factory()->create([
            'image' => 'https://images.example.com/producto-completo.jpg',
        ]);

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('object-fit: contain', false)
            ->assertSee('producto-completo.jpg');
    }

    /** @test */
    public function el_formulario_de_productos_no_muestra_nombres_de_ejemplo_ni_horas_de_voluntariado()
    {
        $product = Product::factory()->create();

        $this->actingAs($this->user)
            ->get(route('products.create'))
            ->assertOk()
            ->assertDontSee('hours_required')
            ->assertDontSee('Laptop Dell XPS')
            ->assertDontSee('Horas de Voluntariado Requeridas');

        $this->get(route('products.edit', $product))
            ->assertOk()
            ->assertDontSee('hours_required')
            ->assertDontSee('Laptop Dell XPS')
            ->assertDontSee('Horas de Voluntariado Requeridas');
    }

    /** @test */
    public function un_administrador_puede_acceder_al_panel_y_a_las_secciones_de_administracion()
    {
        $name = 'Producto de prueba con nombre extenso para el panel administrativo';
        Product::factory()->create(['name' => $name]);

        $this->actingAs($this->user)
            ->get(route('products.dashboard'))
            ->assertOk()
            ->assertSee($name)
            ->assertSee('id="admin-nav-dropdown"', false)
            ->assertSee('id="btn-tienda-admin-nav"', false)
            ->assertSee('Panel de administración')
            ->assertSee('Gestión de usuarios')
            ->assertDontSee('Ver Voluntarios')
            ->assertDontSee(route('volunteers.admin'));

        $this->get(route('products.create'))
            ->assertOk()
            ->assertSee('action="'.route('products.store').'"', false);
        $this->get(route('admin.users'))->assertOk();
        $this->get(route('orders.admin'))->assertOk();
        $this->get(route('volunteers.admin'))->assertOk();
        $this->get(route('admin.users'))
            ->assertDontSee('Hacer Admin')
            ->assertDontSee('Resetear');
        $this->assertFalse(Route::has('admin.users.toggle'));
        $this->assertFalse(Route::has('admin.users.reset'));
    }

    /** @test */
    public function un_usuario_normal_no_puede_acceder_a_rutas_administrativas_ni_modificar_otros_usuarios()
    {
        $customerRole = \App\Models\Role::create(['description' => 'Customer', 'status' => 1]);
        $regularUser = User::factory()->create(['role_id' => $customerRole->id]);
        $product = Product::factory()->create();

        $this->actingAs($regularUser);

        $this->get(route('dashboard'))->assertForbidden();
        $this->get(route('products.dashboard'))->assertForbidden();
        $this->get(route('products.create'))->assertForbidden();
        $this->post(route('products.store'))->assertForbidden();
        $this->get(route('products.edit', $product))->assertForbidden();
        $this->put(route('products.update', $product))->assertForbidden();
        $this->delete(route('products.destroy', $product))->assertForbidden();
        $this->patch(route('products.toggleActive', $product))->assertForbidden();
        $this->get(route('orders.admin'))->assertForbidden();
        $this->delete(route('orders.destroy', 1))->assertForbidden();
        $this->get(route('admin.users'))->assertForbidden();
        $this->assertFalse(Route::has('admin.users.toggle'));
        $this->assertFalse(Route::has('admin.users.reset'));
        $this->get(route('volunteers.admin'))->assertForbidden();
        $this->patch(route('volunteers.updateStatus', 1))->assertForbidden();
        $this->delete(route('volunteers.destroy', 1))->assertForbidden();

        $this->get(route('products.index'))->assertOk();
        $this->get(route('volunteers.index'))->assertOk();
    }

    /** @test */
    public function un_administrador_puede_crear_un_producto_con_imagen()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('product.jpg');

        $productData = [
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 100.50,
            'stock' => 10,
            'image' => $file,
        ];

        $response = $this->actingAs($this->user)
            ->post(route('products.store'), $productData);

        $response->assertRedirect(route('products.dashboard'));
        $this->assertDatabaseHas('products', ['name' => 'Test Product', 'hours_required' => 4]);

        // Verify image storage
        $product = Product::where('name', 'Test Product')->first();
        Storage::disk('public')->assertExists($product->image);
    }

    /** @test */
    public function un_administrador_puede_guardar_y_actualizar_la_imagen_de_un_producto_con_una_url()
    {
        $imageUrl = 'https://images.example.com/producto.jpg';
        $productData = [
            'name' => 'Producto con imagen URL',
            'description' => 'Descripción de prueba',
            'price' => 100.50,
            'stock' => 10,
            'image_url' => $imageUrl,
        ];

        $this->actingAs($this->user)
            ->post(route('products.store'), $productData)
            ->assertRedirect(route('products.dashboard'));

        $product = Product::where('name', 'Producto con imagen URL')->firstOrFail();
        $this->assertSame($imageUrl, $product->image);

        $updatedImageUrl = 'https://cdn.example.com/producto-actualizado.webp';
        $this->put(route('products.update', $product), [
            ...$productData,
            'image_url' => $updatedImageUrl,
        ])->assertRedirect(route('products.dashboard'));

        $this->assertSame($updatedImageUrl, $product->fresh()->image);
    }

    /** @test */
    public function la_creacion_de_un_producto_requiere_datos_validos()
    {
        $response = $this->actingAs($this->user)
            ->post(route('products.store'), [
                'name' => '',
                'price' => -10, // Invalid price
            ]);

        $response->assertSessionHasErrors(['name', 'price']);
    }
}
