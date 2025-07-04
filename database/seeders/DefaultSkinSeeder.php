<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Skin;
use Illuminate\Support\Facades\DB;

class DefaultSkinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Obtener todos los estudiantes que no tienen skins en su inventario
        $students = User::where('role', 'estudiante')
                        ->whereDoesntHave('skins')
                        ->get();

        if ($students->isEmpty()) {
            $this->command->info('No students found needing a default skin.');
            return;
        }

        // Obtener los skins por defecto para cada clase
        $defaultSkins = Skin::whereIn('character_class', ['guerrero', 'mago', 'sanador'])
                            ->orderBy('character_class')
                            ->orderBy('id', 'asc')
                            ->get()
                            ->unique('character_class');

        $skinsMap = $defaultSkins->keyBy('character_class');

        if ($skinsMap->isEmpty()) {
            $this->command->error('No default skins found in the database. Please seed skins first.');
            return;
        }

        $this->command->info("Found {$students->count()} students to update.");

        foreach ($students as $student) {
            if (isset($skinsMap[$student->character_class])) {
                $defaultSkin = $skinsMap[$student->character_class];
                
                // Asignar skin al inventario y equiparla
                $student->skins()->attach($defaultSkin->id);
                $student->skin_equipada_id = $defaultSkin->id;
                $student->save();

                $this->command->info("Assigned skin '{$defaultSkin->nombre}' to student '{$student->name}'.");
            }
        }
        
        $this->command->info('Default skins assigned successfully!');
    }
} 