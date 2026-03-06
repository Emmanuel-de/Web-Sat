<?php

namespace App\Http\Controllers\Facturacion;

use App\Http\Controllers\Controller;
use App\Models\Factura;
use App\Models\FacturaConcepto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FacturacionController extends Controller
{
    // ─── Índice ──────────────────────────────────────────────────
    public function index()
    {
        $facturas = Auth::check()
            ? Factura::delEmisor(Auth::user()->rfc)->latest('fecha_timbrado')->paginate(20)
            : collect();

        return view('pages.facturacion.index', compact('facturas'));
    }

    public function emitir()
    {
        return view('pages.facturacion.emitir');
    }

    // ─── Timbrar CFDI ────────────────────────────────────────────
    public function store(Request $request)
    {
        $data = $request->validate([
            // Emisor
            'rfc_emisor'        => ['required', 'string', 'regex:/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/i'],
            'nombre_emisor'     => ['required', 'string', 'max:300'],
            'regimen_emisor'    => ['required', 'string'],
            // Receptor
            'rfc_receptor'      => ['required', 'string', 'regex:/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/i'],
            'nombre_receptor'   => ['required', 'string', 'max:300'],
            'cp_receptor'       => ['required', 'digits:5'],
            'regimen_receptor'  => ['required', 'string'],
            'uso_cfdi'          => ['required', 'string', 'size:3'],
            // Comprobante
            'tipo_comprobante'  => ['required', 'in:I,E,T,N'],
            'metodo_pago'       => ['required', 'in:PUE,PPD'],
            'forma_pago'        => ['required', 'string'],
            'moneda'            => ['required', 'in:MXN,USD,EUR'],
            'exportacion'       => ['required', 'in:01,02,03'],
            // Conceptos
            'conceptos'         => ['required', 'array', 'min:1'],
            'conceptos.*.clave_prod'     => ['required', 'string'],
            'conceptos.*.descripcion'    => ['required', 'string', 'max:1000'],
            'conceptos.*.clave_unidad'   => ['required', 'string'],
            'conceptos.*.cantidad'       => ['required', 'numeric', 'min:0.001'],
            'conceptos.*.valor_unitario' => ['required', 'numeric', 'min:0'],
        ]);

        // Calcular totales
        $subtotal = 0;
        $conceptosData = [];

        foreach ($data['conceptos'] as $concepto) {
            $importe   = round($concepto['cantidad'] * $concepto['valor_unitario'], 6);
            $ivaTasa   = 0.16;
            $ivaImporte= round($importe * $ivaTasa, 6);
            $subtotal += $importe;

            $conceptosData[] = [
                'clave_prod_serv'=> $concepto['clave_prod'],
                'clave_unidad'   => $concepto['clave_unidad'],
                'descripcion'    => $concepto['descripcion'],
                'cantidad'       => $concepto['cantidad'],
                'valor_unitario' => $concepto['valor_unitario'],
                'importe'        => $importe,
                'objeto_imp'     => '02',
                'iva_tasa'       => $ivaTasa,
                'iva_importe'    => $ivaImporte,
            ];
        }

        $iva   = round($subtotal * 0.16, 2);
        $total = round($subtotal + $iva, 2);

        DB::beginTransaction();
        try {
            $factura = Factura::create([
                'user_id'         => Auth::id(),
                'rfc_emisor'      => strtoupper($data['rfc_emisor']),
                'nombre_emisor'   => $data['nombre_emisor'],
                'regimen_emisor'  => $data['regimen_emisor'],
                'rfc_receptor'    => strtoupper($data['rfc_receptor']),
                'nombre_receptor' => $data['nombre_receptor'],
                'cp_receptor'     => $data['cp_receptor'],
                'regimen_receptor'=> $data['regimen_receptor'],
                'uso_cfdi'        => $data['uso_cfdi'],
                'tipo_comprobante'=> $data['tipo_comprobante'],
                'metodo_pago'     => $data['metodo_pago'],
                'forma_pago'      => $data['forma_pago'],
                'moneda'          => $data['moneda'],
                'exportacion'     => $data['exportacion'],
                'subtotal'        => $subtotal,
                'iva'             => $iva,
                'total'           => $total,
                'estatus'         => 'vigente',
                // En producción estos campos los retornaría el PAC (Proveedor Autorizado de Certificación)
                'noCertificadoSAT'=> '20001000000300022323',
                'selloCFD'        => substr(md5(uniqid()), 0, 40),
                'selloSAT'        => substr(md5(uniqid()), 0, 40),
            ]);

            foreach ($conceptosData as $c) {
                $factura->conceptos()->create($c);
            }

            DB::commit();

            return response()->json([
                'message' => "CFDI timbrado exitosamente. UUID: {$factura->uuid}",
                'uuid'    => $factura->uuid,
                'folio'   => $factura->folio,
                'total'   => number_format($total, 2),
                'pdf_url' => route('facturacion.pdf', $factura->id),
                'xml_url' => route('facturacion.xml', $factura->id),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al timbrar el CFDI: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ─── Verificar CFDI ──────────────────────────────────────────
    public function verificar()
    {
        return view('pages.facturacion.verificar');
    }

    public function verificarStore(Request $request)
    {
        $request->validate([
            'uuid'         => ['required', 'string', 'regex:/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i'],
            'rfc_emisor'   => ['nullable', 'string'],
            'rfc_receptor' => ['nullable', 'string'],
            'total'        => ['nullable', 'numeric'],
        ]);

        $factura = Factura::where('uuid', strtolower($request->uuid))->first();

        if (! $factura) {
            return response()->json([
                'message'  => 'El CFDI no fue encontrado en el sistema.',
                'estatus'  => 'No encontrado',
                'valido'   => false,
            ]);
        }

        // Validaciones adicionales opcionales
        if ($request->rfc_emisor && strtoupper($request->rfc_emisor) !== $factura->rfc_emisor) {
            return response()->json(['message' => 'El RFC del emisor no coincide.', 'valido' => false]);
        }

        return response()->json([
            'message'       => 'El CFDI es válido y vigente.',
            'valido'        => true,
            'uuid'          => $factura->uuid,
            'rfc_emisor'    => $factura->rfc_emisor,
            'rfc_receptor'  => $factura->rfc_receptor,
            'total'         => number_format($factura->total, 2),
            'estatus'       => $factura->estatus,
            'fecha_timbrado'=> $factura->fecha_timbrado->format('d/m/Y H:i'),
        ]);
    }

    // ─── Cancelar CFDI ───────────────────────────────────────────
    public function cancelar(Factura $factura)
    {
        return view('pages.facturacion.cancelar', compact('factura'));
    }

    public function cancelarStore(Request $request, Factura $factura)
    {
        $request->validate([
            'motivo'        => ['required', 'in:01,02,03,04'],
            'uuid_sustituto'=> ['nullable', 'string', 'uuid'],
        ]);

        if ($factura->estatus !== 'vigente') {
            return response()->json([
                'message' => 'Este CFDI ya fue cancelado anteriormente.',
            ], 422);
        }

        // Motivo 01 requiere UUID sustituto
        if ($request->motivo === '01' && ! $request->uuid_sustituto) {
            return response()->json([
                'message' => 'El motivo 01 requiere proporcionar el UUID del CFDI que sustituye.',
            ], 422);
        }

        $factura->update([
            'estatus'          => 'cancelado',
            'motivo_cancelacion'=> $request->motivo,
            'uuid_sustituto'   => $request->uuid_sustituto,
            'fecha_cancelacion' => now(),
        ]);

        return response()->json([
            'message' => 'CFDI cancelado exitosamente. UUID: ' . $factura->uuid,
        ]);
    }

    public function misFacturas()
    {
     $facturas = Auth::check()
        ? Factura::where('user_id', Auth::id())->latest('fecha_timbrado')->paginate(20)
        : collect();

     return view('pages.misfacturacion', compact('facturas'));
    }

    // ─── Descarga PDF ────────────────────────────────────────────
    public function pdf(Factura $factura)
    {
        // En producción generarías el PDF real:
        // $pdf = PDF::loadView('pdfs.factura', compact('factura'));
        // return $pdf->download("CFDI_{$factura->uuid}.pdf");

        return response()->json(['message' => 'PDF no disponible en entorno de desarrollo.'], 501);
    }

    // ─── Descarga XML ────────────────────────────────────────────
    public function xml(Factura $factura)
    {
        // En producción retornarías el XML timbrado almacenado
        $xml = $this->generarXmlCfdi($factura);

        return response($xml, 200, [
            'Content-Type'        => 'application/xml',
            'Content-Disposition' => 'attachment; filename="CFDI_' . $factura->uuid . '.xml"',
        ]);
    }

    // ─── Helpers privados ────────────────────────────────────────
        private function generarXmlCfdi(Factura $factura): string
    {
        $conceptosXml = '';
        foreach ($factura->conceptos as $c) {
            $conceptosXml .= <<<XML

        <cfdi:Concepto ClaveProdServ="{$c->clave_prod_serv}"
                       ClaveUnidad="{$c->clave_unidad}"
                       Descripcion="{$c->descripcion}"
                       Cantidad="{$c->cantidad}"
                       ValorUnitario="{$c->valor_unitario}"
                       Importe="{$c->importe}"
                       ObjetoImp="{$c->objeto_imp}">
            <cfdi:Impuestos>
                <cfdi:Traslados>
                    <cfdi:Traslado Base="{$c->importe}" Impuesto="002" TipoFactor="Tasa"
                                   TasaOCuota="{$c->iva_tasa}" Importe="{$c->iva_importe}"/>
                </cfdi:Traslados>
            </cfdi:Impuestos>
        </cfdi:Concepto>
XML;
        }

        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/4"
                  Version="4.0"
                  UUID="{$factura->uuid}"
                  Folio="{$factura->folio}"
                  Fecha="{$factura->fecha_timbrado->toIso8601String()}"
                  TipoDeComprobante="{$factura->tipo_comprobante}"
                  MetodoPago="{$factura->metodo_pago}"
                  FormaPago="{$factura->forma_pago}"
                  Moneda="{$factura->moneda}"
                  Exportacion="{$factura->exportacion}"
                  SubTotal="{$factura->subtotal}"
                  Total="{$factura->total}"
                  NoCertificado="{$factura->noCertificadoSAT}"
                  Sello="{$factura->selloCFD}">
    <cfdi:Emisor Rfc="{$factura->rfc_emisor}"
                 Nombre="{$factura->nombre_emisor}"
                 RegimenFiscal="{$factura->regimen_emisor}"/>
    <cfdi:Receptor Rfc="{$factura->rfc_receptor}"
                   Nombre="{$factura->nombre_receptor}"
                   DomicilioFiscalReceptor="{$factura->cp_receptor}"
                   RegimenFiscalReceptor="{$factura->regimen_receptor}"
                   UsoCFDI="{$factura->uso_cfdi}"/>
    <cfdi:Conceptos>{$conceptosXml}
    </cfdi:Conceptos>
    <cfdi:Impuestos TotalImpuestosTrasladados="{$factura->iva}">
        <cfdi:Traslados>
            <cfdi:Traslado Impuesto="002" TipoFactor="Tasa"
                           TasaOCuota="0.160000" Importe="{$factura->iva}"/>
        </cfdi:Traslados>
    </cfdi:Impuestos>
    <cfdi:Complemento>
        <tfd:TimbreFiscalDigital xmlns:tfd="http://www.sat.gob.mx/TimbreFiscalDigital"
                                  Version="1.1"
                                  UUID="{$factura->uuid}"
                                  FechaTimbrado="{$factura->fecha_timbrado->toIso8601String()}"
                                  NoCertificadoSAT="{$factura->noCertificadoSAT}"
                                  SelloSAT="{$factura->selloSAT}"
                                  SelloCFD="{$factura->selloCFD}"/>
    </cfdi:Complemento>
</cfdi:Comprobante>
XML;
    }


}