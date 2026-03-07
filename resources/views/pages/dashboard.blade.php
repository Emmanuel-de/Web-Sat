<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Portal SAT – Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
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
.tb-uinfo{}
.tb-uname{font-size:13px;font-weight:700;color:white;line-height:1.2}
.tb-urfc{font-size:10px;color:rgba(255,255,255,.4);font-family:monospace}

/* ── Content ──────────────────────────────────── */
.content{flex:1;padding:28px;overflow-y:auto}
.page-title{font-size:26px;font-weight:700;color:white;margin-bottom:4px}
.page-sub{font-size:14px;color:rgba(255,255,255,.45);margin-bottom:28px}

/* ── KPI cards ────────────────────────────────── */
.kpi-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:28px}
.kpi-card{border-radius:14px;padding:20px;position:relative;overflow:hidden;transition:transform .2s,box-shadow .2s;cursor:default}
.kpi-card:hover{transform:translateY(-2px);box-shadow:0 8px 32px rgba(0,0,0,.25)}
.kpi-card.blue{background:linear-gradient(135deg,#1e3a5f 0%,#1a2f50 100%);border:1px solid rgba(30,90,200,.2)}
.kpi-card.green{background:linear-gradient(135deg,#0d3a24 0%,#0a2f1e 100%);border:1px solid rgba(0,104,71,.25)}
.kpi-card.orange{background:linear-gradient(135deg,#7c2d12 0%,#6b2510 100%);border:1px solid rgba(200,80,20,.25)}
.kpi-card.dark{background:linear-gradient(135deg,#1a2030 0%,#161c28 100%);border:1px solid rgba(255,255,255,.07)}
.kpi-top{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px}
.kpi-icon{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:17px}
.kpi-card.blue   .kpi-icon{background:rgba(30,120,255,.2);color:#60a5fa}
.kpi-card.green  .kpi-icon{background:rgba(0,150,80,.2);color:#4ade80}
.kpi-card.orange .kpi-icon{background:rgba(255,120,20,.2);color:#fb923c}
.kpi-card.dark   .kpi-icon{background:rgba(255,255,255,.07);color:rgba(255,255,255,.5)}
.kpi-badge{font-size:12px;font-weight:700;padding:3px 8px;border-radius:20px;display:flex;align-items:center;gap:4px}
.kpi-badge.up{background:rgba(74,222,128,.15);color:#4ade80}
.kpi-badge.down{background:rgba(248,113,113,.15);color:#f87171}
.kpi-label{font-size:12px;color:rgba(255,255,255,.45);font-weight:600;text-transform:uppercase;letter-spacing:.4px;margin-bottom:8px}
.kpi-value{font-size:30px;font-weight:700;color:white;line-height:1;margin-bottom:4px}
.kpi-meta{font-size:12px;color:rgba(255,255,255,.35)}
.kpi-card.orange .kpi-meta{color:rgba(255,160,100,.6)}

/* ── Charts row ───────────────────────────────── */
.charts-row{display:grid;grid-template-columns:1fr 360px;gap:20px;margin-bottom:28px}
.chart-card{background:#0d1520;border:1px solid rgba(255,255,255,.07);border-radius:14px;padding:22px}
.chart-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px}
.chart-title{font-size:15px;font-weight:700;color:white}
.chart-sub{font-size:12px;color:rgba(255,255,255,.35);margin-top:2px}
.chart-period{font-size:12px;color:rgba(255,255,255,.35);background:rgba(255,255,255,.05);padding:4px 10px;border-radius:6px}

/* ── Donut legend ─────────────────────────────── */
.donut-wrap{display:flex;flex-direction:column;align-items:center;padding-top:10px}
.donut-legend{width:100%;margin-top:20px;display:flex;flex-direction:column;gap:10px}
.legend-item{display:flex;align-items:center;justify-content:space-between;font-size:13px}
.legend-dot{width:10px;height:10px;border-radius:50%;flex-shrink:0}
.legend-label{flex:1;color:rgba(255,255,255,.6);margin-left:8px}
.legend-val{font-weight:700;color:white}

/* ── Bottom row ───────────────────────────────── */
.bottom-row{display:grid;grid-template-columns:1fr 340px;gap:20px}
.panel-card{background:#0d1520;border:1px solid rgba(255,255,255,.07);border-radius:14px;padding:22px}
.panel-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px}
.panel-title{font-size:15px;font-weight:700;color:white}
.panel-link{font-size:13px;color:#4ade80;text-decoration:none;font-weight:600}
.panel-link:hover{color:#86efac}

/* ── Actividad --*/
.act-item{display:flex;align-items:center;gap:14px;padding:11px 0;border-bottom:1px solid rgba(255,255,255,.05)}
.act-item:last-child{border-bottom:none}
.act-dot{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0}
.act-dot.green{background:rgba(0,104,71,.2);color:#4ade80}
.act-dot.blue{background:rgba(30,100,220,.2);color:#60a5fa}
.act-dot.orange{background:rgba(200,80,20,.2);color:#fb923c}
.act-dot.red{background:rgba(200,16,46,.15);color:#f87171}
.act-info{flex:1;min-width:0}
.act-title{font-size:13px;font-weight:700;color:white;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.act-sub{font-size:11px;color:rgba(255,255,255,.35);margin-top:2px}
.act-right{text-align:right;flex-shrink:0}
.act-amount{font-size:14px;font-weight:700;color:white}
.act-time{font-size:11px;color:rgba(255,255,255,.35);margin-top:2px}
.act-status{font-size:11px;font-weight:600;padding:2px 8px;border-radius:10px;margin-top:3px;display:inline-block}
.act-status.pend{background:rgba(251,146,60,.15);color:#fb923c}
.act-status.venc{background:rgba(248,113,113,.15);color:#f87171}

/* ── Obligaciones ─────────────────────────────── */
.ob-item{display:flex;align-items:center;gap:12px;padding:12px 14px;border-radius:8px;margin-bottom:8px;transition:background .2s;cursor:default}
.ob-item:hover{background:rgba(255,255,255,.04)}
.ob-dot{width:10px;height:10px;border-radius:50%;flex-shrink:0}
.ob-info{flex:1}
.ob-name{font-size:13px;font-weight:600;color:white}
.ob-date{font-size:12px;color:rgba(255,255,255,.35);margin-top:2px}
.ob-urgencia{font-size:11px;font-weight:600;padding:2px 8px;border-radius:10px;flex-shrink:0}
.ob-urgencia.pronto{background:rgba(251,146,60,.15);color:#fb923c}
.ob-urgencia.normal{background:rgba(255,255,255,.06);color:rgba(255,255,255,.4)}
.ob-warn-header{display:flex;align-items:center;gap:8px;margin-bottom:14px}
.ob-warn-header i{color:#fb923c}
.ob-warn-header span{font-size:15px;font-weight:700;color:white}

@media(max-width:1100px){.kpi-grid{grid-template-columns:1fr 1fr}.charts-row,.bottom-row{grid-template-columns:1fr}}
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
  <a href="{{ route('dashboard') }}" class="nav-item active">
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
  <a href="{{ route('perfil.index') }}" class="nav-item"> <i class="fas fa-user-circle ni"></i>
    <span class="nav-label">Mi Perfil</span>
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
  <div class="page-title">Bienvenido, {{ Auth::user()->nombre ?? 'Contribuyente' }}</div>
  <div class="page-sub">Aquí tienes el resumen de tu situación fiscal al día de hoy.</div>

  {{-- KPIs --}}
  <div class="kpi-grid">
   <div class="kpi-card blue">
    <div class="kpi-top">
     <div class="kpi-icon"><i class="fas fa-file-invoice-dollar"></i></div>
     <div class="kpi-badge up"><i class="fas fa-arrow-up" style="font-size:9px"></i> 8%</div>
    </div>
    <div class="kpi-label">Facturas Emitidas</div>
    <div class="kpi-value" id="k-facturas">47</div>
    <div class="kpi-meta">Este mes: 12</div>
   </div>
   <div class="kpi-card green">
    <div class="kpi-top">
     <div class="kpi-icon"><i class="fas fa-chart-line"></i></div>
     <div class="kpi-badge up"><i class="fas fa-arrow-up" style="font-size:9px"></i> 12%</div>
    </div>
    <div class="kpi-label">Ingresos Facturados</div>
    <div class="kpi-value">$<span id="k-ingresos">186,400</span></div>
    <div class="kpi-meta">Mes actual</div>
   </div>
   <div class="kpi-card orange">
    <div class="kpi-top">
     <div class="kpi-icon"><i class="fas fa-exclamation-triangle"></i></div>
     <div class="kpi-badge down"><i class="fas fa-clock" style="font-size:9px"></i> Urgente</div>
    </div>
    <div class="kpi-label">Declaraciones Pendientes</div>
    <div class="kpi-value" id="k-decl">2</div>
    <div class="kpi-meta" style="color:#fb923c">Próx. vencimiento: 17 Mar</div>
   </div>
   <div class="kpi-card dark">
    <div class="kpi-top">
     <div class="kpi-icon"><i class="fas fa-credit-card"></i></div>
     <div class="kpi-badge down"><i class="fas fa-arrow-down" style="font-size:9px"></i> 3%</div>
    </div>
    <div class="kpi-label">Pagos Realizados</div>
    <div class="kpi-value">$<span id="k-pagos">24,570</span></div>
    <div class="kpi-meta">Último: 24 Feb</div>
   </div>
  </div>

  {{-- Gráficas --}}
  <div class="charts-row">
   <div class="chart-card">
    <div class="chart-header">
     <div><div class="chart-title">Ingresos Mensuales</div></div>
     <div class="chart-period">Últimos 7 meses</div>
    </div>
    <div style="position:relative; height:180px; width:100%">
  <canvas id="barChart"></canvas>
</div>
   </div>
   <div class="chart-card">
    <div class="chart-header">
     <div><div class="chart-title">Estado de Facturas</div></div>
    </div>
    <div class="donut-wrap">
     <canvas id="donutChart" style="max-width:180px; max-height:180px"></canvas>
     <div class="donut-legend">
      <div class="legend-item"><span class="legend-dot" style="background:#4ade80"></span><span class="legend-label">Vigentes</span><span class="legend-val">45</span></div>
      <div class="legend-item"><span class="legend-dot" style="background:#fb923c"></span><span class="legend-label">Pendientes</span><span class="legend-val">12</span></div>
      <div class="legend-item"><span class="legend-dot" style="background:#f87171"></span><span class="legend-label">Canceladas</span><span class="legend-val">3</span></div>
     </div>
    </div>
   </div>
  </div>

  {{-- Actividad + Obligaciones --}}
  <div class="bottom-row">
   <div class="panel-card">
    <div class="panel-header">
     <div class="panel-title">Actividad Reciente</div>
     <a href="{{ route('facturacion.index') }}" class="panel-link">Ver todo →</a>
    </div>
    <div class="act-item">
     <div class="act-dot green"><i class="fas fa-file-invoice-dollar"></i></div>
     <div class="act-info"><div class="act-title">Factura emitida</div><div class="act-sub">CFDI-2026-0847</div></div>
     <div class="act-right"><div class="act-amount">$4,250.00</div><div class="act-time">Hoy 10:32</div></div>
    </div>
    <div class="act-item">
     <div class="act-dot blue"><i class="fas fa-money-bill-wave"></i></div>
     <div class="act-info"><div class="act-title">Pago ISR</div><div class="act-sub">DEC-ENE-2026</div></div>
     <div class="act-right"><div class="act-amount">$8,120.00</div><div class="act-time">24 Feb</div></div>
    </div>
    <div class="act-item">
     <div class="act-dot orange"><i class="fas fa-file-alt"></i></div>
     <div class="act-info"><div class="act-title">Declaración mensual</div><div class="act-sub">ENE-2026</div></div>
     <div class="act-right"><span class="act-status pend">Pendiente</span></div>
    </div>
    <div class="act-item">
     <div class="act-dot green"><i class="fas fa-file-import"></i></div>
     <div class="act-info"><div class="act-title">Factura recibida</div><div class="act-sub">CFDI-REC-1623</div></div>
     <div class="act-right"><div class="act-amount">$1,800.00</div><div class="act-time">20 Feb</div></div>
    </div>
    <div class="act-item">
     <div class="act-dot red"><i class="fas fa-money-bill-wave"></i></div>
     <div class="act-info"><div class="act-title">Pago IVA</div><div class="act-sub">DEC-ENE-2026</div></div>
     <div class="act-right"><div class="act-amount">$3,450.00</div><span class="act-status venc">Vencido</span></div>
    </div>
   </div>

   <div class="panel-card">
    <div class="ob-warn-header">
     <i class="fas fa-exclamation-triangle"></i>
     <span>Obligaciones Próximas</span>
    </div>
    <div class="ob-item">
     <div class="ob-dot" style="background:#fb923c"></div>
     <div class="ob-info"><div class="ob-name">Declaración mensual Febrero</div><div class="ob-date">17 Mar 2026</div></div>
     <div class="ob-urgencia pronto">En 14 días</div>
    </div>
    <div class="ob-item">
     <div class="ob-dot" style="background:#fb923c"></div>
     <div class="ob-info"><div class="ob-name">Pago provisional ISR</div><div class="ob-date">17 Mar 2026</div></div>
     <div class="ob-urgencia pronto">En 14 días</div>
    </div>
    <div class="ob-item">
     <div class="ob-dot" style="background:#60a5fa"></div>
     <div class="ob-info"><div class="ob-name">DIOT Febrero</div><div class="ob-date">17 Mar 2026</div></div>
     <div class="ob-urgencia normal">Próximo</div>
    </div>
    <div class="ob-item">
     <div class="ob-dot" style="background:#4ade80"></div>
     <div class="ob-info"><div class="ob-name">Declaración anual 2025</div><div class="ob-date">30 Abr 2026</div></div>
     <div class="ob-urgencia normal">58 días</div>
    </div>
   </div>
  </div>

 </div>{{-- /content --}}
</div>{{-- /main --}}

<script>
// ── Sidebar toggle ──────────────────────────────
function toggleSidebar(){
 const sb=document.getElementById('sidebar');
 const mn=document.getElementById('main');
 sb.classList.toggle('collapsed');
}

// ── Bar chart ───────────────────────────────────
const bCtx=document.getElementById('barChart')?.getContext('2d');
if(bCtx){
 new Chart(bCtx,{
  type:'bar',
  data:{
   labels:['Jul','Ago','Sep','Oct','Nov','Dic','Ene'],
   datasets:[{
    data:[14200,17800,16400,9600,18600,21300,18600],
    backgroundColor:'rgba(30,100,220,.7)',
    borderRadius:6,
    borderSkipped:false,
    hoverBackgroundColor:'#4ade80',
   }]
  },
  options:{
   responsive:true,maintainAspectRatio:false,
   plugins:{legend:{display:false},tooltip:{callbacks:{label:c=>'$'+c.raw.toLocaleString()}}},
   scales:{
    x:{grid:{color:'rgba(255,255,255,.04)'},ticks:{color:'rgba(255,255,255,.35)',font:{size:12}}},
    y:{grid:{color:'rgba(255,255,255,.04)'},ticks:{color:'rgba(255,255,255,.35)',font:{size:12},callback:v=>'$'+(v/1000)+'k'},border:{display:false}}
   }
  }
 });
}

// ── Donut chart ─────────────────────────────────
const dCtx=document.getElementById('donutChart')?.getContext('2d');
if(dCtx){
 new Chart(dCtx,{
  type:'doughnut',
  data:{
   labels:['Vigentes','Pendientes','Canceladas'],
   datasets:[{
    data:[45,12,3],
    backgroundColor:['#4ade80','#fb923c','#f87171'],
    borderWidth:3,
    borderColor:'#0d1520',
    hoverBorderWidth:3,
   }]
  },
  options:{
   responsive:false,
   cutout:'72%',
   plugins:{
    legend:{display:false},
    tooltip:{callbacks:{label:c=>c.label+': '+c.raw}}
   }
  }
 });
}

// ── Animated counters ───────────────────────────
function animateCount(id, target, prefix='', suffix=''){
 const el=document.getElementById(id);
 if(!el) return;
 let start=0;
 const duration=1200;
 const step=target/60;
 const timer=setInterval(()=>{
  start=Math.min(start+step, target);
  el.textContent=prefix+Math.floor(start).toLocaleString()+suffix;
  if(start>=target) clearInterval(timer);
 },duration/60);
}
setTimeout(()=>{
 animateCount('k-facturas',47);
 animateCount('k-ingresos',186400);
 animateCount('k-decl',2);
 animateCount('k-pagos',24570);
},300);
</script>
</body>
</html>