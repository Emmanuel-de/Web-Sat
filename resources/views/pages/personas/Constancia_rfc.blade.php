@extends('layouts.app')

@section('title', 'Constancia de Situación Fiscal - SAT')

@section('content')

<div class="sat-page-header">
    <div class="container-sat">
        <div class="sat-breadcrumb">
            <a href="{{ route('home') }}">Inicio</a>
            <span class="sat-breadcrumb-sep"><i class="fas fa-chevron-right" style="font-size:10px"></i></span>
            <a href="{{ route('personas.index') }}">Personas</a>
            <span class="sat-breadcrumb-sep"><i class="fas fa-chevron-right" style="font-size:10px"></i></span>
            <span>Constancia de Situación Fiscal</span>
        </div>
        <h1 class="sat-page-title"><i class="fas fa-file-alt" style="margin-right:12px"></i>Constancia de Situación Fiscal</h1>
        <p class="sat-page-subtitle">Documento oficial de registro ante el SAT</p>
    </div>
</div>

<section class="sat-section">
    <div class="container-sat">

        {{-- Botones de acción --}}
        <div style="display:flex;gap:12px;justify-content:flex-end;margin-bottom:24px;flex-wrap:wrap">
            <button onclick="window.print()" class="btn-sat-green">
                <i class="fas fa-print"></i> Imprimir
            </button>
        <a href="{{ url('/personas/rfc') }}" class="btn-sat-outline">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>

        {{-- ══════════════════════════════════════════
             DOCUMENTO CONSTANCIA (imprimible)
        ══════════════════════════════════════════ --}}
        <div id="constanciaDoc" style="
            background:#fff;
            border:1px solid #d0d0d0;
            border-radius:8px;
            max-width:860px;
            margin:0 auto;
            padding:48px 56px;
            font-family:'Times New Roman', serif;
            color:#111;
            box-shadow:0 4px 24px rgba(0,0,0,.08);
        ">
            {{-- Encabezado institucional --}}
            <div style="display:flex;align-items:center;justify-content:space-between;
                        border-bottom:3px solid #1a7a42;padding-bottom:20px;margin-bottom:28px">
                <div style="display:flex;align-items:center;gap:16px">
                    <div style="width:56px;height:56px;background:#cc0000;border-radius:6px;
                                display:flex;align-items:center;justify-content:center">
                        <span style="color:#fff;font-weight:900;font-size:18px;font-family:Arial,sans-serif">SAT</span>
                    </div>
                    <div>
                        <p style="font-size:13px;font-weight:700;margin:0;color:#cc0000;text-transform:uppercase;letter-spacing:.5px">
                            Servicio de Administración Tributaria
                        </p>
                        <p style="font-size:11px;color:#555;margin:2px 0 0">
                            Secretaría de Hacienda y Crédito Público
                        </p>
                    </div>
                </div>
                <div style="text-align:right">
                    <p style="font-size:11px;color:#555;margin:0">Folio:</p>
                    <p style="font-size:14px;font-weight:700;margin:2px 0 0;font-family:monospace">
                        {{ str_pad($solicitud->id, 10, '0', STR_PAD_LEFT) }}
                    </p>
                    <p style="font-size:11px;color:#555;margin:4px 0 0">
                        {{ now()->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>

            {{-- Título --}}
            <div style="text-align:center;margin-bottom:32px">
                <h2 style="font-size:18px;font-weight:700;text-transform:uppercase;
                           letter-spacing:1px;margin:0 0 6px">
                    Constancia de Situación Fiscal
                </h2>
                <p style="font-size:13px;color:#555;margin:0">
                    Registro Federal de Contribuyentes — Persona Física
                </p>
            </div>

            {{-- RFC destacado --}}
            <div style="background:#f0faf4;border:2px solid #1a7a42;border-radius:8px;
                        padding:20px 32px;margin-bottom:32px;text-align:center">
                <p style="font-size:12px;color:#555;text-transform:uppercase;letter-spacing:1px;margin:0 0 6px">
                    Registro Federal de Contribuyentes (RFC)
                </p>
                <p style="font-size:30px;font-weight:900;letter-spacing:6px;
                           font-family:monospace;color:#1a7a42;margin:0">
                    {{ $solicitud->rfc }}
                </p>
            </div>

            {{-- Datos del contribuyente --}}
            <table style="width:100%;border-collapse:collapse;font-size:13px;margin-bottom:24px">
                <thead>
                    <tr>
                        <td colspan="2" style="background:#1a7a42;color:#fff;padding:10px 14px;
                                               font-weight:700;font-size:12px;text-transform:uppercase;
                                               letter-spacing:.5px">
                            <i class="fas fa-user" style="margin-right:8px"></i>Datos del Contribuyente
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom:1px solid #e8e8e8">
                        <th style="padding:10px 14px;text-align:left;width:220px;background:#fafafa;font-weight:600;color:#444">
                            Nombre completo
                        </th>
                        <td style="padding:10px 14px">
                            {{ strtoupper($solicitud->nombres . ' ' . $solicitud->primer_apellido . ' ' . $solicitud->segundo_apellido) }}
                        </td>
                    </tr>
                    <tr style="border-bottom:1px solid #e8e8e8">
                        <th style="padding:10px 14px;text-align:left;background:#fafafa;font-weight:600;color:#444">CURP</th>
                        <td style="padding:10px 14px;font-family:monospace;font-size:14px">{{ $solicitud->curp }}</td>
                    </tr>
                    <tr style="border-bottom:1px solid #e8e8e8">
                        <th style="padding:10px 14px;text-align:left;background:#fafafa;font-weight:600;color:#444">Fecha de nacimiento</th>
                        <td style="padding:10px 14px">{{ \Carbon\Carbon::parse($solicitud->fecha_nacimiento)->format('d/m/Y') }}</td>
                    </tr>
                    <tr style="border-bottom:1px solid #e8e8e8">
                        <th style="padding:10px 14px;text-align:left;background:#fafafa;font-weight:600;color:#444">Sexo</th>
                        <td style="padding:10px 14px">{{ $solicitud->sexo }}</td>
                    </tr>
                    <tr style="border-bottom:1px solid #e8e8e8">
                        <th style="padding:10px 14px;text-align:left;background:#fafafa;font-weight:600;color:#444">Estado de nacimiento</th>
                        <td style="padding:10px 14px">{{ $solicitud->estado_nacimiento }}</td>
                    </tr>
                    <tr style="border-bottom:1px solid #e8e8e8">
                        <th style="padding:10px 14px;text-align:left;background:#fafafa;font-weight:600;color:#444">Correo electrónico</th>
                        <td style="padding:10px 14px">{{ $solicitud->email }}</td>
                    </tr>
                    <tr>
                        <th style="padding:10px 14px;text-align:left;background:#fafafa;font-weight:600;color:#444">Teléfono</th>
                        <td style="padding:10px 14px">{{ $solicitud->telefono }}</td>
                    </tr>
                </tbody>
            </table>

            {{-- Domicilio fiscal --}}
            <table style="width:100%;border-collapse:collapse;font-size:13px;margin-bottom:24px">
                <thead>
                    <tr>
                        <td colspan="2" style="background:#1a7a42;color:#fff;padding:10px 14px;
                                               font-weight:700;font-size:12px;text-transform:uppercase;
                                               letter-spacing:.5px">
                            <i class="fas fa-map-marker-alt" style="margin-right:8px"></i>Domicilio Fiscal
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom:1px solid #e8e8e8">
                        <th style="padding:10px 14px;text-align:left;width:220px;background:#fafafa;font-weight:600;color:#444">Calle y número</th>
                        <td style="padding:10px 14px">
                            {{ $solicitud->calle }} {{ $solicitud->no_exterior }}
                            @if($solicitud->no_interior) , Int. {{ $solicitud->no_interior }} @endif
                        </td>
                    </tr>
                    <tr style="border-bottom:1px solid #e8e8e8">
                        <th style="padding:10px 14px;text-align:left;background:#fafafa;font-weight:600;color:#444">Colonia</th>
                        <td style="padding:10px 14px">{{ $solicitud->colonia }}</td>
                    </tr>
                    <tr style="border-bottom:1px solid #e8e8e8">
                        <th style="padding:10px 14px;text-align:left;background:#fafafa;font-weight:600;color:#444">Municipio / Alcaldía</th>
                        <td style="padding:10px 14px">{{ $solicitud->municipio }}</td>
                    </tr>
                    <tr style="border-bottom:1px solid #e8e8e8">
                        <th style="padding:10px 14px;text-align:left;background:#fafafa;font-weight:600;color:#444">Estado</th>
                        <td style="padding:10px 14px">{{ $solicitud->estado }}</td>
                    </tr>
                    <tr>
                        <th style="padding:10px 14px;text-align:left;background:#fafafa;font-weight:600;color:#444">Código Postal</th>
                        <td style="padding:10px 14px">{{ $solicitud->codigo_postal }}</td>
                    </tr>
                </tbody>
            </table>

            {{-- Actividad económica --}}
            <table style="width:100%;border-collapse:collapse;font-size:13px;margin-bottom:32px">
                <thead>
                    <tr>
                        <td colspan="2" style="background:#1a7a42;color:#fff;padding:10px 14px;
                                               font-weight:700;font-size:12px;text-transform:uppercase;
                                               letter-spacing:.5px">
                            <i class="fas fa-briefcase" style="margin-right:8px"></i>Actividad Económica
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom:1px solid #e8e8e8">
                        <th style="padding:10px 14px;text-align:left;width:220px;background:#fafafa;font-weight:600;color:#444">Régimen fiscal</th>
                        <td style="padding:10px 14px">{{ $solicitud->regimen_fiscal }}</td>
                    </tr>
                    <tr style="border-bottom:1px solid #e8e8e8">
                        <th style="padding:10px 14px;text-align:left;background:#fafafa;font-weight:600;color:#444">Actividad principal</th>
                        <td style="padding:10px 14px">{{ $solicitud->actividad_principal }}</td>
                    </tr>
                    <tr>
                        <th style="padding:10px 14px;text-align:left;background:#fafafa;font-weight:600;color:#444">Inicio de actividades</th>
                        <td style="padding:10px 14px">{{ \Carbon\Carbon::parse($solicitud->fecha_inicio_actividades)->format('d/m/Y') }}</td>
                    </tr>
                </tbody>
            </table>

            {{-- Estatus --}}
            <div style="background:#f0faf4;border-left:4px solid #1a7a42;padding:14px 20px;
                        border-radius:0 6px 6px 0;margin-bottom:32px;display:flex;
                        align-items:center;gap:12px">
                <i class="fas fa-check-circle" style="color:#1a7a42;font-size:20px"></i>
                <div>
                    <p style="font-weight:700;margin:0;color:#1a7a42;font-size:14px">Contribuyente Activo</p>
                    <p style="font-size:12px;color:#555;margin:2px 0 0">
                        Inscrito al RFC el {{ \Carbon\Carbon::parse($solicitud->created_at)->format('d/m/Y') }}
                    </p>
                </div>
            </div>

            {{-- Pie de documento --}}
            <div style="border-top:1px solid #d0d0d0;padding-top:16px;text-align:center">
                <p style="font-size:11px;color:#888;margin:0 0 4px">
                    Este documento es una constancia de inscripción al RFC generada electrónicamente.
                </p>
                <p style="font-size:11px;color:#888;margin:0">
                    Servicio de Administración Tributaria — sat.gob.mx
                </p>
            </div>
        </div>

    </div>
</section>

@endsection

@push('styles')
<style>
@media print {
    .sat-page-header,
    .sat-breadcrumb,
    nav, header, footer,
    .btn-sat-green,
    .btn-sat-outline { display: none !important; }

    #constanciaDoc {
        box-shadow: none !important;
        border: none !important;
        padding: 0 !important;
        max-width: 100% !important;
    }

    body { background: #fff !important; }
}
</style>
@endpush