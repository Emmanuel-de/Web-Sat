<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable; // <--- Ya no tiene HasApiTokens

    protected $fillable = [
        'tipo', 'rfc', 'curp', 'nombres', 'primer_apellido', 'segundo_apellido',
        'fecha_nacimiento', 'razon_social', 'tipo_sociedad', 'rep_nombre', 'rep_rfc', 
        'email', 'telefono', 'password', 'activo', 'acepta_notificaciones'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'activo' => 'boolean',
    ];

    // Accessor para mostrar el nombre en el Dashboard
    public function getNombreCompletoAttribute(): string
    {
        if ($this->tipo === 'moral') {
            return $this->razon_social ?? 'Empresa sin nombre';
        }
        return trim("{$this->nombres} {$this->primer_apellido} {$this->segundo_apellido}");
    }
}