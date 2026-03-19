<?php

namespace App\Http\Controllers\Personas;

use App\Http\Controllers\Controller;
use App\Models\SolicitudRfc;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'primer_apellido'          => ['required', 'string', 'max:50'],
            'segundo_apellido'         => ['nullable', 'string', 'max:50'],
            'nombres'                  => ['required', 'string', 'max:80'],
            'fecha_nacimiento'         => ['required', 'date', 'before:today'],
            'sexo'                     => ['required', 'in:Hombre,Mujer'],
            'estado_nacimiento'        => ['required', 'string', 'max:50'],
            'curp'                     => ['required', 'string', 'size:18'],
            'email'                    => ['required', 'email', 'max:100'],
            'telefono'                 => ['required', 'digits:10'],
            'tipo_identificacion'      => ['required', 'in:INE,PASAPORTE,CEDULA,CARTILLA'],
            'codigo_postal'            => ['required', 'digits:5'],
            'estado'                   => ['required', 'string', 'max:50'],
            'municipio'                => ['required', 'string', 'max:80'],
            'colonia'                  => ['required', 'string', 'max:100'],
            'calle'                    => ['required', 'string', 'max:100'],
            'no_exterior'              => ['required', 'string', 'max:10'],
            'no_interior'              => ['nullable', 'string', 'max:10'],
            'entre_calles'             => ['nullable', 'string', 'max:200'],
            'regimen_fiscal'           => ['required', 'string'],
            'actividad_principal'      => ['required', 'string', 'max:200'],
            'descripcion_actividad'    => ['nullable', 'string', 'max:500'],
            'fecha_inicio_actividades' => ['required', 'date'],
            'acepta_terminos'          => ['accepted'],
            'acepta_privacidad'        => ['accepted'],
        ]);

        $curp = strtoupper($data['curp']);

        // Verificar solicitud duplicada
        if (SolicitudRfc::where('curp', $curp)->where('estatus', '!=', 'rechazada')->exists()) {
            return response()->json([
                'message' => 'Ya existe una solicitud de RFC registrada para este CURP.',
            ], 422);
        }

        // Generar RFC a partir de la CURP
        $rfc = $this->generarRfcDesdeCurp($curp, $data['fecha_nacimiento']);

        // Asegurar unicidad del RFC
        $rfcBase = $rfc;
        $sufijo  = 1;
        while (SolicitudRfc::where('rfc', $rfc)->exists()) {
            $rfc = $rfcBase . $sufijo;
            $sufijo++;
        }

        // Guardar en base de datos
        $solicitud = SolicitudRfc::create(array_merge($data, [
            'user_id' => Auth::id(),
            'curp'    => $curp,
            'rfc'     => $rfc,
            'estatus' => 'aprobada',
        ]));

        return response()->json([
            'message' => "Tu RFC fue generado exitosamente.",
            'rfc'     => $rfc,
            'folio'   => $solicitud->folio ?? $solicitud->id,
        ]);
    }

    /**
     * Genera un RFC de 13 caracteres usando la CURP como base.
     *
     * Estructura RFC persona física:
     *   [4 letras nombre] + [6 dígitos fecha AAMMDD] + [3 homoclave alfanumérica]
     *
     * Las primeras 10 posiciones se toman directamente de la CURP
     * (que ya contiene apellido+nombre+fecha). La homoclave se genera
     * de forma determinista a partir de un hash de la CURP.
     */
    private function generarRfcDesdeCurp(string $curp, string $fechaNacimiento): string
    {
        // CURP: AAAA YYMMDD H EEEEE NN C
        // RFC:  AAAA YYMMDD HHH
        //       [0-3] letras nombre = CURP[0-3]
        //       [4-9] fecha         = CURP[4-9]
        //       [10-12] homoclave   = generada

        $base = strtoupper(substr($curp, 0, 10)); // 4 letras + 6 fecha

        // Homoclave determinista: 3 caracteres alfanuméricos basados en hash
        $chars     = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $hash      = abs(crc32($curp)); // número positivo reproducible
        $homoA     = $chars[$hash        % 36];
        $homoB     = $chars[($hash >> 5) % 36];
        $homoC     = $chars[($hash >> 10) % 36];
        $homoclave = $homoA . $homoB . $homoC;

        return $base . $homoclave; // 13 caracteres
    }

    // ─── Consulta de RFC ─────────────────────────────────────────
    public function consulta(Request $request)
    {
        $request->validate([
            'rfc'           => ['required', 'string'],
            'tipo_consulta' => ['nullable', 'in:validacion,situacion,lco'],
        ]);

        $rfc  = strtoupper($request->rfc);
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
            // Buscar en solicitudes
            $solicitud = SolicitudRfc::where('rfc', $rfc)->first();
            $resultadoConsulta = (object) [
                'rfc'              => $rfc,
                'nombre'           => $solicitud ? "{$solicitud->nombres} {$solicitud->primer_apellido} {$solicitud->segundo_apellido}" : 'No encontrado en padrón local',
                'tipo'             => 'Persona Física',
                'activo'           => (bool) $solicitud,
                'fecha_inscripcion'=> $solicitud ? $solicitud->created_at->format('d/m/Y') : 'N/D',
            ];
        }

        return view('pages.personas.rfc', compact('resultadoConsulta'))
       ->with('tabActiva', 'consulta');
    }

    // ─── Reimpresión / Constancia ────────────────────────────────
    public function reimpresion(Request $request)
{
    $request->validate([
        'rfc'  => ['required', 'string'],
        'curp' => ['required', 'string'],
    ]);

    $rfc  = strtoupper(trim($request->rfc));
    $curp = strtoupper(trim($request->curp));

    $solicitud = SolicitudRfc::where('rfc', $rfc)
                              ->where('curp', $curp)
                              ->first();

    if (! $solicitud) {
        return back()
            ->withInput()
            ->with('tabActiva', 'reimpresion')
            ->withErrors(['rfc' => 'No se encontró ningún RFC registrado con esos datos. Verifica tu RFC y CURP.']);
    }

    // Redirigir directo a la constancia
    return redirect()->route('personas.rfc.constancia', $solicitud->rfc);
}

    // ─── Descarga de constancia (ruta GET) ───────────────────────
    public function constancia(string $rfc)
    {
        $solicitud = SolicitudRfc::where('rfc', strtoupper($rfc))->firstOrFail();

        // Aquí generarías el PDF real con DomPDF/Snappy.
        // Por ahora devolvemos la vista de constancia:
        return view('pages.personas.constancia_rfc', compact('solicitud'));
    }
    
    public function miConstancia()
{
    $rfc = strtoupper(Auth::user()->rfc ?? '');

    $solicitud = SolicitudRfc::where('rfc', $rfc)->firstOrFail();

    return view('pages.Miconstancia_fiscal', compact('solicitud'));
}

    public function buscar(string $rfc)
{
    $solicitud = SolicitudRfc::where('rfc', strtoupper($rfc))
                              ->where('estatus', 'aprobada')
                              ->first();
    if (! $solicitud) {
        return response()->json(['message' => 'No encontrado'], 404);
    }
    return response()->json([
        'primer_apellido'   => $solicitud->primer_apellido,
        'segundo_apellido'  => $solicitud->segundo_apellido,
        'nombres'           => $solicitud->nombres,
        'curp'              => $solicitud->curp,
        'fecha_nacimiento'  => $solicitud->fecha_nacimiento?->format('Y-m-d'),
        'telefono'          => $solicitud->telefono,
    ]);
}
}