<?php

namespace App\Http\Controllers\Personas;

use App\Http\Controllers\Controller;
use App\Models\SolicitudRfc;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class RfcController extends Controller
{
    // ─── Mostrar formulario ──────────────────────────────────────
    public function index()
    {
        $resultadoConsulta = null;
        return view('pages.personas.rfc', compact('resultadoConsulta'));
    }

    // ─── Inscripción RFC ─────────────────────────────────────────
    public function store(Request $request)
    {
        $data = $request->validate([
            'primer_apellido'         => ['required', 'string', 'max:50'],
            'segundo_apellido'        => ['nullable', 'string', 'max:50'],
            'nombres'                 => ['required', 'string', 'max:80'],
            'fecha_nacimiento'        => ['required', 'date', 'before:today'],
            'sexo'                    => ['required', 'in:H,M'],
            'estado_nacimiento'       => ['required', 'string', 'max:50'],
            'curp'                    => ['required', 'string', 'size:18', 'regex:/^[A-Z]{4}\d{6}[HM][A-Z]{5}[A-Z0-9]\d$/'],
            'email'                   => ['required', 'email', 'max:100'],
            'telefono'                => ['required', 'digits:10'],
            'tipo_identificacion'     => ['required', 'in:INE,PASAPORTE,CEDULA,CARTILLA'],
            // Domicilio
            'codigo_postal'           => ['required', 'digits:5'],
            'estado'                  => ['required', 'string', 'max:50'],
            'municipio'               => ['required', 'string', 'max:80'],
            'colonia'                 => ['required', 'string', 'max:100'],
            'calle'                   => ['required', 'string', 'max:100'],
            'no_exterior'             => ['required', 'string', 'max:10'],
            'no_interior'             => ['nullable', 'string', 'max:10'],
            'entre_calles'            => ['nullable', 'string', 'max:200'],
            // Actividad
            'regimen_fiscal'          => ['required', 'string'],
            'actividad_principal'     => ['required', 'string', 'max:200'],
            'descripcion_actividad'   => ['nullable', 'string', 'max:500'],
            'fecha_inicio_actividades'=> ['required', 'date'],
            'acepta_terminos'         => ['accepted'],
            'acepta_privacidad'       => ['accepted'],
        ]);

        // Verificar que no exista ya una solicitud para este CURP
        if (SolicitudRfc::where('curp', strtoupper($data['curp']))->where('estatus', '!=', 'rechazada')->exists()) {
            return response()->json([
                'message' => 'Ya existe una solicitud de RFC registrada para este CURP.',
            ], 422);
        }

        $solicitud = SolicitudRfc::create(array_merge($data, [
            'user_id' => Auth::id(),
            'curp'    => strtoupper($data['curp']),
        ]));

        // Aquí se integraría con el webservice real del SAT
        // Por ahora simulamos el folio asignado

        return response()->json([
            'message' => "Tu solicitud de RFC fue enviada exitosamente. Folio: {$solicitud->folio}. "
                       . "Recibirás tu RFC en el correo {$data['email']} en un plazo de 24 a 72 horas hábiles.",
            'folio'   => $solicitud->folio,
        ]);
    }

    // ─── Consulta de RFC ─────────────────────────────────────────
    public function consulta(Request $request)
    {
        $request->validate([
            'rfc'           => ['required', 'string', 'regex:/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/i'],
            'tipo_consulta' => ['nullable', 'in:validacion,situacion,lco'],
        ]);

        $rfc = strtoupper($request->rfc);

        // Buscar en BD local primero
        $user = User::where('rfc', $rfc)->first();

        if ($user) {
            $resultadoConsulta = (object) [
                'rfc'              => $user->rfc,
                'nombre'           => $user->nombre_completo,
                'tipo'             => $user->tipo === 'fisica' ? 'Persona Física' : 'Persona Moral',
                'activo'           => $user->activo,
                'fecha_inscripcion'=> $user->created_at->format('d/m/Y'),
            ];
        } else {
            // Aquí integrarías el webservice SAT de consulta RFC
            // https://services.sat.gob.mx/wsDiagnosticoRFC/
            $resultadoConsulta = (object) [
                'rfc'              => $rfc,
                'nombre'           => 'No encontrado en padrón local',
                'tipo'             => 'Desconocido',
                'activo'           => false,
                'fecha_inscripcion'=> 'N/D',
            ];
        }

        return view('pages.personas.rfc', compact('resultadoConsulta'));
    }

    // ─── Reimpresión / Constancia ────────────────────────────────
    public function reimpresion(Request $request)
    {
        $request->validate([
            'rfc'  => ['required', 'string', 'regex:/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/i'],
            'curp' => ['required', 'string', 'size:18'],
        ]);

        $rfc  = strtoupper($request->rfc);
        $curp = strtoupper($request->curp);

        $user = User::where('rfc', $rfc)->where('curp', $curp)->first();

        if (! $user) {
            return response()->json([
                'message' => 'No se encontraron datos con el RFC y CURP proporcionados.',
            ], 404);
        }

        // Aquí generarías el PDF de la Constancia de Situación Fiscal
        // y lo retornarías como descarga:
        // return response()->download(storage_path("app/constancias/{$rfc}.pdf"));

        return response()->json([
            'message'  => 'Constancia generada correctamente.',
            'redirect' => route('personas.cif'), // Redirigir a descarga real
        ]);
    }
}