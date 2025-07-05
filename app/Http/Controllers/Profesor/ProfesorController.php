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
use App\Events\StatsUpdated;
use Illuminate\Support\Facades\Log;
use App\Models\Alternativa;

class ProfesorController extends Controller
{
    /**
     * Mostrar el dashboard del profesor
     */
    public function dashboard()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // "Mis Clases Creadas" son las plantillas: todas las clases del profesor.
        $clasesCreadas = Clase::where('profesor_id', $user->id)->withCount('users')->get();

        // "Clases Finalizadas" es el historial de sesiones terminadas.
        $clasesFinalizadas = Clase::where('profesor_id', $user->id)
            ->where('estado', 'finalizada')
            ->withCount('users')
            ->orderBy('updated_at', 'desc')
            ->get();

        // Clases activas para el contador (estado 'iniciada')
        $clasesActivas = $clasesCreadas->where('estado', 'iniciada');

        // Estudiantes activos para el contador
        $estudiantesActivos = User::whereHas('clasesComoEstudiante', function ($query) {
            $query->where('estado', 'iniciada');
        })->count();

        return view('profesor.dashboard', compact('clasesCreadas', 'clasesFinalizadas', 'clasesActivas', 'estudiantesActivos'));
    }

    public function getDashboardData()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Obtener las clases con el conteo de estudiantes
        $clasesCreadas = Clase::where('profesor_id', $user->id)
            ->withCount('users')
            ->get()
            ->map(function ($clase) {
                return [
                    'id' => $clase->id,
                    'nombre' => $clase->nombre,
                    'estado' => $clase->estado,
                    'codigo' => $clase->codigo,
                    'users_count' => $clase->users_count,
                    'fecha_creacion' => $clase->created_at->format('d/m/Y H:i'),
                    'url_iniciar' => route('profesor.clase.iniciar', $clase),
                    'url_sesion' => route('profesor.clase.sesion', $clase),
                    'url_ruleta' => route('clase.ruleta', $clase),
                    'es_reciente' => $clase->created_at->gt(now()->subHours(24)),
                ];
            });

        // Contar clases activas
        $clasesActivasCount = $clasesCreadas->where('estado', 'iniciada')->count();
        
        // Contar estudiantes en clases activas
        $estudiantesActivosCount = User::whereHas('clasesComoEstudiante', function ($query) {
            $query->where('estado', 'iniciada');
        })->count();

        return response()->json([
            'status' => 'success',
            'clases_activas_count' => $clasesActivasCount,
            'estudiantes_activos_count' => $estudiantesActivosCount,
            'clases' => $clasesCreadas,
            'timestamp' => now()->toDateTimeString()
        ]);
    }

    /**
     * Mostrar los estudiantes del profesor
     */
    public function estudiantes()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $clasesIds = $user->clasesComoProfesor()->pluck('id');
        $estudiantes = User::whereHas('clasesComoEstudiante', function ($query) use ($clasesIds) {
            $query->whereIn('clase_user.clase_id', $clasesIds);
        })->where('role', 'estudiante')->with('clasesComoEstudiante')->get();
        
        $ranking = User::whereHas('clasesComoEstudiante', function ($query) use ($clasesIds) {
            $query->whereIn('clase_user.clase_id', $clasesIds);
        })
        ->where('role', 'estudiante')
        ->orderByDesc('xp')
        ->take(10)
        ->get();

        return view('profesor.estudiantes', compact('user', 'estudiantes', 'ranking'));
    }

    /**
     * Mostrar la página de gestión de clases y preguntas.
     */
    public function gestionClases()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $clases = $user->clasesComoProfesor()->withCount('preguntas')->get();
        return view('profesor.gestion-clases', compact('user', 'clases'));
    }

    /**
     * Mostrar la página de ajustes del profesor.
     */
    public function ajustes()
    {
        $user = Auth::user();
        return view('profesor.ajustes', compact('user'));
    }

    /**
     * Genera un nuevo código para una sesión de clase.
     */
    public function generarCodigo(Clase $clase)
    {
        if ($clase->profesor_id !== Auth::id()) {
            abort(403);
        }

        // Desvincular estudiantes de la sesión anterior para reutilizar la clase
        $clase->users()->detach();

        $nuevoCodigo = strtoupper(Str::random(6));
        while (Clase::where('codigo', $nuevoCodigo)->exists()) {
            $nuevoCodigo = strtoupper(Str::random(6));
        }

        $clase->codigo = $nuevoCodigo;
        $clase->save();

        return response()->json(['codigo' => $nuevoCodigo]);
    }
    
    /**
     * Marca una clase como finalizada.
     */
    public function finalizarClase(Clase $clase)
    {
        if ($clase->profesor_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No tienes permiso para finalizar esta clase.'
            ], 403);
        }

        if ($clase->estado !== 'iniciada') {
            return response()->json([
                'status' => 'error',
                'message' => 'La clase no está actualmente iniciada.'
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Actualizar el estado de la clase
            $clase->update([
                'estado' => 'finalizada',
                'codigo' => null,
                'fin_sesion' => now()
            ]);

            // Calcular duración de la sesión
            $duracion = $clase->inicio_sesion ? now()->diffInMinutes($clase->inicio_sesion) : 0;
            
            // Registrar estadísticas de la sesión (opcional)
            $clase->update([
                'duracion_minutos' => $duracion,
                'estudiantes_participantes' => $clase->users()->count()
            ]);

            // Limpiar caché
            Cache::forget('clase_estado_' . $clase->id);
            
            // Limpiar cualquier estado de juego activo
            Cache::forget('clase_' . $clase->id . '_estado');

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Clase finalizada correctamente',
                'redirect' => route('profesor.dashboard')
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al finalizar la clase: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Ocurrió un error al finalizar la clase. Por favor, inténtalo de nuevo.'
            ], 500);
        }
    }

    public function crearClase()
    {
        return view('profesor.crear-clase');
    }

    public function storeClase(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tema' => 'required|string|max:255',
            'preguntas' => 'required|array|min:1',
            'preguntas.*.texto' => 'required|string',
            'preguntas.*.alternativas' => 'required|array|min:2',
            'preguntas.*.alternativas.*.texto' => 'required|string',
            'preguntas.*.correcta' => 'required|numeric'
        ]);

        DB::transaction(function () use ($request) {
            $clase = Clase::create([
                'profesor_id' => Auth::id(),
                'nombre' => $request->nombre,
                'tema' => $request->tema,
                'codigo' => null, // El código se generará al iniciar una sesión
            ]);

            foreach ($request->preguntas as $preguntaData) {
                $pregunta = $clase->preguntas()->create([
                    'pregunta' => $preguntaData['texto']
                ]);

                foreach ($preguntaData['alternativas'] as $index => $alternativaData) {
                    $pregunta->alternativas()->create([
                        'texto' => $alternativaData['texto'],
                        'es_correcta' => $index == $preguntaData['correcta'],
                    ]);
                }
            }
        });

        return redirect()->route('profesor.dashboard')->with('success', '¡Clase creada con éxito!');
    }

    /**
     * Mostrar los detalles y preguntas de una clase específica.
     */
    public function verClase(Clase $clase)
    {
        // Asegurarse de que el profesor solo pueda ver sus propias clases
        if ($clase->profesor_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.');
        }

        $clase->load('preguntas.alternativas');
        return view('profesor.ver-clase', compact('clase'));
    }

    /**
     * Mostrar el formulario para editar una clase existente.
     */
    public function editarClase(Clase $clase)
    {
        // Asegurarse de que el profesor solo pueda editar sus propias clases
        if ($clase->profesor_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.');
        }

        $clase->load('preguntas.alternativas');
        return view('profesor.editar-clase', compact('clase'));
    }

    /**
     * Actualizar una clase existente en la base de datos.
     */
    public function actualizarClase(Request $request, Clase $clase)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tema' => 'required|string|max:255',
            'preguntas' => 'required|array|min:1',
            'preguntas.*.texto' => 'required|string',
            'preguntas.*.alternativas' => 'required|array|min:2',
            'preguntas.*.alternativas.*.texto' => 'required|string',
            'preguntas.*.correcta' => 'required|numeric'
        ]);

        DB::transaction(function () use ($request, $clase) {
            $clase->update([
                'nombre' => $request->nombre,
                'tema' => $request->tema,
            ]);

            // Eliminar preguntas viejas y sus alternativas
            $clase->preguntas()->delete(); // Esto eliminará en cascada las alternativas

            foreach ($request->preguntas as $preguntaData) {
                $pregunta = $clase->preguntas()->create([
                    'pregunta' => $preguntaData['texto']
                ]);

                foreach ($preguntaData['alternativas'] as $index => $alternativaData) {
                    $pregunta->alternativas()->create([
                        'texto' => $alternativaData['texto'],
                        'es_correcta' => ($index == $preguntaData['correcta'])
                    ]);
                }
            }
        });

        return redirect()->route('profesor.gestion-clases')->with('success', 'Clase actualizada exitosamente.');
    }

    public function destroy(Clase $clase)
    {
        // Asegurarse de que el profesor solo pueda borrar sus propias clases
        if ($clase->profesor_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.');
        }

        DB::transaction(function () use ($clase) {
            // Eliminar alternativas, preguntas y finalmente la clase.
            $clase->preguntas()->each(function ($pregunta) {
                $pregunta->alternativas()->delete();
            });
            $clase->preguntas()->delete();
            $clase->users()->detach(); // Desvincular estudiantes
            $clase->delete();
        });

        return redirect()->route('profesor.gestion-clases')->with('success', 'Clase eliminada correctamente.');
    }

    public function iniciarClase(Clase $clase)
    {
        if ($clase->profesor_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No tienes permiso para iniciar esta clase.'
            ], 403);
        }

        // Verificar si ya hay una clase activa
        $claseActiva = Clase::where('profesor_id', Auth::id())
            ->where('id', '!=', $clase->id)
            ->where('estado', 'iniciada')
            ->first();

        if ($claseActiva) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ya tienes una clase activa. Finaliza la clase actual antes de iniciar una nueva.'
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Generar un código único de 6 caracteres y en mayúsculas
            do {
                $codigo = strtoupper(Str::random(6));
            } while (Clase::where('codigo', $codigo)->where('estado', 'iniciada')->exists());

            // Actualizar la clase
            $clase->update([
                'codigo' => $codigo,
                'estado' => 'iniciada',
                'inicio_sesion' => now(),
                'fin_sesion' => null
            ]);
            
            // Limpiar estudiantes de sesiones anteriores
            $clase->users()->detach();
            
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Clase iniciada correctamente',
                'redirect' => route('profesor.clase.sesion', $clase)
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al iniciar la clase: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Ocurrió un error al iniciar la clase. Por favor, inténtalo de nuevo.'
            ], 500);
        }
    }

    public function sesionClase(Clase $clase)
    {
        if ($clase->profesor_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.');
        }

        // Asegurarse de que una clase iniciada siempre tenga un código.
        if ($clase->estado === 'iniciada' && empty($clase->codigo)) {
            $codigo = strtoupper(Str::random(6));
            // Asegurar que el código no esté en uso por otra clase activa
            while (Clase::where('codigo', $codigo)->where('estado', 'iniciada')->exists()) {
                $codigo = strtoupper(Str::random(6));
            }
            $clase->codigo = $codigo;
            $clase->save();
        }

        // Cargar la relación de usuarios (estudiantes) con su skin equipada
        $clase->load(['users.skinEquipada', 'preguntas.alternativas']);

        // Obtener el estado actual del juego desde el caché si existe
        $estadoJuego = Cache::get('clase_' . $clase->id . '_estado');

        return view('profesor.sesion-clase', compact('clase', 'estadoJuego'));
    }

    public function gestionarPreguntas()
    {
        // Implementación pendiente
    }

    public function importarEstudiantesExcel()
    {
        // Implementación pendiente
    }

    public function getEstudiantes($claseId)
    {
        $clase = Clase::findOrFail($claseId);
        
        // Verificar que el profesor sea dueño de la clase
        if ($clase->profesor_id !== Auth::id()) {
            abort(403, 'No tienes permiso para acceder a esta clase.');
        }
        
        $estudiantes = $clase->users()
            ->where('role', 'estudiante')
            ->select('id', 'name', 'email')
            ->get();

        return response()->json($estudiantes);
    }

    /**
     * Obtiene los estudiantes de una clase para la ruleta.
     */
    public function getEstudiantesForRuleta($claseId)
    {
        $clase = Clase::findOrFail($claseId);
        $estudiantes = $clase->users()
            ->where('role', 'estudiante')
            ->with('skinEquipada') // Carga eficiente
            ->select('users.id', 'users.name', 'users.xp', 'users.nivel', 'users.gold', 'users.skin_equipada_id')
            ->get()
            ->map(function($user) {
                $user->avatar_url = $user->skinEquipada ? asset($user->skinEquipada->video_url) : asset('videos/guerrero-basico.mp4'); // Usar video_url
                return $user;
            });

        return response()->json($estudiantes);
    }
    
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
        
        // Redirigir a la vista de sesión de clase
        return redirect()->route('profesor.clase.sesion', $clase);
    }
    
    /**
     * Seleccionar un estudiante aleatorio para la ruleta
     */
    public function seleccionarEstudiante(Clase $clase)
    {
        if ($clase->profesor_id !== Auth::id()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $estudiantes = $clase->users()->where('role', 'estudiante')->get();

        if ($estudiantes->isEmpty()) {
            return response()->json(['message' => 'No hay estudiantes en la clase.'], 404);
        }

        $estudianteGanador = $estudiantes->random();

        // Cargar las preguntas de la clase con sus alternativas
        $pregunta = $clase->preguntas()->with('alternativas')->inRandomOrder()->first();

        return response()->json([
            'estudiante' => $estudianteGanador,
            'pregunta' => $pregunta,
        ]);
    }

    public function evaluarRespuesta(Request $request, Clase $clase, User $user, Pregunta $pregunta)
    {
        if ($clase->profesor_id !== Auth::id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $request->validate([
            'alternativa_id' => 'required|integer|exists:alternativas,id',
        ]);

        $alternativa = Alternativa::find($request->alternativa_id);

        if ($alternativa->pregunta_id !== $pregunta->id) {
            return response()->json(['error' => 'La alternativa no pertenece a esta pregunta.'], 400);
        }

        $esCorrecta = $alternativa->es_correcta;
        $xp_amount = $esCorrecta ? 20 : -5;
        $gold_amount = $esCorrecta ? 10 : -5;
        
        $user->xp += $xp_amount;
        $user->gold += $gold_amount;

        if ($user->gold < 0) $user->gold = 0;
        
        $user->save();

        broadcast(new StatsUpdated($user->fresh()))->toOthers();

        return response()->json([
            'success' => true,
            'es_correcta' => $esCorrecta,
            'message' => $esCorrecta ? '¡Respuesta correcta!' : 'Respuesta incorrecta.',
        ]);
    }

    public function actualizarEstadoJuego(Request $request, Clase $clase)
    {
        if ($clase->profesor_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.');
        }

        $cacheKey = 'clase_' . $clase->id . '_estado';

        // Si la solicitud es para limpiar el estado (volver a la ruleta)
        if ($request->has('clear') && $request->clear == 'true') {
            Cache::put($cacheKey, ['status' => 'waiting'], now()->addHour());
            return response()->json(['status' => 'success', 'message' => 'Estado reiniciado a la espera.']);
        }

        // Lógica para establecer el estado de la pregunta
        $validated = $request->validate([
            'student_id' => 'required|integer|exists:users,id',
            'question' => 'required|array',
            'question.id' => 'required|integer|exists:preguntas,id',
            'question.texto_pregunta' => 'required|string',
            'question.alternativas' => 'required|array',
        ]);

        $estadoJuego = [
            'status' => 'questioning',
            'student_id' => $validated['student_id'],
            'question' => $validated['question'],
            'timestamp' => now(),
        ];

        Cache::put($cacheKey, $estadoJuego, now()->addHour());

        return response()->json(['status' => 'success', 'message' => 'Estado del juego actualizado.']);
    }

    public function llamarEstudianteAleatorio(Clase $clase)
    {
        if ($clase->profesor_id !== Auth::id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $estudiante = $clase->users()->inRandomOrder()->first();

        if (!$estudiante) {
            return response()->json(['error' => 'No hay estudiantes en la clase'], 404);
        }

        $pregunta = $clase->preguntas()->with('alternativas')->inRandomOrder()->first();

        if (!$pregunta) {
            return response()->json(['error' => 'No hay preguntas en esta clase'], 404);
        }

        // Guardar el estado actual en el caché por 5 minutos
        Cache::put('clase_' . $clase->id . '_estado', [
            'estudiante_seleccionado_id' => $estudiante->id,
            'estudiante_seleccionado_nombre' => $estudiante->name,
            'pregunta_actual' => $pregunta,
        ], now()->addMinutes(5));

        // Devolver el estudiante y la pregunta para que el frontend los muestre
        return response()->json([
            'estudiante' => $estudiante->load('skinEquipada'),
            'pregunta' => $pregunta
        ]);
    }

    public function resetEstado(Clase $clase)
    {
        if ($clase->profesor_id !== Auth::id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        Cache::forget('clase_' . $clase->id . '_estado');

        return response()->json(['success' => 'Estado reseteado']);
    }

    public function asignarNuevaPregunta(Clase $clase)
    {
        if ($clase->profesor_id !== Auth::id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $estadoClase = Cache::get('clase_' . $clase->id . '_estado');

        if (!$estadoClase || !isset($estadoClase['estudiante_seleccionado_id'])) {
            return response()->json(['error' => 'No hay ningún estudiante seleccionado actualmente.'], 422);
        }

        $preguntaAleatoria = $clase->preguntas()->with('alternativas')->inRandomOrder()->first();

        if (!$preguntaAleatoria) {
            return response()->json(['error' => 'No se encontraron más preguntas para esta clase.'], 404);
        }

        // Actualizar el estado en caché con la nueva pregunta, manteniendo al estudiante
        $estadoClase['pregunta_actual'] = $preguntaAleatoria;
        Cache::put('clase_' . $clase->id . '_estado', $estadoClase, now()->addMinutes(5));

        return response()->json(['pregunta' => $preguntaAleatoria]);
    }

    public function recompensarEstudiante(Request $request, Clase $clase)
    {
        if ($clase->profesor_id !== Auth::id()) {
            abort(403, 'No eres el profesor de esta clase.');
        }

        $request->validate([
            'student_id' => 'required|integer|exists:users,id',
            'xp' => 'required|integer|min:0',
            'gold' => 'required|integer|min:0',
        ]);

        $estudiante = User::find($request->student_id);

        if (!$clase->users->contains($estudiante)) {
            return response()->json(['success' => false, 'message' => 'El estudiante no pertenece a esta clase.'], 422);
        }

        $estudiante->xp += $request->xp;
        $estudiante->gold += $request->gold;
        $estudiante->save();

        broadcast(new StatsUpdated($estudiante->fresh()))->toOthers();

        $notificationMessage = "¡Has ganado {$request->xp} XP y {$request->gold} de oro!";
        $cacheKey = "recompensa_notificacion_{$clase->id}_{$estudiante->id}";
        Cache::put($cacheKey, $notificationMessage, now()->addSeconds(60)); // Guardar por 60 segundos

        return response()->json(['success' => true, 'message' => "¡{$estudiante->name} ha recibido {$request->xp} XP y {$request->gold} de oro!"]);
    }

    public function modificarEstadisticas(Request $request, Clase $clase, User $user)
    {
        if ($clase->profesor_id !== Auth::id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $request->validate([
            'stat' => 'required|string|in:health,xp,gold',
            'amount' => 'required|integer',
        ]);

        $stat = $request->input('stat');
        $amount = $request->input('amount');

        $user->$stat += $amount;

        // Asegurarse de que los valores no sean negativos
        if ($user->$stat < 0) {
            $user->$stat = 0;
        }

        if ($stat === 'xp') {
            // Recalcular nivel
            $user->nivel = floor($user->xp / 100) + 1;
        }
        
        $user->save();

        // Disparar el evento para notificar al estudiante en tiempo real
        broadcast(new StatsUpdated($user->fresh()))->toOthers();

        return response()->json([
            'success' => true,
            'message' => 'Estadísticas actualizadas.',
            'new_value' => $user->$stat,
            'new_level' => $user->nivel,
        ]);
    }
}
