<?php

namespace App\Http\Controllers\Personas;

use App\Http\Controllers\Controller;
use App\Models\EFirma;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
class EFirmaController extends Controller
{
    // ══════════════════════════════════════════════════════════════
    //  GET  /personas/e-firma
    // ══════════════════════════════════════════════════════════════
    public function index(): View
    {
        return view('pages.personas.e_firma');
    }
    
    public function miEfirma(): \Illuminate\View\View
    {
    return view('pages.mie_firma');
    }
    // ══════════════════════════════════════════════════════════════
    //  POST /personas/e-firma   (nueva | renovacion | revocacion)
    // ══════════════════════════════════════════════════════════════
    public function store(Request $request)
    {
        $tipo = $request->input('tipo');

        $rules     = $this->reglasValidacion($tipo);
        $validator = Validator::make($request->all(), $rules, $this->mensajes());

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('tab_activa', $tipo);
        }

        $archivos = $this->guardarArchivos($request, $tipo);

        $folio = EFirma::generarFolio($tipo);
        $datos = array_merge(
            $request->only([
                'tipo','rfc','curp','email','telefono',
                'fecha_nacimiento','primer_apellido','segundo_apellido','nombres',
                'tipo_identificacion','estado_modulo','modulo_efirma','fecha_cita',
                'horario_cita','via_renovacion','no_serie','motivo_revocacion',
                'descripcion_revocacion',
            ]),
            $archivos,
            [
                'folio'    => $folio,
                'estatus'  => 'pendiente',
                'no_serie' => $request->input('no_serie') ?: EFirma::generarNoSerie(),
            ]
        );

        $datos['rfc']  = strtoupper($datos['rfc']  ?? '');
        $datos['curp'] = strtoupper($datos['curp'] ?? '');

        $efirma = EFirma::create($datos);

        $this->enviarCorreoConfirmacion($efirma);

        $mensaje = match($tipo) {
            'renovacion' => "Tu solicitud de renovación fue registrada con folio <strong>{$folio}</strong>. Recibirás instrucciones en tu correo.",
            'revocacion' => "Tu certificado será revocado. Folio de acuse: <strong>{$folio}</strong>.",
            default      => "Solicitud registrada con folio <strong>{$folio}</strong>. Revisa tu correo para los detalles de tu cita.",
        };

