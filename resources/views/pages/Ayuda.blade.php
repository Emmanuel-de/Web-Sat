<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Portal SAT – Ayuda</title>
<link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Source Sans Pro',sans-serif;background:#0f1923;color:#e2e8f0;display:flex;min-height:100vh;overflow-x:hidden}

/* ── Sidebar ── */
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

/* ── Main ── */
.main{flex:1;margin-left:220px;transition:margin-left .25s;min-height:100vh;display:flex;flex-direction:column}
.sidebar.collapsed~.main{margin-left:64px}

/* ── Topbar ── */
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

/* ── Content ── */
.content{flex:1;padding:28px;overflow-y:auto}
.page-title{font-size:26px;font-weight:700;color:white;margin-bottom:4px}
.page-sub{font-size:14px;color:rgba(255,255,255,.45);margin-bottom:28px}

/* ── Hero banner ── */
.help-hero{background:linear-gradient(135deg,#006847 0%,#004d35 60%,#003d2a 100%);border-radius:16px;padding:36px 32px;margin-bottom:32px;position:relative;overflow:hidden}
.help-hero::before{content:'';position:absolute;right:-60px;top:-60px;width:220px;height:220px;background:rgba(255,255,255,.04);border-radius:50%}
.help-hero::after{content:'';position:absolute;right:60px;bottom:-80px;width:160px;height:160px;background:rgba(255,255,255,.03);border-radius:50%}
.help-hero-inner{position:relative;z-index:1;display:flex;align-items:center;gap:20px;flex-wrap:wrap}
.help-hero-icon{width:56px;height:56px;background:rgba(255,255,255,.15);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:24px;color:white;flex-shrink:0}
.help-hero-text h1{font-size:22px;font-weight:700;color:white;margin-bottom:6px}
.help-hero-text p{font-size:14px;color:rgba(255,255,255,.65);max-width:560px}
.help-search{margin-top:20px;position:relative;max-width:500px}
.help-search input{width:100%;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);border-radius:10px;padding:12px 18px 12px 44px;font-size:14px;color:white;outline:none;font-family:inherit;transition:all .2s}
.help-search input::placeholder{color:rgba(255,255,255,.45)}
.help-search input:focus{background:rgba(255,255,255,.18);border-color:rgba(255,255,255,.4)}
.help-search i{position:absolute;left:16px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,.5);font-size:14px;pointer-events:none}

