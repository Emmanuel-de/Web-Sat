<?php

namespace App\Http\Controllers\Personas;

use App\Http\Controllers\Controller;
use App\Models\SolicitudRfc;
use Illuminate\Support\Facades\Auth;

class MiRfcController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $solicitud = null;

        // 1️⃣ Buscar por CURP del usuario (fuente de verdad principal)
        if (!empty($user->curp)) {
            $solicitud = SolicitudRfc::where('curp', strtoupper(trim($user->curp)))
                            ->where('estatus', 'aprobada')
                            ->latest()
                            ->first();
        }

        // 2️⃣ Fallback: buscar por user_id por si acaso
        if (!$solicitud) {
            $solicitud = SolicitudRfc::where('user_id', $user->id)
                            ->where('estatus', 'aprobada')
                            ->latest()
                            ->first();
        }

        return view('pages.personas.mi_rfc', compact('solicitud'));
    }
}
