<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuarios de prueba
        User::create([
            'name' => 'Profesor Demo',
            
            'email' => 'profesor@demo.com',
            'password' => Hash::make('password123'),
            'role' => 'profesor',
            'avatar' => 'default-avatar.png',
            'puntos_experiencia' => 500,
            'nivel' => 5,
        ]);

        User::create([
            'name' => 'Estudiante Demo',
            
            'email' => 'estudiante@demo.com',
            'password' => Hash::make('password123'),
            'role' => 'estudiante',
            'avatar' => 'default-avatar.png',
            'puntos_experiencia' => 150,
            'nivel' => 2,
        ]);

        // Crear más usuarios de ejemplo
        User::create([
            'name' => 'María González',
            
            'email' => 'maria@ejemplo.com',
            'password' => Hash::make('password123'),
            'role' => 'estudiante',
            'avatar' => 'default-avatar.png',
            'puntos_experiencia' => 75,
            'nivel' => 1,
        ]);

        User::create([
            'name' => 'Carlos Rodríguez',
            
            'email' => 'carlos@ejemplo.com',
            'password' => Hash::make('password123'),
            'role' => 'profesor',
            'avatar' => 'default-avatar.png',
            'puntos_experiencia' => 800,
            'nivel' => 8,
        ]);
    }
} 