<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Clase;
use App\Models\Pregunta;
use App\Models\User;
use App\Models\HistorialLlamadas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Events\EstudianteSeleccionado;

class ProfesorController extends Controller
{
    // ... otros métodos ...

    /**
     * Mostrar la vista de la ruleta
     */
    public function mostrarRuleta(Clase $clase)
    {
        // Verificar que el profesor sea dueño de la clase
        if ($clase->profesor_id !== Auth::id()) {
            abort(403, 'No tienes permiso para acceder a esta clase.');
        }
        
        // Si la clase no está iniciada, iniciarla automáticamente y generar código
        if ($clase->estado !== 'iniciada') {
            // Verificar si ya hay una clase activa
            $claseActiva = Clase::where('profesor_id', Auth::id())
                ->where('id', '!=', $clase->id)
                ->where('estado', 'iniciada')
                ->first();

            if ($claseActiva) {
                return redirect()->route('profesor.dashboard')
                    ->with('error', 'Ya tienes una clase activa. Finaliza la clase actual antes de iniciar una nueva.');
            }

            // Generar código único y actualizar estado
            do {
                $codigo = strtoupper(Str::random(6));
            } while (Clase::where('codigo', $codigo)->where('estado', 'iniciada')->exists());

            $clase->update([
                'codigo' => $codigo,
                'estado' => 'iniciada',
                'inicio_sesion' => now(),
                'fin_sesion' => null
            ]);
            
            // Limpiar estudiantes de sesiones anteriores
            $clase->users()->detach();
        }
        
        // Obtener estudiantes de la clase con la tabla users explícitamente referenciada
        $estudiantes = DB::table('users')
            ->join('clase_user', 'users.id', '=', 'clase_user.user_id')
            ->where('clase_user.clase_id', $clase->id)
            ->where('users.role', 'estudiante')
            ->select('users.id', 'users.name', 'users.email')
            ->get();
            
        return view('profesor.ruleta', compact('clase', 'estudiantes'));
    }

    // ... resto de los métodos ...
}
