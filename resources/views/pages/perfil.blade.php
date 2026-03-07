<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Portal SAT – Mi Perfil</title>
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

/* ── Profile hero ─────────────────────────────── */
.profile-hero{background:#0d1520;border:1px solid rgba(255,255,255,.07);border-radius:16px;padding:28px;margin-bottom:24px;display:flex;align-items:center;gap:24px;position:relative;overflow:hidden}
.profile-hero::before{content:'';position:absolute;top:-60px;right:-60px;width:220px;height:220px;background:radial-gradient(circle,rgba(0,104,71,.15) 0%,transparent 70%);pointer-events:none}
.hero-avatar-wrap{position:relative;flex-shrink:0}
.hero-avatar{width:90px;height:90px;background:linear-gradient(135deg,#006847,#27ae60);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:34px;font-weight:700;color:white;border:3px solid rgba(74,222,128,.25)}
.hero-avatar-edit{position:absolute;bottom:2px;right:2px;width:26px;height:26px;background:#006847;border:2px solid #0d1520;border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background .2s}
.hero-avatar-edit:hover{background:#00875a}
.hero-avatar-edit i{font-size:11px;color:white}
.hero-info{flex:1}
.hero-name{font-size:22px;font-weight:700;color:white;margin-bottom:4px}
.hero-rfc{font-size:14px;color:rgba(255,255,255,.4);font-family:monospace;margin-bottom:10px}
.hero-badges{display:flex;gap:8px;flex-wrap:wrap}
.hero-badge{font-size:12px;font-weight:600;padding:4px 12px;border-radius:20px;display:flex;align-items:center;gap:6px}
.hero-badge.green{background:rgba(0,104,71,.2);color:#4ade80;border:1px solid rgba(74,222,128,.2)}
.hero-badge.blue{background:rgba(30,100,220,.15);color:#60a5fa;border:1px solid rgba(96,165,250,.2)}
.hero-badge.gray{background:rgba(255,255,255,.06);color:rgba(255,255,255,.45);border:1px solid rgba(255,255,255,.08)}
.hero-stats{display:flex;flex-direction:column;gap:10px;text-align:center;flex-shrink:0}
.hero-stat{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06);border-radius:10px;padding:12px 18px}
.hero-stat-val{font-size:20px;font-weight:700;color:white}
.hero-stat-label{font-size:11px;color:rgba(255,255,255,.35);margin-top:2px}

/* ── Profile grid ─────────────────────────────── */
.profile-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px}
.profile-full{grid-column:1/-1}

/* ── Panel card ───────────────────────────────── */
.panel-card{background:#0d1520;border:1px solid rgba(255,255,255,.07);border-radius:14px;padding:24px}
.panel-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:22px}
.panel-title-wrap{display:flex;align-items:center;gap:10px}
.panel-icon{width:34px;height:34px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0}
.panel-icon.green{background:rgba(0,104,71,.2);color:#4ade80}
.panel-icon.blue{background:rgba(30,100,220,.15);color:#60a5fa}
.panel-icon.orange{background:rgba(200,80,20,.15);color:#fb923c}
.panel-icon.purple{background:rgba(147,51,234,.15);color:#c084fc}
.panel-title{font-size:15px;font-weight:700;color:white}
.panel-subtitle{font-size:12px;color:rgba(255,255,255,.35);margin-top:2px}
.edit-toggle-btn{font-size:13px;font-weight:600;padding:7px 16px;border-radius:8px;border:none;cursor:pointer;display:flex;align-items:center;gap:6px;transition:all .2s;font-family:inherit}
.edit-toggle-btn.btn-edit{background:rgba(255,255,255,.06);color:rgba(255,255,255,.6)}
.edit-toggle-btn.btn-edit:hover{background:rgba(255,255,255,.1);color:white}
.edit-toggle-btn.btn-save{background:linear-gradient(135deg,#006847,#00875a);color:white}
.edit-toggle-btn.btn-save:hover{background:linear-gradient(135deg,#007a54,#009966)}
.edit-toggle-btn.btn-cancel{background:rgba(248,113,113,.1);color:#f87171}
.edit-toggle-btn.btn-cancel:hover{background:rgba(248,113,113,.2)}

/* ── Form fields ──────────────────────────────── */
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.form-group{display:flex;flex-direction:column;gap:6px}
.form-group.full{grid-column:1/-1}
.form-label{font-size:12px;font-weight:600;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:.4px}
.form-value{font-size:14px;color:white;padding:10px 14px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:8px;min-height:40px;display:flex;align-items:center}
.form-value.mono{font-family:monospace;font-size:13px;color:#4ade80}
.form-value.muted{color:rgba(255,255,255,.35);font-style:italic}
.form-input{font-size:14px;color:white;padding:10px 14px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:8px;outline:none;transition:all .2s;font-family:inherit;width:100%}
.form-input::placeholder{color:rgba(255,255,255,.25)}
.form-input:focus{background:rgba(255,255,255,.08);border-color:rgba(0,104,71,.5);box-shadow:0 0 0 3px rgba(0,104,71,.1)}
.form-input:disabled{opacity:.4;cursor:not-allowed}
select.form-input{cursor:pointer}
select.form-input option{background:#1a2535;color:white}
.form-input-wrap{position:relative}
.form-input-wrap .form-input{padding-right:40px}
.form-input-icon{position:absolute;right:13px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,.25);font-size:13px;pointer-events:none}
.field-hint{font-size:11px;color:rgba(255,255,255,.25);margin-top:2px}

/* ── Divider ──────────────────────────────────── */
.divider{height:1px;background:rgba(255,255,255,.06);margin:20px 0}

/* ── Password section ─────────────────────────── */
.password-strength{margin-top:8px;display:flex;flex-direction:column;gap:6px}
.strength-bar-wrap{display:flex;gap:4px;height:4px}
.strength-bar{flex:1;border-radius:2px;background:rgba(255,255,255,.08);transition:background .3s}
.strength-bar.active.weak{background:#f87171}
.strength-bar.active.fair{background:#fb923c}
.strength-bar.active.good{background:#facc15}
.strength-bar.active.strong{background:#4ade80}
.strength-label{font-size:11px;color:rgba(255,255,255,.35)}

/* ── Alert / Toast ────────────────────────────── */
.alert-success{background:rgba(0,104,71,.15);border:1px solid rgba(74,222,128,.25);border-radius:10px;padding:12px 16px;display:flex;align-items:center;gap:10px;font-size:13px;color:#4ade80;margin-bottom:20px;animation:slideDown .3s ease}
.alert-error{background:rgba(200,16,46,.12);border:1px solid rgba(248,113,113,.25);border-radius:10px;padding:12px 16px;display:flex;align-items:center;gap:10px;font-size:13px;color:#f87171;margin-bottom:20px;animation:slideDown .3s ease}
@keyframes slideDown{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:translateY(0)}}

/* ── Session / security table ─────────────────── */
.session-item{display:flex;align-items:center;gap:14px;padding:12px 0;border-bottom:1px solid rgba(255,255,255,.05)}
.session-item:last-child{border-bottom:none}
.session-icon{width:36px;height:36px;background:rgba(255,255,255,.05);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:15px;color:rgba(255,255,255,.4);flex-shrink:0}
.session-info{flex:1}
.session-device{font-size:13px;font-weight:600;color:white}
.session-meta{font-size:11px;color:rgba(255,255,255,.35);margin-top:2px}
.session-current{font-size:11px;font-weight:600;padding:2px 8px;border-radius:10px;background:rgba(74,222,128,.12);color:#4ade80}
.session-revoke{font-size:12px;font-weight:600;padding:5px 12px;border-radius:6px;border:1px solid rgba(248,113,113,.25);background:transparent;color:#f87171;cursor:pointer;transition:all .2s;font-family:inherit}
.session-revoke:hover{background:rgba(248,113,113,.1)}

/* ── 2FA toggle ───────────────────────────────── */
.toggle-wrap{display:flex;align-items:center;justify-content:space-between;padding:14px 0;border-bottom:1px solid rgba(255,255,255,.05)}
.toggle-wrap:last-child{border-bottom:none}
.toggle-info{}
.toggle-name{font-size:14px;font-weight:600;color:white}
.toggle-desc{font-size:12px;color:rgba(255,255,255,.35);margin-top:2px}
.toggle{position:relative;width:44px;height:24px;flex-shrink:0}
.toggle input{opacity:0;width:0;height:0}
.toggle-slider{position:absolute;inset:0;background:rgba(255,255,255,.1);border-radius:12px;cursor:pointer;transition:.3s}
.toggle-slider::before{content:'';position:absolute;width:18px;height:18px;left:3px;top:3px;background:rgba(255,255,255,.4);border-radius:50%;transition:.3s}
.toggle input:checked+.toggle-slider{background:#006847}
.toggle input:checked+.toggle-slider::before{transform:translateX(20px);background:white}

/* ── Action buttons row ───────────────────────── */
.btn-row{display:flex;align-items:center;gap:10px;margin-top:20px}
.btn{font-size:13px;font-weight:600;padding:9px 20px;border-radius:8px;border:none;cursor:pointer;display:flex;align-items:center;gap:7px;transition:all .2s;font-family:inherit}
.btn-primary{background:linear-gradient(135deg,#006847,#00875a);color:white}
.btn-primary:hover{background:linear-gradient(135deg,#007a54,#009966);box-shadow:0 4px 16px rgba(0,104,71,.3)}
.btn-secondary{background:rgba(255,255,255,.06);color:rgba(255,255,255,.6);border:1px solid rgba(255,255,255,.08)}
.btn-secondary:hover{background:rgba(255,255,255,.1);color:white}
.btn-danger{background:rgba(248,113,113,.1);color:#f87171;border:1px solid rgba(248,113,113,.2)}
.btn-danger:hover{background:rgba(248,113,113,.2)}

/* ── Responsive ───────────────────────────────── */
@media(max-width:1100px){.profile-grid{grid-template-columns:1fr}.form-grid{grid-template-columns:1fr}.hero-stats{flex-direction:row}}
@media(max-width:768px){.profile-hero{flex-direction:column;text-align:center}.hero-badges{justify-content:center}.hero-stats{flex-direction:row;justify-content:center}}
@media(max-width:640px){.kpi-grid{grid-template-columns:1fr}.content{padding:16px}.sidebar{display:none}}
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
  <a href="{{ route('contacto.citas.index') }}" class="nav-item">
   <i class="fas fa-calendar-check ni"></i><span class="nav-label">Citas</span>
  </a>

  <div class="sb-section">Cuenta</div>
  <a href="#" class="nav-item">
   <i class="fas fa-calendar ni"></i><span class="nav-label">Calendario fiscal</span>
  </a>
  <a href="{{ route('perfil.index') }}" class="nav-item active">
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
     <div class="tb-uname">{{ Auth::user()->nombres ?? 'Usuario' }}  {{ Auth::user()->primer_apellido ?? '' }} {{ Auth::user()->segundo_apellido ?? '' }}</div>
     <div class="tb-urfc">{{ Auth::user()->rfc ?? 'RFC' }} · CDMX</div>
    </div>
   </div>
  </div>
 </div>

 <div class="content">
  <div class="page-title">Mi Perfil</div>
  <div class="page-sub">Consulta y actualiza tu información personal y configuración de cuenta.</div>

  {{-- Mensajes de sesión --}}
  @if(session('success'))
   <div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
  @endif
  @if(session('error'))
   <div class="alert-error"><i class="fas fa-times-circle"></i> {{ session('error') }}</div>
  @endif
  @if($errors->any())
   <div class="alert-error"><i class="fas fa-times-circle"></i> Por favor revisa los campos marcados.</div>
  @endif

  {{-- Hero card --}}
  <div class="profile-hero">
   <div class="hero-avatar-wrap">
    <div class="hero-avatar">{{ strtoupper(substr(Auth::user()->nombres ?? 'U', 0, 2)) }}</div>
    <div class="hero-avatar-edit" title="Cambiar foto (próximamente)"><i class="fas fa-camera"></i></div>
   </div>
   <div class="hero-info">
    <div class="hero-name">{{ Auth::user()->nombres ?? '' }} {{ Auth::user()->primer_apellido ?? '' }} {{ Auth::user()->segundo_apellido ?? '' }}</div>
    <div class="hero-rfc">{{ Auth::user()->rfc ?? '—' }}</div>
    <div class="hero-badges">
     <span class="hero-badge green"><i class="fas fa-circle" style="font-size:7px"></i> Activo</span>
     <span class="hero-badge blue"><i class="fas fa-user"></i> Persona Física</span>
     <span class="hero-badge gray"><i class="fas fa-calendar-alt"></i> Desde {{ Auth::user()->created_at ? \Carbon\Carbon::parse(Auth::user()->created_at)->format('M Y') : '2024' }}</span>
    </div>
   </div>
   <div class="hero-stats">
    <div class="hero-stat">
     <div class="hero-stat-val">47</div>
     <div class="hero-stat-label">Facturas</div>
    </div>
    <div class="hero-stat">
     <div class="hero-stat-val">12</div>
     <div class="hero-stat-label">Declaraciones</div>
    </div>
   </div>
  </div>

  {{-- Datos personales + Datos fiscales --}}
  <div class="profile-grid">

   {{-- Datos personales --}}
   <div class="panel-card" id="card-personal">
    <div class="panel-header">
     <div class="panel-title-wrap">
      <div class="panel-icon green"><i class="fas fa-user"></i></div>
      <div>
       <div class="panel-title">Datos Personales</div>
       <div class="panel-subtitle">Nombre, CURP y fecha de nacimiento</div>
      </div>
     </div>
     <div style="display:flex;gap:8px">
      <button class="edit-toggle-btn btn-cancel" id="cancel-personal" onclick="cancelEdit('personal')" style="display:none">
       <i class="fas fa-times"></i> Cancelar
      </button>
      <button class="edit-toggle-btn btn-edit" id="btn-personal" onclick="toggleEdit('personal')">
       <i class="fas fa-pen"></i> Editar
      </button>
     </div>
    </div>

    <form action="{{ route('perfil.actualizar') }}" method="POST" id="form-personal" onsubmit="return confirmSave(event,'personal')">
     @csrf @method('PUT')
     <input type="hidden" name="seccion" value="personal">
     <div class="form-grid">
      <div class="form-group">
       <label class="form-label">Nombre(s)</label>
       <div class="form-value view-personal">{{ Auth::user()->nombres ?? '—' }}</div>
       <input class="form-input edit-personal" name="nombres" value="{{ Auth::user()->nombres ?? '' }}" placeholder="Nombre(s)" style="display:none" required>
       @error('nombre')<span style="font-size:11px;color:#f87171">{{ $message }}</span>@enderror
      </div>
      <div class="form-group">
       <label class="form-label">Primer Apellido</label>
       <div class="form-value view-personal">{{ Auth::user()->primer_apellido ?? '—' }}</div>
       <input class="form-input edit-personal" name="primer_apellido" value="{{ Auth::user()->primer_apellido ?? '' }}" placeholder="Primer apellido" style="display:none" required>
       @error('primer_apellido')<span style="font-size:11px;color:#f87171">{{ $message }}</span>@enderror
      </div>
      <div class="form-group">
       <label class="form-label">Segundo Apellido</label>
       <div class="form-value view-personal">{{ Auth::user()->segundo_apellido ?? '—' }}</div>
       <input class="form-input edit-personal" name="segundo_apellido" value="{{ Auth::user()->segundo_apellido ?? '' }}" placeholder="Segundo apellido (opcional)" style="display:none">
      </div>
      <div class="form-group">
       <label class="form-label">Fecha de Nacimiento</label>
       <div class="form-value view-personal">{{ Auth::user()->fecha_nacimiento ? \Carbon\Carbon::parse(Auth::user()->fecha_nacimiento)->format('d/m/Y') : '—' }}</div>
       <input class="form-input edit-personal" type="date" name="fecha_nacimiento" value="{{ Auth::user()->fecha_nacimiento ?? '' }}" style="display:none">
      </div>
      <div class="form-group full">
       <label class="form-label">CURP</label>
       <div class="form-value mono view-personal">{{ Auth::user()->curp ?? '—' }}</div>
       <div class="form-input-wrap edit-personal" style="display:none">
        <input class="form-input" name="curp" value="{{ Auth::user()->curp ?? '' }}" placeholder="CURP de 18 caracteres" maxlength="18" style="text-transform:uppercase">
        <i class="fas fa-id-badge form-input-icon"></i>
       </div>
       <span class="field-hint edit-personal" style="display:none">La CURP no puede modificarse desde este portal si está verificada con el RENAPO.</span>
      </div>
     </div>
     <div class="btn-row edit-personal" style="display:none">
      <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar cambios</button>
     </div>
    </form>
   </div>

   {{-- Datos fiscales --}}
   <div class="panel-card" id="card-fiscal">
    <div class="panel-header">
     <div class="panel-title-wrap">
      <div class="panel-icon blue"><i class="fas fa-landmark"></i></div>
      <div>
       <div class="panel-title">Datos Fiscales</div>
       <div class="panel-subtitle">RFC, régimen y actividad económica</div>
      </div>
     </div>
    </div>
    <div class="form-grid">
     <div class="form-group full">
      <label class="form-label">RFC</label>
      <div class="form-value mono">{{ Auth::user()->rfc ?? '—' }}</div>
      <span class="field-hint">El RFC es asignado por el SAT y no puede modificarse aquí.</span>
     </div>
     <div class="form-group">
      <label class="form-label">Régimen Fiscal</label>
      <div class="form-value">{{ Auth::user()->regimen_fiscal ?? 'Régimen Simplificado de Confianza' }}</div>
     </div>
     <div class="form-group">
      <label class="form-label">Actividad Económica</label>
      <div class="form-value">{{ Auth::user()->actividad_economica ?? 'Servicios Profesionales' }}</div>
     </div>
     <div class="form-group full">
      <label class="form-label">Domicilio Fiscal</label>
      <div class="form-value">{{ Auth::user()->domicilio_fiscal ?? 'No registrado' }}</div>
     </div>
     <div class="form-group">
      <label class="form-label">Fecha de Inscripción al RFC</label>
      <div class="form-value">{{ Auth::user()->fecha_inscripcion ? \Carbon\Carbon::parse(Auth::user()->fecha_inscripcion)->format('d/m/Y') : '—' }}</div>
     </div>
     <div class="form-group">
      <label class="form-label">Estado</label>
      <div class="form-value" style="color:#4ade80"><i class="fas fa-circle" style="font-size:8px;margin-right:6px"></i> Activo ante el SAT</div>
     </div>
    </div>
   </div>

   {{-- Datos de contacto --}}
   <div class="panel-card" id="card-contacto">
    <div class="panel-header">
     <div class="panel-title-wrap">
      <div class="panel-icon orange"><i class="fas fa-address-book"></i></div>
      <div>
       <div class="panel-title">Datos de Contacto</div>
       <div class="panel-subtitle">Correo, teléfono y notificaciones</div>
      </div>
     </div>
     <div style="display:flex;gap:8px">
      <button class="edit-toggle-btn btn-cancel" id="cancel-contacto" onclick="cancelEdit('contacto')" style="display:none">
       <i class="fas fa-times"></i> Cancelar
      </button>
      <button class="edit-toggle-btn btn-edit" id="btn-contacto" onclick="toggleEdit('contacto')">
       <i class="fas fa-pen"></i> Editar
      </button>
     </div>
    </div>
    <form action="{{ route('perfil.actualizar') }}" method="POST" id="form-contacto" onsubmit="return confirmSave(event,'contacto')">
     @csrf @method('PUT')
     <input type="hidden" name="seccion" value="contacto">
     <div class="form-grid">
      <div class="form-group full">
       <label class="form-label">Correo Electrónico</label>
       <div class="form-value view-contacto">{{ Auth::user()->email ?? '—' }}</div>
       <div class="form-input-wrap edit-contacto" style="display:none">
        <input class="form-input" name="email" type="email" value="{{ Auth::user()->email ?? '' }}" placeholder="correo@ejemplo.com" required>
        <i class="fas fa-envelope form-input-icon"></i>
       </div>
       @error('email')<span style="font-size:11px;color:#f87171">{{ $message }}</span>@enderror
      </div>
      <div class="form-group">
       <label class="form-label">Teléfono Celular</label>
       <div class="form-value view-contacto">{{ Auth::user()->telefono ?? '—' }}</div>
       <div class="form-input-wrap edit-contacto" style="display:none">
        <input class="form-input" name="telefono" type="tel" value="{{ Auth::user()->telefono ?? '' }}" placeholder="10 dígitos" maxlength="10">
        <i class="fas fa-mobile-alt form-input-icon"></i>
       </div>
      </div>
      <div class="form-group">
       <label class="form-label">Teléfono Fijo</label>
       <div class="form-value view-contacto">{{ Auth::user()->telefono_fijo ?? '—' }}</div>
       <div class="form-input-wrap edit-contacto" style="display:none">
        <input class="form-input" name="telefono_fijo" type="tel" value="{{ Auth::user()->telefono_fijo ?? '' }}" placeholder="10 dígitos" maxlength="10">
        <i class="fas fa-phone form-input-icon"></i>
       </div>
      </div>
     </div>
     <div class="btn-row edit-contacto" style="display:none">
      <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar cambios</button>
     </div>
    </form>
   </div>

   {{-- Seguridad --}}
   <div class="panel-card" id="card-seguridad">
    <div class="panel-header">
     <div class="panel-title-wrap">
      <div class="panel-icon purple"><i class="fas fa-shield-alt"></i></div>
      <div>
       <div class="panel-title">Seguridad</div>
       <div class="panel-subtitle">Contraseña y autenticación</div>
      </div>
     </div>
    </div>

    <form action="{{ route('perfil.password') }}" method="POST" id="form-password">
     @csrf @method('PUT')
     <div class="form-grid">
      <div class="form-group full">
       <label class="form-label">Contraseña Actual</label>
       <div class="form-input-wrap">
        <input class="form-input" type="password" name="password_actual" id="pw-actual" placeholder="Tu contraseña actual" autocomplete="current-password">
        <i class="fas fa-eye form-input-icon" style="cursor:pointer" onclick="togglePw('pw-actual',this)"></i>
       </div>
       @error('password_actual')<span style="font-size:11px;color:#f87171">{{ $message }}</span>@enderror
      </div>
      <div class="form-group">
       <label class="form-label">Nueva Contraseña</label>
       <div class="form-input-wrap">
        <input class="form-input" type="password" name="password" id="pw-new" placeholder="Mínimo 8 caracteres" oninput="checkStrength(this.value)" autocomplete="new-password">
        <i class="fas fa-eye form-input-icon" style="cursor:pointer" onclick="togglePw('pw-new',this)"></i>
       </div>
       <div class="password-strength">
        <div class="strength-bar-wrap">
         <div class="strength-bar" id="sb1"></div>
         <div class="strength-bar" id="sb2"></div>
         <div class="strength-bar" id="sb3"></div>
         <div class="strength-bar" id="sb4"></div>
        </div>
        <span class="strength-label" id="strength-label">Ingresa una contraseña</span>
       </div>
       @error('password')<span style="font-size:11px;color:#f87171">{{ $message }}</span>@enderror
      </div>
      <div class="form-group">
       <label class="form-label">Confirmar Nueva Contraseña</label>
       <div class="form-input-wrap">
        <input class="form-input" type="password" name="password_confirmation" id="pw-confirm" placeholder="Repite la nueva contraseña" autocomplete="new-password">
        <i class="fas fa-eye form-input-icon" style="cursor:pointer" onclick="togglePw('pw-confirm',this)"></i>
       </div>
      </div>
     </div>
     <div class="btn-row">
      <button type="submit" class="btn btn-primary"><i class="fas fa-key"></i> Cambiar contraseña</button>
     </div>
    </form>

    <div class="divider"></div>

    <div class="toggle-wrap">
     <div class="toggle-info">
      <div class="toggle-name">Autenticación de dos factores (2FA)</div>
      <div class="toggle-desc">Recibe un código por SMS o correo al iniciar sesión</div>
     </div>
     <label class="toggle">
      <input type="checkbox" id="toggle-2fa" onchange="toggle2FA(this.checked)" {{ Auth::user()->two_factor_enabled ?? false ? 'checked' : '' }}>
      <span class="toggle-slider"></span>
     </label>
    </div>
    <div class="toggle-wrap">
     <div class="toggle-info">
      <div class="toggle-name">Notificaciones por correo</div>
      <div class="toggle-desc">Alertas de vencimientos y actividad de cuenta</div>
     </div>
     <label class="toggle">
      <input type="checkbox" {{ Auth::user()->notificaciones_email ?? true ? 'checked' : '' }}>
      <span class="toggle-slider"></span>
     </label>
    </div>
   </div>

  </div>{{-- /profile-grid --}}

  {{-- Sesiones activas --}}
  <div class="panel-card" style="margin-bottom:24px">
   <div class="panel-header">
    <div class="panel-title-wrap">
     <div class="panel-icon blue"><i class="fas fa-laptop"></i></div>
     <div>
      <div class="panel-title">Sesiones Activas</div>
      <div class="panel-subtitle">Dispositivos con acceso a tu cuenta</div>
     </div>
    </div>
    <form action="{{ route('perfil.sesiones.cerrar') }}" method="POST">
     @csrf
     <button type="submit" class="btn btn-danger" onclick="return confirm('¿Cerrar todas las demás sesiones?')">
      <i class="fas fa-sign-out-alt"></i> Cerrar todas
     </button>
    </form>
   </div>

   <div class="session-item">
    <div class="session-icon"><i class="fas fa-desktop"></i></div>
    <div class="session-info">
     <div class="session-device">Windows · Chrome 122 <span class="session-current">Sesión actual</span></div>
     <div class="session-meta">Ciudad de México · Hoy a las {{ now()->format('H:i') }}</div>
    </div>
   </div>
   <div class="session-item">
    <div class="session-icon"><i class="fas fa-mobile-alt"></i></div>
    <div class="session-info">
     <div class="session-device">Android · Chrome Mobile</div>
     <div class="session-meta">Ciudad de México · Hace 2 horas</div>
    </div>
    <form action="{{ route('perfil.sesiones.revocar') }}" method="POST">
     @csrf
     <input type="hidden" name="session_id" value="mobile_session_id">
     <button type="submit" class="session-revoke">Revocar</button>
    </form>
   </div>

  </div>

  {{-- Zona de peligro --}}
  <div class="panel-card" style="border-color:rgba(248,113,113,.15)">
   <div class="panel-header">
    <div class="panel-title-wrap">
     <div class="panel-icon" style="background:rgba(248,113,113,.1);color:#f87171"><i class="fas fa-exclamation-triangle"></i></div>
     <div>
      <div class="panel-title" style="color:#f87171">Zona de Peligro</div>
      <div class="panel-subtitle">Acciones irreversibles sobre tu cuenta</div>
     </div>
    </div>
   </div>
   <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 0;border-bottom:1px solid rgba(255,255,255,.05)">
    <div>
     <div style="font-size:14px;font-weight:600;color:white">Exportar mis datos</div>
     <div style="font-size:12px;color:rgba(255,255,255,.35);margin-top:2px">Descarga toda la información fiscal asociada a tu cuenta</div>
    </div>
    <a href="{{ route('perfil.exportar') }}" class="btn btn-secondary"><i class="fas fa-download"></i> Exportar</a>
   </div>
   <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 0">
    <div>
     <div style="font-size:14px;font-weight:600;color:#f87171">Eliminar cuenta</div>
     <div style="font-size:12px;color:rgba(255,255,255,.35);margin-top:2px">Esta acción eliminará permanentemente tu acceso al portal</div>
    </div>
    <button class="btn btn-danger" onclick="confirmDelete()"><i class="fas fa-trash-alt"></i> Eliminar</button>
   </div>
  </div>

 </div>{{-- /content --}}
</div>{{-- /main --}}

{{-- Modal confirmación eliminar --}}
<div id="modal-delete" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:999;display:none;align-items:center;justify-content:center">
 <div style="background:#0d1520;border:1px solid rgba(248,113,113,.25);border-radius:16px;padding:32px;max-width:420px;width:90%;animation:slideDown .25s ease">
  <div style="font-size:40px;text-align:center;margin-bottom:16px">⚠️</div>
  <div style="font-size:18px;font-weight:700;color:white;text-align:center;margin-bottom:8px">¿Eliminar cuenta?</div>
  <div style="font-size:14px;color:rgba(255,255,255,.45);text-align:center;margin-bottom:24px;line-height:1.6">Esta acción es <strong style="color:#f87171">irreversible</strong>. Se eliminará todo acceso al portal. Tu información fiscal en el SAT no se verá afectada.</div>
  <form action="{{ route('perfil.eliminar') }}" method="POST">
   @csrf @method('DELETE')
   <input class="form-input" type="password" name="password_confirm" placeholder="Confirma tu contraseña para continuar" style="margin-bottom:16px">
   <div style="display:flex;gap:10px">
    <button type="button" class="btn btn-secondary" style="flex:1;justify-content:center" onclick="closeModal()">Cancelar</button>
    <button type="submit" class="btn btn-danger" style="flex:1;justify-content:center"><i class="fas fa-trash-alt"></i> Eliminar</button>
   </div>
  </form>
 </div>
</div>

<script>
// ── Sidebar toggle ──────────────────────────────
function toggleSidebar(){
 document.getElementById('sidebar').classList.toggle('collapsed');
}

// ── Edit mode toggle ────────────────────────────
function toggleEdit(section){
 const viewEls  = document.querySelectorAll('.view-'+section);
 const editEls  = document.querySelectorAll('.edit-'+section);
 const btn      = document.getElementById('btn-'+section);
 const cancelBtn= document.getElementById('cancel-'+section);
 const isEditing= btn.classList.contains('btn-save');

 if(!isEditing){
  viewEls.forEach(el=>el.style.display='none');
  editEls.forEach(el=>el.style.display='flex');
  btn.innerHTML='<i class="fas fa-save"></i> Guardar';
  btn.classList.replace('btn-edit','btn-save');
  cancelBtn.style.display='flex';
 } else {
  document.getElementById('form-'+section).submit();
 }
}

function cancelEdit(section){
 const viewEls  = document.querySelectorAll('.view-'+section);
 const editEls  = document.querySelectorAll('.edit-'+section);
 const btn      = document.getElementById('btn-'+section);
 const cancelBtn= document.getElementById('cancel-'+section);

 viewEls.forEach(el=>el.style.display='flex');
 editEls.forEach(el=>el.style.display='none');
 btn.innerHTML='<i class="fas fa-pen"></i> Editar';
 btn.classList.replace('btn-save','btn-edit');
 cancelBtn.style.display='none';
}

function confirmSave(e, section){ return true; }

// ── Password visibility ─────────────────────────
function togglePw(id, icon){
 const inp=document.getElementById(id);
 if(inp.type==='password'){inp.type='text';icon.classList.replace('fa-eye','fa-eye-slash')}
 else{inp.type='password';icon.classList.replace('fa-eye-slash','fa-eye')}
}

// ── Password strength ───────────────────────────
function checkStrength(val){
 const bars=[1,2,3,4].map(i=>document.getElementById('sb'+i));
 const label=document.getElementById('strength-label');
 const classes=['weak','fair','good','strong'];
 const labels=['Muy débil','Regular','Buena','Muy segura'];
 let score=0;
 if(val.length>=8) score++;
 if(/[A-Z]/.test(val)) score++;
 if(/[0-9]/.test(val)) score++;
 if(/[^A-Za-z0-9]/.test(val)) score++;
 bars.forEach((b,i)=>{
  b.className='strength-bar';
  if(i<score) b.classList.add('active', classes[score-1]);
 });
 label.textContent=val.length===0?'Ingresa una contraseña':labels[score-1]||labels[0];
 label.style.color=['#f87171','#fb923c','#facc15','#4ade80'][score-1]||'rgba(255,255,255,.35)';
}

// ── 2FA toggle ──────────────────────────────────
function toggle2FA(enabled){
 fetch('{{ route("perfil.2fa") }}',{
  method:'POST',
  headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},
  body:JSON.stringify({enabled})
 }).then(r=>r.json()).then(d=>{
  if(!d.ok) alert('Error al actualizar la configuración.');
 });
}

// ── Delete modal ────────────────────────────────
function confirmDelete(){
 const modal=document.getElementById('modal-delete');
 modal.style.display='flex';
}
function closeModal(){
 document.getElementById('modal-delete').style.display='none';
}
document.getElementById('modal-delete')?.addEventListener('click',function(e){
 if(e.target===this) closeModal();
});

// ── Auto-dismiss alerts ──────────────────────── 
setTimeout(()=>{
 document.querySelectorAll('.alert-success,.alert-error').forEach(el=>{
  el.style.transition='opacity .5s';
  el.style.opacity='0';
  setTimeout(()=>el.remove(),500);
 });
},4000);
</script>
</body>
</html>