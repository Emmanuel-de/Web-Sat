@extends('layouts.dashboard-layout')

@section('title', 'Mi Constancia Fiscal')

@push('styles')
<style>
/* ── Page header ──────────────────────────────── */
.page-title{font-size:26px;font-weight:700;color:white;margin-bottom:4px}
.page-sub{font-size:14px;color:rgba(255,255,255,.45);margin-bottom:28px}

/* ── Action bar ───────────────────────────────── */
.action-bar{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:24px;flex-wrap:wrap}
.action-bar-left{display:flex;gap:10px;flex-wrap:wrap}
.btn-action{display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:8px;font-size:13px;font-weight:700;text-decoration:none;cursor:pointer;border:none;transition:all .2s}
.btn-action.green{background:#16a34a;color:white}
.btn-action.green:hover{background:#15803d}
.btn-action.outline{background:transparent;color:rgba(255,255,255,.7);border:1px solid rgba(255,255,255,.15)}
.btn-action.outline:hover{background:rgba(255,255,255,.06);color:white}
.btn-action.blue{background:rgba(30,100,220,.8);color:white}
.btn-action.blue:hover{background:rgba(30,100,220,1)}

/* ── Status banner ────────────────────────────── */
.status-banner{border-radius:14px;padding:18px 24px;display:flex;align-items:center;gap:14px;margin-bottom:24px}
.status-banner.activo{background:linear-gradient(135deg,#0d3a24,#0a2f1e);border:1px solid rgba(0,150,80,.3)}
.status-banner.inactivo{background:linear-gradient(135deg,#3b0a0a,#2d0606);border:1px solid rgba(220,30,30,.3)}
.status-banner.pendiente{background:linear-gradient(135deg,#7c2d12,#6b2510);border:1px solid rgba(200,80,20,.3)}
.sbanner-icon{width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0}
.sbanner-icon.activo{background:rgba(74,222,128,.15);color:#4ade80}
.sbanner-icon.inactivo{background:rgba(248,113,113,.15);color:#f87171}
.sbanner-icon.pendiente{background:rgba(251,146,60,.15);color:#fb923c}
.sbanner-text h3{font-size:16px;font-weight:700;color:white;margin:0 0 3px}
.sbanner-text p{font-size:13px;color:rgba(255,255,255,.5);margin:0}
.sbanner-badge{margin-left:auto;font-size:12px;font-weight:700;padding:5px 14px;border-radius:20px;flex-shrink:0}
.sbanner-badge.activo{background:rgba(74,222,128,.15);color:#4ade80;border:1px solid rgba(74,222,128,.2)}
.sbanner-badge.inactivo{background:rgba(248,113,113,.15);color:#f87171;border:1px solid rgba(248,113,113,.2)}
.sbanner-badge.pendiente{background:rgba(251,146,60,.15);color:#fb923c;border:1px solid rgba(251,146,60,.2)}

/* ── Document wrapper ─────────────────────────── */
.doc-wrapper{background:#0d1520;border:1px solid rgba(255,255,255,.07);border-radius:14px;padding:24px;margin-bottom:20px}
.doc-inner{background:#ffffff;border-radius:10px;padding:48px 52px;font-family:'Georgia',serif;color:#111;position:relative;overflow:hidden}
.doc-watermark{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%) rotate(-35deg);font-size:80px;font-weight:900;color:rgba(26,122,66,.04);white-space:nowrap;pointer-events:none;letter-spacing:8px;font-family:Arial,sans-serif}

/* ── Doc header ───────────────────────────────── */
.doc-header{display:flex;align-items:center;justify-content:space-between;border-bottom:3px solid #1a7a42;padding-bottom:20px;margin-bottom:28px}
.doc-logo{display:flex;align-items:center;gap:14px}
.doc-logo-box{width:52px;height:52px;background:#cc0000;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.doc-logo-box span{color:#fff;font-weight:900;font-size:17px;font-family:Arial,sans-serif}
.doc-logo-text p{margin:0}
.doc-logo-text .inst{font-size:12px;font-weight:700;color:#cc0000;text-transform:uppercase;letter-spacing:.5px}
.doc-logo-text .dep{font-size:11px;color:#666;margin-top:2px !important}
.doc-folio{text-align:right}
.doc-folio .folio-label{font-size:11px;color:#777;margin:0;font-family:Arial,sans-serif}
.doc-folio .folio-num{font-size:13px;font-weight:700;font-family:'Courier New',monospace;color:#111;margin:2px 0 0}
.doc-folio .folio-date{font-size:11px;color:#777;margin:3px 0 0}

/* ── Doc title ────────────────────────────────── */
.doc-title-block{text-align:center;margin-bottom:28px}
.doc-title-block h2{font-size:17px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;margin:0 0 5px;color:#111}
.doc-title-block p{font-size:12px;color:#666;margin:0;font-family:Arial,sans-serif}

/* ── RFC highlight ────────────────────────────── */
.rfc-box{background:#f0faf4;border:2px solid #1a7a42;border-radius:8px;padding:18px 28px;margin-bottom:28px;text-align:center}
.rfc-box .rfc-label{font-size:11px;color:#555;text-transform:uppercase;letter-spacing:1.5px;margin:0 0 6px;font-family:Arial,sans-serif}
.rfc-box .rfc-val{font-size:28px;font-weight:900;letter-spacing:8px;font-family:'Courier New',monospace;color:#1a7a42;margin:0}

/* ── Data tables ──────────────────────────────── */
.doc-table{width:100%;border-collapse:collapse;font-size:13px;margin-bottom:22px}
.doc-table .section-head td{background:#1a7a42;color:#fff;padding:9px 13px;font-weight:700;font-size:11px;text-transform:uppercase;letter-spacing:.5px;font-family:Arial,sans-serif}
.doc-table .section-head td i{margin-right:7px}
.doc-table tbody tr{border-bottom:1px solid #ebebeb}
.doc-table tbody tr:last-child{border-bottom:none}
.doc-table th{padding:9px 13px;text-align:left;width:200px;background:#f9f9f9;font-weight:600;color:#444;font-family:Arial,sans-serif;font-size:12px}
.doc-table td{padding:9px 13px;color:#222;font-family:Arial,sans-serif;font-size:13px}
.doc-table .mono{font-family:'Courier New',monospace;font-size:13px}

/* ── Estatus block ────────────────────────────── */
.doc-status{background:#f0faf4;border-left:4px solid #1a7a42;padding:13px 18px;border-radius:0 6px 6px 0;margin-bottom:28px;display:flex;align-items:center;gap:12px}
.doc-status i{color:#1a7a42;font-size:18px;flex-shrink:0}
.doc-status .est-title{font-weight:700;margin:0;color:#1a7a42;font-size:13px;font-family:Arial,sans-serif}
.doc-status .est-sub{font-size:11px;color:#555;margin:2px 0 0;font-family:Arial,sans-serif}

/* ── QR + footer ──────────────────────────────── */
.doc-footer-row{display:flex;align-items:flex-end;justify-content:space-between;gap:20px;border-top:1px solid #ddd;padding-top:18px}
.doc-footer-text p{font-size:10px;color:#999;margin:0 0 2px;font-family:Arial,sans-serif}
.qr-placeholder{width:72px;height:72px;border:2px solid #ddd;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.qr-placeholder svg{width:56px;height:56px;opacity:.25}

/* ── No-data state ────────────────────────────── */
.no-constancia{text-align:center;padding:52px 20px}
.no-constancia-icon{width:80px;height:80px;border-radius:20px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);display:flex;align-items:center;justify-content:center;font-size:32px;color:rgba(255,255,255,.2);margin:0 auto 20px}
.no-constancia-title{font-size:18px;font-weight:700;color:white;margin-bottom:8px}
.no-constancia-sub{font-size:14px;color:rgba(255,255,255,.4);margin-bottom:28px;max-width:380px;margin-left:auto;margin-right:auto}

/* ── Print styles ─────────────────────────────── */
@media print {
    .page-title,.page-sub,.action-bar,.status-banner,
    nav,header,footer,.dashboard-sidebar,.doc-wrapper > *:not(.doc-inner){
        display:none !important;
    }
    .doc-wrapper{background:transparent !important;border:none !important;padding:0 !important}
    .doc-inner{box-shadow:none !important;border-radius:0 !important;padding:28px !important}
    body{background:#fff !important}
}

@media(max-width:700px){
    .doc-inner{padding:28px 20px}
    .doc-header{flex-direction:column;align-items:flex-start;gap:12px}
    .doc-folio{text-align:left}
    .doc-footer-row{flex-direction:column;align-items:flex-start}
}
</style>
@endpush

@section('content')

@php
 /**
  * Obtiene la solicitud/constancia más reciente del usuario autenticado.
  * Ajusta el modelo y campos según tu implementación real.
  */
 $user      = Auth::user();
 $rfc       = strtoupper($user->rfc ?? '');
 // Intenta buscar en tu modelo de solicitudes (ajusta el namespace/modelo que uses)
 $solicitud = null;
 if ($rfc && class_exists(\App\Models\SolicitudRfc::class)) {
     $solicitud = \App\Models\SolicitudRfc::where('rfc', $rfc)->latest()->first();
 }
 // Fallback: construir datos desde el propio usuario
 if (!$solicitud) {
     $solicitud = (object)[
         'id'                       => $user->id ?? 1,
         'rfc'                      => $rfc,
         'nombres'                  => $user->nombres ?? '',
         'primer_apellido'          => $user->primer_apellido ?? '',
         'segundo_apellido'         => $user->segundo_apellido ?? '',
         'curp'                     => $user->curp ?? '—',
         'fecha_nacimiento'         => $user->fecha_nacimiento ?? null,
         'sexo'                     => $user->sexo ?? '—',
         'estado_nacimiento'        => $user->estado_nacimiento ?? '—',
         'email'                    => $user->email ?? '—',
         'telefono'                 => $user->telefono ?? '—',
         'calle'                    => $user->calle ?? '—',
         'no_exterior'              => $user->no_exterior ?? '',
         'no_interior'              => $user->no_interior ?? '',
         'colonia'                  => $user->colonia ?? '—',
         'municipio'                => $user->municipio ?? '—',
         'estado'                   => $user->estado ?? '—',
         'codigo_postal'            => $user->codigo_postal ?? '—',
         'regimen_fiscal'           => $user->regimen_fiscal ?? '—',
         'actividad_principal'      => $user->actividad_principal ?? '—',
         'fecha_inicio_actividades' => $user->fecha_inicio_actividades ?? now(),
         'created_at'               => $user->created_at ?? now(),
         'estatus'                  => 'activo',
     ];
 }

 $nombreCompleto = strtoupper(trim(
     ($solicitud->nombres ?? '') . ' ' .
     ($solicitud->primer_apellido ?? '') . ' ' .
     ($solicitud->segundo_apellido ?? '')
 ));
 $folio = str_pad($solicitud->id, 10, '0', STR_PAD_LEFT);

 $estatusMap = [
     'activo'    => ['label' => 'Activo',    'class' => 'activo',    'icon' => 'fa-check-circle'],
     'inactivo'  => ['label' => 'Inactivo',  'class' => 'inactivo',  'icon' => 'fa-times-circle'],
     'pendiente' => ['label' => 'Pendiente', 'class' => 'pendiente', 'icon' => 'fa-clock'],
 ];
 $estatus = $estatusMap[$solicitud->estatus ?? 'activo'] ?? $estatusMap['activo'];
@endphp

<div class="page-title">Mi Constancia Fiscal</div>
<div class="page-sub">Documento oficial de inscripción al Registro Federal de Contribuyentes.</div>

{{-- ── Barra de acciones ─────────────────────── --}}
<div class="action-bar">
 <div class="action-bar-left">
  <button onclick="window.print()" class="btn-action green">
   <i class="fas fa-print"></i> Imprimir
  </button>
  <button onclick="descargarPDF()" class="btn-action blue">
   <i class="fas fa-file-pdf"></i> Descargar PDF
  </button>
 </div>
 <a href="{{ route('dashboard') }}" class="btn-action outline">
  <i class="fas fa-arrow-left"></i> Volver al inicio
 </a>
</div>

{{-- ── Banner de estatus ─────────────────────── --}}
<div class="status-banner {{ $estatus['class'] }}">
 <div class="sbanner-icon {{ $estatus['class'] }}">
  <i class="fas {{ $estatus['icon'] }}"></i>
 </div>
 <div class="sbanner-text">
  <h3>Contribuyente {{ $estatus['label'] }}</h3>
  <p>RFC <strong style="color:white">{{ $rfc }}</strong> · Inscrito el {{ \Carbon\Carbon::parse($solicitud->created_at)->format('d/m/Y') }}</p>
 </div>
 <div class="sbanner-badge {{ $estatus['class'] }}">
  <i class="fas {{ $estatus['icon'] }}" style="margin-right:5px;font-size:10px"></i>
  {{ strtoupper($estatus['label']) }}
 </div>
</div>

{{-- ── Documento imprimible ──────────────────── --}}
<div class="doc-wrapper">
 <div class="doc-inner" id="constanciaDoc">

  {{-- Marca de agua --}}
  <div class="doc-watermark">SAT</div>

  {{-- Encabezado --}}
  <div class="doc-header">
   <div class="doc-logo">
    <div class="doc-logo-box"><span>SAT</span></div>
    <div class="doc-logo-text">
     <p class="inst">Servicio de Administración Tributaria</p>
     <p class="dep">Secretaría de Hacienda y Crédito Público</p>
    </div>
   </div>
   <div class="doc-folio">
    <p class="folio-label">Folio:</p>
    <p class="folio-num">{{ $folio }}</p>
    <p class="folio-date">{{ now()->format('d/m/Y H:i') }}</p>
   </div>
  </div>

  {{-- Título --}}
  <div class="doc-title-block">
   <h2>Constancia de Situación Fiscal</h2>
   <p>Registro Federal de Contribuyentes — Persona Física</p>
  </div>

  {{-- RFC destacado --}}
  <div class="rfc-box">
   <p class="rfc-label">Registro Federal de Contribuyentes (RFC)</p>
   <p class="rfc-val">{{ $solicitud->rfc }}</p>
  </div>

  {{-- Datos del contribuyente --}}
  <table class="doc-table">
   <thead>
    <tr class="section-head">
     <td colspan="2"><i class="fas fa-user"></i>Datos del Contribuyente</td>
    </tr>
   </thead>
   <tbody>
    <tr>
     <th>Nombre completo</th>
     <td>{{ $nombreCompleto ?: '—' }}</td>
    </tr>
    <tr>
     <th>CURP</th>
     <td class="mono">{{ $solicitud->curp ?? '—' }}</td>
    </tr>
    <tr>
     <th>Fecha de nacimiento</th>
     <td>
      @if($solicitud->fecha_nacimiento)
       {{ \Carbon\Carbon::parse($solicitud->fecha_nacimiento)->format('d/m/Y') }}
      @else —
      @endif
     </td>
    </tr>
    <tr>
     <th>Sexo</th>
     <td>{{ $solicitud->sexo ?? '—' }}</td>
    </tr>
    <tr>
     <th>Estado de nacimiento</th>
     <td>{{ $solicitud->estado_nacimiento ?? '—' }}</td>
    </tr>
    <tr>
     <th>Correo electrónico</th>
     <td>{{ $solicitud->email ?? '—' }}</td>
    </tr>
    <tr>
     <th>Teléfono</th>
     <td>{{ $solicitud->telefono ?? '—' }}</td>
    </tr>
   </tbody>
  </table>

  {{-- Domicilio fiscal --}}
  <table class="doc-table">
   <thead>
    <tr class="section-head">
     <td colspan="2"><i class="fas fa-map-marker-alt"></i>Domicilio Fiscal</td>
    </tr>
   </thead>
   <tbody>
    <tr>
     <th>Calle y número</th>
     <td>
      {{ $solicitud->calle ?? '—' }}
      @if(!empty($solicitud->no_exterior)) {{ $solicitud->no_exterior }} @endif
      @if(!empty($solicitud->no_interior)) , Int. {{ $solicitud->no_interior }} @endif
     </td>
    </tr>
    <tr>
     <th>Colonia</th>
     <td>{{ $solicitud->colonia ?? '—' }}</td>
    </tr>
    <tr>
     <th>Municipio / Alcaldía</th>
     <td>{{ $solicitud->municipio ?? '—' }}</td>
    </tr>
    <tr>
     <th>Estado</th>
     <td>{{ $solicitud->estado ?? '—' }}</td>
    </tr>
    <tr>
     <th>Código Postal</th>
     <td>{{ $solicitud->codigo_postal ?? '—' }}</td>
    </tr>
   </tbody>
  </table>

  {{-- Actividad económica --}}
  <table class="doc-table">
   <thead>
    <tr class="section-head">
     <td colspan="2"><i class="fas fa-briefcase"></i>Actividad Económica</td>
    </tr>
   </thead>
   <tbody>
    <tr>
     <th>Régimen fiscal</th>
     <td>{{ $solicitud->regimen_fiscal ?? '—' }}</td>
    </tr>
    <tr>
     <th>Actividad principal</th>
     <td>{{ $solicitud->actividad_principal ?? '—' }}</td>
    </tr>
    <tr>
     <th>Inicio de actividades</th>
     <td>
      @if($solicitud->fecha_inicio_actividades)
       {{ \Carbon\Carbon::parse($solicitud->fecha_inicio_actividades)->format('d/m/Y') }}
      @else —
      @endif
     </td>
    </tr>
   </tbody>
  </table>

  {{-- Estatus contribuyente --}}
  <div class="doc-status">
   <i class="fas fa-check-circle"></i>
   <div>
    <p class="est-title">Contribuyente {{ $estatus['label'] }}</p>
    <p class="est-sub">Inscrito al RFC el {{ \Carbon\Carbon::parse($solicitud->created_at)->format('d/m/Y') }}</p>
   </div>
  </div>

  {{-- Footer del documento --}}
  <div class="doc-footer-row">
   <div class="doc-footer-text">
    <p>Este documento es una constancia de inscripción al RFC generada electrónicamente.</p>
    <p>Puede verificarse en <strong>sat.gob.mx</strong> con el folio <strong>{{ $folio }}</strong>.</p>
    <p>Servicio de Administración Tributaria — sat.gob.mx</p>
   </div>
   {{-- QR decorativo (puedes reemplazar por un QR real con tu folio) --}}
   <div class="qr-placeholder" title="Código de verificación">
    <svg viewBox="0 0 29 29" xmlns="http://www.w3.org/2000/svg" fill="#1a7a42">
     <rect x="0" y="0" width="7" height="7"/><rect x="9" y="0" width="1" height="1"/><rect x="11" y="0" width="3" height="1"/>
     <rect x="15" y="0" width="1" height="1"/><rect x="22" y="0" width="7" height="7"/><rect x="1" y="1" width="5" height="5" fill="white"/>
     <rect x="2" y="2" width="3" height="3"/><rect x="23" y="1" width="5" height="5" fill="white"/><rect x="24" y="2" width="3" height="3"/>
     <rect x="0" y="9" width="1" height="3"/><rect x="4" y="9" width="2" height="1"/><rect x="9" y="9" width="3" height="3"/>
     <rect x="14" y="9" width="1" height="1"/><rect x="16" y="9" width="2" height="1"/><rect x="22" y="9" width="5" height="1"/>
     <rect x="0" y="22" width="7" height="7"/><rect x="1" y="23" width="5" height="5" fill="white"/><rect x="2" y="24" width="3" height="3"/>
     <rect x="9" y="22" width="3" height="1"/><rect x="14" y="22" width="2" height="2"/><rect x="18" y="22" width="4" height="1"/>
     <rect x="24" y="22" width="5" height="3"/><rect x="9" y="25" width="1" height="4"/><rect x="12" y="25" width="2" height="4"/>
     <rect x="16" y="25" width="3" height="2"/><rect x="21" y="25" width="3" height="4"/><rect x="26" y="26" width="3" height="3"/>
    </svg>
   </div>
  </div>

 </div>{{-- /doc-inner --}}
</div>{{-- /doc-wrapper --}}

@endsection

@push('scripts')
<script>
function descargarPDF() {
    // Implementación básica via print-to-PDF del navegador
    // Si tienes dompdf o similar instalado, puedes hacer una petición a un endpoint
    const btn = document.querySelectorAll('.btn-action');
    btn.forEach(b => b.style.display = 'none');
    window.print();
    setTimeout(() => btn.forEach(b => b.style.display = ''), 500);
}
</script>
@endpush