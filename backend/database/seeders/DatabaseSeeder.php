<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Roles y permisos
        $this->call([
            RolePermissionSeeder::class,
        ]);

        // Usuario admin
        $adminRole = \App\Models\Role::where('name', 'admin')->first();
        User::firstOrCreate(
            ['email' => 'kristofercanotaborda@gmail.com'],
            [
                'name' => 'Kristofer Cano',
                'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
                'role_id' => $adminRole->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Usuario de prueba
        $standardRole = \App\Models\Role::where('name', 'standard')->first();
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role_id' => $standardRole->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Usuario premium
        $premiumRole = \App\Models\Role::where('name', 'premium')->first();
        User::firstOrCreate(
            ['email' => 'premium@example.com'],
            [
                'name' => 'Premium User',
                'password' => \Illuminate\Support\Facades\Hash::make('premium123'),
                'role_id' => $premiumRole->id,
                'is_active' => true,
                'email_verified_at' => now(),
                'premium_expires_at' => now()->addMonths(12), // Premium por 12 meses
            ]
        );

        // Libros reales
        $this->call([
            LibroSeeder::class,
            ComicSeeder::class,
            MangaSeeder::class,
            UserDemographicsSeeder::class, // 15 usuarios de prueba con datos demográficos
            DashboardAnalyticsSeeder::class, // 30 usuarios + 500 activity logs + 10 pagos
            ReadingHistorySeeder::class, // Historial de lectura para todos los usuarios
        ]);

        // Generar archivos PDF después de los seeders
        \Illuminate\Support\Facades\Artisan::call('generate:pdfs');
    }
}
