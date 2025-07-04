<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'gender',
        'email',
        'password',
        'role',
        'character_class',
        'avatar',
        'xp',
        'gold',
        'health',
        'nivel',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    /**
     * Verificar si el usuario es un profesor
     */
    public function isProfesor()
    {
        return $this->role === 'profesor';
    }

    /**
     * Verificar si el usuario es un estudiante
     */
    public function isEstudiante()
    {
        return $this->role === 'estudiante';
    }

    /**
     * Set the user's experience points and update level automatically.
     *
     * @param  int  $value
     * @return void
     */
    public function setXpAttribute($value)
    {
        if ($value < 0) {
            $value = 0;
        }
        $this->attributes['xp'] = $value;
        $this->attributes['nivel'] = floor($this->attributes['xp'] / 100) + 1;
    }

    /**
     * Agregar puntos de experiencia al usuario
     */
    public function agregarExperiencia($puntos)
    {
        $this->xp += $puntos;
        
        // El nivel se actualiza automáticamente a través del mutador setXpAttribute
        
        $this->save();
    }

    /**
     * Obtener el progreso hacia el siguiente nivel
     */
    public function getProgresoNivel()
    {
        $puntosParaSiguienteNivel = $this->nivel * 100;
        $puntosActuales = $this->xp % 100;
        return ($puntosActuales / 100) * 100; // Porcentaje
    }

    /**
     * Relación con las clases que un usuario imparte como profesor.
     */
    public function clasesComoProfesor()
    {
        return $this->hasMany(Clase::class, 'profesor_id');
    }

    /**
     * Relación que obtiene la skin equipada por el usuario.
     */
    public function skinEquipada()
    {
        return $this->belongsTo(Skin::class, 'skin_equipada_id');
    }

    public function clasesComoEstudiante()
    {
        return $this->belongsToMany(Clase::class, 'clase_user', 'user_id', 'clase_id');
    }

    /**
     * Relación con todas las skins que posee el usuario.
     */
    public function skins()
    {
        return $this->belongsToMany(Skin::class, 'skin_user');
    }
}