        return back()->with([
            'success'    => $mensaje,
            'folio'      => $folio,
            'tab_activa' => $tipo,
        ]);
    }

    // ══════════════════════════════════════════════════════════════
    //  POST /personas/e-firma/verificar
    // ══════════════════════════════════════════════════════════════
    public function verificar(Request $request)
    {
        // Validar: RFC requerido SOLO si no hay archivo .cer adjunto
        $request->validate([
            'rfc' => [
                Rule::requiredIf(fn() => !$request->hasFile('archivo_cer_verificar')),
                'nullable',
                'regex:/^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/i',
            ],
            'archivo_cer_verificar' => 'nullable|file|max:1024',
        ], [
            'rfc.required' => 'El RFC es obligatorio cuando no adjuntas un archivo .cer.',
            'rfc.regex'    => 'El formato del RFC no es válido.',
        ]);

        // Obtener RFC — del campo o extrayéndolo del nombre del archivo .cer
        $rfc = strtoupper(trim($request->input('rfc', '')));

        if (empty($rfc) && $request->hasFile('archivo_cer_verificar')) {
            $nombreArchivo = $request->file('archivo_cer_verificar')->getClientOriginalName();
            preg_match('/([A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3})/i', $nombreArchivo, $matches);
            $rfc = strtoupper($matches[1] ?? '');
        }

        if (empty($rfc)) {
            return redirect()->route('personas.e_firma')
                ->withInput()
                ->with('error_verificacion', 'No se pudo determinar el RFC. Ingrésalo manualmente.')
                ->with('tab_activa', 'verificar');
        }

        // Buscar la e.firma más reciente (cualquier estatus) del RFC
        $efirma = EFirma::where('rfc', $rfc)
            ->whereIn('tipo', ['nueva', 'renovacion'])
            ->latest()
            ->first();

        if (!$efirma) {
            return redirect()->route('personas.e_firma')
                ->withInput()
                ->with('error_verificacion', "No se encontró ningún certificado para el RFC <strong>{$rfc}</strong>.")
                ->with('tab_activa', 'verificar');
        }

        $emision     = $efirma->created_at;
        $vencimiento = $emision->copy()->addYears(4);
        $vigente     = now()->lt($vencimiento);

        $resultadoVerificacion = (object)[
            'id'                => $efirma->id,
            'rfc'               => $efirma->rfc,
            'nombre'            => trim("{$efirma->primer_apellido} {$efirma->segundo_apellido} {$efirma->nombres}"),
            'curp'              => $efirma->curp ?? '—',
            'no_serie'          => $efirma->no_serie ?? '—',
            'folio'             => $efirma->folio,
            'estatus'           => $efirma->estatus,
            'modulo'            => $efirma->modulo_efirma   ?? '—',
            'estado_modulo'     => $efirma->estado_modulo   ?? '—',
            'fecha_cita'        => $efirma->fecha_cita
                                    ? \Carbon\Carbon::parse($efirma->fecha_cita)->format('d/m/Y')
                                    : '—',
            'fecha_emision'     => $emision->format('d/m/Y'),
            'fecha_vencimiento' => $vencimiento->format('d/m/Y'),
            'vigente'           => $vigente,
        ];

        // Convertir a array para serialización correcta en sesión flash
        // Los objetos PHP stdClass no siempre se serializan bien en sesión
        $datosVerificacion = [
            'id'                => $resultadoVerificacion->id,
            'rfc'               => $resultadoVerificacion->rfc,
            'nombre'            => $resultadoVerificacion->nombre,
            'curp'              => $resultadoVerificacion->curp,
            'no_serie'          => $resultadoVerificacion->no_serie,
            'folio'             => $resultadoVerificacion->folio,
            'estatus'           => $resultadoVerificacion->estatus,
            'modulo'            => $resultadoVerificacion->modulo,
            'estado_modulo'     => $resultadoVerificacion->estado_modulo,
            'fecha_cita'        => $resultadoVerificacion->fecha_cita,
            'fecha_emision'     => $resultadoVerificacion->fecha_emision,
            'fecha_vencimiento' => $resultadoVerificacion->fecha_vencimiento,
            'vigente'           => $resultadoVerificacion->vigente,
        ];

        return redirect()->route('personas.e_firma')
            ->with('tab_activa', 'verificar')
            ->with('resultadoVerificacion', $datosVerificacion);
    }

    // ══════════════════════════════════════════════════════════════
    //  GET  /personas/e-firma/{id}/descargar-cer
    // ══════════════════════════════════════════════════════════════
    public function descargarCer(int $id)
    {
        $efirma      = EFirma::findOrFail($id);
        $emision     = $efirma->created_at;
        $vencimiento = $emision->copy()->addYears(4);
        $nombre      = trim("{$efirma->primer_apellido} {$efirma->segundo_apellido} {$efirma->nombres}");
        $fechaCita   = $efirma->fecha_cita
                        ? \Carbon\Carbon::parse($efirma->fecha_cita)->format('d/m/Y')
                        : '—';

        $sep = str_repeat('=', 60);

        $contenido  = "{$sep}\n";
        $contenido .= "  CERTIFICADO DE FIRMA ELECTRONICA AVANZADA (e.firma)\n";
        $contenido .= "  Servicio de Administracion Tributaria - SAT\n";
        $contenido .= "{$sep}\n\n";
        $contenido .= "  RFC              : {$efirma->rfc}\n";
        $contenido .= "  CURP             : {$efirma->curp}\n";
        $contenido .= "  NOMBRE           : {$nombre}\n\n";
        $contenido .= "  NO. DE SERIE     : {$efirma->no_serie}\n";
        $contenido .= "  FOLIO SAT        : {$efirma->folio}\n";
        $contenido .= "  ESTATUS          : " . strtoupper($efirma->estatus) . "\n\n";
        $contenido .= "  FECHA EMISION    : {$emision->format('d/m/Y H:i:s')}\n";
        $contenido .= "  FECHA VENCIMIENTO: {$vencimiento->format('d/m/Y H:i:s')}\n\n";

        if ($efirma->modulo_efirma) {
            $contenido .= "  MODULO SAT       : {$efirma->modulo_efirma}\n";
            $contenido .= "  ESTADO           : {$efirma->estado_modulo}\n";
            $contenido .= "  FECHA CITA       : {$fechaCita}\n";
            $contenido .= "  HORARIO CITA     : {$efirma->horario_cita}\n\n";
        }

        $contenido .= "{$sep}\n";
        $contenido .= "  NOTA: Este archivo es una representacion de tu solicitud.\n";
        $contenido .= "  El certificado oficial (.cer) te sera entregado en el\n";
        $contenido .= "  modulo SAT el dia de tu cita.\n";
        $contenido .= "{$sep}\n";

        $nombreArchivo = "efirma_{$efirma->rfc}_{$efirma->no_serie}.cer";

        return response($contenido, 200, [
            'Content-Type'        => 'application/octet-stream',
            'Content-Disposition' => "attachment; filename=\"{$nombreArchivo}\"",
            'Cache-Control'       => 'no-cache, no-store, must-revalidate',
        ]);
    }

    // ══════════════════════════════════════════════════════════════
    //  GET  /api/rfc-autocomplete?q=XXXX
    // ══════════════════════════════════════════════════════════════
    public function rfcAutocomplete(Request $request): JsonResponse
    {
        $q = strtoupper(trim($request->input('q', '')));

        if (strlen($q) < 3) {
            return response()->json([]);
        }

        // 1. Buscar primero en tabla users
        $resultados = \App\Models\User::where('rfc', 'like', "{$q}%")
            ->select('rfc', 'curp', 'email', 'telefono',
                     'primer_apellido', 'segundo_apellido', 'nombres', 'fecha_nacimiento')
            ->limit(8)
            ->get()
            ->map(fn($u) => [
                'rfc'              => strtoupper($u->rfc ?? ''),
                'nombre'           => trim(($u->primer_apellido ?? '') . ' ' . ($u->segundo_apellido ?? '') . ' ' . ($u->nombres ?? '')),
                'curp'             => strtoupper($u->curp ?? ''),
                'email'            => $u->email ?? '',
                'telefono'         => $u->telefono ?? '',
                'primer_apellido'  => $u->primer_apellido ?? '',
                'segundo_apellido' => $u->segundo_apellido ?? '',
                'nombres'          => $u->nombres ?? '',
                'fecha_nacimiento' => $u->fecha_nacimiento
                                        ? \Carbon\Carbon::parse($u->fecha_nacimiento)->format('Y-m-d')
                                        : '',
                'fuente'           => 'users',
            ]);

        // 2. Complementar con e_firmas si hay menos de 8 resultados
        if ($resultados->count() < 8) {
            $rfcsYa = $resultados->pluck('rfc')->toArray();

            $desdeFirmas = EFirma::where('rfc', 'like', "{$q}%")
                ->whereNotIn('rfc', $rfcsYa)
                ->select('rfc','curp','email','telefono',
                         'primer_apellido','segundo_apellido','nombres','fecha_nacimiento')
                ->latest()
                ->limit(8 - $resultados->count())
                ->get()
                ->unique('rfc')
                ->values()
                ->map(fn($e) => [
                    'rfc'              => $e->rfc,
                    'nombre'           => trim("{$e->primer_apellido} {$e->segundo_apellido} {$e->nombres}"),
                    'curp'             => $e->curp ?? '',
                    'email'            => $e->email ?? '',
                    'telefono'         => $e->telefono ?? '',
                    'primer_apellido'  => $e->primer_apellido ?? '',
                    'segundo_apellido' => $e->segundo_apellido ?? '',
                    'nombres'          => $e->nombres ?? '',
                    'fecha_nacimiento' => optional($e->fecha_nacimiento)->format('Y-m-d') ?? '',
                    'fuente'           => 'efirma',
                ]);

            $resultados = $resultados->merge($desdeFirmas);
        }

        return response()->json($resultados->values());
    }

    // ══════════════════════════════════════════════════════════════
    //  GET  /api/modulos/{estado}
    // ══════════════════════════════════════════════════════════════
    public function modulosPorEstado(string $estado): JsonResponse
    {
        $modulos = $this->catalogoModulos();
        $lista   = $modulos[urldecode($estado)] ?? [];

        return response()->json($lista);
    }

    // ══════════════════════════════════════════════════════════════
    //  PRIVADOS
    // ══════════════════════════════════════════════════════════════

    private function reglasValidacion(string $tipo): array
    {
        $rfcRegex  = ['required', 'regex:/^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/i'];
        $curpRegex = ['required', 'regex:/^[A-Z]{4}[0-9]{6}[HM][A-Z]{5}[A-Z0-9]{2}$/i'];

        $comunes = [
            'tipo'     => 'required|in:nueva,renovacion,revocacion',
            'rfc'      => $rfcRegex,
            'email'    => 'required|email|max:150',
            'telefono' => 'required|digits:10',
        ];

        return match($tipo) {
            'nueva' => array_merge($comunes, [
                'curp'                 => $curpRegex,
                'fecha_nacimiento'     => 'required|date|before:today',
                'primer_apellido'      => 'required|string|max:80',
                'nombres'              => 'required|string|max:100',
                'tipo_identificacion'  => 'required|in:INE,PASAPORTE,CEDULA,CARTILLA',
                'email_confirmation'   => 'required|same:email',
                'contrasena_clave'     => 'required|min:8|confirmed',
                'estado_modulo'        => 'required|string',
                'modulo_efirma'        => 'required|string',
                'fecha_cita'           => 'required|date|after:today',
                'horario_cita'         => 'required|string',
                'archivo_ine'          => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'archivo_domicilio'    => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'archivo_curp'         => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'archivo_foto'         => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                'acepta_terminos'      => 'accepted',
                'acepta_privacidad'    => 'accepted',
            ]),
            'renovacion' => array_merge($comunes, [
                'curp'             => $curpRegex,
                'contrasena_clave' => 'required|min:8|confirmed',
                'archivo_cer'      => 'nullable|file|mimes:cer|max:1024',
            ]),
            'revocacion' => array_merge($comunes, [
                'no_serie'            => 'required|string|max:30',
                'motivo_revocacion'   => 'required|in:ROBO,COMPROMISO,CAMBIO_DATOS,FALLECIMIENTO,OTRO',
                'confirma_revocacion' => 'accepted',
            ]),
            default => $comunes,
        };
    }

    private function mensajes(): array
    {
        return [
            'rfc.regex'                    => 'El formato del RFC no es válido (ej: GOML850101ABC).',
            'curp.regex'                   => 'El formato del CURP no es válido.',
            'email_confirmation.same'      => 'Los correos electrónicos no coinciden.',
            'contrasena_clave.confirmed'   => 'Las contraseñas no coinciden.',
            'contrasena_clave.min'         => 'La contraseña debe tener al menos 8 caracteres.',
            'archivo_ine.required'         => 'La identificación oficial es obligatoria.',
            'archivo_domicilio.required'   => 'El comprobante de domicilio es obligatorio.',
            'archivo_curp.required'        => 'El CURP impreso es obligatorio.',
            'acepta_terminos.accepted'     => 'Debes aceptar los términos y condiciones.',
            'acepta_privacidad.accepted'   => 'Debes aceptar el aviso de privacidad.',
            'confirma_revocacion.accepted' => 'Debes confirmar que entiendes que la revocación es irreversible.',
            'fecha_cita.after'             => 'La fecha de cita debe ser posterior a hoy.',
            'telefono.digits'              => 'El teléfono debe tener exactamente 10 dígitos.',
        ];
    }

    private function guardarArchivos(Request $request, string $tipo): array
    {
        $campos    = ['archivo_ine','archivo_domicilio','archivo_curp','archivo_foto','archivo_cer'];
        $guardados = [];
        $rfc       = strtoupper($request->input('rfc', 'UNKNOWN'));
        $carpeta   = "efirma/{$rfc}/{$tipo}/" . date('Ymd');

        foreach ($campos as $campo) {
            if ($request->hasFile($campo) && $request->file($campo)->isValid()) {
                $path              = $request->file($campo)->store($carpeta, 'private');
                $guardados[$campo] = $path;
            }
        }

        return $guardados;
    }

    private function enviarCorreoConfirmacion(EFirma $efirma): void
    {
        try {
            // Mail::to($efirma->email)->send(new \App\Mail\EFirmaConfirmacion($efirma));
        } catch (\Throwable $e) {
            logger()->error('Error enviando correo e.firma: ' . $e->getMessage());
        }
    }

    private function catalogoModulos(): array
    {
        return [
            'Aguascalientes'      => [
                ['nombre' => 'Módulo Aguascalientes Centro', 'municipio' => 'Aguascalientes'],
                ['nombre' => 'Módulo Aguascalientes Norte',  'municipio' => 'Aguascalientes'],
            ],
            'Baja California'     => [
                ['nombre' => 'Módulo Tijuana',  'municipio' => 'Tijuana'],
                ['nombre' => 'Módulo Mexicali', 'municipio' => 'Mexicali'],
                ['nombre' => 'Módulo Ensenada', 'municipio' => 'Ensenada'],
            ],
            'Baja California Sur' => [
                ['nombre' => 'Módulo La Paz',    'municipio' => 'La Paz'],
                ['nombre' => 'Módulo Los Cabos', 'municipio' => 'Los Cabos'],
            ],
            'Campeche'    => [['nombre' => 'Módulo Campeche', 'municipio' => 'Campeche']],
            'Chiapas'     => [
                ['nombre' => 'Módulo Tuxtla Gutiérrez', 'municipio' => 'Tuxtla Gutiérrez'],
                ['nombre' => 'Módulo Tapachula',        'municipio' => 'Tapachula'],
            ],
            'Chihuahua'   => [
                ['nombre' => 'Módulo Chihuahua',     'municipio' => 'Chihuahua'],
                ['nombre' => 'Módulo Ciudad Juárez', 'municipio' => 'Ciudad Juárez'],
            ],
            'Ciudad de México' => [
                ['nombre' => 'Módulo Iztapalapa',        'municipio' => 'Iztapalapa'],
                ['nombre' => 'Módulo Centro Histórico',  'municipio' => 'Cuauhtémoc'],
                ['nombre' => 'Módulo Tlalpan',           'municipio' => 'Tlalpan'],
                ['nombre' => 'Módulo Gustavo A. Madero', 'municipio' => 'Gustavo A. Madero'],
                ['nombre' => 'Módulo Álvaro Obregón',    'municipio' => 'Álvaro Obregón'],
                ['nombre' => 'Módulo Coyoacán',          'municipio' => 'Coyoacán'],
            ],
            'Coahuila'    => [
                ['nombre' => 'Módulo Saltillo', 'municipio' => 'Saltillo'],
                ['nombre' => 'Módulo Torreón',  'municipio' => 'Torreón'],
                ['nombre' => 'Módulo Monclova', 'municipio' => 'Monclova'],
            ],
            'Colima'      => [
                ['nombre' => 'Módulo Colima',     'municipio' => 'Colima'],
                ['nombre' => 'Módulo Manzanillo', 'municipio' => 'Manzanillo'],
            ],
            'Durango'     => [['nombre' => 'Módulo Durango', 'municipio' => 'Durango']],
            'Estado de México' => [
                ['nombre' => 'Módulo Toluca',       'municipio' => 'Toluca'],
                ['nombre' => 'Módulo Ecatepec',     'municipio' => 'Ecatepec'],
                ['nombre' => 'Módulo Naucalpan',    'municipio' => 'Naucalpan'],
                ['nombre' => 'Módulo Texcoco',      'municipio' => 'Texcoco'],
                ['nombre' => 'Módulo Tlalnepantla', 'municipio' => 'Tlalnepantla'],
            ],
            'Guanajuato'  => [
                ['nombre' => 'Módulo León',       'municipio' => 'León'],
                ['nombre' => 'Módulo Guanajuato', 'municipio' => 'Guanajuato'],
                ['nombre' => 'Módulo Celaya',     'municipio' => 'Celaya'],
                ['nombre' => 'Módulo Irapuato',   'municipio' => 'Irapuato'],
            ],
            'Guerrero'    => [
                ['nombre' => 'Módulo Acapulco',     'municipio' => 'Acapulco'],
                ['nombre' => 'Módulo Chilpancingo', 'municipio' => 'Chilpancingo'],
            ],
            'Hidalgo'     => [
                ['nombre' => 'Módulo Pachuca', 'municipio' => 'Pachuca'],
                ['nombre' => 'Módulo Tula',    'municipio' => 'Tula'],
            ],
            'Jalisco'     => [
                ['nombre' => 'Módulo Guadalajara Centro', 'municipio' => 'Guadalajara'],
                ['nombre' => 'Módulo Zapopan',            'municipio' => 'Zapopan'],
                ['nombre' => 'Módulo Puerto Vallarta',    'municipio' => 'Puerto Vallarta'],
                ['nombre' => 'Módulo Lagos de Moreno',    'municipio' => 'Lagos de Moreno'],
            ],
            'Michoacán'   => [
                ['nombre' => 'Módulo Morelia',         'municipio' => 'Morelia'],
                ['nombre' => 'Módulo Lázaro Cárdenas', 'municipio' => 'Lázaro Cárdenas'],
                ['nombre' => 'Módulo Uruapan',         'municipio' => 'Uruapan'],
            ],
            'Morelos'     => [
                ['nombre' => 'Módulo Cuernavaca', 'municipio' => 'Cuernavaca'],
                ['nombre' => 'Módulo Cuautla',    'municipio' => 'Cuautla'],
            ],
            'Nayarit'     => [['nombre' => 'Módulo Tepic', 'municipio' => 'Tepic']],
            'Nuevo León'  => [
                ['nombre' => 'Módulo Monterrey Centro', 'municipio' => 'Monterrey'],
                ['nombre' => 'Módulo San Nicolás',      'municipio' => 'San Nicolás de los Garza'],
                ['nombre' => 'Módulo Guadalupe',        'municipio' => 'Guadalupe'],
                ['nombre' => 'Módulo Apodaca',          'municipio' => 'Apodaca'],
            ],
            'Oaxaca'      => [
                ['nombre' => 'Módulo Oaxaca',      'municipio' => 'Oaxaca de Juárez'],
                ['nombre' => 'Módulo Salina Cruz', 'municipio' => 'Salina Cruz'],
            ],
            'Puebla'      => [
                ['nombre' => 'Módulo Puebla Centro', 'municipio' => 'Puebla'],
                ['nombre' => 'Módulo Tehuacán',      'municipio' => 'Tehuacán'],
            ],
            'Querétaro'   => [
                ['nombre' => 'Módulo Querétaro',        'municipio' => 'Querétaro'],
                ['nombre' => 'Módulo San Juan del Río', 'municipio' => 'San Juan del Río'],
            ],
            'Quintana Roo' => [
                ['nombre' => 'Módulo Cancún',           'municipio' => 'Benito Juárez'],
                ['nombre' => 'Módulo Chetumal',         'municipio' => 'Othón P. Blanco'],
                ['nombre' => 'Módulo Playa del Carmen', 'municipio' => 'Solidaridad'],
            ],
            'San Luis Potosí' => [
                ['nombre' => 'Módulo San Luis Potosí', 'municipio' => 'San Luis Potosí'],
                ['nombre' => 'Módulo Ciudad Valles',   'municipio' => 'Ciudad Valles'],
            ],
            'Sinaloa'     => [
                ['nombre' => 'Módulo Culiacán',  'municipio' => 'Culiacán'],
                ['nombre' => 'Módulo Los Mochis','municipio' => 'Ahome'],
                ['nombre' => 'Módulo Mazatlán',  'municipio' => 'Mazatlán'],
            ],
            'Sonora'      => [
                ['nombre' => 'Módulo Hermosillo',     'municipio' => 'Hermosillo'],
                ['nombre' => 'Módulo Nogales',        'municipio' => 'Nogales'],
                ['nombre' => 'Módulo Ciudad Obregón', 'municipio' => 'Cajeme'],
            ],
            'Tabasco'     => [['nombre' => 'Módulo Villahermosa', 'municipio' => 'Centro']],
            'Tamaulipas'  => [
                ['nombre' => 'Módulo Tampico',         'municipio' => 'Tampico'],
                ['nombre' => 'Módulo Reynosa',         'municipio' => 'Reynosa'],
                ['nombre' => 'Módulo Matamoros',       'municipio' => 'Matamoros'],
                ['nombre' => 'Módulo Nuevo Laredo',    'municipio' => 'Nuevo Laredo'],
                ['nombre' => 'Módulo Ciudad Victoria', 'municipio' => 'Victoria'],
            ],
            'Tlaxcala'    => [['nombre' => 'Módulo Tlaxcala', 'municipio' => 'Tlaxcala']],
            'Veracruz'    => [
                ['nombre' => 'Módulo Veracruz Puerto', 'municipio' => 'Veracruz'],
                ['nombre' => 'Módulo Xalapa',          'municipio' => 'Xalapa'],
                ['nombre' => 'Módulo Coatzacoalcos',   'municipio' => 'Coatzacoalcos'],
                ['nombre' => 'Módulo Córdoba',         'municipio' => 'Córdoba'],
            ],
            'Yucatán'     => [
                ['nombre' => 'Módulo Mérida Norte', 'municipio' => 'Mérida'],
                ['nombre' => 'Módulo Mérida Sur',   'municipio' => 'Mérida'],
            ],
            'Zacatecas'   => [
                ['nombre' => 'Módulo Zacatecas', 'municipio' => 'Zacatecas'],
                ['nombre' => 'Módulo Fresnillo', 'municipio' => 'Fresnillo'],
            ],
        ];
    }
}