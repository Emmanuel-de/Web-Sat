<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Mi RFC – Portal SAT</title>
<link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Source Sans Pro',sans-serif;background:#0f1923;color:#e2e8f0;display:flex;min-height:100vh;overflow-x:hidden}

/* ── Sidebar ──────────────────────────────────── */
.sidebar{width:220px;background:#0d1520;border-right:1px solid rgba(255,255,255,.06);display:flex;flex-direction:column;position:fixed;top:0;left:0;height:100vh;z-index:100;transition:width .25s}
.sidebar.collapsed{width:64px}
.sb-top{padding:20px 16px 16px;border-bottom:1px solid rgba(255,255,255,.06);display:flex;align-items:center;gap:10px;min-height:64px}
.sb-logo{width:36px;height:36px;background:linear-gradient(135deg,#006847,#004d35);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:16px;color:white;flex-shrink:0}
.sb-brand{font-size:14px;font-weight:700;color:white;white-space:nowrap;overflow:hidden;transition:opacity .2s}
.sb-brand small{display:block;font-size:11px;font-weight:400;color:rgba(255,255,255,.45);margin-top:1px}
.sidebar.collapsed .sb-brand,.sidebar.collapsed .nav-label,.sidebar.collapsed .sb-badge{opacity:0;pointer-events:none;width:0;overflow:hidden}
.sb-toggle{width:28px;height:28px;background:rgba(255,255,255,.06);border:none;border-radius:6px;color:rgba(255,255,255,.5);cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:13px;margin-left:auto;flex-shrink:0;transition:background .2s}
.sb-toggle:hover{background:rgba(255,255,255,.12);color:white}
.sb-nav{flex:1;padding:12px 10px;overflow-y:auto;display:flex;flex-direction:column;gap:2px}
.nav-item{display:flex;align-items:center;gap:10px;padding:10px 10px;border-radius:8px;cursor:pointer;transition:background .15s,color .15s;text-decoration:none;color:rgba(255,255,255,.55);font-size:14px;font-weight:600;white-space:nowrap;position:relative}
.nav-item:hover{background:rgba(255,255,255,.06);color:rgba(255,255,255,.85)}
.nav-item.active{background:rgba(0,104,71,.2);color:#4ade80}
.nav-item .ni{width:20px;text-align:center;font-size:15px;flex-shrink:0}
.sb-badge{background:#c8102e;color:white;font-size:10px;font-weight:700;padding:2px 6px;border-radius:10px;margin-left:auto;flex-shrink:0}
.sb-section{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:rgba(255,255,255,.25);padding:14px 12px 6px;white-space:nowrap;overflow:hidden}
.sidebar.collapsed .sb-section{opacity:0}
.sb-user{padding:14px 12px;border-top:1px solid rgba(255,255,255,.06);display:flex;align-items:center;gap:10px}
.sb-avatar{width:36px;height:36px;background:linear-gradient(135deg,#006847,#27ae60);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:white;flex-shrink:0}
.sb-user-info{overflow:hidden}
.sb-user-name{font-size:13px;font-weight:700;color:white;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;transition:opacity .2s}
.sb-user-rfc{font-size:11px;color:rgba(255,255,255,.4);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-family:monospace;transition:opacity .2s}
.sidebar.collapsed .sb-user-name,.sidebar.collapsed .sb-user-rfc{opacity:0;width:0}

/* ── Main ─────────────────────────────────────── */
.main{flex:1;margin-left:220px;transition:margin-left .25s;min-height:100vh;display:flex;flex-direction:column}
.sidebar.collapsed~.main{margin-left:64px}

/* ── Top bar ──────────────────────────────────── */
.topbar{height:64px;background:#0d1520;border-bottom:1px solid rgba(255,255,255,.06);display:flex;align-items:center;padding:0 28px;gap:16px;position:sticky;top:0;z-index:50}
.search-wrap{flex:1;max-width:440px;position:relative}
.search-input{width:100%;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.08);border-radius:10px;padding:9px 14px 9px 40px;font-size:14px;color:rgba(255,255,255,.7);outline:none;transition:all .2s;font-family:inherit}
.search-input::placeholder{color:rgba(255,255,255,.3)}
.search-input:focus{background:rgba(255,255,255,.09);border-color:rgba(0,104,71,.5);color:white}
.search-icon{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,.3);font-size:13px;pointer-events:none}
.topbar-right{display:flex;align-items:center;gap:12px;margin-left:auto}
.tb-icon-btn{width:36px;height:36px;background:rgba(255,255,255,.06);border:none;border-radius:8px;color:rgba(255,255,255,.5);cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:14px;transition:all .2s;position:relative;text-decoration:none}
.tb-icon-btn:hover{background:rgba(255,255,255,.1);color:white}
.tb-notif-dot{position:absolute;top:6px;right:6px;width:7px;height:7px;background:#c8102e;border-radius:50%;border:2px solid #0d1520}
.tb-user{display:flex;align-items:center;gap:8px;padding:6px 10px;background:rgba(255,255,255,.06);border-radius:10px;cursor:pointer}
.tb-av{width:32px;height:32px;background:linear-gradient(135deg,#006847,#27ae60);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:white}
.tb-uname{font-size:13px;font-weight:700;color:white;line-height:1.2}
.tb-urfc{font-size:10px;color:rgba(255,255,255,.4);font-family:monospace}

/* ── Content ──────────────────────────────────── */
.content{flex:1;padding:28px;overflow-y:auto}
.page-title{font-size:26px;font-weight:700;color:white;margin-bottom:4px}
.page-sub{font-size:14px;color:rgba(255,255,255,.45);margin-bottom:28px}

/* ── Variables ────────────────────────────────── */
:root{
 --green:#4ade80;--green-d:#006847;--blue:#60a5fa;
 --orange:#fb923c;--card:#0d1520;--border:rgba(255,255,255,.07);--muted:rgba(255,255,255,.35);
}

/* ── Status banner ────────────────────────────── */
.status-banner{display:flex;align-items:center;gap:14px;background:linear-gradient(135deg,#0d3a24,#0a2f1e);border:1px solid rgba(0,104,71,.3);border-radius:14px;padding:18px 24px;margin-bottom:28px}
.status-dot{width:12px;height:12px;border-radius:50%;background:var(--green);flex-shrink:0;box-shadow:0 0 0 4px rgba(74,222,128,.2);animation:pulse 2s infinite}
@keyframes pulse{0%,100%{box-shadow:0 0 0 4px rgba(74,222,128,.2)}50%{box-shadow:0 0 0 8px rgba(74,222,128,.08)}}
.status-text{font-size:14px;font-weight:700;color:var(--green)}
.status-detail{font-size:12px;color:var(--muted);margin-top:2px}
.status-rfc{margin-left:auto;font-family:monospace;font-size:22px;font-weight:900;letter-spacing:4px;color:white;background:rgba(0,0,0,.25);padding:8px 18px;border-radius:8px;border:1px solid var(--border)}

/* ── Grid ─────────────────────────────────────── */
.rfc-grid{display:grid;grid-template-columns:1fr 320px;gap:20px;margin-bottom:28px}

/* ── Panels ───────────────────────────────────── */
.panel-card{background:var(--card);border:1px solid var(--border);border-radius:14px;padding:24px}
.panel-header{display:flex;align-items:center;gap:10px;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid var(--border)}
.panel-icon{width:36px;height:36px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0}
.panel-icon.green{background:rgba(0,150,80,.18);color:var(--green)}
.panel-icon.blue{background:rgba(30,100,220,.18);color:var(--blue)}
.panel-icon.orange{background:rgba(200,80,20,.18);color:var(--orange)}
.panel-title{font-size:15px;font-weight:700;color:white}
.panel-sub{font-size:12px;color:var(--muted);margin-top:2px}

/* ── Data fields ──────────────────────────────── */
.data-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.data-field{display:flex;flex-direction:column;gap:4px}
.data-field.full{grid-column:1/-1}
.data-label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--muted)}
.data-value{font-size:14px;font-weight:600;color:white;background:rgba(255,255,255,.04);border:1px solid var(--border);border-radius:8px;padding:10px 14px;line-height:1.4}
.data-value.mono{font-family:monospace;letter-spacing:1px;font-size:15px}
.data-value.green-val{color:var(--green)}

/* ── RFC side ─────────────────────────────────── */
.rfc-side{display:flex;flex-direction:column;gap:16px}
.rfc-highlight{background:linear-gradient(135deg,#0d3a24 0%,#0a2f1e 100%);border:1px solid rgba(0,104,71,.3);border-radius:14px;padding:24px;text-align:center}
.rfc-label-sm{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--muted);margin-bottom:10px}
.rfc-big{font-family:monospace;font-size:26px;font-weight:900;letter-spacing:3px;color:var(--green);text-shadow:0 0 24px rgba(74,222,128,.3);margin-bottom:12px;word-break:break-all}
.rfc-copy-btn{background:rgba(74,222,128,.1);border:1px solid rgba(74,222,128,.2);color:var(--green);padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;transition:all .2s;font-family:inherit;width:100%;display:flex;align-items:center;justify-content:center;gap:6px}
.rfc-copy-btn:hover{background:rgba(74,222,128,.18)}
.rfc-copy-btn.copied{background:rgba(74,222,128,.25);color:#86efac}

/* ── Action btns ──────────────────────────────── */
.action-btn{display:flex;align-items:center;gap:10px;padding:13px 16px;border-radius:10px;cursor:pointer;transition:all .2s;text-decoration:none;border:1px solid var(--border);font-family:inherit;width:100%;font-size:14px;font-weight:600;background:rgba(255,255,255,.04);color:rgba(255,255,255,.7)}
.action-btn:hover{background:rgba(255,255,255,.08);color:white}
.action-btn.primary{background:linear-gradient(135deg,rgba(0,104,71,.3),rgba(0,80,55,.3));border-color:rgba(0,104,71,.35);color:var(--green)}
.action-btn.primary:hover{background:linear-gradient(135deg,rgba(0,104,71,.45),rgba(0,80,55,.45))}
.ab-icon{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0;background:rgba(255,255,255,.07)}
.action-btn.primary .ab-icon{background:rgba(74,222,128,.15);color:var(--green)}
.ab-info{flex:1;text-align:left}
.ab-title{font-size:13px;font-weight:700;line-height:1.3}
.ab-sub{font-size:11px;color:var(--muted);margin-top:2px;font-weight:400}
.ab-arrow{color:var(--muted);font-size:11px}

/* ── Chips ────────────────────────────────────── */
.chip{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:20px;font-size:12px;font-weight:700}
.chip.active{background:rgba(74,222,128,.12);color:var(--green)}

/* ── Timeline ─────────────────────────────────── */
.tl-item{display:flex;align-items:flex-start;gap:14px;padding:14px 0;border-bottom:1px solid var(--border)}
.tl-item:last-child{border-bottom:none}
.tl-dot{width:10px;height:10px;border-radius:50%;flex-shrink:0;margin-top:4px}
.tl-info{flex:1}
.tl-name{font-size:13px;font-weight:600;color:white}
.tl-date{font-size:12px;color:var(--muted);margin-top:2px}
.tl-badge{font-size:11px;font-weight:700;padding:2px 8px;border-radius:10px;flex-shrink:0}
.tl-badge.pronto{background:rgba(251,146,60,.15);color:var(--orange)}
.tl-badge.normal{background:rgba(255,255,255,.06);color:var(--muted)}

/* ── Sin RFC ──────────────────────────────────── */
.no-rfc-wrap{text-align:center;padding:60px 40px}
.no-rfc-icon{width:72px;height:72px;border-radius:50%;background:rgba(255,255,255,.05);display:flex;align-items:center;justify-content:center;font-size:28px;color:var(--muted);margin:0 auto 20px}
.no-rfc-title{font-size:18px;font-weight:700;color:white;margin-bottom:8px}
.no-rfc-sub{font-size:14px;color:var(--muted);margin-bottom:28px;max-width:320px;margin-left:auto;margin-right:auto}
.btn-solicitar{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,var(--green-d),#004d35);color:var(--green);padding:12px 24px;border-radius:10px;font-size:14px;font-weight:700;text-decoration:none;border:1px solid rgba(0,104,71,.3);transition:all .2s}
.btn-solicitar:hover{filter:brightness(1.1);color:#86efac}

/* ── Toast ────────────────────────────────────── */
.toast{position:fixed;bottom:28px;right:28px;background:#1a2030;border:1px solid rgba(74,222,128,.3);border-radius:10px;padding:12px 18px;display:flex;align-items:center;gap:10px;font-size:13px;font-weight:600;color:var(--green);transform:translateY(100px);opacity:0;transition:all .3s;z-index:9999;box-shadow:0 8px 32px rgba(0,0,0,.35)}
.toast.show{transform:translateY(0);opacity:1}

@media(max-width:1100px){.rfc-grid{grid-template-columns:1fr}}
@media(max-width:640px){.data-grid{grid-template-columns:1fr}.content{padding:16px}.sidebar{display:none}}
</style>
</head>
<body>

{{-- ── Sidebar ──────────────────────────────────── --}}
<nav class="sidebar" id="sidebar">
 <div class="sb-top">
  <div class="sb-logo"><i class="fas fa-landmark"></i></div>
  <div class="sb-brand">Portal SAT<small>Sistema de Administración Tributaria</small></div>
  <button class="sb-toggle" onclick="toggleSidebar()" title="Colapsar menú"><i class="fas fa-bars"></i></button>
 </div>

 <div class="sb-nav">
  <a href="{{ route('dashboard') }}" class="nav-item">
   <i class="fas fa-home ni"></i><span class="nav-label">Inicio</span>
  </a>
  <a href="{{ route('declaraciones.usuario') }}" class="nav-item">
   <i class="fas fa-file-invoice ni"></i><span class="nav-label">Mis Declaraciones</span>
  </a>
  <a href="{{ route('facturacion.mis_facturas') }}" class="nav-item">
   <i class="fas fa-file-invoice-dollar ni"></i><span class="nav-label">Facturas CFDI</span>
   <span class="sb-badge">3</span>
  </a>

  <div class="sb-section">Trámites</div>
  <a href="{{ route('personas.mi_rfc') }}" class="nav-item active">
   <i class="fas fa-id-card ni"></i><span class="nav-label">Mi RFC</span>
  </a>
  <a href="{{ route('personas.cif') }}" class="nav-item">
   <i class="fas fa-file-alt ni"></i><span class="nav-label">Constancia Fiscal</span>
  </a>
  <a href="{{ route('personas.e_firma') }}" class="nav-item">
   <i class="fas fa-signature ni"></i><span class="nav-label">e.firma</span>
  </a>
  <a href="{{ route('contacto.citas.index') }}" class="nav-item">
   <i class="fas fa-calendar-check ni"></i><span class="nav-label">Citas</span>
  </a>

  <div class="sb-section">Cuenta</div>
  <a href="#" class="nav-item">
   <i class="fas fa-calendar ni"></i><span class="nav-label">Calendario fiscal</span>
  </a>
  <a href="{{ route('perfil.index') }}" class="nav-item">
   <i class="fas fa-user-circle ni"></i><span class="nav-label">Mi Perfil</span>
  </a>
 </div>

 <div>
  <a href="{{ route('ayuda') }}" class="nav-item" style="margin:0 10px 6px">
   <i class="fas fa-question-circle ni"></i><span class="nav-label">Ayuda</span>
  </a>
  <form action="{{ route('logout') }}" method="POST">
   @csrf
   <button type="submit" class="nav-item" style="width:calc(100% - 20px);margin:0 10px 10px;background:none;border:none;cursor:pointer;color:rgba(255,255,255,.45);font-size:14px;font-weight:600;font-family:inherit">
    <i class="fas fa-sign-out-alt ni"></i><span class="nav-label">Cerrar Sesión</span>
   </button>
  </form>
  <div class="sb-user">
   <div class="sb-avatar">{{ strtoupper(substr(Auth::user()->nombres ?? 'U', 0, 1)) }}</div>
   <div class="sb-user-info">
    <div class="sb-user-name">{{ Auth::user()->nombres ?? 'Usuario' }} {{ Auth::user()->primer_apellido ?? '' }}</div>
    <div class="sb-user-rfc">{{ $solicitud->rfc ?? (Auth::user()->rfc ?? 'RFC') }}</div>
   </div>
  </div>
 </div>
</nav>

{{-- ── Main ──────────────────────────────────────── --}}
<div class="main" id="main">

 <div class="topbar">
  <div class="search-wrap">
   <i class="fas fa-search search-icon"></i>
   <input type="text" class="search-input" placeholder="Buscar trámites, facturas, declaraciones...">
  </div>
  <div class="topbar-right">
   <a href="{{ route('facturacion.index') }}" class="tb-icon-btn" title="Notificaciones">
    <i class="fas fa-bell"></i><span class="tb-notif-dot"></span>
   </a>
   <div class="tb-user">
    <div class="tb-av">{{ strtoupper(substr(Auth::user()->nombres ?? 'U', 0, 2)) }}</div>
    <div>
     <div class="tb-uname">{{ Auth::user()->nombres ?? 'Usuario' }} {{ Auth::user()->primer_apellido ?? '' }} {{ Auth::user()->segundo_apellido ?? '' }}</div>
     <div class="tb-urfc">{{ $solicitud->rfc ?? (Auth::user()->rfc ?? 'RFC') }} · CDMX</div>
    </div>
   </div>
  </div>
 </div>

 <div class="content">
  <div class="page-title">Mi RFC</div>
  <div class="page-sub">Consulta tu situación fiscal y descarga tu constancia oficial.</div>

  @if($solicitud)

  {{-- Banner --}}
  <div class="status-banner">
   <div class="status-dot"></div>
   <div>
    <div class="status-text"><i class="fas fa-check-circle" style="margin-right:6px"></i>Contribuyente Activo</div>
    <div class="status-detail">Inscrito el {{ \Carbon\Carbon::parse($solicitud->created_at)->format('d \d\e F \d\e Y') }}</div>
   </div>
   <div class="status-rfc">{{ $solicitud->rfc }}</div>
  </div>

  <div class="rfc-grid">

   {{-- Izquierda --}}
   <div style="display:flex;flex-direction:column;gap:20px">

    {{-- Datos personales --}}
    <div class="panel-card">
     <div class="panel-header">
      <div class="panel-icon green"><i class="fas fa-user"></i></div>
      <div><div class="panel-title">Datos Personales</div><div class="panel-sub">Información registrada ante el SAT</div></div>
     </div>
     <div class="data-grid">
      <div class="data-field full">
       <span class="data-label">Nombre completo</span>
       <span class="data-value">{{ strtoupper($solicitud->nombres.' '.$solicitud->primer_apellido.' '.$solicitud->segundo_apellido) }}</span>
      </div>
      <div class="data-field">
       <span class="data-label">RFC</span>
       <span class="data-value mono green-val">{{ $solicitud->rfc }}</span>
      </div>
      <div class="data-field">
       <span class="data-label">CURP</span>
       <span class="data-value mono" style="font-size:13px">{{ $solicitud->curp }}</span>
      </div>
      <div class="data-field">
       <span class="data-label">Fecha de nacimiento</span>
       <span class="data-value">{{ \Carbon\Carbon::parse($solicitud->fecha_nacimiento)->format('d/m/Y') }}</span>
      </div>
      <div class="data-field">
       <span class="data-label">Sexo</span>
       <span class="data-value">{{ $solicitud->sexo }}</span>
      </div>
      <div class="data-field">
       <span class="data-label">Estado de nacimiento</span>
       <span class="data-value">{{ $solicitud->estado_nacimiento }}</span>
      </div>
      <div class="data-field">
       <span class="data-label">Correo electrónico</span>
       <span class="data-value">{{ $solicitud->email }}</span>
      </div>
      <div class="data-field">
       <span class="data-label">Teléfono</span>
       <span class="data-value">{{ $solicitud->telefono }}</span>
      </div>
     </div>
    </div>

    {{-- Domicilio --}}
    <div class="panel-card">
     <div class="panel-header">
      <div class="panel-icon blue"><i class="fas fa-map-marker-alt"></i></div>
      <div><div class="panel-title">Domicilio Fiscal</div><div class="panel-sub">Dirección registrada ante el SAT</div></div>
     </div>
     <div class="data-grid">
      <div class="data-field full">
       <span class="data-label">Calle y número</span>
       <span class="data-value">{{ $solicitud->calle }} {{ $solicitud->no_exterior }}@if($solicitud->no_interior), Int. {{ $solicitud->no_interior }}@endif</span>
      </div>
      <div class="data-field">
       <span class="data-label">Colonia</span>
       <span class="data-value">{{ $solicitud->colonia }}</span>
      </div>
      <div class="data-field">
       <span class="data-label">Código Postal</span>
       <span class="data-value">{{ $solicitud->codigo_postal }}</span>
      </div>
      <div class="data-field">
       <span class="data-label">Municipio / Alcaldía</span>
       <span class="data-value">{{ $solicitud->municipio }}</span>
      </div>
      <div class="data-field">
       <span class="data-label">Estado</span>
       <span class="data-value">{{ $solicitud->estado }}</span>
      </div>
      @if($solicitud->entre_calles)
      <div class="data-field full">
       <span class="data-label">Entre calles</span>
       <span class="data-value">{{ $solicitud->entre_calles }}</span>
      </div>
      @endif
     </div>
    </div>

    {{-- Actividad --}}
    <div class="panel-card">
     <div class="panel-header">
      <div class="panel-icon orange"><i class="fas fa-briefcase"></i></div>
      <div><div class="panel-title">Actividad Económica</div><div class="panel-sub">Régimen fiscal y actividad</div></div>
     </div>
     <div class="data-grid">
      <div class="data-field full">
       <span class="data-label">Régimen fiscal</span>
       <span class="data-value">{{ $solicitud->regimen_fiscal }}</span>
      </div>
      <div class="data-field full">
       <span class="data-label">Actividad principal</span>
       <span class="data-value">{{ $solicitud->actividad_principal }}</span>
      </div>
      @if($solicitud->descripcion_actividad)
      <div class="data-field full">
       <span class="data-label">Descripción</span>
       <span class="data-value">{{ $solicitud->descripcion_actividad }}</span>
      </div>
      @endif
      <div class="data-field">
       <span class="data-label">Inicio de actividades</span>
       <span class="data-value">{{ \Carbon\Carbon::parse($solicitud->fecha_inicio_actividades)->format('d/m/Y') }}</span>
      </div>
      <div class="data-field">
       <span class="data-label">Estatus</span>
       <span class="data-value"><span class="chip active"><i class="fas fa-circle" style="font-size:7px"></i> Activo</span></span>
      </div>
     </div>
    </div>

   </div>

   {{-- Derecha --}}
   <div class="rfc-side">

    <div class="rfc-highlight">
     <div class="rfc-label-sm"><i class="fas fa-id-card" style="margin-right:5px"></i>Tu RFC</div>
     <div class="rfc-big">{{ $solicitud->rfc }}</div>
     <button class="rfc-copy-btn" id="copyBtn" onclick="copiarRfc('{{ $solicitud->rfc }}')">
      <i class="fas fa-copy"></i> Copiar RFC
     </button>
    </div>

    <div class="panel-card" style="padding:16px">
     <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--muted);margin-bottom:12px">Acciones</div>
     <div style="display:flex;flex-direction:column;gap:8px">
      <a href="{{ route('personas.rfc.constancia', $solicitud->rfc) }}" class="action-btn primary" target="_blank">
       <div class="ab-icon"><i class="fas fa-download"></i></div>
       <div class="ab-info"><div class="ab-title">Descargar Constancia</div><div class="ab-sub">Constancia de Situación Fiscal</div></div>
       <i class="fas fa-chevron-right ab-arrow"></i>
      </a>
      <a href="#" class="action-btn" onclick="event.preventDefault();abrirImprimir('{{ route('personas.rfc.constancia', $solicitud->rfc) }}')">
       <div class="ab-icon"><i class="fas fa-print"></i></div>
       <div class="ab-info"><div class="ab-title">Imprimir Constancia</div><div class="ab-sub">Versión imprimible oficial</div></div>
       <i class="fas fa-chevron-right ab-arrow"></i>
      </a>
      <a href="{{ route('perfil.index') }}" class="action-btn">
       <div class="ab-icon"><i class="fas fa-edit"></i></div>
       <div class="ab-info"><div class="ab-title">Actualizar Datos</div><div class="ab-sub">Modificar información fiscal</div></div>
       <i class="fas fa-chevron-right ab-arrow"></i>
      </a>
      <a href="{{ route('personas.e_firma') }}" class="action-btn">
       <div class="ab-icon"><i class="fas fa-signature"></i></div>
       <div class="ab-info"><div class="ab-title">Gestionar e.firma</div><div class="ab-sub">Firma electrónica avanzada</div></div>
       <i class="fas fa-chevron-right ab-arrow"></i>
      </a>
     </div>
    </div>

    <div class="panel-card" style="padding:20px">
     <div style="display:flex;align-items:center;gap:8px;margin-bottom:16px">
      <i class="fas fa-exclamation-triangle" style="color:var(--orange);font-size:14px"></i>
      <span style="font-size:14px;font-weight:700;color:white">Obligaciones Próximas</span>
     </div>
     <div>
      <div class="tl-item">
       <div class="tl-dot" style="background:var(--orange)"></div>
       <div class="tl-info"><div class="tl-name">Declaración mensual Febrero</div><div class="tl-date">17 Mar 2026</div></div>
       <span class="tl-badge pronto">En 14 días</span>
      </div>
      <div class="tl-item">
       <div class="tl-dot" style="background:var(--orange)"></div>
       <div class="tl-info"><div class="tl-name">Pago provisional ISR</div><div class="tl-date">17 Mar 2026</div></div>
       <span class="tl-badge pronto">En 14 días</span>
      </div>
      <div class="tl-item">
       <div class="tl-dot" style="background:#60a5fa"></div>
       <div class="tl-info"><div class="tl-name">DIOT Febrero</div><div class="tl-date">17 Mar 2026</div></div>
       <span class="tl-badge normal">Próximo</span>
      </div>
      <div class="tl-item">
       <div class="tl-dot" style="background:var(--green)"></div>
       <div class="tl-info"><div class="tl-name">Declaración anual 2025</div><div class="tl-date">30 Abr 2026</div></div>
       <span class="tl-badge normal">58 días</span>
      </div>
     </div>
    </div>

    <div class="panel-card" style="padding:16px 20px">
     <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--muted);margin-bottom:10px">Datos de inscripción</div>
     <div style="display:flex;flex-direction:column;gap:10px">
      <div style="display:flex;justify-content:space-between;align-items:center">
       <span style="font-size:13px;color:var(--muted)">Folio</span>
       <span style="font-size:13px;font-weight:700;color:white;font-family:monospace">{{ str_pad($solicitud->id,10,'0',STR_PAD_LEFT) }}</span>
      </div>
      <div style="display:flex;justify-content:space-between;align-items:center">
       <span style="font-size:13px;color:var(--muted)">Inscripción</span>
       <span style="font-size:13px;font-weight:700;color:white">{{ \Carbon\Carbon::parse($solicitud->created_at)->format('d/m/Y') }}</span>
      </div>
      <div style="display:flex;justify-content:space-between;align-items:center">
       <span style="font-size:13px;color:var(--muted)">Estatus</span>
       <span class="chip active"><i class="fas fa-circle" style="font-size:7px"></i> Aprobado</span>
      </div>
      <div style="display:flex;justify-content:space-between;align-items:center">
       <span style="font-size:13px;color:var(--muted)">Tipo</span>
       <span style="font-size:13px;font-weight:700;color:white">Persona Física</span>
      </div>
     </div>
    </div>

   </div>{{-- /rfc-side --}}
  </div>{{-- /rfc-grid --}}

  @else

  <div class="panel-card">
   <div class="no-rfc-wrap">
    <div class="no-rfc-icon"><i class="fas fa-id-card"></i></div>
    <div class="no-rfc-title">Aún no tienes un RFC registrado</div>
    <p class="no-rfc-sub">Solicita tu RFC de manera gratuita y rápida. El proceso toma menos de 5 minutos.</p>
    <a href="{{ route('personas.rfc') }}" class="btn-solicitar">
     <i class="fas fa-plus-circle"></i> Solicitar mi RFC
    </a>
   </div>
  </div>

  @endif

 </div>{{-- /content --}}
</div>{{-- /main --}}

<div class="toast" id="toast">
 <i class="fas fa-check-circle"></i> RFC copiado al portapapeles
</div>

<script>
function toggleSidebar(){
 document.getElementById('sidebar').classList.toggle('collapsed');
}
function copiarRfc(rfc){
 navigator.clipboard.writeText(rfc).then(()=>{
  const btn=document.getElementById('copyBtn');
  const toast=document.getElementById('toast');
  btn.classList.add('copied');
  btn.innerHTML='<i class="fas fa-check"></i> ¡Copiado!';
  toast.classList.add('show');
  setTimeout(()=>{
   btn.classList.remove('copied');
   btn.innerHTML='<i class="fas fa-copy"></i> Copiar RFC';
   toast.classList.remove('show');
  },2500);
 }).catch(()=>{
  const el=document.createElement('textarea');
  el.value=rfc;document.body.appendChild(el);el.select();
  document.execCommand('copy');document.body.removeChild(el);
 });
}
function abrirImprimir(url){
 const win=window.open(url,'_blank');
 win.onload=()=>win.print();
}
</script>
</body>
</html>