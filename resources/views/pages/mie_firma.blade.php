@extends('layouts.dashboard-layout')

@section('title', 'Mi e.firma')

@push('styles')
<style>
/* ── Page header ──────────────────────────────── */
.page-title{font-size:26px;font-weight:700;color:white;margin-bottom:4px}
.page-sub{font-size:14px;color:rgba(255,255,255,.45);margin-bottom:28px}

/* ── Status banner ────────────────────────────── */
.efirma-banner{border-radius:14px;padding:22px 26px;display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:28px;flex-wrap:wrap}
.efirma-banner.vigente{background:linear-gradient(135deg,#0d3a24 0%,#0a2f1e 100%);border:1px solid rgba(0,150,80,.3)}
.efirma-banner.pendiente{background:linear-gradient(135deg,#7c2d12 0%,#6b2510 100%);border:1px solid rgba(200,80,20,.3)}
.efirma-banner.vencido{background:linear-gradient(135deg,#3b0a0a 0%,#2d0606 100%);border:1px solid rgba(220,30,30,.3)}
.efirma-banner.sin-registro{background:linear-gradient(135deg,#1a2030 0%,#161c28 100%);border:1px solid rgba(255,255,255,.07)}
.banner-left{display:flex;align-items:center;gap:16px}
.banner-icon{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0}
.banner-icon.vigente{background:rgba(74,222,128,.15);color:#4ade80}
.banner-icon.pendiente{background:rgba(251,146,60,.15);color:#fb923c}
.banner-icon.vencido{background:rgba(248,113,113,.15);color:#f87171}
.banner-icon.sin-registro{background:rgba(255,255,255,.07);color:rgba(255,255,255,.4)}
.banner-text h2{font-size:18px;font-weight:700;color:white;margin:0 0 4px}
.banner-text p{font-size:13px;color:rgba(255,255,255,.5);margin:0}
.banner-actions{display:flex;gap:10px;flex-wrap:wrap}
.btn-primary{display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:8px;font-size:13px;font-weight:700;text-decoration:none;transition:all .2s;cursor:pointer;border:none}
.btn-primary.green{background:#16a34a;color:white}
.btn-primary.green:hover{background:#15803d}
.btn-primary.outline{background:transparent;color:rgba(255,255,255,.7);border:1px solid rgba(255,255,255,.15)}
.btn-primary.outline:hover{background:rgba(255,255,255,.06);color:white}
.btn-primary.red{background:#dc2626;color:white}
.btn-primary.red:hover{background:#b91c1c}

/* ── Grid ─────────────────────────────────────── */
.efirma-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px}
.efirma-full{grid-column:1/-1}

/* ── Cards ────────────────────────────────────── */
.info-card{background:#0d1520;border:1px solid rgba(255,255,255,.07);border-radius:14px;padding:22px}
.card-header{display:flex;align-items:center;gap:10px;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid rgba(255,255,255,.06)}
.card-header-icon{width:34px;height:34px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:14px}
.card-header-icon.blue{background:rgba(30,100,220,.2);color:#60a5fa}
.card-header-icon.green{background:rgba(0,150,80,.2);color:#4ade80}
.card-header-icon.orange{background:rgba(200,80,20,.2);color:#fb923c}
.card-header-icon.purple{background:rgba(120,60,220,.2);color:#c084fc}
.card-title{font-size:14px;font-weight:700;color:white}

/* ── Data rows ────────────────────────────────── */
.data-row{display:flex;align-items:flex-start;justify-content:space-between;padding:10px 0;border-bottom:1px solid rgba(255,255,255,.04)}
.data-row:last-child{border-bottom:none}
.data-label{font-size:12px;color:rgba(255,255,255,.4);font-weight:600;text-transform:uppercase;letter-spacing:.4px;flex-shrink:0;padding-top:1px}
.data-value{font-size:13px;font-weight:600;color:white;text-align:right;word-break:break-all}
.data-value.mono{font-family:'Courier New',monospace;font-size:12px;color:#60a5fa}
.data-value.green{color:#4ade80}
.data-value.orange{color:#fb923c}
.data-value.red{color:#f87171}
.data-value.muted{color:rgba(255,255,255,.35);font-weight:400}

/* ── Status badge ─────────────────────────────── */
.status-badge{display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px}
.status-badge.vigente{background:rgba(74,222,128,.15);color:#4ade80}
.status-badge.pendiente{background:rgba(251,146,60,.15);color:#fb923c}
.status-badge.vencido{background:rgba(248,113,113,.15);color:#f87171}
.status-badge.cancelado{background:rgba(150,150,150,.12);color:rgba(255,255,255,.4)}
.status-dot{width:6px;height:6px;border-radius:50%;background:currentColor}

/* ── Vigencia bar ─────────────────────────────── */
.vigencia-wrap{margin-top:14px}
.vigencia-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:8px}
.vigencia-label{font-size:12px;color:rgba(255,255,255,.4);font-weight:600;text-transform:uppercase;letter-spacing:.4px}
.vigencia-pct{font-size:13px;font-weight:700;color:white}
.vigencia-bar{height:8px;background:rgba(255,255,255,.07);border-radius:4px;overflow:hidden}
.vigencia-fill{height:100%;border-radius:4px;transition:width 1s ease}
.vigencia-fill.good{background:linear-gradient(90deg,#16a34a,#4ade80)}
.vigencia-fill.warn{background:linear-gradient(90deg,#d97706,#fb923c)}
.vigencia-fill.bad{background:linear-gradient(90deg,#dc2626,#f87171)}
.vigencia-dates{display:flex;justify-content:space-between;margin-top:8px;font-size:11px;color:rgba(255,255,255,.3)}

/* ── Cita card ────────────────────────────────── */
.cita-item{display:flex;align-items:center;gap:14px;padding:14px;background:rgba(255,255,255,.03);border-radius:10px;border:1px solid rgba(255,255,255,.06)}
.cita-icon{width:40px;height:40px;border-radius:10px;background:rgba(96,165,250,.12);color:#60a5fa;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0}
.cita-info{flex:1}
.cita-modulo{font-size:14px;font-weight:700;color:white;margin-bottom:3px}
.cita-detail{font-size:12px;color:rgba(255,255,255,.4)}
.cita-date{text-align:right;flex-shrink:0}
.cita-fecha{font-size:15px;font-weight:700;color:#60a5fa}
.cita-hora{font-size:12px;color:rgba(255,255,255,.35);margin-top:2px}

/* ── No-registro state ─────────────────────────── */
.no-efirma-wrap{text-align:center;padding:48px 20px}
.no-efirma-icon{width:80px;height:80px;border-radius:20px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);display:flex;align-items:center;justify-content:center;font-size:32px;color:rgba(255,255,255,.2);margin:0 auto 20px}
.no-efirma-title{font-size:18px;font-weight:700;color:white;margin-bottom:8px}
.no-efirma-sub{font-size:14px;color:rgba(255,255,255,.4);margin-bottom:28px;max-width:380px;margin-left:auto;margin-right:auto}

/* ── History table ────────────────────────────── */
.hist-table{width:100%;border-collapse:collapse}
.hist-table th{font-size:11px;color:rgba(255,255,255,.35);font-weight:700;text-transform:uppercase;letter-spacing:.4px;padding:10px 14px;text-align:left;border-bottom:1px solid rgba(255,255,255,.06)}
.hist-table td{font-size:13px;color:rgba(255,255,255,.7);padding:12px 14px;border-bottom:1px solid rgba(255,255,255,.04)}
.hist-table tr:last-child td{border-bottom:none}
.hist-table tr:hover td{background:rgba(255,255,255,.02)}

/* ── Alert box ────────────────────────────────── */
.alert-box{display:flex;align-items:flex-start;gap:12px;padding:14px 16px;border-radius:10px;margin-bottom:20px}
.alert-box.warn{background:rgba(251,146,60,.08);border:1px solid rgba(251,146,60,.2)}
.alert-box.info{background:rgba(96,165,250,.08);border:1px solid rgba(96,165,250,.15)}
.alert-box i{font-size:15px;margin-top:1px;flex-shrink:0}
.alert-box.warn i{color:#fb923c}
.alert-box.info i{color:#60a5fa}
.alert-box p{font-size:13px;color:rgba(255,255,255,.6);margin:0;line-height:1.5}
.alert-box p strong{color:white}

/* ── Responsive ───────────────────────────────── */
@media(max-width:900px){.efirma-grid{grid-template-columns:1fr}}
@media(max-width:640px){.efirma-banner{flex-direction:column;align-items:flex-start}.banner-actions{width:100%}.banner-actions .btn-primary{flex:1;justify-content:center}}
</style>
@endpush

@section('content')

<div class="page-title">Mi e.firma</div>
<div class="page-sub">Gestiona tu Firma Electrónica Avanzada y consulta el estado de tu certificado.</div>

{{-- ── Mensajes de sesión ─────────────────────── --}}
@if(session('success'))
 <div class="alert-box info" style="margin-bottom:20px">
  <i class="fas fa-check-circle"></i>
  <p>{!! session('success') !!}</p>
 </div>
@endif

@if(session('error') || session('error_verificacion'))
 <div class="alert-box warn" style="margin-bottom:20px">
  <i class="fas fa-exclamation-triangle"></i>
  <p>{!! session('error') ?? session('error_verificacion') !!}</p>
 </div>
@endif

@php
 /**
  * Busca la e.firma más reciente del usuario autenticado.
  * Si el modelo EFirma tiene relación con users por RFC, ajusta esta consulta.
  */
 $rfc = strtoupper(Auth::user()->rfc ?? '');
 $efirma = $rfc
   ? \App\Models\EFirma::where('rfc', $rfc)
       ->whereIn('tipo', ['nueva', 'renovacion'])
       ->latest()
       ->first()
   : null;

 $vigente    = false;
 $pctVigencia = 0;
 $diasRestantes = 0;
 $vencimiento = null;

 if ($efirma) {
   $emision     = $efirma->created_at;
   $vencimiento = $emision->copy()->addYears(4);
   $vigente     = now()->lt($vencimiento);
   $totalDias   = $emision->diffInDays($vencimiento);
   $diasTranscurridos = $emision->diffInDays(now());
   $pctVigencia = $totalDias > 0 ? round(($diasTranscurridos / $totalDias) * 100) : 100;
   $diasRestantes = (int) now()->diffInDays($vencimiento, false);
 }

 $estatusColor = match($efirma?->estatus ?? '') {
   'activo', 'vigente' => 'vigente',
   'pendiente'         => 'pendiente',
   'vencido'           => 'vencido',
   default             => 'pendiente',
 };
 $bannerClass  = $efirma ? ($vigente ? 'vigente' : 'vencido') : 'sin-registro';
@endphp

{{-- ── Banner principal ──────────────────────── --}}
@if($efirma)
 <div class="efirma-banner {{ $bannerClass }}">
  <div class="banner-left">
   <div class="banner-icon {{ $bannerClass }}">
    <i class="fas fa-{{ $vigente ? 'shield-alt' : 'exclamation-triangle' }}"></i>
   </div>
   <div class="banner-text">
    <h2>Certificado {{ $vigente ? 'VIGENTE' : 'VENCIDO' }}</h2>
    <p>
     @if($vigente)
      Tu e.firma está activa y puede usarse para trámites.
      @if($diasRestantes < 120)
       · <strong style="color:#fb923c">Vence en {{ $diasRestantes }} días</strong>
      @endif
     @else
      Tu certificado ha vencido. Es necesario renovarlo para continuar con trámites.
     @endif
    </p>
   </div>
  </div>
  <div class="banner-actions">
   <a href="{{ route('personas.e_firma') }}?tab=renovacion"
      class="btn-primary {{ $vigente ? 'outline' : 'green' }}">
    <i class="fas fa-sync-alt"></i> Renovar
   </a>
   <a href="{{ route('personas.e_firma.descargar', $efirma->id) }}"
      class="btn-primary green">
    <i class="fas fa-download"></i> Descargar .cer
   </a>
  </div>
 </div>

 {{-- ── Grid de info ───────────────────────────── --}}
 <div class="efirma-grid">

  {{-- Datos del titular --}}
  <div class="info-card">
   <div class="card-header">
    <div class="card-header-icon blue"><i class="fas fa-user"></i></div>
    <div class="card-title">Datos del Titular</div>
   </div>
   <div class="data-row">
    <span class="data-label">RFC</span>
    <span class="data-value mono">{{ $efirma->rfc }}</span>
   </div>
   <div class="data-row">
    <span class="data-label">Nombre</span>
    <span class="data-value">
     {{ trim("{$efirma->primer_apellido} {$efirma->segundo_apellido} {$efirma->nombres}") ?: '—' }}
    </span>
   </div>
   <div class="data-row">
    <span class="data-label">CURP</span>
    <span class="data-value mono">{{ $efirma->curp ?? '—' }}</span>
   </div>
   <div class="data-row">
    <span class="data-label">Correo</span>
    <span class="data-value">{{ $efirma->email ?? '—' }}</span>
   </div>
   <div class="data-row">
    <span class="data-label">Teléfono</span>
    <span class="data-value">{{ $efirma->telefono ?? '—' }}</span>
   </div>
  </div>

  {{-- Datos del certificado --}}
  <div class="info-card">
   <div class="card-header">
    <div class="card-header-icon green"><i class="fas fa-certificate"></i></div>
    <div class="card-title">Datos del Certificado</div>
   </div>
   <div class="data-row">
    <span class="data-label">No. serie</span>
    <span class="data-value mono">{{ $efirma->no_serie ?? '—' }}</span>
   </div>
   <div class="data-row">
    <span class="data-label">Folio SAT</span>
    <span class="data-value mono">{{ $efirma->folio ?? '—' }}</span>
   </div>
   <div class="data-row">
    <span class="data-label">Emisión</span>
    <span class="data-value">{{ $efirma->created_at->format('d/m/Y') }}</span>
   </div>
   <div class="data-row">
    <span class="data-label">Vencimiento</span>
    <span class="data-value {{ $vigente ? '' : 'red' }}">{{ $vencimiento?->format('d/m/Y') ?? '—' }}</span>
   </div>
   <div class="data-row">
    <span class="data-label">Estatus</span>
    <span class="data-value">
     <span class="status-badge {{ $estatusColor }}">
      <span class="status-dot"></span>
      {{ ucfirst($efirma->estatus ?? 'Desconocido') }}
     </span>
    </span>
   </div>
  </div>

  {{-- Vigencia --}}
  <div class="info-card">
   <div class="card-header">
    <div class="card-header-icon orange"><i class="fas fa-hourglass-half"></i></div>
    <div class="card-title">Vigencia del Certificado</div>
   </div>
   <div class="vigencia-wrap">
    <div class="vigencia-header">
     <span class="vigencia-label">Tiempo transcurrido</span>
     <span class="vigencia-pct">{{ min($pctVigencia, 100) }}%</span>
    </div>
    <div class="vigencia-bar">
     <div class="vigencia-fill {{ $pctVigencia >= 90 ? 'bad' : ($pctVigencia >= 70 ? 'warn' : 'good') }}"
          style="width:{{ min($pctVigencia, 100) }}%"></div>
    </div>
    <div class="vigencia-dates">
     <span>{{ $efirma->created_at->format('d/m/Y') }}</span>
     <span>{{ $vencimiento?->format('d/m/Y') }}</span>
    </div>
   </div>
   <div class="data-row" style="margin-top:8px">
    <span class="data-label">Días restantes</span>
    <span class="data-value {{ $diasRestantes < 60 ? 'red' : ($diasRestantes < 120 ? 'orange' : 'green') }}">
     {{ $diasRestantes > 0 ? $diasRestantes . ' días' : 'Vencido' }}
    </span>
   </div>
   <div class="data-row">
    <span class="data-label">Tipo de trámite</span>
    <span class="data-value" style="text-transform:capitalize">{{ $efirma->tipo ?? '—' }}</span>
   </div>

   @if($diasRestantes > 0 && $diasRestantes < 120)
    <div class="alert-box warn" style="margin-top:16px;margin-bottom:0">
     <i class="fas fa-exclamation-triangle"></i>
     <p>Tu certificado vence pronto. <strong>Te recomendamos renovarlo antes de que expire</strong> para no interrumpir tus trámites fiscales.</p>
    </div>
   @endif
  </div>

  {{-- Cita SAT --}}
  <div class="info-card">
   <div class="card-header">
    <div class="card-header-icon purple"><i class="fas fa-calendar-check"></i></div>
    <div class="card-title">Cita Agendada en Módulo SAT</div>
   </div>
   @if($efirma->modulo_efirma)
    <div class="cita-item">
     <div class="cita-icon"><i class="fas fa-map-marker-alt"></i></div>
     <div class="cita-info">
      <div class="cita-modulo">{{ $efirma->modulo_efirma }}</div>
      <div class="cita-detail">
       {{ $efirma->estado_modulo ?? '—' }}
      </div>
     </div>
     <div class="cita-date">
      <div class="cita-fecha">
       {{ $efirma->fecha_cita ? \Carbon\Carbon::parse($efirma->fecha_cita)->format('d/m/Y') : '—' }}
      </div>
      @if($efirma->horario_cita)
       <div class="cita-hora">{{ $efirma->horario_cita }}</div>
      @endif
     </div>
    </div>
    <div class="data-row" style="margin-top:14px">
     <span class="data-label">Estado</span>
     <span class="data-value">{{ $efirma->estado_modulo ?? '—' }}</span>
    </div>
    <div class="data-row">
     <span class="data-label">Módulo</span>
     <span class="data-value">{{ $efirma->modulo_efirma }}</span>
    </div>
    <div class="data-row">
     <span class="data-label">Fecha cita</span>
     <span class="data-value">
      {{ $efirma->fecha_cita ? \Carbon\Carbon::parse($efirma->fecha_cita)->format('d/m/Y') : '—' }}
     </span>
    </div>
   @else
    <div style="text-align:center;padding:28px 0">
     <i class="fas fa-calendar-times" style="font-size:32px;color:rgba(255,255,255,.12);display:block;margin-bottom:12px"></i>
     <p style="font-size:13px;color:rgba(255,255,255,.35);margin:0">No hay cita registrada</p>
    </div>
   @endif
  </div>

  {{-- Historial de trámites --}}
  @php
   $historial = \App\Models\EFirma::where('rfc', $rfc)->latest()->take(6)->get();
  @endphp
  @if($historial->count() > 1)
   <div class="info-card efirma-full">
    <div class="card-header">
     <div class="card-header-icon blue"><i class="fas fa-history"></i></div>
     <div class="card-title">Historial de Trámites</div>
    </div>
    <div style="overflow-x:auto">
     <table class="hist-table">
      <thead>
       <tr>
        <th>Folio</th>
        <th>Tipo</th>
        <th>No. Serie</th>
        <th>Fecha</th>
        <th>Estatus</th>
       </tr>
      </thead>
      <tbody>
       @foreach($historial as $h)
        <tr>
         <td style="font-family:'Courier New',monospace;color:#60a5fa;font-size:12px">{{ $h->folio }}</td>
         <td style="text-transform:capitalize">{{ $h->tipo }}</td>
         <td style="font-family:'Courier New',monospace;font-size:12px;color:rgba(255,255,255,.5)">{{ $h->no_serie ?? '—' }}</td>
         <td>{{ $h->created_at->format('d/m/Y') }}</td>
         <td>
          <span class="status-badge {{ match($h->estatus) { 'activo','vigente' => 'vigente', 'pendiente' => 'pendiente', 'vencido','cancelado' => 'vencido', default => 'pendiente' } }}">
           <span class="status-dot"></span>
           {{ ucfirst($h->estatus) }}
          </span>
         </td>
        </tr>
       @endforeach
      </tbody>
     </table>
    </div>
   </div>
  @endif

 </div>{{-- /efirma-grid --}}

 {{-- Acciones rápidas --}}
 <div class="info-card" style="margin-bottom:20px">
  <div class="card-header">
   <div class="card-header-icon orange"><i class="fas fa-bolt"></i></div>
   <div class="card-title">Acciones Rápidas</div>
  </div>
  <div style="display:flex;gap:12px;flex-wrap:wrap">
   <a href="{{ route('personas.e_firma.descargar', $efirma->id) }}"
      class="btn-primary green">
    <i class="fas fa-download"></i> Descargar .cer
   </a>
   <a href="{{ route('personas.e_firma') }}?tab=renovacion"
      class="btn-primary outline">
    <i class="fas fa-sync-alt"></i> Renovar e.firma
   </a>
   <a href="{{ route('personas.e_firma') }}?tab=revocacion"
      class="btn-primary red">
    <i class="fas fa-ban"></i> Revocar certificado
   </a>
  </div>
 </div>

@else
 {{-- ── Sin e.firma registrada ───────────────── --}}
 <div class="efirma-banner sin-registro">
  <div class="banner-left">
   <div class="banner-icon sin-registro"><i class="fas fa-times-circle"></i></div>
   <div class="banner-text">
    <h2>Sin e.firma registrada</h2>
    <p>No se encontró ningún certificado asociado a tu RFC <strong style="color:white">{{ $rfc ?: '—' }}</strong>.</p>
   </div>
  </div>
  <div class="banner-actions">
   <a href="{{ route('personas.e_firma') }}" class="btn-primary green">
    <i class="fas fa-plus"></i> Solicitar e.firma
   </a>
  </div>
 </div>

 <div class="info-card">
  <div class="no-efirma-wrap">
   <div class="no-efirma-icon"><i class="fas fa-fingerprint"></i></div>
   <div class="no-efirma-title">¿Qué es la e.firma?</div>
   <div class="no-efirma-sub">La Firma Electrónica Avanzada (e.firma) es un conjunto de datos que te identifica al realizar trámites y servicios en el SAT y otras dependencias del gobierno.</div>
   <a href="{{ route('personas.e_firma') }}" class="btn-primary green">
    <i class="fas fa-plus"></i> Comenzar trámite
   </a>
  </div>
 </div>
@endif

@endsection