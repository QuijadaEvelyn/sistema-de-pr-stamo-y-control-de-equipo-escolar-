<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Equipment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'encargado@stock.test'],
            [
                'name' => 'Encargado Principal',
                'password' => Hash::make('Password123!'),
                'role' => 'encargado',
            ]
        );

        User::updateOrCreate(
            ['email' => 'solicitante@stock.test'],
            [
                'name' => 'Solicitante Principal',
                'password' => Hash::make('Password123!'),
                'role' => 'solicitante',
            ]
        );

        for ($i = 1; $i <= 10; $i++) {
            User::updateOrCreate(
                ['email' => "usuario{$i}@stock.test"],
                [
                    'name' => "Usuario de Prueba {$i}",
                    'password' => Hash::make('Password123!'),
                    'role' => 'solicitante',
                ]
            );
        }

        $categories = [
            [
                'name' => 'Laptops',
                'description' => 'Computadoras portátiles institucionales.'
            ],
            [
                'name' => 'Proyectores',
                'description' => 'Proyectores para clases y presentaciones.'
            ],
            [
                'name' => 'Audio',
                'description' => 'Micrófonos, bocinas y equipos de sonido.'
            ],
            [
                'name' => 'Accesorios',
                'description' => 'Cables, adaptadores y periféricos.'
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }

        $equipment = [
            [
                'category' => 'Laptops',
                'name' => 'Laptop Dell Latitude',
                'code' => 'LAP-001',
                'description' => 'Laptop para actividades académicas.',
                'quantity' => 10,
            ],
            [
                'category' => 'Laptops',
                'name' => 'Laptop HP ProBook',
                'code' => 'LAP-002',
                'description' => 'Computadora portátil para docentes.',
                'quantity' => 8,
            ],
            [
                'category' => 'Proyectores',
                'name' => 'Proyector Epson',
                'code' => 'PRO-001',
                'description' => 'Proyector para aulas.',
                'quantity' => 5,
            ],
            [
                'category' => 'Proyectores',
                'name' => 'Proyector BenQ',
                'code' => 'PRO-002',
                'description' => 'Proyector multimedia.',
                'quantity' => 4,
            ],
            [
                'category' => 'Audio',
                'name' => 'Micrófono inalámbrico',
                'code' => 'AUD-001',
                'description' => 'Micrófono para exposiciones.',
                'quantity' => 12,
            ],
            [
                'category' => 'Audio',
                'name' => 'Bocina portátil',
                'code' => 'AUD-002',
                'description' => 'Bocina para eventos académicos.',
                'quantity' => 6,
            ],
            [
                'category' => 'Accesorios',
                'name' => 'Adaptador HDMI',
                'code' => 'ACC-001',
                'description' => 'Adaptador de video.',
                'quantity' => 20,
            ],
            [
                'category' => 'Accesorios',
                'name' => 'Cable HDMI',
                'code' => 'ACC-002',
                'description' => 'Cable para conexión de proyectores.',
                'quantity' => 15,
            ],
        ];

        foreach ($equipment as $item) {
            $category = Category::where(
                'name',
                $item['category']
            )->firstOrFail();

            Equipment::firstOrCreate(
                ['code' => $item['code']],
                [
                    'category_id' => $category->id,
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'total_quantity' => $item['quantity'],
                    'available_quantity' => $item['quantity'],
                    'status' => 'activo',
                ]
            );
        }
    }
}
