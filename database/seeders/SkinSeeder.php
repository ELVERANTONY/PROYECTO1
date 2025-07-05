<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skin;
use Illuminate\Support\Facades\DB;

class SkinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Limpiar la tabla de skins para evitar duplicados
        DB::table('skins')->delete();
        DB::table('skin_user')->delete();

        $skins = [
            // === GUERRERO ===
            [
                'nombre' => 'Guerrero Básico',
                'descripcion' => 'Armadura básica para un guerrero que recién comienza su aventura.',
                'character_class' => 'guerrero',
                'precio' => 0,
                'video_url' => 'videos/guerrero-basico.mp4',
            ],
            [
                'nombre' => 'Guerrero Intermedio',
                'descripcion' => 'Una armadura forjada para el combate.',
                'character_class' => 'guerrero',
                'precio' => 200,
                'video_url' => 'videos/guerrero-intermedio.mp4',
            ],
            [
                'nombre' => 'Guerrero Avanzado',
                'descripcion' => 'Armadura legendaria de un campeón.',
                'character_class' => 'guerrero',
                'precio' => 450,
                'video_url' => 'videos/guerrero-avanzado.mp4',
            ],
            // === MAGO ===
            [
                'nombre' => 'Mago Básico',
                'descripcion' => 'Túnica de aprendiz para el estudio de las artes arcanas.',
                'character_class' => 'mago',
                'precio' => 0,
                'video_url' => 'videos/mago-basico.mp4',
            ],
            [
                'nombre' => 'Mago Intermedio',
                'descripcion' => 'Una túnica imbuida con poder arcano.',
                'character_class' => 'mago',
                'precio' => 250,
                'video_url' => 'videos/mago-intermedio.mp4',
            ],
            [
                'nombre' => 'Mago Avanzado',
                'descripcion' => 'Vestimentas de un archimago.',
                'character_class' => 'mago',
                'precio' => 500,
                'video_url' => 'videos/mago-avanzado.mp4',
            ],
            // === SANADOR ===
            [
                'nombre' => 'Sanador Básico',
                'descripcion' => 'Vestimentas humildes para un sanador que empieza su camino.',
                'character_class' => 'sanador',
                'precio' => 0,
                'video_url' => 'videos/sanador-basico.mp4',
            ],
            [
                'nombre' => 'Sanador Intermedio',
                'descripcion' => 'Armadura bendecida que irradia un aura de protección.',
                'character_class' => 'sanador',
                'precio' => 220,
                'video_url' => 'videos/sanador-intermedio.mp4',
            ],
            [
                'nombre' => 'Sanador Avanzado',
                'descripcion' => 'Indumentaria de un alto clérigo.',
                'character_class' => 'sanador',
                'precio' => 480,
                'video_url' => 'videos/sanador-avanzado.mp4',
            ]
        ];

        foreach ($skins as $skin) {
            Skin::create($skin);
        }

        $this->command->info('Skins table seeded successfully!');
    }
} 