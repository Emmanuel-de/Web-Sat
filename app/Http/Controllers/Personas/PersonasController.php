<?php

namespace App\Http\Controllers\Personas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonasController extends Controller
{
    public function index()
    {
        return view('pages.personas.index');
    }

    // ─── e.firma ─────────────────────────────────────────────────
    public function eFirma()
    {
        return view('pages.personas.e_firma');
    }

    public function eFirmaStore(Request $request)
    {
        $request->validate([
            'rfc'    => ['required', 'string', 'regex:/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/i'],
            'curp'   => ['required', 'string', 'size:18'],
            'email'  => ['required', 'email'],
            'tipo'   => ['required', 'in:nueva,renovacion'],
        ]);

        // Lógica de solicitud de e.firma
        // Generalmente requiere presencia física en módulo SAT

        return response()->json([
            'message' => 'Solicitud de e.firma registrada. '
                       . 'Deberás acudir a un módulo SAT con tu documentación. '
                       . 'Se ha enviado un correo a ' . $request->email . ' con los pasos a seguir.',
        ]);
    }

    // ─── Constancia de Situación Fiscal ─────────────────────────
    public function cif()
    {
        return view('pages.personas.cif');
    }

    public function cifStore(Request $request)
    {
        $request->validate([
            'rfc'  => ['required', 'string', 'regex:/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/i'],
            'curp' => ['required', 'string', 'size:18'],
        ]);

        // Aquí generarías el PDF real de la CIF
        // Usando la librería DOMPDF o similar:
        // $pdf = PDF::loadView('pdfs.cif', compact('user'));
        // return $pdf->download("CIF_{$request->rfc}.pdf");

        return response()->json([
            'message'  => 'Constancia de Situación Fiscal generada.',
            'redirect' => '/descargar-cif/' . strtoupper($request->rfc),
        ]);
    }

    // ─── Declaración Anual (redirección) ─────────────────────────
    public function declaracionAnual()
    {
        return redirect()->route('declaraciones.index');
    }

    public function declaracionProvisional()
    {
        return redirect()->route('declaraciones.index');
    }

    public function isr()
    {
        return view('pages.personas.isr');
    }

    // ─── Facturación (redirección) ───────────────────────────────
    public function facturacion()
    {
        return redirect()->route('facturacion.index');
    }

    public function verificarCfdi()
    {
        return redirect()->route('facturacion.verificar');
    }

    // ─── Buzón Tributario ────────────────────────────────────────
    public function buzonTributario()
    {
        $this->middleware('auth');

        $mensajes = [];
        if (Auth::check()) {
            // $mensajes = BuzonMensaje::where('user_id', Auth::id())->latest()->paginate(15);
        }

        return view('pages.personas.buzon', compact('mensajes'));
    }


    public function eFirmaVerificar(Request $request)
{
    $request->validate([
        'rfc' => ['required', 'regex:/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/i'],
    ]);

    $user   = User::where('rfc', strtoupper($request->rfc))->first();
    $vigente = (bool) $user;

    $resultadoVerificacion = (object) [
        'rfc'               => strtoupper($request->rfc),
        'no_serie'          => $request->no_serie ?? '20001000000300022323',
        'vigente'           => $vigente,
        'fecha_emision'     => $vigente ? $user->created_at->format('d/m/Y') : 'N/D',
        'fecha_vencimiento' => $vigente ? $user->created_at->addYears(4)->format('d/m/Y') : 'N/D',
    ];

    return view('pages.personas.e_firma', compact('resultadoVerificacion'));
}


}
