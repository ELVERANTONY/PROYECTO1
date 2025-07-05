<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Clase extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'codigo',
        'profesor_id',
        'tema',
        'estado'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($clase) {
            // Generar un código único de 6 caracteres alfanuméricos
            $clase->codigo = strtoupper(Str::random(6));
        });
    }

    // Relación con el profesor (usuario)
    public function profesor()
    {
        return $this->belongsTo(User::class, 'profesor_id');
    }

    // Relación con las preguntas
    public function preguntas()
    {
        return $this->hasMany(Pregunta::class);
    }

    // Relación con los estudiantes
    public function users()
    {
        return $this->belongsToMany(User::class, 'clase_user', 'clase_id', 'user_id');
    }
}