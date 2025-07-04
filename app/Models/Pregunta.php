<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    use HasFactory;

    protected $fillable = ['clase_id', 'pregunta'];

    // Relación con la clase
    public function clase()
    {
        return $this->belongsTo(Clase::class);
    }

    public function alternativas()
    {
        return $this->hasMany(Alternativa::class);
    }
} 