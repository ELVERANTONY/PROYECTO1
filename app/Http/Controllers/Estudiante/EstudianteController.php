<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Skin;
use Illuminate\Support\Facades\Auth;
use App\Models\Clase;
use App\Models\Pregunta;
use App\Models\Alternativa;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class EstudianteController extends Controller
{
    /**
     * Mostrar el dashboard del estudiante
     */
    public function dashboard()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Obtener la clase activa (si existe) con el conteo de estudiantes
        $claseActiva = $user->clasesComoEstudiante()
            ->select('clases.*')
            ->withCount('users')
            ->whereIn('estado', ['pendiente', 'iniciada'])
            ->orderBy('updated_at', 'desc')
            ->first();
            
        // Obtener las clases finalizadas con información básica
        $clasesFinalizadas = $user->clasesComoEstudiante()
            ->select('clases.id', 'clases.nombre', 'clases.profesor_id', 'clases.estado', 'clases.updated_at')
            ->with(['profesor' => function($query) {
                $query->select('id', 'name');
            }])
            ->where('estado', 'finalizada')
            ->orderBy('updated_at', 'desc')
            ->get();
        
        // Mensaje de éxito al unirse a una clase
        $mensajeUnido = session('mensaje_unido');
        
        // Preparar datos para la vista
        return view('estudiante.dashboard', [
            'user' => $user->load('skinEquipada'),
            'claseActiva' => $claseActiva,
            'clasesFinalizadas' => $clasesFinalizadas,
            'mensajeUnido' => $mensajeUnido,
            'fechaActual' => now()->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Mostrar las clases finalizadas del estudiante
     */
    public function clases()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        // Obtener solo las clases finalizadas del estudiante
        $clasesFinalizadas = $user->clasesComoEstudiante()
            ->where('estado', 'finalizada')
            ->orderBy('updated_at', 'desc')
            ->get();
            
        return view('estudiante.clases', [
            'user' => $user,
            'clasesFinalizadas' => $clasesFinalizadas
        ]);
    }

    /**
     * Mostrar la tienda de skins
     */
    public function tienda()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $userSkinsIds = $user->skins->pluck('id')->toArray();

        // Skins para la tienda: solo las de la clase del usuario, que no sean gratis y que no haya comprado.
        $skinsDisponibles = Skin::where('character_class', $user->character_class)
                                ->where('precio', '>', 0)
                                ->whereNotIn('id', $userSkinsIds)
                                ->get();

        // Skins para el inventario, que el usuario ya ha comprado
        $skinsCompradas = $user->skins;

        // Comodines - definidos en el backend, no en la BD
        $comodines = [
            [
                'id' => 'subir_3_niveles',
                'nombre' => 'Impulso de Poder',
                'descripcion' => 'Asciende 3 niveles de experiencia de golpe.',
                'precio' => 300,
                'nivel_requerido' => 1,
                'image_url' => 'images/ImpulsodePoder.png'
            ],
            [
                'id' => 'pacto_del_erudito',
                'nombre' => 'Pacto del Erudito',
                'descripcion' => 'Extiende el plazo de tu próximo laboratorio por 1 hora.',
                'precio' => 210,
                'nivel_requerido' => 2,
                'image_url' => 'images/PactodelErudito.png'
            ],
            [
                'id' => 'mas_tiempo_examen',
                'nombre' => 'Reloj de Arena Arcano',
                'descripcion' => 'Añade 20 minutos extra a tu próximo examen.',
                'precio' => 220,
                'nivel_requerido' => 3,
                'image_url' => 'images/RelojArenaArcano.jpeg'
            ],
            [
                'id' => 'segunda_oportunidad',
                'nombre' => 'Pergamino del Destino',
                'descripcion' => 'Te otorga una segunda oportunidad en una evaluación futura.',
                'precio' => 250,
                'nivel_requerido' => 4,
                'image_url' => 'images/PergaminodelDestino.png'
            ]
        ];

        return view('estudiante.tienda', compact('user', 'skinsDisponibles', 'skinsCompradas', 'comodines'));
    }

    /**
     * Mostrar el ranking de estudiantes
     */
    public function ranking()
    {
        $user = Auth::user();
        
        // Lógica para obtener el ranking, ahora con la skin equipada para el avatar
        $ranking = User::where('role', 'estudiante')
            ->orderByDesc('nivel') 
            ->orderByDesc('xp')
            ->with('skinEquipada') // Cargar la skin para mostrar el avatar
            ->take(20) // Tomar los 20 mejores
            ->get();

        return view('estudiante.ranking', compact('user', 'ranking'));
    }

    /**
     * Mostrar la página de ajustes
     */
    public function ajustes()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return view('estudiante.ajustes', ['user' => $user->load('skins')]);
    }

    public function unirseClase(Request $request)
    {
        // Verificar si la solicitud espera una respuesta JSON
        if ($request->wantsJson() || $request->ajax()) {
            try {
                $request->validate(['codigo' => 'required|string']);

                /** @var \App\Models\User $user */
                $user = Auth::user();
                $clase = Clase::where('codigo', $request->codigo)
                    ->withCount('users')
                    ->with('profesor:id,name')
                    ->first();

                if (!$clase) {
                    return response()->json([
                        'status' => 'error', 
                        'message' => 'El código de clase no es válido o la clase no existe.'
                    ]);
                }

                if ($clase->estado === 'finalizada') {
                    return response()->json([
                        'status' => 'error', 
                        'message' => 'Esta clase ya ha finalizado.'
                    ]);
                }

                if ($user->clasesComoEstudiante()->where('clase_id', $clase->id)->exists()) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Ya estás unido a esta clase.',
                        'redirect' => route('estudiante.dashboard')
                    ]);
                }

                // Adjuntar el usuario a la clase
                $user->clasesComoEstudiante()->attach($clase->id);
                
                // Recargar la relación para obtener los datos actualizados
                $clase->loadCount('users');
                $clase->load('profesor:id,name');
                
                // Disparar evento de estudiante unido a la clase
                event(new \App\Events\EstudianteUnidoEvent($user, $clase->id));

                // Obtener el HTML de la tarjeta de clase actualizada
                $claseActiva = $clase;
                $view = view('estudiante.partials.clase-activa', compact('claseActiva'))->render();

                return response()->json([
                    'status' => 'success',
                    'message' => '¡Te has unido a la clase ' . $clase->nombre . '!',
                    'view' => $view,
                    'redirect' => route('estudiante.dashboard')
                ]);

            } catch (\Exception $e) {
                Log::error('Error al unirse a la clase: ' . $e->getMessage());
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ocurrió un error al procesar la solicitud: ' . $e->getMessage()
                ]);
            }
        }
        
        // Si no es una petición AJAX, redirigir con un mensaje de error
        return redirect()->route('estudiante.dashboard')->with('error', 'Método no permitido');
    }

    public function sesionClase(Clase $clase)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        // Usar clasesComoEstudiante si la relación clases es null
        $clases = $user->clasesComoEstudiante;
        if (!$clases || !$clases->contains($clase)) {
            return redirect()->route('estudiante.dashboard')->with('error', 'No estás inscrito en esta clase.');
        }

        return view('estudiante.sesion-clase', compact('clase', 'user'));
    }

    public function esperandoClase(Clase $clase)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Verificar que el usuario esté inscrito en la clase
        if (!$user->clasesComoEstudiante()->where('clase_id', $clase->id)->exists()) {
            return redirect()->route('estudiante.dashboard')->with('error', 'No estás inscrito en esta clase.');
        }

        // Si la clase ya inició, redirigir directamente a la sesión
        if ($clase->estado === 'iniciada') {
            return redirect()->route('estudiante.clase.sesion', $clase);
        }

        // Obtener los datos del usuario para mostrar en la vista
        $oro = $user->gold ?? 0;
        $xp = $user->xp ?? 0;

        return view('estudiante.esperando-clase', compact('clase', 'oro', 'xp'));
    }

    public function getEstudiantes(Clase $clase)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        // Asegurarse de que el estudiante autenticado pertenece a la clase
        if (!$user->clasesComoEstudiante()->where('clase_id', $clase->id)->exists()) {
            abort(403, 'No tienes acceso a esta clase.');
        }

        // Cargar los estudiantes de la clase con su skin equipada
        $estudiantes = $clase->users()->with('skinEquipada')->get();
        
        return response()->json($estudiantes);
    }

    public function responderPregunta(Request $request)
    {
        $request->validate([
            'pregunta_id' => 'required|exists:preguntas,id',
            'alternativa_id' => 'required|exists:alternativas,id',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $alternativa = Alternativa::find($request->alternativa_id);

        if ($alternativa->es_correcta) {
            $xpGanada = 20; // Recompensa por respuesta correcta
            $goldGanado = 10;

            $user->xp += $xpGanada;
            $user->gold += $goldGanado;
            $user->save();

            broadcast(new \App\Events\StatsUpdated($user->fresh()))->toOthers();

            return response()->json([
                'correcta' => true,
                'message' => '¡Respuesta correcta!',
                'xp_ganada' => $xpGanada,
                'gold_ganado' => $goldGanado,
            ]);
        }

        return response()->json([
            'correcta' => false,
            'message' => 'Respuesta incorrecta. ¡Sigue intentándolo!',
            'alternativa_correcta_id' => $alternativa->pregunta->alternativas->where('es_correcta', true)->first()->id
        ]);
    }

    public function getEstadoJuego(Clase $clase)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->clasesComoEstudiante->contains($clase)) {
            abort(403);
        }

        $estado = Cache::get('estado_juego_' . $clase->id);

        return response()->json($estado);
    }

    public function getNotificacion(Clase $clase)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->clasesComoEstudiante()->where('clase_id', $clase->id)->exists()) {
            abort(403, 'No tienes acceso a esta clase.');
        }

        $cacheKey = "recompensa_notificacion_{$clase->id}_{$user->id}";
        $notification = Cache::pull($cacheKey);

        if ($notification) {
            return response()->json(['notification' => $notification]);
        }

        return response()->json(null);
    }

    public function comprarSkin(Request $request)
    {
        $request->validate([
            'skin_id' => 'required|exists:skins,id',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $skin = Skin::find($request->skin_id);

        if (!$skin) {
            return redirect()->route('estudiante.tienda')->with('error', 'Esta skin no existe.');
        }

        // --- VALIDACIÓN DE NIVEL REQUERIDO ---
        $requiredLevel = 0;
        if (str_contains(strtolower($skin->nombre), 'intermedio')) {
            $requiredLevel = 2;
        } elseif (str_contains(strtolower($skin->nombre), 'avanzado')) {
            $requiredLevel = 4;
        }

        if ($user->nivel < $requiredLevel) {
            return redirect()->route('estudiante.tienda')->with('error', "Necesitas ser nivel {$requiredLevel} para comprar esta skin.");
        }

        // --- VALIDACIÓN DE SEGURIDAD ---
        // Verificar que la skin sea para la clase del personaje
        if ($skin->character_class !== $user->character_class) {
            return redirect()->route('estudiante.tienda')->with('error', 'No puedes comprar una skin de otra clase.');
        }

        if ($user->gold < $skin->precio) {
            return redirect()->route('estudiante.tienda')->with('error', 'No tienes suficiente oro.');
        }

        if ($user->skins->contains($skin->id)) {
            return redirect()->route('estudiante.tienda')->with('error', 'Ya tienes esta skin comprada.');
        }

        $user->decrement('gold', $skin->precio);
        $user->skins()->attach($skin->id);

        return redirect()->route('estudiante.tienda')->with('success', '¡Skin comprada con éxito! Ahora puedes equiparla desde tu inventario.');
    }

    /**
     * Equipar una skin comprada
     */
    public function equiparSkin(Request $request)
    {
        $request->validate(['skin_id' => 'required|exists:skins,id']);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $skinId = $request->input('skin_id');

        // Verificar que el usuario posee la skin
        if (!$user->skins->contains($skinId)) {
            return redirect()->route('estudiante.tienda')->with('error', 'No posees esta skin.');
        }

        // Equipar la nueva skin
        $user->skin_equipada_id = $skinId;
        $user->save();

        return redirect()->route('estudiante.tienda')->with('success', '¡Skin equipada!');
    }

    /**
     * Verifica el estado de la clase para la redirección del lobby.
     */
    public function checkClaseStatus(Clase $clase)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        // Cargar el conteo de estudiantes y actualizar la clase
        $clase->loadCount('users');
        $clase->refresh();

        // Verificar si el usuario está inscrito en la clase
        if (!$user->clasesComoEstudiante->contains($clase)) {
            return response()->json([
                'status' => 'error',
                'estado' => 'no_inscrito',
                'message' => 'No estás inscrito en esta clase.'
            ], 403);
        }

        // Determinar el estado actual de la clase
        $estado = $clase->estado;
        
        // Construir la respuesta base
        $respuesta = [
            'status' => 'success',
            'estado' => $estado,
            'clase_id' => $clase->id,
            'clase_nombre' => $clase->nombre,
            'estudiantes_count' => $clase->users_count,
            'updated_at' => $clase->updated_at->diffForHumans()
        ];

        // Agregar información adicional según el estado
        switch ($estado) {
            case 'iniciada':
                $respuesta['redirect'] = route('estudiante.clase.sesion', $clase);
                $respuesta['message'] = '¡La clase ha comenzado! Redirigiendo...';
                break;
                
            case 'finalizada':
                $respuesta['message'] = 'Esta clase ya ha finalizado.';
                $respuesta['redirect'] = route('estudiante.dashboard');
                break;
                
            case 'pendiente':
            default:
                $respuesta['message'] = 'La clase aún no ha comenzado. Por favor, espera a que el profesor la inicie.';
                break;
        }

        return response()->json($respuesta);
    }

    /**
     * Comprar un comodín de la tienda
     */
    public function comprarComodin(Request $request)
    {
        $request->validate(['comodin_id' => 'required|string']);

        $comodinId = $request->input('comodin_id');

        // Definir los comodines aquí también para validar la compra
        $comodines = [
            'subir_3_niveles' => ['precio' => 300, 'niveles' => 3, 'nivel_requerido' => 1],
            'pacto_del_erudito' => ['precio' => 210, 'nivel_requerido' => 2],
            'mas_tiempo_examen' => ['precio' => 220, 'nivel_requerido' => 3],
            'segunda_oportunidad' => ['precio' => 250, 'nivel_requerido' => 4]
        ];

        if (!isset($comodines[$comodinId])) {
            return redirect()->route('estudiante.tienda')->with('error', 'El comodín seleccionado no es válido.');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $comodin = $comodines[$comodinId];

        // Validar nivel requerido
        if ($user->nivel < $comodin['nivel_requerido']) {
            return redirect()->route('estudiante.tienda')->with('error', 'No tienes el nivel suficiente para comprar este comodín.');
        }

        // Validar oro
        if ($user->gold < $comodin['precio']) {
            return redirect()->route('estudiante.tienda')->with('error', 'No tienes suficiente oro para comprar este comodín.');
        }

        // Restar oro
        $user->gold -= $comodin['precio'];

        // Aplicar efecto del comodín
        if ($comodinId === 'subir_3_niveles') {
            $nivelesASubir = $comodin['niveles'];
            
            $xpGanado = $nivelesASubir * 100;
            $user->agregarExperiencia($xpGanado);
            
            return redirect()->route('estudiante.tienda')->with('success', "¡Comodín comprado! Has subido {$nivelesASubir} niveles.");
        }
        
        $user->save();
        return redirect()->route('estudiante.tienda')->with('success', '¡Comodín comprado con éxito!');
    }
}