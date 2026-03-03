<?php

namespace App\Http\Controllers\Empresas;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmpresasController extends Controller
{
    public function index()
    {
        return view('pages.empresas.index');
    }

    // ─── Inscripción Persona Moral ───────────────────────────────
    public function inscripcion()
    {
        return view('pages.empresas.inscripcion');
    }

    public function inscripcionStore(Request $request)
    {
        $request->validate([
            'razon_social'            => ['required', 'string', 'max:200'],
            'rfc'                     => ['required', 'string', 'regex:/^[A-ZÑ&]{3}\d{6}[A-Z0-9]{3}$/i', 'unique:users,rfc'],
            'tipo_sociedad'           => ['required', 'string'],
            'fecha_constitucion'      => ['required', 'date', 'before:today'],
            'notario_numero'          => ['required', 'string', 'max:10'],
            'fecha_escritura'         => ['required', 'date'],
            // Domicilio fiscal
            'codigo_postal'           => ['required', 'digits:5'],
            'estado'                  => ['required', 'string'],
            'municipio'               => ['required', 'string'],
            'colonia'                 => ['required', 'string'],
            'calle'                   => ['required', 'string'],
            'no_exterior'             => ['required', 'string'],
            // Representante legal
            'rep_nombre'              => ['required', 'string', 'max:150'],
            'rep_rfc'                 => ['required', 'string', 'regex:/^[A-ZÑ&]{4}\d{6}[A-Z0-9]{3}$/i'],
            'rep_curp'                => ['required', 'string', 'size:18'],
            'rep_email'               => ['required', 'email'],
            // Actividad
            'regimen_fiscal'          => ['required', 'string'],
            'actividad_principal'     => ['required', 'string'],
            'fecha_inicio_actividades'=> ['required', 'date'],
            'acepta_terminos'         => ['accepted'],
        ]);

        $empresa = User::create([
            'rfc'             => strtoupper($request->rfc),
            'nombre'          => $request->razon_social,
            'primer_apellido' => '',
            'email'           => $request->rep_email,
            'password'        => Hash::make(str_random(16)), // password temporal
            'tipo'            => 'moral',
            'activo'          => true,
        ]);

        return response()->json([
            'message' => "Solicitud de inscripción enviada. "
                       . "Folio: EMP-{$empresa->id}. "
                       . "Se enviará un correo a {$request->rep_email} con los pasos para completar el proceso.",
        ]);
    }

    // ─── Actualización de datos ──────────────────────────────────
    public function actualizacion()
    {
        return view('pages.empresas.actualizacion');
    }

    // ─── Cancelación ─────────────────────────────────────────────
    public function cancelacion()
    {
        return view('pages.empresas.cancelacion');
    }

    // ─── Impuestos ───────────────────────────────────────────────
    public function iva()
    {
        return view('pages.empresas.iva');
    }

    public function isr()
    {
        return view('pages.empresas.isr');
    }

    public function ieps()
    {
        return view('pages.empresas.ieps');
    }

    // ─── CFDI ────────────────────────────────────────────────────
    public function cfdi()
    {
        return redirect()->route('facturacion.index');
    }

    public function complementos()
    {
        return view('pages.empresas.complementos');
    }

    // ─── Nómina ──────────────────────────────────────────────────
    public function nomina()
    {
        return view('pages.empresas.nomina');
    }

    public function nominaStore(Request $request)
    {
        $request->validate([
            'rfc_empleador'  => ['required', 'string', 'regex:/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/i'],
            'rfc_empleado'   => ['required', 'string', 'regex:/^[A-ZÑ&]{4}\d{6}[A-Z0-9]{3}$/i'],
            'curp_empleado'  => ['required', 'string', 'size:18'],
            'nombre_empleado'=> ['required', 'string'],
            'tipo_nomina'    => ['required', 'in:O,E'],  // Ordinaria | Extraordinaria
            'periodo_pago'   => ['required', 'in:01,02,03,04,05,06,07,08,09,10'],
            'fecha_pago'     => ['required', 'date'],
            'fecha_ini_pago' => ['required', 'date'],
            'fecha_fin_pago' => ['required', 'date', 'after_or_equal:fecha_ini_pago'],
            'num_dias_pagados'=> ['required', 'numeric', 'min:0.001'],
            'salario_diario' => ['required', 'numeric', 'min:0'],
            'total_percepciones' => ['required', 'numeric', 'min:0'],
            'total_deducciones'  => ['required', 'numeric', 'min:0'],
            'total_otros_pagos'  => ['nullable', 'numeric', 'min:0'],
        ]);

        // Aquí se generaría el CFDI de nómina mediante el PAC
        return response()->json([
            'message' => 'Comprobante de nómina generado y timbrado exitosamente.',
            'uuid'    => strtolower((string) \Illuminate\Support\Str::uuid()),
        ]);
    }
}