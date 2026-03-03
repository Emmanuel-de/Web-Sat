<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    use HasFactory;

    protected $table = 'noticias';

    protected $fillable = [
        'titulo',
        'slug',
        'resumen',
        'contenido',
        'imagen',
        'categoria',  // 'personas' | 'empresas' | 'general' | 'normatividad'
        'activo',
        'fecha',
        'autor',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'fecha'  => 'date',
    ];

    public function scopeActivas($q)            { return $q->where('activo', true); }
    public function scopeRecientes($q, $n = 3)  { return $q->orderByDesc('fecha')->take($n); }
    public function scopePorCategoria($q, $cat) { return $q->where('categoria', $cat); }
}