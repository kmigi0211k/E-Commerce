<?php

namespace Database\Seeders;

use App\Models\Person;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roleAdmin = Role::firstOrCreate(['description' => 'Admin']);
        Role::firstOrCreate(['description' => 'Customer']);

        $this->seedAdminIfConfigured($roleAdmin);

        $products = [
            [
                'name' => 'Laptop Dell XPS 13 Plus',
                'description' => 'Portátil ultradelgado con procesador Intel Core i7 de 13.ª generación, 16 GB de RAM, SSD NVMe de 512 GB y pantalla OLED 4K. Ideal para trabajo, estudio y creación de contenido.',
                'price' => 1500000,
                'stock' => 15,
                'hours_required' => 50,
                'creation_date' => '2026-01-10',
                'is_active' => true,
                'image' => 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Monitor Gamer LG UltraGear 27" 144Hz IPS',
                'description' => 'Monitor de 27 pulgadas con panel IPS Full HD, frecuencia de actualización de 144 Hz y tiempo de respuesta de 1 ms. Ofrece movimiento fluido y colores claros para jugar y trabajar.',
                'price' => 850000,
                'stock' => 10,
                'hours_required' => 60,
                'creation_date' => '2026-01-12',
                'is_active' => true,
                'image' => 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Teclado Mecánico RGB Red Switch',
                'description' => 'Teclado mecánico con interruptores rojos lineales, retroiluminación RGB configurable y conexión por cable. Diseñado para escritura cómoda y respuesta rápida en juegos.',
                'price' => 180000,
                'stock' => 30,
                'hours_required' => 15,
                'creation_date' => '2026-01-14',
                'is_active' => true,
                'image' => 'https://images.unsplash.com/photo-1600294037681-c80b4cb5b434?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Cargador Rápido HP USB-C 65W',
                'description' => 'Cargador compacto de 65 W con conexión USB-C para equipos compatibles. Incluye protección eléctrica y es práctico para cargar portátiles, tabletas y teléfonos.',
                'price' => 150000,
                'stock' => 20,
                'hours_required' => 8,
                'creation_date' => '2026-01-15',
                'is_active' => true,
                'image' => 'https://images.unsplash.com/photo-1583863788434-e58a36330cf0?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Tablet Samsung Galaxy Tab A8 64GB',
                'description' => 'Tableta con pantalla de 10,5 pulgadas, 64 GB de almacenamiento y 4 GB de memoria RAM. Adecuada para estudiar, navegar y disfrutar de contenido multimedia.',
                'price' => 620000,
                'stock' => 12,
                'hours_required' => 25,
                'creation_date' => '2026-01-16',
                'is_active' => true,
                'image' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Audífonos Inalámbricos Bluetooth',
                'description' => 'Audífonos inalámbricos con micrófono integrado, conexión Bluetooth y estuche de carga USB-C. Ideales para llamadas, música y uso diario.',
                'price' => 120000,
                'stock' => 25,
                'hours_required' => 10,
                'creation_date' => '2026-01-18',
                'is_active' => true,
                'image' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Consola PlayStation 5 Slim Digital Edition',
                'description' => 'Consola de videojuegos de formato compacto con almacenamiento SSD de alta velocidad y soporte para juegos digitales de PlayStation 5. Disfruta de cargas rápidas y gráficos de nueva generación.',
                'price' => 75000,
                'stock' => 40,
                'hours_required' => 12,
                'creation_date' => '2026-01-20',
                'is_active' => true,
                'image' => 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Control inalámbrico DualSense para PS5',
                'description' => 'Control inalámbrico compatible con PlayStation 5, con respuesta háptica y gatillos adaptativos en juegos compatibles. Incluye micrófono integrado y conexión USB-C para carga.',
                'price' => 95000,
                'stock' => 50,
                'hours_required' => 30,
                'creation_date' => '2026-01-22',
                'is_active' => true,
                'image' => 'https://gmedia.playstation.com/is/image/SIEPDC/dualsense-controller-product-thumbnail-01-en-14sep21?$facebook$',
            ],
            [
                'name' => 'Router Wi-Fi 6 AX3000',
                'description' => 'Router de doble banda con tecnología Wi-Fi 6 para conectar varios dispositivos y mejorar la cobertura de la red doméstica. Incluye puertos Ethernet para conexiones cableadas.',
                'price' => 450000,
                'stock' => 8,
                'hours_required' => 20,
                'creation_date' => '2026-01-25',
                'is_active' => true,
                'image' => 'https://images.unsplash.com/photo-1580481077194-4c48398e7275?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Cámara Web Full HD 1080p',
                'description' => 'Cámara web con resolución Full HD 1080p y micrófono integrado para videollamadas, clases virtuales y reuniones en línea. Se conecta por USB a equipos compatibles.',
                'price' => 75000,
                'stock' => 40,
                'hours_required' => 6,
                'creation_date' => '2026-01-28',
                'is_active' => true,
                'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=800&q=80',
            ],
        ];

        foreach ($products as $pData) {
            Product::firstOrCreate(['name' => $pData['name']], $pData);
        }
    }

    private function seedAdminIfConfigured(Role $roleAdmin): void
    {
        $admin = config('services.bootstrap_admin');
        $username = trim((string) ($admin['username'] ?? ''));
        $password = (string) ($admin['password'] ?? '');
        $email = strtolower(trim((string) ($admin['email'] ?? '')));
        $names = trim((string) ($admin['name'] ?? ''));

        if ($username === '' && $password === '' && $email === '' && $names === '') {
            return;
        }

        if ($username === '' || strlen($password) < 12 || ! filter_var($email, FILTER_VALIDATE_EMAIL) || $names === '') {
            throw new RuntimeException('Configura BOOTSTRAP_ADMIN_USERNAME, BOOTSTRAP_ADMIN_PASSWORD (12 caracteres mínimo), BOOTSTRAP_ADMIN_EMAIL y BOOTSTRAP_ADMIN_NAME para crear el administrador inicial.');
        }

        DB::transaction(function () use ($username, $password, $email, $names, $roleAdmin) {
            $person = Person::firstOrCreate(
                ['email' => $email],
                ['names' => $names]
            );

            $user = User::firstOrCreate(
                ['user_name' => $username],
                [
                    'password' => Hash::make($password),
                    'status' => true,
                    'person_id' => $person->id,
                    'role_id' => $roleAdmin->id,
                ]
            );

            if ($user->role_id !== $roleAdmin->id) {
                throw new RuntimeException('El nombre configurado para el administrador inicial ya pertenece a un usuario con otro rol.');
            }
        });
    }
}
