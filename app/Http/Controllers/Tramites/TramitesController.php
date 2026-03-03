<?php

namespace App\Http\Controllers\Tramites;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Declaracion;
use App\Models\ModuloSat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TramitesController extends Controller
{
    public function index()
    {
        return view('pages.tramites.index');
    }

    // ─── CITAS ───────────────────────────────────────────────────
    public function citas()
    {
        $modulos = ModuloSat::activos()->orderBy('estado')->get();
        $misCitas = Auth::check()
            ? Cita::where('user_id', Auth::id())->latest()->paginate(10)
            : collect();

        return redirect()->route('contacto.index');
    }

    public function citasStore(Request $request)
    {
        $data = $request->validate([
            'rfc'          => ['required', 'string', 'regex:/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/i'],
            'curp'         => ['required', 'string', 'size:18'],
            'nombre'       => ['required', 'string', 'max:150'],
            'email'        => ['required', 'email'],
            'telefono'     => ['nullable', 'digits:10'],
            'estado'       => ['required', 'string'],
            'modulo'       => ['required', 'string'],
            'tramite'      => ['required', 'in:RFC,EFIRMA,CIF,DEVOLUCION,DECLARACIONES,ACLARACIONES,OTRO'],
            'fecha'        => ['required', 'date', 'after:today', 'before:' . now()->addDays(60)->toDateString()],
            'horario'      => ['required', 'string'],
            'observaciones'=> ['nullable', 'string', 'max:500'],
        ]);

        // Verificar disponibilidad (máx. 10 citas por módulo/fecha/horario)
        $modulo = ModuloSat::where('nombre', $data['modulo'])->first();

        $citasExistentes = Cita::where('modulo_sat_id', optional($modulo)->id)
                               ->where('fecha', $data['fecha'])
                               ->where('horario', $data['horario'])
                               ->whereIn('estatus', ['pendiente', 'confirmada'])
                               ->count();

        if ($citasExistentes >= 10) {
            return response()->json([
                'message' => 'El horario seleccionado ya no tiene disponibilidad. Por favor elige otro.',
            ], 422);
        }

        $cita = Cita::create([
            'user_id'      => Auth::id(),
            'rfc'          => strtoupper($data['rfc']),
            'curp'         => strtoupper($data['curp']),
            'nombre'       => $data['nombre'],
            'email'        => $data['email'],
            'telefono'     => $data['telefono'],
            'modulo_sat_id'=> optional($modulo)->id,
            'tramite'      => $data['tramite'],
            'fecha'        => $data['fecha'],
            'horario'      => $data['horario'],
            'observaciones'=> $data['observaciones'],
        ]);

        // Aquí enviarías el correo de confirmación:
        // Mail::to($data['email'])->send(new CitaConfirmadaMail($cita));

        return response()->json([
            'message'           => "¡Cita confirmada! Tu folio es {$cita->folio}. "
                                 . "Se enviará un correo de confirmación a {$data['email']}.",
            'folio'             => $cita->folio,
            'codigo_confirmacion'=> $cita->codigo_confirmacion,
        ]);
    }

    public function citasCancelar(string $folio)
    {
        $cita = Cita::where('folio', $folio)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

        if (! in_array($cita->estatus, ['pendiente', 'confirmada'])) {
            return back()->with('error', 'Esta cita no puede cancelarse.');
        }

        $cita->update(['estatus' => 'cancelada']);

        return back()->with('success', "Cita {$folio} cancelada correctamente.");
    }

    // ─── CONSULTAS ───────────────────────────────────────────────
    public function consultas()
    {
        return view('pages.tramites.consultas');
    }

    public function consultasStore(Request $request)
    {
        $request->validate([
            'rfc'  => ['required', 'string', 'regex:/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/i'],
            'tipo' => ['required', 'in:aclaracion,solicitud,aviso'],
        ]);

        return response()->json([
            'message' => 'Consulta registrada correctamente.',
            'folio'   => 'SOL-' . date('Y') . '-' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT),
        ]);
    }

    // ─── OPINIÓN DE CUMPLIMIENTO ─────────────────────────────────
    public function opiniones()
    {
        return view('pages.tramites.opiniones');
    }

    public function opinionesStore(Request $request)
    {
        $request->validate([
            'rfc' => ['required', 'string', 'regex:/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/i'],
        ]);

        $rfc = strtoupper($request->rfc);

        // Aquí consultarías el webservice del SAT para la opinión real
        // En simulación:
        $declaraciones = Declaracion::where('rfc', $rfc)->count();
        $cumple = $declaraciones > 0;

        return response()->json([
            'rfc'       => $rfc,
            'cumple'    => $cumple,
            'estatus'   => $cumple ? 'POSITIVA' : 'NEGATIVA',
            'mensaje'   => $cumple
                ? "El contribuyente {$rfc} está al corriente de sus obligaciones fiscales."
                : "El contribuyente {$rfc} presenta incumplimientos en sus obligaciones fiscales.",
            'fecha'     => now()->format('d/m/Y H:i'),
        ]);
    }

    // ─── DEVOLUCIONES ────────────────────────────────────────────
    public function devoluciones()
    {
        return view('pages.tramites.devoluciones');
    }

    public function devolucionesStore(Request $request)
    {
        $data = $request->validate([
            'rfc'            => ['required', 'string', 'regex:/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/i'],
            'ejercicio'      => ['required', 'integer', 'min:2000'],
            'impuesto'       => ['required', 'in:ISR,IVA,IEPS'],
            'tipo_tramite'   => ['required', 'in:devolucion,compensacion'],
            'monto_solicitado'=> ['required', 'numeric', 'min:0.01'],
            'cuenta_clabe'   => ['required', 'string', 'size:18'],
            'banco'          => ['required', 'string'],
        ]);

        $folio = 'DEV-' . date('Y') . '-' . str_pad(mt_rand(1, 9999999), 7, '0', STR_PAD_LEFT);

        return response()->json([
            'message' => "Solicitud de devolución registrada. Folio: {$folio}. "
                       . "El SAT tiene hasta 40 días hábiles para resolver tu solicitud. "
                       . "Puedes dar seguimiento con este folio.",
            'folio'   => $folio,
        ]);
    }

    // ─── COMPENSACIONES Y SALDO A FAVOR ─────────────────────────
    public function compensaciones()
    {
        return view('pages.tramites.compensaciones');
    }

    public function saldoFavor()
    {
        $declaraciones = Auth::check()
            ? Declaracion::where('user_id', Auth::id())
                         ->where('saldo_favor', '>', 0)
                         ->get()
            : collect();

        return view('pages.tramites.saldo_favor', compact('declaraciones'));
    }
}