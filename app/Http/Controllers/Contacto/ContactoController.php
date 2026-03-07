<?php

namespace App\Http\Controllers\Contacto;

use App\Http\Controllers\Controller;
use App\Models\Contacto;
use App\Models\Cita;
use App\Models\ModuloSat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactoController extends Controller
{
    public function index()
    {
        $modulos  = ModuloSat::activos()->orderBy('estado')->get();
        $misCitas = Auth::check()
            ? Cita::where('user_id', Auth::id())->with('modulo')->latest()->paginate(5)
            : collect();

        return view('pages.contacto.index', compact('modulos', 'misCitas'));
    }

    // ─── Mensaje / Consulta ──────────────────────────────────────
    public function mensajeStore(Request $request)
    {
        $data = $request->validate([
            'nombre'           => ['required', 'string', 'max:150'],
            'rfc'              => ['nullable', 'string', 'regex:/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/i'],
            'email'            => ['required', 'email'],
            'telefono'         => ['nullable', 'digits:10'],
            'tema'             => ['required', 'in:RFC,DECLARACIONES,FACTURACION,DEVOLUCIONES,EFIRMA,OTROS'],
            'mensaje'          => ['required', 'string', 'min:20', 'max:2000'],
            'acepta_privacidad'=> ['accepted'],
        ]);

        $contacto = Contacto::create([
            'user_id'          => Auth::id(),
            'nombre'           => $data['nombre'],
            'rfc'              => isset($data['rfc']) ? strtoupper($data['rfc']) : null,
            'email'            => $data['email'],
            'telefono'         => $data['telefono'] ?? null,
            'tipo'             => 'mensaje',
            'tema'             => $data['tema'],
            'mensaje'          => $data['mensaje'],
            'acepta_privacidad'=> true,
        ]);

        // Aquí enviarías el correo de acuse al contribuyente
        // Mail::to($data['email'])->send(new ContactoAcuseMail($contacto));

        return response()->json([
            'message' => "Tu consulta fue recibida. Folio: {$contacto->folio}. "
                       . "Te responderemos a {$data['email']} en un máximo de 5 días hábiles.",
            'folio'   => $contacto->folio,
        ]);
    }

    // ─── Quejas y Sugerencias ────────────────────────────────────
    public function quejaStore(Request $request)
    {
        $data = $request->validate([
            'tipo'        => ['required', 'in:QUEJA,SUGERENCIA,RECONOCIMIENTO,DENUNCIA'],
            'area'        => ['nullable', 'in:MODULO,PORTAL,TELEFONO,OTRO'],
            'descripcion' => ['required', 'string', 'min:30', 'max:3000'],
            'nombre'      => ['nullable', 'string', 'max:150'],
            'email'       => ['nullable', 'email'],
        ]);

        $contacto = Contacto::create([
            'user_id'    => Auth::id(),
            'nombre'     => $data['nombre'] ?? 'Anónimo',
            'email'      => $data['email'] ?? null,
            'tipo'       => strtolower($data['tipo']),
            'area'       => $data['area'] ?? null,
            'descripcion'=> $data['descripcion'],
        ]);

        $mensajes = [
            'QUEJA'          => 'Tu queja fue registrada y será atendida en un plazo de 10 días hábiles.',
            'SUGERENCIA'     => 'Tu sugerencia fue recibida. Gracias por ayudarnos a mejorar.',
            'RECONOCIMIENTO' => 'Tu reconocimiento fue registrado. Lo haremos llegar al área correspondiente.',
            'DENUNCIA'       => 'Tu denuncia fue registrada con carácter confidencial y será investigada.',
        ];

        return response()->json([
            'message' => $mensajes[$data['tipo']] . " Folio: {$contacto->folio}",
            'folio'   => $contacto->folio,
        ]);
    }

    public function citasIndex()
{
    $modulos  = ModuloSat::activos()->orderBy('estado')->get();
    $misCitas = Auth::check()
        ? Cita::where('user_id', Auth::id())->with('modulo')->latest()->paginate(5)
        : collect();

    return view('pages.Miscitas', compact('modulos', 'misCitas'));
}

    public function citaStore(Request $request)
{
    $data = $request->validate([
        'rfc'          => ['required','string','regex:/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/i'],
        'curp'         => ['required','string','size:18'],
        'nombre'       => ['required','string','max:150'],
        'email'        => ['required','email'],
        'telefono'     => ['nullable','digits:10'],
        'modulo_sat_id'=> ['required','exists:modulos_sat,id'],
        'tramite'      => ['required','in:RFC,EFIRMA,CIF,DECLARACION,DEVOLUCION,OPINION,FACTURACION,OTROS'],
        'fecha'        => ['required','date','after:today'],
        'horario'      => ['required','string'],
        'observaciones'=> ['nullable','string','max:500'],
    ]);

    $cita = Cita::create([
        ...$data,
        'user_id' => Auth::id(),
        'rfc'     => strtoupper($data['rfc']),
        'curp'    => strtoupper($data['curp']),
    ]);

    return redirect()->route('contacto.citas.index')
        ->with('success', "Cita confirmada. Folio: {$cita->folio} · Código: {$cita->codigo_confirmacion}");
}

public function citaCancelar(string $folio)
{
    $cita = Cita::where('folio', $folio)
                ->where('user_id', Auth::id())
                ->firstOrFail();

    $cita->update(['estatus' => 'cancelada']);

    return redirect()->route('contacto.citas.index')
        ->with('success', "La cita {$folio} fue cancelada correctamente.");
}
}
