<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use App\Models\Clase;
use App\Models\Pregunta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class EstudianteClaseController extends Controller
{
    public function getEstado(Clase $clase)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Verificar que el estudiante esté inscrito en la clase
        if (!$user->clasesComoEstudiante->contains($clase->id)) {
            return response()->json([
                'error' => 'No estás inscrito en esta clase',
                'redirect' => route('estudiante.dashboard')
            ], 403);
        }

        // Refrescar el modelo para obtener el estado más reciente
        $clase->refresh();
        
        // Cargar la relación de usuarios para contar estudiantes
        $clase->loadCount('users');

        // Preparar la respuesta básica
        $response = [
            'estado' => $clase->estado,
            'estudiantes_count' => $clase->users_count,
            'updated_at' => now()->format('Y-m-d H:i:s'),
            'hora_actualizacion' => now()->diffForHumans()
        ];

        // Si la clase está activa, incluir información adicional
        if ($clase->estado === 'iniciada') {
            $estadoJuego = Cache::get('clase_' . $clase->id . '_estado', [
                'estudiante_seleccionado_id' => null,
                'pregunta_actual' => null,
            ]);
            
            $response['juego_estado'] = $estadoJuego;
            
            // Si el estudiante actual es el seleccionado, incluir la pregunta
            if (Auth::id() == ($estadoJuego['estudiante_seleccionado_id'] ?? null)) {
                $response['es_tu_turno'] = true;
                // Aquí podrías incluir la pregunta actual si es necesario
            }
        }

        return response()->json($response);
    }

    public function responder(Request $request, Clase $clase)
    {
        $request->validate([
            'question_id' => 'required|exists:preguntas,id',
            'alternative_id' => 'required|exists:alternativas,id',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $pregunta = Pregunta::with('alternativas')->find($request->question_id);
        $alternativaCorrecta = $pregunta->alternativas->where('es_correcta', true)->first();

        $esCorrecta = $request->alternative_id == $alternativaCorrecta->id;

        $xpGanada = 0;
        $oroGanado = 0;

        if ($esCorrecta) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $xpGanada = rand(10, 25);
            $oroGanado = rand(5, 15);
            $user->xp += $xpGanada;
            $user->gold += $oroGanado;
            $user->save();

            broadcast(new \App\Events\StatsUpdated($user->fresh()))->toOthers();
        }

        return response()->json([
            'correct' => $esCorrecta,
            'correct_alternative_id' => $alternativaCorrecta->id,
            'xp_ganada' => $xpGanada,
            'oro_ganado' => $oroGanado,
            'total_xp' => $user->fresh()->xp,
            'total_gold' => $user->fresh()->gold,
            'total_nivel' => $user->fresh()->nivel,
        ]);
    }
}
