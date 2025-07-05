<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Skin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Mostrar el formulario de registro
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Procesar el registro de usuario
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'gender' => 'required|in:hombre,mujer',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:estudiante,profesor',
            'character_class' => 'required_if:role,estudiante|in:mago,guerrero,sanador|nullable',
        ], [
            'name.required' => 'El nombre es obligatorio',
            'gender.required' => 'Debes seleccionar un género.',
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El email debe tener un formato válido',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'role.required' => 'Debe seleccionar un rol',
            'role.in' => 'El rol seleccionado no es válido',
            'character_class.required_if' => 'Debes seleccionar una clase para tu personaje.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Buscar si el usuario ya existe y actualizarlo, o crear uno nuevo
        $user = User::updateOrCreate(
            ['email' => $request->email],
            [
                'name' => $request->name,
                'gender' => $request->gender,
                'password' => bcrypt($request->password),
                'role' => $request->role,
                'character_class' => $request->role === 'estudiante' ? $request->character_class : null,
                'avatar' => 'default-avatar.png',
                'puntos_experiencia' => 0,
                'nivel' => 1,
            ]
        );
        
        // Si el usuario es un estudiante, asignarle el skin por defecto de su clase
        if ($user->isEstudiante()) {
            // Asegurarse de que el usuario no tenga ya skins asignadas para evitar duplicados
            if ($user->skins()->count() == 0) {
                $defaultSkin = Skin::where('character_class', $user->character_class)
                               ->orderBy('id', 'asc')
                               ->first();

                if ($defaultSkin) {
                    $user->skins()->attach($defaultSkin->id);
                    $user->skin_equipada_id = $defaultSkin->id;
                    $user->save();
                }
            }
        }

        // Redirigir a la página de login con un mensaje de éxito.
        return redirect()->route('login')->with('success', '¡Tu cuenta ha sido creada exitosamente! Ahora puedes iniciar sesión.');
    }

    /**
     * Mostrar el formulario de login
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Procesar el login
     */
    public function login(Request $request)
    {
        Log::info('Intento de inicio de sesión', ['email' => $request->email]);
        
        // Validar los datos del formulario
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        Log::info('Validación pasada', ['email' => $request->email]);

        // Intentar autenticar al usuario
        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            Log::info('Usuario autenticado', [
                'id' => Auth::id(),
                'email' => Auth::user()->email,
                'role' => Auth::user()->role
            ]);
            
            // Redirigir según el rol del usuario
            if (Auth::user()->role === 'profesor') {
                Log::info('Redirigiendo a dashboard de profesor');
                return redirect()->route('profesor.dashboard');
            } else {
                Log::info('Redirigiendo a dashboard de estudiante');
                return redirect()->route('estudiante.dashboard');
            }
        }

        Log::warning('Autenticación fallida', ['email' => $request->email]);
        // Si la autenticación falla
        return back()->withErrors([
            'email' => 'Las credenciales no son válidas.',
        ])->onlyInput('email');
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome')->with('success', 'Has cerrado sesión exitosamente.');
    }
} 