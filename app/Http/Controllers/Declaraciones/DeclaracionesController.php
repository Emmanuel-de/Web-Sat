<?php

namespace App\Http\Controllers\Declaraciones;

use App\Http\Controllers\Controller;
use App\Models\Declaracion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeclaracionesController extends Controller
{
    // ─── Índice ──────────────────────────────────────────────────
    public function index()
    {
        $declaraciones = Auth::check()
            ? Declaracion::where('user_id', Auth::id())->latest()->paginate(15)
            : collect();

        return view('pages.declaraciones.index', compact('declaraciones'));
    }

    // ─── Declaración Anual ───────────────────────────────────────
    public function anualStore(Request $request)
    {
        $data = $request->validate([
            'rfc'                    => ['required', 'string', 'regex:/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/i'],
            'ejercicio'              => ['required', 'integer', 'min:2000', 'max:' . date('Y')],
            'tipo'                   => ['required', 'in:normal,complementaria'],
            'ingresos_acumulables'   => ['nullable', 'numeric', 'min:0'],
            'ingresos_exentos'       => ['nullable', 'numeric', 'min:0'],
            'honorarios_medicos'     => ['nullable', 'numeric', 'min:0'],
            'gastos_hospitalarios'   => ['nullable', 'numeric', 'min:0'],
            'primas_seguro'          => ['nullable', 'numeric', 'min:0'],
            'colegiaturas'           => ['nullable', 'numeric', 'min:0'],
            'intereses_hipotecarios' => ['nullable', 'numeric', 'min:0'],
            'donativos'              => ['nullable', 'numeric', 'min:0'],
            'isr_retenido'           => ['nullable', 'numeric', 'min:0'],
            'bajo_protesta'          => ['accepted'],
        ]);

        // Calcular ISR según tablas SAT (simplificado)
        $ingresos    = floatval($data['ingresos_acumulables'] ?? 0);
        $deducciones = array_sum(array_map(
            fn($k) => floatval($data[$k] ?? 0),
            ['honorarios_medicos','gastos_hospitalarios','primas_seguro','colegiaturas','intereses_hipotecarios','donativos']
        ));
        // Límite de deducciones personales: 15% del ingreso o 5 UMAs anuales
        $limiteDeduccion = min($ingresos * 0.15, 183_007.80);
        $deducciones     = min($deducciones, $limiteDeduccion);

        $baseGravable   = max(0, $ingresos - $deducciones);
        $isrDeterminado = $this->calcularISR($baseGravable);
        $isrRetenido    = floatval($data['isr_retenido'] ?? 0);
        $saldoCargo     = max(0, $isrDeterminado - $isrRetenido);
        $saldoFavor     = max(0, $isrRetenido - $isrDeterminado);

        $declaracion = Declaracion::create([
            'user_id'               => Auth::id(),
            'rfc'                   => strtoupper($data['rfc']),
            'tipo'                  => 'anual',
            'tipo_declaracion'      => $data['tipo'],
            'ejercicio'             => $data['ejercicio'],
            'ingresos_acumulables'  => $data['ingresos_acumulables'] ?? 0,
            'ingresos_exentos'      => $data['ingresos_exentos'] ?? 0,
            'honorarios_medicos'    => $data['honorarios_medicos'] ?? 0,
            'gastos_hospitalarios'  => $data['gastos_hospitalarios'] ?? 0,
            'primas_seguro'         => $data['primas_seguro'] ?? 0,
            'colegiaturas'          => $data['colegiaturas'] ?? 0,
            'intereses_hipotecarios'=> $data['intereses_hipotecarios'] ?? 0,
            'donativos'             => $data['donativos'] ?? 0,
            'base_gravable'         => $baseGravable,
            'isr_determinado'       => $isrDeterminado,
            'isr_retenido'          => $isrRetenido,
            'saldo_cargo'           => $saldoCargo,
            'saldo_favor'           => $saldoFavor,
            'importe'               => $saldoCargo,
            'fecha_limite'          => "{$data['ejercicio']}-04-30",
        ]);

        return response()->json([
            'message'     => "Declaración anual {$data['ejercicio']} presentada exitosamente. "
                           . "No. de operación: {$declaracion->no_operacion}",
            'no_operacion'=> $declaracion->no_operacion,
            'isr_cargo'   => number_format($saldoCargo, 2),
            'isr_favor'   => number_format($saldoFavor, 2),
        ]);
    }

    // ─── Pagos Provisionales / Definitivos ───────────────────────
    public function provisionalStore(Request $request)
    {
        $data = $request->validate([
            'rfc'           => ['required', 'string', 'regex:/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/i'],
            'impuesto'      => ['required', 'in:ISR,IVA,IEPS'],
            'periodo'       => ['required', 'integer', 'min:1', 'max:12'],
            'ejercicio'     => ['required', 'integer', 'min:2000', 'max:' . date('Y')],
            'tipo_pago'     => ['required', 'in:normal,complementaria,extemporanea'],
            'base_impuesto' => ['nullable', 'numeric', 'min:0'],
            'tasa'          => ['nullable', 'numeric', 'min:0', 'max:100'],
            'importe'       => ['required', 'numeric', 'min:0'],
        ]);

        $declaracion = Declaracion::create([
            'user_id'         => Auth::id(),
            'rfc'             => strtoupper($data['rfc']),
            'tipo'            => 'provisional',
            'impuesto'        => $data['impuesto'],
            'periodo'         => $data['periodo'],
            'ejercicio'       => $data['ejercicio'],
            'tipo_declaracion'=> $data['tipo_pago'],
            'base_gravable'   => $data['base_impuesto'] ?? 0,
            'importe'         => $data['importe'],
        ]);

        $nombreMes = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                      'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        return response()->json([
            'message'     => "Pago provisional de {$data['impuesto']} - "
                           . "{$nombreMes[$data['periodo']]} {$data['ejercicio']} presentado exitosamente. "
                           . "No. de operación: {$declaracion->no_operacion}",
            'no_operacion'=> $declaracion->no_operacion,
        ]);
    }

    // ─── Declaración Complementaria ──────────────────────────────
    public function complementariaStore(Request $request)
    {
        $request->validate([
            'rfc'           => ['required', 'string'],
            'no_operacion'  => ['required', 'string'],
            'motivo'        => ['nullable', 'string', 'max:500'],
        ]);

        $original = Declaracion::where('no_operacion', $request->no_operacion)
                               ->where('rfc', strtoupper($request->rfc))
                               ->first();

        if (! $original) {
            return response()->json([
                'message' => 'No se encontró una declaración con ese número de operación para el RFC indicado.',
            ], 404);
        }

        return response()->json([
            'message'      => 'Declaración encontrada correctamente. Puedes proceder a corregirla.',
            'declaracion'  => $original,
            'redirect'     => route('declaraciones.index'),
        ]);
    }

    // ─── Acuse de recibo ─────────────────────────────────────────
    public function acuse(Declaracion $declaracion)
    {
        // Verificar que la declaración pertenece al usuario autenticado
        if (Auth::id() !== $declaracion->user_id) {
            abort(403, 'No tienes permiso para ver este acuse.');
        }

        // Aquí generarías el PDF del acuse
        // $pdf = PDF::loadView('pdfs.acuse', compact('declaracion'));
        // return $pdf->download("Acuse_{$declaracion->no_operacion}.pdf");

        return view('pages.declaraciones.acuse', compact('declaracion'));
    }

    // ─── Buscar por No. de Operación (API) ───────────────────────
    public function buscarDeclaracion($noOperacion)
    {
        $declaracion = Declaracion::where('no_operacion', $noOperacion)->first();

        if (! $declaracion) {
            return response()->json(['message' => 'No encontrada'], 404);
        }

        return response()->json($declaracion);
    }

    // ─── Cálculo de ISR (tablas 2024) ────────────────────────────
    private function calcularISR(float $base): float
    {
        // Tarifa anual Art. 152 LISR (simplificada)
        $tabla = [
            [0,        5_952.84,    0,           1.92],
            [5_952.85, 50_524.92,  114.29,       6.40],
            [50_524.93,88_793.04,  2_966.91,     10.88],
            [88_793.05,103_218.00, 7_130.48,     16.00],
            [103_218.01,123_580.20,9_438.47,     17.92],
            [123_580.21,249_243.48,13_087.37,    21.36],
            [249_243.49,392_841.96,39_929.05,    23.52],
            [392_841.97,750_000.00,73_703.41,    30.00],
            [750_000.01,1_000_000.00,180_850.82, 32.00],
            [1_000_000.01,3_000_000.00,260_850.81,34.00],
            [3_000_000.01,PHP_FLOAT_MAX, 940_850.81,35.00],
        ];

        foreach ($tabla as [$limInf, $limSup, $cuota, $porcentaje]) {
            if ($base >= $limInf && $base <= $limSup) {
                return round($cuota + (($base - $limInf) * ($porcentaje / 100)), 2);
            }
        }

        return 0;
    }
    
    public function misDeclaraciones()
    {
     // Buscamos las declaraciones que pertenecen al usuario logueado
     // (Asumiendo que tienes un modelo Declaracion y la tabla tiene user_id)
     $declaraciones = \App\Models\Declaracion::where('user_id', auth()->id())->get();

     return view('pages.declaraciones', compact('declaraciones'));
    }

    public function store(Request $request)
    {
        
    $request->validate([
        'tipo_declaracion' => ['required', 'in:mensual,anual,diot,complementaria'],
        'ejercicio'        => ['required', 'integer', 'min:2000', 'max:' . (date('Y') + 1)],
    ]);

    $tipo = $request->input('tipo_declaracion');

    // Campos base — no_operacion y estatus los genera el modelo automáticamente
    $datos = [
        'user_id'          => Auth::id(),
        'rfc'              => Auth::user()->rfc ?? $request->rfc,
        'tipo'             => $tipo,               // mensual|anual|diot|complementaria
        'tipo_declaracion' => $request->tipo_presentacion ?? 'normal',
        'ejercicio'        => $request->ejercicio,
        'base_gravable'    => 0,
        'isr_determinado'  => 0,
        'isr_retenido'     => 0,
        'saldo_cargo'      => 0,
        'saldo_favor'      => 0,
        'importe'          => 0,
    ];

    if ($tipo === 'mensual') {
    $ing     = floatval($request->ingresos_cobrados   ?? 0);
    $exen    = floatval($request->ingresos_exentos    ?? 0);
    $ded     = floatval($request->deduc_gastos        ?? 0)
             + floatval($request->deduc_nomina        ?? 0)
             + floatval($request->deduc_otras         ?? 0);
    $base    = max(0, $ing - $exen - $ded);

    // Usar los valores calculados por el JS (ya vienen correctos)
    $isrDet  = floatval($request->isr_determinado     ?? 0);
    $ivaPagar= floatval($request->iva_pagar           ?? 0);
    $saldo   = floatval($request->saldo               ?? 0);
    $cargo   = max(0,  $saldo);
    $favor   = max(0, -$saldo);

    $datos = array_merge($datos, [
        'periodo'              => $request->periodo,
        'ingresos_acumulables' => $ing,
        'ingresos_exentos'     => $exen,
        'base_gravable'        => $base,
        'isr_determinado'      => $isrDet,   // 949.00 ✅
        'isr_retenido'         => floatval($request->isr_retenido ?? 0),
        'saldo_cargo'          => $cargo,     // 439.00 ✅
        'saldo_favor'          => $favor,
        'importe'              => $cargo,
    ]);
 
     
    }  elseif ($tipo === 'anual') {

        $ing  = floatval($request->ingresos_acumulables    ?? 0);
        $exen = floatval($request->ingresos_exentos_anual  ?? 0);
        $ded  = array_sum(array_map(
            fn($k) => floatval($request->input($k, 0)),
            ['ded_honorarios_medicos','ded_gastos_hospitalarios','ded_seguro_medico',
             'ded_colegiaturas','ded_intereses_hipotecarios','ded_donativos',
             'ded_afore','ded_transporte_escolar','ded_otras_anual']
        ));
        $ret  = floatval($request->isr_retenido_anual        ?? 0);
        $prov = floatval($request->pagos_provisionales_anual ?? 0);

        $base   = max(0, $ing - $exen - $ded);
        $isrDet = $this->calcularISR($base);
        $cargo  = max(0, $isrDet - $ret - $prov);
        $favor  = max(0, ($ret + $prov) - $isrDet);

        $datos = array_merge($datos, [
            'periodo'               => 'Enero – Diciembre',
            'tipo_declaracion'      => $request->tipo_presentacion_anual ?? 'normal',
            'ingresos_acumulables'  => $ing,
            'ingresos_exentos'      => $exen,
            // Mapeo a columnas del modelo
            'honorarios_medicos'    => $request->ded_honorarios_medicos    ?? 0,
            'gastos_hospitalarios'  => $request->ded_gastos_hospitalarios  ?? 0,
            'primas_seguro'         => $request->ded_seguro_medico         ?? 0,
            'colegiaturas'          => $request->ded_colegiaturas          ?? 0,
            'intereses_hipotecarios'=> $request->ded_intereses_hipotecarios ?? 0,
            'donativos'             => $request->ded_donativos             ?? 0,
            'base_gravable'         => $base,
            'isr_determinado'       => $isrDet,
            'isr_retenido'          => $ret + $prov,
            'saldo_cargo'           => $cargo,
            'saldo_favor'           => $favor,
            'importe'               => $cargo,
            'fecha_limite'          => $request->ejercicio . '-04-30',
        ]);

    } elseif ($tipo === 'diot') {

        $datos += [
            'periodo'  => $request->periodo_diot,
            'ejercicio'=> $request->ejercicio_diot ?? $request->ejercicio,
            'impuesto' => 'IVA',
        ];

    } elseif ($tipo === 'complementaria') {

        $datos = array_merge($datos, [
            'periodo'                => 'Complementaria',
            'tipo_declaracion'       => 'complementaria',
            'no_operacion_original'  => $request->folio_original,
            'motivo'                 => $request->descripcion_correccion,
        ]);
    }
    
    Declaracion::create($datos);

    return redirect()->route('declaraciones.usuario')
        ->with('success', '¡Declaración presentada correctamente!');
    }

    public function show(Declaracion $declaracion)
   {
    abort_if(Auth::id() !== $declaracion->user_id, 403);
    return view('pages.declaraciones.show', compact('declaracion'));
   }
}