/* ── Contact cards ── */
.contact-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:32px}
.contact-card{background:#0d1520;border:1px solid rgba(255,255,255,.07);border-radius:14px;padding:28px 24px;text-align:center;transition:all .25s;position:relative;overflow:hidden}
.contact-card::before{content:'';position:absolute;inset:0;opacity:0;transition:opacity .25s;border-radius:14px}
.contact-card:hover{border-color:rgba(255,255,255,.13);transform:translateY(-2px);box-shadow:0 8px 32px rgba(0,0,0,.3)}
.contact-card.phone::before{background:radial-gradient(circle at 50% 0%,rgba(200,16,46,.08),transparent 70%)}
.contact-card.chat::before{background:radial-gradient(circle at 50% 0%,rgba(0,104,71,.1),transparent 70%)}
.contact-card.module::before{background:radial-gradient(circle at 50% 0%,rgba(251,191,36,.06),transparent 70%)}
.contact-card:hover::before{opacity:1}
.contact-card-icon{width:56px;height:56px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:22px;margin:0 auto 16px}
.contact-card-icon.red{background:rgba(200,16,46,.12);color:#f87171}
.contact-card-icon.green{background:rgba(0,104,71,.2);color:#4ade80}
.contact-card-icon.yellow{background:rgba(251,191,36,.1);color:#fbbf24}
.contact-card h3{font-size:15px;font-weight:700;color:white;margin-bottom:8px}
.contact-card p{font-size:13px;color:rgba(255,255,255,.45);line-height:1.5;margin-bottom:16px;min-height:36px}
.contact-phone{font-size:26px;font-weight:700;color:#c8102e;font-family:monospace;letter-spacing:1px;margin-bottom:4px}
.contact-hours{font-size:12px;color:rgba(255,255,255,.35);line-height:1.5}
.btn-contact{display:inline-flex;align-items:center;gap:7px;padding:10px 22px;border-radius:8px;font-size:13px;font-weight:700;cursor:pointer;transition:all .2s;text-decoration:none;font-family:inherit;border:none}
.btn-contact.green{background:linear-gradient(135deg,#006847,#00875a);color:white}
.btn-contact.green:hover{background:linear-gradient(135deg,#007a54,#009966);box-shadow:0 4px 16px rgba(0,104,71,.35)}
.btn-contact.outline{background:transparent;border:1px solid rgba(255,255,255,.15);color:rgba(255,255,255,.7)}
.btn-contact.outline:hover{background:rgba(255,255,255,.06);color:white;border-color:rgba(255,255,255,.25)}

/* ── Panel card ── */
.panel-card{background:#0d1520;border:1px solid rgba(255,255,255,.07);border-radius:14px;padding:24px;margin-bottom:24px}
.panel-header{display:flex;align-items:center;gap:10px;margin-bottom:20px}
.panel-icon{width:34px;height:34px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0}
.panel-icon.green{background:rgba(0,104,71,.2);color:#4ade80}
.panel-icon.blue{background:rgba(30,100,220,.15);color:#60a5fa}
.panel-icon.orange{background:rgba(200,80,20,.15);color:#fb923c}
.panel-icon.yellow{background:rgba(251,191,36,.1);color:#fbbf24}
.panel-title{font-size:15px;font-weight:700;color:white}
.panel-subtitle{font-size:12px;color:rgba(255,255,255,.35);margin-top:2px}

/* ── FAQ accordion ── */
.faq-list{display:flex;flex-direction:column;gap:8px}
.faq-item{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:10px;overflow:hidden;transition:border-color .2s}
.faq-item.open{border-color:rgba(0,104,71,.3)}
.faq-q{display:flex;align-items:center;justify-content:space-between;padding:16px 18px;cursor:pointer;gap:12px;user-select:none}
.faq-q:hover{background:rgba(255,255,255,.02)}
.faq-q-text{font-size:14px;font-weight:600;color:rgba(255,255,255,.85);flex:1}
.faq-item.open .faq-q-text{color:#4ade80}
.faq-arrow{width:24px;height:24px;background:rgba(255,255,255,.06);border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:11px;color:rgba(255,255,255,.4);flex-shrink:0;transition:all .25s}
.faq-item.open .faq-arrow{background:rgba(0,104,71,.2);color:#4ade80;transform:rotate(180deg)}
.faq-a{max-height:0;overflow:hidden;transition:max-height .35s ease,padding .25s}
.faq-item.open .faq-a{max-height:400px;padding-bottom:16px}
.faq-a-inner{padding:0 18px;font-size:13px;color:rgba(255,255,255,.55);line-height:1.7}
.faq-a-inner a{color:#4ade80;text-decoration:none}
.faq-a-inner a:hover{text-decoration:underline}
.faq-a-inner ul{margin:8px 0 0 18px}
.faq-a-inner ul li{margin-bottom:4px}

/* ── Guides grid ── */
.guides-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
.guide-card{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:10px;padding:18px;display:flex;align-items:flex-start;gap:14px;transition:all .2s;cursor:pointer;text-decoration:none}
.guide-card:hover{background:rgba(255,255,255,.05);border-color:rgba(255,255,255,.1);transform:translateY(-1px)}
.guide-card-icon{width:38px;height:38px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0}
.guide-card-icon.green{background:rgba(0,104,71,.2);color:#4ade80}
.guide-card-icon.blue{background:rgba(30,100,220,.15);color:#60a5fa}
.guide-card-icon.orange{background:rgba(200,80,20,.15);color:#fb923c}
.guide-card-icon.purple{background:rgba(139,92,246,.12);color:#a78bfa}
.guide-card-icon.yellow{background:rgba(251,191,36,.1);color:#fbbf24}
.guide-card-icon.red{background:rgba(200,16,46,.1);color:#f87171}
.guide-card-info h4{font-size:13px;font-weight:700;color:white;margin-bottom:4px}
.guide-card-info p{font-size:12px;color:rgba(255,255,255,.4);line-height:1.5}
.guide-tag{font-size:10px;font-weight:700;padding:2px 8px;border-radius:10px;display:inline-block;margin-top:6px}
.guide-tag.new{background:rgba(74,222,128,.12);color:#4ade80;border:1px solid rgba(74,222,128,.2)}
.guide-tag.popular{background:rgba(251,191,36,.1);color:#fbbf24;border:1px solid rgba(251,191,36,.2)}

/* ── Quick links ── */
.quick-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px}
.quick-card{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:10px;padding:16px;text-align:center;transition:all .2s;text-decoration:none;display:block}
.quick-card:hover{background:rgba(255,255,255,.06);border-color:rgba(255,255,255,.1);transform:translateY(-1px)}
.quick-card i{font-size:20px;margin-bottom:10px;display:block}
.quick-card span{font-size:12px;font-weight:600;color:rgba(255,255,255,.6);display:block}

/* ── Status banner ── */
.status-banner{background:rgba(0,104,71,.08);border:1px solid rgba(74,222,128,.15);border-radius:10px;padding:14px 18px;display:flex;align-items:center;gap:12px;margin-bottom:24px}
.status-dot{width:8px;height:8px;background:#4ade80;border-radius:50%;flex-shrink:0;box-shadow:0 0 8px rgba(74,222,128,.5);animation:pulse 2s infinite}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.5}}
.status-text{font-size:13px;color:rgba(255,255,255,.65)}
.status-text strong{color:#4ade80}

/* ── Two col layout ── */
.two-col{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px}

/* ── Info row ── */
.info-row{display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid rgba(255,255,255,.04)}
.info-row:last-child{border-bottom:none}
.info-row-icon{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0}
.info-row-text{flex:1}
.info-row-label{font-size:12px;color:rgba(255,255,255,.35);font-weight:600;text-transform:uppercase;letter-spacing:.3px}
.info-row-val{font-size:13px;color:rgba(255,255,255,.75);margin-top:2px}

/* ── Responsive ── */
@media(max-width:1100px){.contact-grid{grid-template-columns:1fr 1fr}.guides-grid{grid-template-columns:1fr 1fr}.quick-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:640px){.content{padding:16px}.sidebar{display:none}.contact-grid,.two-col{grid-template-columns:1fr}.guides-grid{grid-template-columns:1fr}.quick-grid{grid-template-columns:repeat(2,1fr)}}
</style>
</head>
<body>

{{-- ── Sidebar ── --}}
<nav class="sidebar" id="sidebar">
  <div class="sb-top">
    <div class="sb-logo"><i class="fas fa-landmark"></i></div>
    <div class="sb-brand">Portal SAT<small>Sistema de Administración Tributaria</small></div>
    <button class="sb-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
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
    <a href="{{ route('perfil.index') }}" class="nav-item">
      <i class="fas fa-user-circle ni"></i><span class="nav-label">Mi Perfil</span>
    </a>
  </div>
  <div>
    <a href="{{ route('ayuda') }}" class="nav-item active" style="margin:0 10px 6px">
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
      <div>
        <div class="sb-user-name">{{ Auth::user()->nombres ?? 'Usuarios' }} {{ Auth::user()->primer_apellido ?? '' }}</div>
        <div class="sb-user-rfc">{{ Auth::user()->rfc ?? 'RFC' }}</div>
      </div>
    </div>
  </div>
</nav>

{{-- ── Main ── --}}
<div class="main" id="main">
  <div class="topbar">
    <div class="search-wrap">
      <i class="fas fa-search search-icon"></i>
      <input type="text" class="search-input" placeholder="Buscar trámites, facturas, declaraciones...">
    </div>
    <div class="topbar-right">
      <button class="tb-icon-btn">
        <i class="fas fa-bell"></i>
        <span class="tb-notif-dot"></span>
      </button>
      <div class="tb-user">
        <div class="tb-av">{{ strtoupper(substr(Auth::user()->nombres ?? 'U', 0, 2)) }}</div>
        <div>
          <div class="tb-uname">{{ Auth::user()->nombres ?? 'Usuarios' }} {{ Auth::user()->primer_apellido ?? '' }} {{ Auth::user()->segundo_apellido ?? '' }}</div>
          <div class="tb-urfc">{{ Auth::user()->rfc ?? 'RFC' }} · CDMX</div>
        </div>
      </div>
    </div>
  </div>

  <div class="content">

    {{-- ── Hero ── --}}
    <div class="help-hero">
      <div class="help-hero-inner">
        <div class="help-hero-icon"><i class="fas fa-headset"></i></div>
        <div class="help-hero-text">
          <h1>Centro de Ayuda y Atención al Contribuyente</h1>
          <p>Encuentra respuestas, guías y canales de contacto para resolver tus dudas fiscales.</p>
        </div>
      </div>
      <div class="help-search">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="¿En qué podemos ayudarte? Ej: cómo presentar declaración..." id="help-search-input" oninput="filterFaq(this.value)">
      </div>
    </div>

    {{-- ── Status ── --}}
    <div class="status-banner">
      <div class="status-dot"></div>
      <span class="status-text"><strong>Todos los sistemas operativos</strong> — Portal SAT, timbrado CFDI y servicios en línea funcionando con normalidad. Última verificación: hace 5 minutos.</span>
    </div>

    {{-- ── Accesos rápidos ── --}}
    <div class="panel-card" style="margin-bottom:24px">
      <div class="panel-header">
        <div class="panel-icon green"><i class="fas fa-bolt"></i></div>
        <div>
          <div class="panel-title">Accesos Rápidos</div>
          <div class="panel-subtitle">Los trámites más solicitados</div>
        </div>
      </div>
      <div class="quick-grid">
        <a href="{{ route('declaraciones.usuario') }}" class="quick-card">
          <i class="fas fa-file-invoice" style="color:#4ade80"></i>
          <span>Presentar Declaración</span>
        </a>
        <a href="{{ route('facturacion.mis_facturas') }}" class="quick-card">
          <i class="fas fa-file-invoice-dollar" style="color:#60a5fa"></i>
          <span>Emitir CFDI</span>
        </a>
        <a href="{{ route('personas.cif') }}" class="quick-card">
          <i class="fas fa-file-alt" style="color:#fb923c"></i>
          <span>Constancia Fiscal</span>
        </a>
        <a href="{{ route('contacto.citas.index') }}" class="quick-card">
          <i class="fas fa-calendar-check" style="color:#fbbf24"></i>
          <span>Agendar Cita</span>
        </a>
        <a href="{{ route('personas.rfc') }}" class="quick-card">
          <i class="fas fa-id-card" style="color:#a78bfa"></i>
          <span>Consultar RFC</span>
        </a>
        <a href="{{ route('personas.e_firma') }}" class="quick-card">
          <i class="fas fa-signature" style="color:#f87171"></i>
          <span>e.firma</span>
        </a>
        <a href="{{ route('perfil.index') }}" class="quick-card">
          <i class="fas fa-user-cog" style="color:#34d399"></i>
          <span>Mi Perfil</span>
        </a>
        <a href="#faq" class="quick-card">
          <i class="fas fa-question-circle" style="color:#60a5fa"></i>
          <span>Preguntas Frecuentes</span>
        </a>
      </div>
    </div>

    {{-- ── Contacto y Atención ── --}}
    <div class="panel-header" style="margin-bottom:16px">
      <div class="panel-icon green"><i class="fas fa-headset"></i></div>
      <div>
        <div class="panel-title" style="font-size:17px">Contacto y Atención al Contribuyente</div>
        <div class="panel-subtitle">Agenda una cita, envía una consulta o contáctanos por teléfono</div>
      </div>
    </div>

    <div class="contact-grid" style="margin-bottom:32px">

      {{-- Teléfono --}}
      <div class="contact-card phone">
        <div class="contact-card-icon red"><i class="fas fa-phone-alt"></i></div>
        <h3>SAT MarcaSAT</h3>
        <p>Atención telefónica directa con asesores del SAT</p>
        <div class="contact-phone">55 627 22 728</div>
        <div class="contact-hours">
          Lunes a Viernes<br>8:00 a 21:00 hrs<br>
          <span style="color:rgba(255,255,255,.25);font-size:11px;margin-top:4px;display:block">Sábados 9:00 a 13:00 hrs</span>
        </div>
      </div>

      {{-- Chat --}}
      <div class="contact-card chat">
        <div class="contact-card-icon green"><i class="fas fa-comments"></i></div>
        <h3>Chat en línea</h3>
        <p>Atención inmediata por chat con un asesor del SAT</p>
        <a href="https://chat.sat.gob.mx" target="_blank" class="btn-contact green">
          <i class="fas fa-comments"></i> Iniciar chat
        </a>
        <div class="contact-hours" style="margin-top:12px">
          Lunes a Viernes<br>8:00 a 21:00 hrs
        </div>
      </div>

      {{-- Módulos --}}
      <div class="contact-card module">
        <div class="contact-card-icon yellow"><i class="fas fa-map-marker-alt"></i></div>
        <h3>Módulos de servicio</h3>
        <p>Encuentra el módulo SAT más cercano a tu domicilio</p>
        <a href="{{ route('contacto.index') }}" class="btn-contact outline">
          <i class="fas fa-search-location"></i> Buscar módulo
        </a>
        <div class="contact-hours" style="margin-top:12px">
          Atención presencial<br>con cita previa
        </div>
      </div>

    </div>

    {{-- ── Dos columnas: Horarios + Canales digitales ── --}}
    <div class="two-col">

      <div class="panel-card" style="margin-bottom:0">
        <div class="panel-header">
          <div class="panel-icon orange"><i class="fas fa-clock"></i></div>
          <div>
            <div class="panel-title">Horarios de Atención</div>
            <div class="panel-subtitle">Todos los canales disponibles</div>
          </div>
        </div>
        <div class="info-row">
          <div class="info-row-icon" style="background:rgba(200,16,46,.1)"><i class="fas fa-phone" style="color:#f87171;font-size:12px"></i></div>
          <div class="info-row-text">
            <div class="info-row-label">MarcaSAT</div>
            <div class="info-row-val">Lunes a Viernes 8:00–21:00 · Sábados 9:00–13:00</div>
          </div>
        </div>
        <div class="info-row">
          <div class="info-row-icon" style="background:rgba(0,104,71,.15)"><i class="fas fa-comments" style="color:#4ade80;font-size:12px"></i></div>
          <div class="info-row-text">
            <div class="info-row-label">Chat en línea</div>
            <div class="info-row-val">Lunes a Viernes 8:00–21:00</div>
          </div>
        </div>
        <div class="info-row">
          <div class="info-row-icon" style="background:rgba(251,191,36,.08)"><i class="fas fa-building" style="color:#fbbf24;font-size:12px"></i></div>
          <div class="info-row-text">
            <div class="info-row-label">Módulos presenciales</div>
            <div class="info-row-val">Lunes a Viernes 9:00–16:00 (con cita)</div>
          </div>
        </div>
        <div class="info-row">
          <div class="info-row-icon" style="background:rgba(96,165,250,.1)"><i class="fas fa-globe" style="color:#60a5fa;font-size:12px"></i></div>
          <div class="info-row-text">
            <div class="info-row-label">Portal en línea</div>
            <div class="info-row-val">Disponible las 24 horas, los 365 días</div>
          </div>
        </div>
        <div class="info-row">
          <div class="info-row-icon" style="background:rgba(167,139,250,.1)"><i class="fas fa-envelope" style="color:#a78bfa;font-size:12px"></i></div>
          <div class="info-row-text">
            <div class="info-row-label">Correo electrónico</div>
            <div class="info-row-val">orientacion@sat.gob.mx</div>
          </div>
        </div>
      </div>

      <div class="panel-card" style="margin-bottom:0">
        <div class="panel-header">
          <div class="panel-icon blue"><i class="fas fa-mobile-alt"></i></div>
          <div>
            <div class="panel-title">Canales Digitales Oficiales</div>
            <div class="panel-subtitle">Plataformas del SAT</div>
          </div>
        </div>
        <div class="info-row">
          <div class="info-row-icon" style="background:rgba(96,165,250,.1)"><i class="fas fa-globe" style="color:#60a5fa;font-size:12px"></i></div>
          <div class="info-row-text">
            <div class="info-row-label">Portal oficial SAT</div>
            <div class="info-row-val"><a href="https://sat.gob.mx" target="_blank" style="color:#4ade80;text-decoration:none">www.sat.gob.mx</a></div>
          </div>
        </div>
        <div class="info-row">
          <div class="info-row-icon" style="background:rgba(29,161,242,.1)"><i class="fab fa-twitter" style="color:#1da1f2;font-size:12px"></i></div>
          <div class="info-row-text">
            <div class="info-row-label">Twitter / X</div>
            <div class="info-row-val"><a href="https://twitter.com/SATMX" target="_blank" style="color:#4ade80;text-decoration:none">@SATMX</a></div>
          </div>
        </div>
        <div class="info-row">
          <div class="info-row-icon" style="background:rgba(24,119,242,.1)"><i class="fab fa-facebook" style="color:#1877f2;font-size:12px"></i></div>
          <div class="info-row-text">
            <div class="info-row-label">Facebook</div>
            <div class="info-row-val"><a href="https://facebook.com/SATMX" target="_blank" style="color:#4ade80;text-decoration:none">SAT México Oficial</a></div>
          </div>
        </div>
        <div class="info-row">
          <div class="info-row-icon" style="background:rgba(255,0,0,.08)"><i class="fab fa-youtube" style="color:#f87171;font-size:12px"></i></div>
          <div class="info-row-text">
            <div class="info-row-label">YouTube</div>
            <div class="info-row-val"><a href="https://youtube.com/@SATMX" target="_blank" style="color:#4ade80;text-decoration:none">Canal SAT México</a></div>
          </div>
        </div>
        <div class="info-row">
          <div class="info-row-icon" style="background:rgba(167,139,250,.1)"><i class="fas fa-mobile" style="color:#a78bfa;font-size:12px"></i></div>
          <div class="info-row-text">
            <div class="info-row-label">App SAT Móvil</div>
            <div class="info-row-val">Disponible en App Store y Google Play</div>
          </div>
        </div>
      </div>

    </div>

    {{-- ── Guías de uso ── --}}
    <div class="panel-card">
      <div class="panel-header">
        <div class="panel-icon blue"><i class="fas fa-book-open"></i></div>
        <div>
          <div class="panel-title">Guías de Uso del Portal</div>
          <div class="panel-subtitle">Aprende a usar los servicios paso a paso</div>
        </div>
      </div>
      <div class="guides-grid">
        <a href="{{ route('declaraciones.usuario') }}" class="guide-card">
          <div class="guide-card-icon green"><i class="fas fa-file-invoice"></i></div>
          <div class="guide-card-info">
            <h4>Cómo presentar tu declaración mensual</h4>
            <p>Guía paso a paso para el pago provisional de ISR e IVA</p>
            <span class="guide-tag popular"><i class="fas fa-fire" style="margin-right:3px"></i> Popular</span>
          </div>
        </a>
        <a href="{{ route('declaraciones.usuario') }}" class="guide-card">
          <div class="guide-card-icon blue"><i class="fas fa-calendar-alt"></i></div>
          <div class="guide-card-info">
            <h4>Declaración Anual de personas físicas</h4>
            <p>Deducciones, ingresos acumulables y cálculo de ISR anual</p>
            <span class="guide-tag popular"><i class="fas fa-fire" style="margin-right:3px"></i> Popular</span>
          </div>
        </a>
        <a href="{{ route('facturacion.index') }}" class="guide-card">
          <div class="guide-card-icon orange"><i class="fas fa-file-invoice-dollar"></i></div>
          <div class="guide-card-info">
            <h4>Emitir un CFDI 4.0</h4>
            <p>Crea y timbra facturas electrónicas con los nuevos requisitos</p>
            <span class="guide-tag new"><i class="fas fa-star" style="margin-right:3px"></i> Actualizado</span>
          </div>
        </a>
        <a href="{{ route('personas.cif') }}" class="guide-card">
          <div class="guide-card-icon purple"><i class="fas fa-file-alt"></i></div>
          <div class="guide-card-info">
            <h4>Obtener Constancia de Situación Fiscal</h4>
            <p>Descarga tu constancia actualizada en minutos</p>
          </div>
        </a>
        <a href="{{ route('personas.e_firma') }}" class="guide-card">
          <div class="guide-card-icon yellow"><i class="fas fa-signature"></i></div>
          <div class="guide-card-info">
            <h4>Activar y renovar tu e.firma</h4>
            <p>Firma electrónica avanzada para trámites fiscales</p>
          </div>
        </a>
        <a href="{{ route('contacto.index') }}" class="guide-card">
          <div class="guide-card-icon red"><i class="fas fa-calendar-check"></i></div>
          <div class="guide-card-info">
            <h4>Agendar cita en módulo SAT</h4>
            <p>Reserva tu espacio para atención presencial</p>
          </div>
        </a>
      </div>
    </div>

    {{-- ── FAQ ── --}}
    <div class="panel-card" id="faq">
      <div class="panel-header">
        <div class="panel-icon yellow"><i class="fas fa-question-circle"></i></div>
        <div>
          <div class="panel-title">Preguntas Frecuentes</div>
          <div class="panel-subtitle">Las dudas más comunes de los contribuyentes</div>
        </div>
      </div>

      <div class="faq-list" id="faq-list">

        <div class="faq-item" data-q="¿cuándo debo presentar mi declaración mensual?">
          <div class="faq-q" onclick="toggleFaq(this)">
            <span class="faq-q-text">¿Cuándo debo presentar mi declaración mensual?</span>
            <span class="faq-arrow"><i class="fas fa-chevron-down"></i></span>
          </div>
          <div class="faq-a">
            <div class="faq-a-inner">
              La declaración mensual de pago provisional debe presentarse a más tardar el <strong>día 17 del mes siguiente</strong> al período que se declara. Por ejemplo, la declaración de enero debe presentarse antes del 17 de febrero. Si el día 17 cae en día inhábil, el plazo se extiende al siguiente día hábil.
            </div>
          </div>
        </div>

        <div class="faq-item" data-q="¿qué es el régimen simplificado de confianza resico?">
          <div class="faq-q" onclick="toggleFaq(this)">
            <span class="faq-q-text">¿Qué es el Régimen Simplificado de Confianza (RESICO)?</span>
            <span class="faq-arrow"><i class="fas fa-chevron-down"></i></span>
          </div>
          <div class="faq-a">
            <div class="faq-a-inner">
              El RESICO es un régimen fiscal diseñado para personas físicas con ingresos anuales de hasta <strong>3.5 millones de pesos</strong>. Sus principales ventajas son:
              <ul>
                <li>Tasas de ISR reducidas: entre 1% y 2.5% sobre ingresos cobrados</li>
                <li>Sin necesidad de llevar contabilidad compleja</li>
                <li>Declaración mensual simplificada</li>
                <li>Sin presentar DIOT</li>
              </ul>
            </div>
          </div>
        </div>

        <div class="faq-item" data-q="¿cómo obtengo mi constancia de situación fiscal?">
          <div class="faq-q" onclick="toggleFaq(this)">
            <span class="faq-q-text">¿Cómo obtengo mi Constancia de Situación Fiscal?</span>
            <span class="faq-arrow"><i class="fas fa-chevron-down"></i></span>
          </div>
          <div class="faq-a">
            <div class="faq-a-inner">
              Puedes obtenerla de tres formas:
              <ul>
                <li><strong>En este portal:</strong> Ve a la sección <a href="{{ route('personas.cif') }}">Constancia Fiscal</a> e ingresa tu RFC y contraseña.</li>
                <li><strong>SAT ID:</strong> Desde la app SAT ID en tu celular.</li>
                <li><strong>Módulo SAT:</strong> Presencialmente con una identificación oficial.</li>
              </ul>
              La constancia incluye tu régimen fiscal, obligaciones y domicilio registrado.
            </div>
          </div>
        </div>

        <div class="faq-item" data-q="¿qué es el cfdi 4.0 y qué cambió?">
          <div class="faq-q" onclick="toggleFaq(this)">
            <span class="faq-q-text">¿Qué es el CFDI 4.0 y qué cambió respecto al 3.3?</span>
            <span class="faq-arrow"><i class="fas fa-chevron-down"></i></span>
          </div>
          <div class="faq-a">
            <div class="faq-a-inner">
              El CFDI 4.0 es la versión vigente de la factura electrónica en México. Los principales cambios respecto al 3.3 son:
              <ul>
                <li>Nombre y domicilio fiscal del receptor son <strong>obligatorios</strong></li>
                <li>Nuevo campo <strong>"Exportación"</strong> requerido en todos los CFDI</li>
                <li>Nuevos campos de <strong>"Objetos de impuestos"</strong> en conceptos</li>
                <li>Campo <strong>"RegimenFiscalReceptor"</strong> obligatorio</li>
              </ul>
              Cualquier CFDI emitido en versión 3.3 después del plazo establecido fue rechazado automáticamente.
            </div>
          </div>
        </div>

        <div class="faq-item" data-q="¿cómo recupero mi contraseña del sat?">
          <div class="faq-q" onclick="toggleFaq(this)">
            <span class="faq-q-text">¿Cómo recupero mi contraseña o acceso al portal?</span>
            <span class="faq-arrow"><i class="fas fa-chevron-down"></i></span>
          </div>
          <div class="faq-a">
            <div class="faq-a-inner">
              Si olvidaste tu contraseña puedes recuperarla:
              <ul>
                <li><strong>Con e.firma:</strong> En el portal SAT usa la opción "Generación de contraseña con e.firma"</li>
                <li><strong>Con SAT ID:</strong> Descarga la app y usa tu información biométrica</li>
                <li><strong>Módulo SAT:</strong> Presencialmente con identificación oficial (requiere cita)</li>
              </ul>
              Llama al <strong>55 627 22 728</strong> para orientación adicional.
            </div>
          </div>
        </div>

        <div class="faq-item" data-q="¿qué deducciones personales puedo aplicar en mi declaración anual?">
          <div class="faq-q" onclick="toggleFaq(this)">
            <span class="faq-q-text">¿Qué deducciones personales puedo aplicar en mi declaración anual?</span>
            <span class="faq-arrow"><i class="fas fa-chevron-down"></i></span>
          </div>
          <div class="faq-a">
            <div class="faq-a-inner">
              Las deducciones personales permitidas son:
              <ul>
                <li>Honorarios médicos, dentales y gastos hospitalarios</li>
                <li>Primas de seguros de gastos médicos</li>
                <li>Gastos funerarios</li>
                <li>Donativos a instituciones autorizadas</li>
                <li>Intereses reales de crédito hipotecario</li>
                <li>Aportaciones voluntarias a AFORE</li>
                <li>Colegiaturas (nivel preescolar a bachillerato)</li>
                <li>Transporte escolar obligatorio</li>
              </ul>
              El límite es el <strong>15% del ingreso anual</strong> o <strong>5 UMAs anuales</strong>, lo que resulte menor. Todos deben estar pagados con CFDI.
            </div>
          </div>
        </div>

        <div class="faq-item" data-q="¿qué pasa si no presento mi declaración a tiempo?">
          <div class="faq-q" onclick="toggleFaq(this)">
            <span class="faq-q-text">¿Qué pasa si no presento mi declaración a tiempo?</span>
            <span class="faq-arrow"><i class="fas fa-chevron-down"></i></span>
          </div>
          <div class="faq-a">
            <div class="faq-a-inner">
              Presentar una declaración fuera de plazo genera:
              <ul>
                <li><strong>Recargos:</strong> 1.47% mensual sobre el impuesto no pagado</li>
                <li><strong>Actualización:</strong> El monto se actualiza con el INPC</li>
                <li><strong>Multa:</strong> Entre $1,810 y $22,400 pesos por omisión de declaración</li>
                <li>Posible requerimiento del SAT con multas adicionales</li>
              </ul>
              Puedes presentar una declaración extemporánea en cualquier momento para regularizarte.
            </div>
          </div>
        </div>

        <div class="faq-item" data-q="¿cómo cancelo una factura cfdi?">
          <div class="faq-q" onclick="toggleFaq(this)">
            <span class="faq-q-text">¿Cómo cancelo una factura CFDI?</span>
            <span class="faq-arrow"><i class="fas fa-chevron-down"></i></span>
          </div>
          <div class="faq-a">
            <div class="faq-a-inner">
              Para cancelar un CFDI debes indicar el <strong>motivo de cancelación</strong>:
              <ul>
                <li><strong>01:</strong> Comprobante emitido con errores con relación (requiere UUID sustituto)</li>
                <li><strong>02:</strong> Comprobante emitido con errores sin relación</li>
                <li><strong>03:</strong> No se llevó a cabo la operación</li>
                <li><strong>04:</strong> Operación nominativa relacionada en una factura global</li>
              </ul>
              El receptor tiene <strong>72 horas</strong> para aceptar o rechazar la cancelación. Si no responde, se cancela automáticamente.
            </div>
          </div>
        </div>

      </div>
    </div>

    {{-- ── Aviso legal ── --}}
    <div style="text-align:center;padding:16px 0 8px;font-size:12px;color:rgba(255,255,255,.2)">
      Portal SAT — Servicio de Administración Tributaria · Gobierno de México<br>
      Para trámites oficiales visita <a href="https://sat.gob.mx" target="_blank" style="color:rgba(74,222,128,.5);text-decoration:none">www.sat.gob.mx</a>
    </div>

  </div>{{-- /content --}}
</div>{{-- /main --}}

<script>
// ── Sidebar ────────────────────────────────────────
function toggleSidebar(){
  document.getElementById('sidebar').classList.toggle('collapsed');
}

// ── FAQ accordion ──────────────────────────────────
function toggleFaq(el){
  const item = el.closest('.faq-item');
  const isOpen = item.classList.contains('open');
  // Cerrar todos
  document.querySelectorAll('.faq-item.open').forEach(i => i.classList.remove('open'));
  // Abrir el actual si estaba cerrado
  if(!isOpen) item.classList.add('open');
}

// ── Filtrar FAQ ────────────────────────────────────
function filterFaq(q){
  const term = q.toLowerCase().trim();
  document.querySelectorAll('.faq-item').forEach(item => {
    const text = (item.dataset.q || '') + ' ' + item.textContent.toLowerCase();
    item.style.display = !term || text.includes(term) ? '' : 'none';
  });
}
</script>
</body>
</html>