<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Estudiante\EstudianteController;
use App\Http\Controllers\Estudiante\EstudianteClaseController;
use App\Http\Controllers\Profesor\ProfesorController;
use Illuminate\Support\Facades\Auth;

// Registrar el middleware 'role' globalmente
Route::aliasMiddleware('role', \App\Http\Middleware\CheckRole::class);

// Ruta principal
Route::get('/', function () {
    if (Auth::check()) {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->isProfesor()) {
            return redirect()->route('profesor.dashboard');
        }
        return redirect()->route('estudiante.dashboard');
    }
    return view('welcome');
})->name('welcome');

// Rutas de autenticación para invitados (no logueados)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Ruta de logout para usuarios autenticados
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Rutas protegidas para estudiantes
Route::middleware(['auth', 'role:estudiante'])->prefix('estudiante')->name('estudiante.')->group(function () {
    Route::get('/dashboard', [EstudianteController::class, 'dashboard'])->name('dashboard');
    Route::get('/clases', [EstudianteController::class, 'clases'])->name('clases');
    Route::post('/clases/unirse', [EstudianteController::class, 'unirseClase'])->name('clases.unirse');
    Route::get('/clases/{clase}/sesion', [EstudianteController::class, 'sesionClase'])->name('clase.sesion');
    Route::get('/clases/{clase}/esperando', [EstudianteController::class, 'esperandoClase'])->name('clase.esperando');
    Route::get('/clases/{clase}/estado', [EstudianteClaseController::class, 'getEstado'])->name('clase.estado');
    Route::post('/clases/{clase}/responder', [EstudianteClaseController::class, 'responder'])->name('clase.responder');
    Route::get('/clases/{clase}/get-notificacion', [EstudianteController::class, 'getNotificacion'])->name('clase.get-notificacion');
    Route::get('/clases/{clase}/check-status', [EstudianteController::class, 'checkClaseStatus'])->name('clases.check-status');
    Route::post('/clases/responder', [EstudianteController::class, 'responderPregunta'])->name('clases.responder.old');
    Route::get('/tienda', [EstudianteController::class, 'tienda'])->name('tienda');
    Route::post('/tienda/comprar', [EstudianteController::class, 'comprarSkin'])->name('tienda.comprar');
    Route::post('/tienda/equipar', [EstudianteController::class, 'equiparSkin'])->name('tienda.equipar');
    Route::post('/tienda/comprar-comodin', [EstudianteController::class, 'comprarComodin'])->name('tienda.comprar-comodin');
    Route::get('/ranking', [EstudianteController::class, 'ranking'])->name('ranking');
    Route::get('/ajustes', [EstudianteController::class, 'ajustes'])->name('ajustes');
});

// Rutas protegidas para profesores
Route::middleware(['auth', 'role:profesor'])->name('profesor.')->prefix('profesor')->group(function () {
    Route::get('/dashboard', [ProfesorController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/datos', [ProfesorController::class, 'getDashboardData'])->name('dashboard.datos');
    Route::get('/clases/crear', [ProfesorController::class, 'crearClase'])->name('clases.crear');
    Route::post('/clases', [ProfesorController::class, 'storeClase'])->name('clases.store');
    Route::post('/clases/{clase}/generar-codigo', [ProfesorController::class, 'generarCodigo'])->name('clases.generar-codigo');
    Route::post('/clases/{clase}/finalizar', [ProfesorController::class, 'finalizarClase'])->name('clases.finalizar');
    Route::post('/clases/{clase}/recompensar', [ProfesorController::class, 'recompensarEstudiante'])->name('clases.recompensar');
    Route::get('/clases/{clase}/ver', [ProfesorController::class, 'verClase'])->name('clases.ver');
    Route::get('/clases/{clase}/editar', [ProfesorController::class, 'editarClase'])->name('clases.editar');
    Route::put('/clases/{clase}', [ProfesorController::class, 'actualizarClase'])->name('clases.actualizar');
    Route::delete('/clases/{clase}', [ProfesorController::class, 'destroy'])->name('clases.destroy');
    Route::post('/clases/{clase}/iniciar', [ProfesorController::class, 'iniciarClase'])->name('clase.iniciar');
    Route::get('/clases/{clase}/sesion', [ProfesorController::class, 'sesionClase'])->name('clase.sesion');
    Route::post('/{clase}/llamar-estudiante', [ProfesorController::class, 'llamarEstudianteAleatorio'])->name('clase.llamarEstudiante');
    Route::post('/{clase}/reset-estado', [ProfesorController::class, 'resetEstado'])->name('clase.resetEstado');
    Route::post('/{clase}/nueva-pregunta', [ProfesorController::class, 'asignarNuevaPregunta'])->name('clase.nuevaPregunta');
    Route::post('/clases/{clase}/estudiantes/{user}/modificar-stats', [ProfesorController::class, 'modificarEstadisticas'])->name('clase.modificar-stats');
    Route::post('/clases/{clase}/estudiantes/{user}/pregunta/{pregunta}/evaluar', [ProfesorController::class, 'evaluarRespuesta'])->name('clase.evaluar-respuesta');
    Route::post('/clases/{clase}/actualizar-estado', [ProfesorController::class, 'actualizarEstadoJuego'])->name('clase.estado.actualizar');
    Route::post('/clases/{clase}/seleccionar-estudiante', [ProfesorController::class, 'seleccionarEstudiante'])->name('clase.seleccionar-estudiante');
    Route::get('/estudiantes', [ProfesorController::class, 'estudiantes'])->name('estudiantes');
    Route::get('/gestion-clases', [ProfesorController::class, 'gestionClases'])->name('gestion-clases');
    Route::get('/ajustes', [ProfesorController::class, 'ajustes'])->name('ajustes');
    Route::get('/clases/{clase}/estudiantes', [ProfesorController::class, 'getEstudiantesForRuleta'])->name('clase.estudiantes');
});

// Rutas compartidas
Route::middleware(['auth'])->group(function () {
    Route::get('/clases/{clase}/participantes', [ProfesorController::class, 'getEstudiantesForRuleta'])->name('clase.participantes');
    Route::get('/clases/{clase}/ruleta', [ProfesorController::class, 'mostrarRuleta'])->name('clase.ruleta');
});

// --- RUTA DE DEPURACIÓN TEMPORAL ---
Route::get('/debug-login', function () {
    $email = 'profesor@demo.com';
    $password = 'password123';

    $user = \App\Models\User::where('email', $email)->first();

    if (!$user) {
        return 'DEBUG: Usuario no encontrado.';
    }

    if (\Illuminate\Support\Facades\Hash::check($password, $user->password)) {
        return 'DEBUG: ¡Éxito! La contraseña es correcta.';
    } else {
        return 'DEBUG: Fallo. La contraseña no coincide.';
    }
});
