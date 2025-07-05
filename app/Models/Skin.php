<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skin extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'imagen',
        'precio',
        'disponible'
    ];

    // Relación muchos a muchos con usuarios
    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'skin_usuario')
                    ->withPivot('equipado')
                    ->withTimestamps();
    }
} 