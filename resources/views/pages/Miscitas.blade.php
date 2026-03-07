<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Portal SAT – Mis Citas</title>
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

/* ── Layout dos columnas ──────────────────────── */
.citas-layout{display:grid;grid-template-columns:1fr 380px;gap:24px;align-items:start}

/* ── Card base ────────────────────────────────── */
.panel-card{background:#0d1520;border:1px solid rgba(255,255,255,.07);border-radius:14px;padding:26px}
.panel-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:22px}
.panel-title{font-size:16px;font-weight:700;color:white;display:flex;align-items:center;gap:10px}
.panel-title i{color:#4ade80;font-size:15px}

/* ── Header banner ────────────────────────────── */
.cita-banner{background:linear-gradient(135deg,#006847 0%,#004d35 100%);border-radius:14px;padding:22px 26px;margin-bottom:24px;display:flex;align-items:center;gap:18px;border:1px solid rgba(0,104,71,.3)}
.cita-banner-icon{width:52px;height:52px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;color:white;flex-shrink:0}
.cita-banner-text h2{font-size:18px;font-weight:700;color:white;margin-bottom:3px}
.cita-banner-text p{font-size:13px;color:rgba(255,255,255,.7)}

/* ── Formulario ───────────────────────────────── */
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}
.form-group{display:flex;flex-direction:column;gap:6px}
.form-group.full{grid-column:1/-1}
.form-label{font-size:12px;font-weight:700;color:rgba(255,255,255,.5);text-transform:uppercase;letter-spacing:.5px}
.form-label span{color:#f87171;margin-left:2px}
.form-control{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:10px;padding:11px 14px;font-size:14px;color:white;font-family:inherit;outline:none;transition:all .2s;appearance:none;-webkit-appearance:none}
.form-control::placeholder{color:rgba(255,255,255,.25)}
.form-control:focus{background:rgba(255,255,255,.09);border-color:rgba(0,104,71,.6);box-shadow:0 0 0 3px rgba(0,104,71,.15)}
.form-control:disabled{opacity:.4;cursor:not-allowed}
select.form-control{background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='rgba(255,255,255,0.4)' d='M6 8L1 3h10z'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 14px center;padding-right:36px}
select.form-control option{background:#0d1520;color:white}
textarea.form-control{resize:vertical;min-height:100px}
.form-hint{font-size:11px;color:rgba(255,255,255,.3);margin-top:2px}

/* ── Botón submit ─────────────────────────────── */
.btn-cita{display:inline-flex;align-items:center;gap:10px;padding:13px 28px;background:linear-gradient(135deg,#006847,#00834f);border:none;border-radius:10px;color:white;font-size:15px;font-weight:700;font-family:inherit;cursor:pointer;transition:all .2s;margin-top:8px}
.btn-cita:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(0,104,71,.4)}
.btn-cita:active{transform:translateY(0)}
.btn-cita:disabled{opacity:.5;cursor:not-allowed;transform:none}

/* ── Alert flash ──────────────────────────────── */
.alert{padding:14px 18px;border-radius:10px;font-size:14px;font-weight:600;margin-bottom:20px;display:flex;align-items:center;gap:10px}
.alert-success{background:rgba(74,222,128,.1);border:1px solid rgba(74,222,128,.25);color:#4ade80}
.alert-error{background:rgba(248,113,113,.1);border:1px solid rgba(248,113,113,.25);color:#f87171}

/* ── Lista de citas ───────────────────────────── */
.cita-item{padding:16px;border-radius:10px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06);margin-bottom:10px;transition:background .2s}
.cita-item:hover{background:rgba(255,255,255,.06)}
.cita-item:last-child{margin-bottom:0}
.cita-folio{font-size:11px;font-weight:700;color:rgba(255,255,255,.35);font-family:monospace;margin-bottom:6px}
.cita-tramite{font-size:14px;font-weight:700;color:white;margin-bottom:4px}
.cita-modulo{font-size:12px;color:rgba(255,255,255,.45);margin-bottom:8px;display:flex;align-items:center;gap:5px}
.cita-footer{display:flex;align-items:center;justify-content:space-between}
.cita-fecha{font-size:13px;font-weight:600;color:rgba(255,255,255,.6);display:flex;align-items:center;gap:6px}
.cita-fecha i{color:#4ade80;font-size:11px}
.cita-status{font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px}
.cita-status.confirmada{background:rgba(74,222,128,.15);color:#4ade80}
.cita-status.pendiente{background:rgba(251,146,60,.15);color:#fb923c}
.cita-status.cancelada{background:rgba(248,113,113,.15);color:#f87171}
.cita-status.atendida{background:rgba(96,165,250,.15);color:#60a5fa}

/* ── Btn cancelar ─────────────────────────────── */
.btn-cancelar{font-size:11px;color:rgba(248,113,113,.6);background:none;border:none;cursor:pointer;font-family:inherit;font-weight:600;padding:0;transition:color .2s}
.btn-cancelar:hover{color:#f87171}

/* ── Empty state ──────────────────────────────── */
.empty-state{text-align:center;padding:36px 20px;color:rgba(255,255,255,.3)}
.empty-state i{font-size:36px;margin-bottom:12px;display:block;color:rgba(255,255,255,.15)}
.empty-state p{font-size:13px}

/* ── Info box ─────────────────────────────────── */
.info-box{background:rgba(96,165,250,.07);border:1px solid rgba(96,165,250,.15);border-radius:10px;padding:14px 16px;margin-top:20px}
.info-box p{font-size:12px;color:rgba(255,255,255,.45);line-height:1.6}
.info-box p strong{color:rgba(96,165,250,.8)}

/* ── Divider ──────────────────────────────────── */
.form-divider{height:1px;background:rgba(255,255,255,.06);margin:4px 0 20px;grid-column:1/-1}
.section-label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:rgba(255,255,255,.25);grid-column:1/-1;margin-top:4px}

@media(max-width:1100px){.citas-layout{grid-template-columns:1fr}}
@media(max-width:640px){.form-grid{grid-template-columns:1fr}.content{padding:16px}.sidebar{display:none}}
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
  <a href="{{ route('personas.rfc') }}" class="nav-item">
   <i class="fas fa-id-card ni"></i><span class="nav-label">Mi RFC</span>
  </a>
  <a href="{{ route('personas.cif') }}" class="nav-item">
   <i class="fas fa-file-alt ni"></i><span class="nav-label">Constancia Fiscal</span>
  </a>
  <a href="{{ route('personas.e_firma') }}" class="nav-item">
   <i class="fas fa-signature ni"></i><span class="nav-label">e.firma</span>
  </a>
  <a href="{{ route('contacto.citas.index') }}" class="nav-item active">
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
    <div class="sb-user-name">{{ Auth::user()->nombres ?? 'Usuarios' }} {{ Auth::user()->primer_apellido ?? '' }}</div>
    <div class="sb-user-rfc">{{ Auth::user()->rfc ?? 'RFC' }}</div>
   </div>
  </div>
 </div>
</nav>

{{-- ── Main ─────────────────────────────────────── --}}
<div class="main" id="main">

 <div class="topbar">
  <div class="search-wrap">
   <i class="fas fa-search search-icon"></i>
   <input type="text" class="search-input" placeholder="Buscar trámites, facturas, declaraciones...">
  </div>
  <div class="topbar-right">
   <a href="{{ route('facturacion.index') }}" class="tb-icon-btn" title="Notificaciones">
    <i class="fas fa-bell"></i>
    <span class="tb-notif-dot"></span>
   </a>
   <div class="tb-user">
    <div class="tb-av">{{ strtoupper(substr(Auth::user()->nombres ?? 'U', 0, 2)) }}</div>
    <div class="tb-uinfo">
     <div class="tb-uname">{{ Auth::user()->nombres ?? 'Usuarios' }} {{ Auth::user()->primer_apellido ?? '' }} {{ Auth::user()->segundo_apellido ?? '' }}</div>
     <div class="tb-urfc">{{ Auth::user()->rfc ?? 'RFC' }} · CDMX</div>
    </div>
   </div>
  </div>
 </div>

 <div class="content">
  <div class="page-title">Mis Citas</div>
  <div class="page-sub">Agenda y gestiona tus citas en los módulos SAT de toda la República.</div>

  {{-- Flash messages --}}
  @if(session('success'))
   <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
  @endif
  @if(session('error'))
   <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
  @endif

  <div class="citas-layout">

   {{-- ── Columna izquierda: Formulario ───────── --}}
   <div>
    <div class="cita-banner">
     <div class="cita-banner-icon"><i class="fas fa-calendar-check"></i></div>
     <div class="cita-banner-text">
      <h2>Agenda tu cita en línea</h2>
      <p>Programa tu visita a cualquier módulo SAT en México</p>
     </div>
    </div>

    <div class="panel-card">
     <div class="panel-header">
      <div class="panel-title"><i class="fas fa-plus-circle"></i> Nueva Cita</div>
     </div>

     <form action="{{ route('contacto.cita.store') }}" method="POST" id="formCita">
      @csrf

      <div class="form-grid">

       {{-- Datos personales --}}
       <div class="section-label">Datos Personales</div>

       <div class="form-group">
        <label class="form-label">RFC <span>*</span></label>
        <input type="text"
               name="rfc"
               class="form-control"
               placeholder="Tu RFC"
               value="{{ old('rfc', Auth::user()->rfc ?? '') }}"
               maxlength="13"
               style="text-transform:uppercase"
               required>
        @error('rfc')<span style="font-size:12px;color:#f87171">{{ $message }}</span>@enderror
       </div>

       <div class="form-group">
        <label class="form-label">CURP <span>*</span></label>
        <input type="text"
               name="curp"
               class="form-control"
               placeholder="Tu CURP"
               value="{{ old('curp', Auth::user()->curp ?? '') }}"
               maxlength="18"
               style="text-transform:uppercase"
               required>
        @error('curp')<span style="font-size:12px;color:#f87171">{{ $message }}</span>@enderror
       </div>

       <div class="form-group">
        <label class="form-label">Nombre completo <span>*</span></label>
        <input type="text"
               name="nombre"
               class="form-control"
               placeholder="Como aparece en tu identificación"
               value="{{ old('nombre', Auth::user()->nombres.' '.(Auth::user()->primer_apellido ?? '').' '.(Auth::user()->segundo_apellido ?? '')) }}"
               required>
        @error('nombre')<span style="font-size:12px;color:#f87171">{{ $message }}</span>@enderror
       </div>

       <div class="form-group">
        <label class="form-label">Correo electrónico <span>*</span></label>
        <input type="email"
               name="email"
               class="form-control"
               placeholder="correo@ejemplo.com"
               value="{{ old('email', Auth::user()->email ?? '') }}"
               required>
        @error('email')<span style="font-size:12px;color:#f87171">{{ $message }}</span>@enderror
       </div>

       <div class="form-group">
        <label class="form-label">Teléfono</label>
        <input type="tel"
               name="telefono"
               class="form-control"
               placeholder="10 dígitos"
               value="{{ old('telefono', Auth::user()->telefono ?? '') }}"
               maxlength="10">
        @error('telefono')<span style="font-size:12px;color:#f87171">{{ $message }}</span>@enderror
       </div>

       <div class="form-divider"></div>

       {{-- Módulo y trámite --}}
       <div class="section-label">Módulo y Trámite</div>

       <div class="form-group">
        <label class="form-label">Estado <span>*</span></label>
        <select name="estado_filtro" id="selectEstado" class="form-control" required onchange="cargarModulos(this.value)">
         <option value="">Seleccionar estado...</option>
         @foreach($modulos->pluck('estado')->unique()->sort() as $estado)
          <option value="{{ $estado }}" {{ old('estado_filtro') == $estado ? 'selected' : '' }}>{{ $estado }}</option>
         @endforeach
        </select>
       </div>

       <div class="form-group">
        <label class="form-label">Módulo SAT <span>*</span></label>
        <select name="modulo_sat_id" id="selectModulo" class="form-control" required disabled>
         <option value="">Primero selecciona tu estado</option>
        </select>
        @error('modulo_sat_id')<span style="font-size:12px;color:#f87171">{{ $message }}</span>@enderror
       </div>

       <div class="form-group full">
        <label class="form-label">Trámite a realizar <span>*</span></label>
        <select name="tramite" class="form-control" required>
         <option value="">Seleccionar trámite...</option>
         <option value="RFC"              {{ old('tramite')=='RFC' ? 'selected':'' }}>Inscripción / Actualización de RFC</option>
         <option value="EFIRMA"           {{ old('tramite')=='EFIRMA' ? 'selected':'' }}>e.firma (Obtención / Renovación)</option>
         <option value="CIF"              {{ old('tramite')=='CIF' ? 'selected':'' }}>Constancia de Situación Fiscal</option>
         <option value="DECLARACION"      {{ old('tramite')=='DECLARACION' ? 'selected':'' }}>Declaración anual / provisional</option>
         <option value="DEVOLUCION"       {{ old('tramite')=='DEVOLUCION' ? 'selected':'' }}>Devolución de impuestos</option>
         <option value="OPINION"          {{ old('tramite')=='OPINION' ? 'selected':'' }}>Opinión de cumplimiento</option>
         <option value="FACTURACION"      {{ old('tramite')=='FACTURACION' ? 'selected':'' }}>Facturación electrónica (CFDI)</option>
         <option value="OTROS"            {{ old('tramite')=='OTROS' ? 'selected':'' }}>Otro trámite</option>
        </select>
        @error('tramite')<span style="font-size:12px;color:#f87171">{{ $message }}</span>@enderror
       </div>

       <div class="form-divider"></div>

       {{-- Fecha y horario --}}
       <div class="section-label">Fecha y Horario</div>

       <div class="form-group">
        <label class="form-label">Fecha preferida <span>*</span></label>
        <input type="date"
               name="fecha"
               id="inputFecha"
               class="form-control"
               value="{{ old('fecha') }}"
               min="{{ now()->addDay()->format('Y-m-d') }}"
               required>
        <span class="form-hint">Lunes a viernes (días hábiles)</span>
        @error('fecha')<span style="font-size:12px;color:#f87171">{{ $message }}</span>@enderror
       </div>

       <div class="form-group">
        <label class="form-label">Horario preferido <span>*</span></label>
        <select name="horario" class="form-control" required>
         <option value="">Seleccionar...</option>
         <option value="09:00 - 09:30" {{ old('horario')=='09:00 - 09:30' ? 'selected':'' }}>09:00 – 09:30</option>
         <option value="09:30 - 10:00" {{ old('horario')=='09:30 - 10:00' ? 'selected':'' }}>09:30 – 10:00</option>
         <option value="10:00 - 10:30" {{ old('horario')=='10:00 - 10:30' ? 'selected':'' }}>10:00 – 10:30</option>
         <option value="10:30 - 11:00" {{ old('horario')=='10:30 - 11:00' ? 'selected':'' }}>10:30 – 11:00</option>
         <option value="11:00 - 11:30" {{ old('horario')=='11:00 - 11:30' ? 'selected':'' }}>11:00 – 11:30</option>
         <option value="11:30 - 12:00" {{ old('horario')=='11:30 - 12:00' ? 'selected':'' }}>11:30 – 12:00</option>
         <option value="12:00 - 12:30" {{ old('horario')=='12:00 - 12:30' ? 'selected':'' }}>12:00 – 12:30</option>
         <option value="12:30 - 13:00" {{ old('horario')=='12:30 - 13:00' ? 'selected':'' }}>12:30 – 13:00</option>
         <option value="13:00 - 13:30" {{ old('horario')=='13:00 - 13:30' ? 'selected':'' }}>13:00 – 13:30</option>
         <option value="13:30 - 14:00" {{ old('horario')=='13:30 - 14:00' ? 'selected':'' }}>13:30 – 14:00</option>
         <option value="16:00 - 16:30" {{ old('horario')=='16:00 - 16:30' ? 'selected':'' }}>16:00 – 16:30</option>
         <option value="16:30 - 17:00" {{ old('horario')=='16:30 - 17:00' ? 'selected':'' }}>16:30 – 17:00</option>
        </select>
        @error('horario')<span style="font-size:12px;color:#f87171">{{ $message }}</span>@enderror
       </div>

       {{-- Observaciones --}}
       <div class="form-group full">
        <label class="form-label">Observaciones adicionales</label>
        <textarea name="observaciones" class="form-control" placeholder="Indica algún requerimiento especial o información adicional para tu cita...">{{ old('observaciones') }}</textarea>
       </div>

      </div>{{-- /form-grid --}}

      <button type="submit" class="btn-cita" id="btnSubmit">
       <i class="fas fa-calendar-check"></i> Confirmar cita
      </button>

     </form>
    </div>{{-- /panel-card --}}
   </div>

   {{-- ── Columna derecha: Mis citas ───────────── --}}
   <div>
    <div class="panel-card">
     <div class="panel-header">
      <div class="panel-title"><i class="fas fa-list-ul"></i> Citas Agendadas</div>
      <span style="font-size:12px;color:rgba(255,255,255,.35)">{{ $misCitas->total() }} total</span>
     </div>

     @if($misCitas->count())
      @foreach($misCitas as $cita)
       <div class="cita-item">
        <div class="cita-folio">{{ $cita->folio }}</div>
        <div class="cita-tramite">{{ $cita->tramite }}</div>
        <div class="cita-modulo">
         <i class="fas fa-map-marker-alt" style="color:#4ade80;font-size:10px"></i>
         {{ $cita->modulo->nombre ?? '—' }}, {{ $cita->modulo->municipio ?? '' }}
        </div>
        <div class="cita-footer">
         <div class="cita-fecha">
          <i class="fas fa-clock"></i>
          {{ \Carbon\Carbon::parse($cita->fecha)->translatedFormat('d M Y') }} · {{ $cita->horario }}
         </div>
         <span class="cita-status {{ $cita->estatus }}">{{ ucfirst($cita->estatus) }}</span>
        </div>
        @if(in_array($cita->estatus, ['confirmada','pendiente']))
         <div style="margin-top:10px;text-align:right">
          <form action="{{ route('tramites.citas.cancelar', $cita->folio) }}" method="GET" style="display:inline">
           <button type="submit" class="btn-cancelar" onclick="return confirm('¿Cancelar esta cita?')">
            <i class="fas fa-times-circle"></i> Cancelar cita
           </button>
          </form>
         </div>
        @endif
       </div>
      @endforeach

      {{-- Paginación --}}
      <div style="margin-top:16px">
       {{ $misCitas->links() }}
      </div>

     @else
      <div class="empty-state">
       <i class="fas fa-calendar-times"></i>
       <p>No tienes citas agendadas aún.<br>Usa el formulario para programar tu primera cita.</p>
      </div>
     @endif

     <div class="info-box">
      <p>
       <strong>¿Necesitas cancelar?</strong> Puedes cancelar tu cita hasta 24 horas antes de la fecha programada.<br><br>
       <strong>Código de confirmación:</strong> Se envía a tu correo al confirmar la cita.
      </p>
     </div>

    </div>{{-- /panel-card --}}
   </div>

  </div>{{-- /citas-layout --}}
 </div>{{-- /content --}}
</div>{{-- /main --}}

<script>
// ── Sidebar toggle ──────────────────────────────
function toggleSidebar(){
 document.getElementById('sidebar').classList.toggle('collapsed');
}

// ── Deshabilitar fines de semana en el date picker ──
document.getElementById('inputFecha')?.addEventListener('input', function(){
 const d = new Date(this.value + 'T00:00:00');
 const day = d.getDay();
 if(day === 0 || day === 6){
  this.setCustomValidity('Solo días hábiles (lunes a viernes).');
  this.reportValidity();
  this.value = '';
 } else {
  this.setCustomValidity('');
 }
});

// ── Carga dinámica de módulos por estado ─────────
const modulosPorEstado = @json($modulos->groupBy('estado'));

function cargarModulos(estado){
 const sel = document.getElementById('selectModulo');
 sel.innerHTML = '<option value="">Cargando...</option>';
 sel.disabled = true;

 const lista = modulosPorEstado[estado] ?? [];
 sel.innerHTML = '<option value="">Selecciona un módulo</option>';
 lista.forEach(m => {
  const opt = document.createElement('option');
  opt.value = m.id;
  opt.textContent = m.nombre + ' – ' + m.municipio;
  sel.appendChild(opt);
 });
 sel.disabled = lista.length === 0;
}

// Restaurar módulo si hay old() después de un error de validación
@if(old('estado_filtro'))
 document.getElementById('selectEstado').value = "{{ old('estado_filtro') }}";
 cargarModulos("{{ old('estado_filtro') }}");
 setTimeout(() => {
  document.getElementById('selectModulo').value = "{{ old('modulo_sat_id') }}";
 }, 50);
@endif

// ── Spinner en submit ───────────────────────────
document.getElementById('formCita')?.addEventListener('submit', function(){
 const btn = document.getElementById('btnSubmit');
 btn.disabled = true;
 btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
});
</script>
</body>
</html>