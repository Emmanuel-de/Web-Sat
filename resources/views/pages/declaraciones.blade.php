<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Portal SAT – Declaraciones</title>
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

/* ── Alerts ───────────────────────────────────── */
.alert-success{background:rgba(0,104,71,.15);border:1px solid rgba(74,222,128,.25);border-radius:10px;padding:12px 16px;display:flex;align-items:center;gap:10px;font-size:13px;color:#4ade80;margin-bottom:20px;animation:slideDown .3s ease}
.alert-error{background:rgba(200,16,46,.12);border:1px solid rgba(248,113,113,.25);border-radius:10px;padding:12px 16px;display:flex;align-items:center;gap:10px;font-size:13px;color:#f87171;margin-bottom:20px;animation:slideDown .3s ease}
@keyframes slideDown{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:translateY(0)}}

/* ── KPI mini strip ───────────────────────────── */
.kpi-strip{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:28px}
.kpi-mini{background:#0d1520;border:1px solid rgba(255,255,255,.07);border-radius:12px;padding:16px 18px;display:flex;align-items:center;gap:14px}
.kpi-mini-icon{width:38px;height:38px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0}
.kpi-mini-icon.green{background:rgba(0,104,71,.2);color:#4ade80}
.kpi-mini-icon.blue{background:rgba(30,100,220,.15);color:#60a5fa}
.kpi-mini-icon.orange{background:rgba(200,80,20,.15);color:#fb923c}
.kpi-mini-icon.red{background:rgba(200,16,46,.12);color:#f87171}
.kpi-mini-val{font-size:22px;font-weight:700;color:white;line-height:1}
.kpi-mini-label{font-size:11px;color:rgba(255,255,255,.35);margin-top:3px;font-weight:600;text-transform:uppercase;letter-spacing:.3px}

/* ── Panel card ───────────────────────────────── */
.panel-card{background:#0d1520;border:1px solid rgba(255,255,255,.07);border-radius:14px;padding:24px;margin-bottom:24px}
.panel-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px}
.panel-title-wrap{display:flex;align-items:center;gap:10px}
.panel-icon{width:34px;height:34px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0}
.panel-icon.green{background:rgba(0,104,71,.2);color:#4ade80}
.panel-icon.blue{background:rgba(30,100,220,.15);color:#60a5fa}
.panel-icon.orange{background:rgba(200,80,20,.15);color:#fb923c}
.panel-title{font-size:15px;font-weight:700;color:white}
.panel-subtitle{font-size:12px;color:rgba(255,255,255,.35);margin-top:2px}

/* ── Table ────────────────────────────────────── */
.table-controls{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.tbl-search-wrap{position:relative}
.tbl-search{background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08);border-radius:8px;padding:8px 12px 8px 34px;font-size:13px;color:rgba(255,255,255,.7);outline:none;font-family:inherit;width:200px;transition:all .2s}
.tbl-search::placeholder{color:rgba(255,255,255,.25)}
.tbl-search:focus{background:rgba(255,255,255,.08);border-color:rgba(0,104,71,.4);color:white}
.tbl-search-icon{position:absolute;left:11px;top:50%;transform:translateY(-50%);font-size:12px;color:rgba(255,255,255,.25);pointer-events:none}
.tbl-filter{background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08);border-radius:8px;padding:8px 12px;font-size:13px;color:rgba(255,255,255,.6);outline:none;font-family:inherit;cursor:pointer;transition:all .2s}
.tbl-filter:focus{border-color:rgba(0,104,71,.4)}
.tbl-filter option{background:#1a2535}
.btn-new{display:flex;align-items:center;gap:7px;padding:9px 18px;background:linear-gradient(135deg,#006847,#00875a);border:none;border-radius:8px;color:white;font-size:13px;font-weight:700;cursor:pointer;font-family:inherit;transition:all .2s;text-decoration:none;margin-left:auto}
.btn-new:hover{background:linear-gradient(135deg,#007a54,#009966);box-shadow:0 4px 16px rgba(0,104,71,.35)}

.table-wrap{overflow-x:auto;margin:-4px}
table{width:100%;border-collapse:collapse;min-width:700px}
thead tr th{padding:10px 14px;text-align:left;font-size:11px;font-weight:700;color:rgba(255,255,255,.3);text-transform:uppercase;letter-spacing:.5px;border-bottom:1px solid rgba(255,255,255,.06)}
tbody tr{border-bottom:1px solid rgba(255,255,255,.04);transition:background .15s;cursor:default}
tbody tr:last-child{border-bottom:none}
tbody tr:hover{background:rgba(255,255,255,.03)}
tbody td{padding:13px 14px;font-size:13px;color:rgba(255,255,255,.75)}
.td-bold{font-weight:700;color:white}
.td-mono{font-family:monospace;font-size:12px;color:rgba(255,255,255,.5)}
.td-green{color:#4ade80;font-weight:700}
.td-red{color:#f87171;font-weight:700}

/* ── Badges ───────────────────────────────────── */
.badge{font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;display:inline-flex;align-items:center;gap:5px;white-space:nowrap}
.badge-presentada{background:rgba(74,222,128,.12);color:#4ade80;border:1px solid rgba(74,222,128,.2)}
.badge-pendiente{background:rgba(251,146,60,.12);color:#fb923c;border:1px solid rgba(251,146,60,.2)}
.badge-vencida{background:rgba(248,113,113,.12);color:#f87171;border:1px solid rgba(248,113,113,.2)}
.badge-en-revision{background:rgba(96,165,250,.12);color:#60a5fa;border:1px solid rgba(96,165,250,.2)}
.badge-cancelada{background:rgba(255,255,255,.06);color:rgba(255,255,255,.4);border:1px solid rgba(255,255,255,.1)}

/* ── Action btn ───────────────────────────────── */
.act-btn{width:30px;height:30px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.07);border-radius:6px;display:inline-flex;align-items:center;justify-content:center;cursor:pointer;transition:all .2s;text-decoration:none;color:rgba(255,255,255,.4);font-size:12px}
.act-btn:hover{background:rgba(255,255,255,.1);color:white}
.act-btn.green:hover{background:rgba(74,222,128,.15);color:#4ade80;border-color:rgba(74,222,128,.2)}
.act-btn.red:hover{background:rgba(248,113,113,.12);color:#f87171;border-color:rgba(248,113,113,.2)}

/* ── Empty state ──────────────────────────────── */
.empty-state{text-align:center;padding:48px 20px;color:rgba(255,255,255,.25)}
.empty-state i{font-size:40px;margin-bottom:14px;display:block;opacity:.3}
.empty-state p{font-size:14px}

/* ── Pagination ───────────────────────────────── */
.pagination{display:flex;align-items:center;justify-content:between;margin-top:18px;gap:6px;flex-wrap:wrap}
.page-info{font-size:13px;color:rgba(255,255,255,.35);flex:1}
.page-btns{display:flex;gap:4px}
.page-btn{width:32px;height:32px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.07);border-radius:6px;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:13px;color:rgba(255,255,255,.5);transition:all .2s;font-family:inherit}
.page-btn:hover{background:rgba(255,255,255,.1);color:white}
.page-btn.active{background:rgba(0,104,71,.25);border-color:rgba(74,222,128,.25);color:#4ade80}

/* ── Divider ──────────────────────────────────── */
.divider{height:1px;background:rgba(255,255,255,.06);margin:28px 0}

/* ── Form header ──────────────────────────────── */
.form-hero{background:linear-gradient(135deg,#006847 0%,#004d35 100%);border-radius:12px;padding:22px 24px;margin-bottom:24px;display:flex;align-items:center;gap:16px;position:relative;overflow:hidden}
.form-hero::after{content:'';position:absolute;right:-30px;top:-30px;width:140px;height:140px;background:rgba(255,255,255,.05);border-radius:50%}
.form-hero-icon{width:46px;height:46px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;color:white;flex-shrink:0;z-index:1}
.form-hero-info{z-index:1}
.form-hero-title{font-size:18px;font-weight:700;color:white}
.form-hero-sub{font-size:13px;color:rgba(255,255,255,.65);margin-top:3px}
.form-hero-badge{margin-left:auto;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.2);color:white;font-size:12px;font-weight:700;padding:5px 14px;border-radius:20px;z-index:1;white-space:nowrap}

/* ── Tipo tabs ────────────────────────────────── */
.tipo-tabs{display:flex;gap:8px;margin-bottom:24px;flex-wrap:wrap}
.tipo-tab{padding:9px 20px;border-radius:8px;font-size:13px;font-weight:700;cursor:pointer;border:1px solid rgba(255,255,255,.08);background:rgba(255,255,255,.04);color:rgba(255,255,255,.45);transition:all .2s;font-family:inherit}
.tipo-tab:hover{background:rgba(255,255,255,.07);color:rgba(255,255,255,.75)}
.tipo-tab.active{background:rgba(0,104,71,.2);border-color:rgba(74,222,128,.3);color:#4ade80}

/* ── Form sections ────────────────────────────── */
.form-section{margin-bottom:26px}
.form-section-title{font-size:13px;font-weight:700;color:rgba(255,255,255,.55);text-transform:uppercase;letter-spacing:.5px;margin-bottom:16px;display:flex;align-items:center;gap:8px}
.form-section-title::after{content:'';flex:1;height:1px;background:rgba(255,255,255,.06)}
.form-grid-3{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
.form-grid-2{display:grid;grid-template-columns:repeat(2,1fr);gap:16px}
.form-grid-1{display:grid;grid-template-columns:1fr;gap:16px}
.form-full{grid-column:1/-1}

/* ── Form group ───────────────────────────────── */
.form-group{display:flex;flex-direction:column;gap:6px}
.form-label{font-size:12px;font-weight:600;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:.4px}
.form-label .req{color:#f87171;margin-left:2px}
.form-input-wrap{position:relative}
.form-input{width:100%;font-size:14px;color:white;padding:10px 14px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:8px;outline:none;transition:all .2s;font-family:inherit}
.form-input::placeholder{color:rgba(255,255,255,.2)}
.form-input:focus{background:rgba(255,255,255,.08);border-color:rgba(0,104,71,.5);box-shadow:0 0 0 3px rgba(0,104,71,.1)}
.form-input:disabled{opacity:.35;cursor:not-allowed}
select.form-input{cursor:pointer}
select.form-input option{background:#1a2535;color:white}
.input-prefix{position:absolute;left:13px;top:50%;transform:translateY(-50%);font-size:14px;font-weight:600;color:rgba(255,255,255,.3);pointer-events:none}
.form-input.has-prefix{padding-left:28px}
.field-hint{font-size:11px;color:rgba(255,255,255,.25);margin-top:2px}
.form-error{font-size:11px;color:#f87171;margin-top:2px}

/* ── ISR Resumen ──────────────────────────────── */
.isr-resumen{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:12px;padding:22px;margin:4px 0 0}
.isr-grid{display:grid;grid-template-columns:1fr 1fr 1fr;gap:0}
.isr-col{padding:0 20px;border-right:1px solid rgba(255,255,255,.07);text-align:center}
.isr-col:first-child{padding-left:0;text-align:left}
.isr-col:last-child{border-right:none;text-align:right}
.isr-label{font-size:12px;color:rgba(255,255,255,.35);font-weight:600;text-transform:uppercase;letter-spacing:.4px;margin-bottom:8px}
.isr-value{font-size:28px;font-weight:700;color:white;font-family:monospace}
.isr-value.positive{color:#4ade80}
.isr-value.negative{color:#f87171}
.isr-input{width:100%;font-size:20px;font-weight:700;color:white;padding:6px 0;background:transparent;border:none;border-bottom:1px solid rgba(255,255,255,.12);outline:none;font-family:monospace;text-align:center;transition:border-color .2s}
.isr-input:focus{border-color:rgba(0,104,71,.5)}
.isr-input::placeholder{color:rgba(255,255,255,.15);font-size:18px}
.isr-col-saldo{background:rgba(0,104,71,.1);border:1px solid rgba(74,222,128,.15);border-radius:8px;padding:14px 18px;text-align:center}

/* ── Protest checkbox ─────────────────────────── */
.protest-wrap{display:flex;align-items:flex-start;gap:12px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:10px;padding:16px;margin:20px 0}
.protest-wrap input[type=checkbox]{width:18px;height:18px;accent-color:#006847;cursor:pointer;flex-shrink:0;margin-top:1px}
.protest-text{font-size:13px;color:rgba(255,255,255,.55);line-height:1.5}
.protest-text strong{color:rgba(255,255,255,.8)}

/* ── Footer bar ───────────────────────────────── */
.form-footer{display:flex;align-items:center;justify-content:space-between;padding:18px 0 0;border-top:1px solid rgba(255,255,255,.07);flex-wrap:wrap;gap:12px}
.footer-deadline{display:flex;align-items:center;gap:8px;font-size:13px;color:rgba(255,255,255,.35)}
.footer-deadline i{color:#fb923c}
.footer-deadline span{color:#fb923c;font-weight:600}
.footer-btns{display:flex;gap:10px}
.btn{font-size:13px;font-weight:700;padding:10px 22px;border-radius:8px;border:none;cursor:pointer;display:flex;align-items:center;gap:7px;transition:all .2s;font-family:inherit}
.btn-outline{background:transparent;border:1px solid rgba(255,255,255,.15);color:rgba(255,255,255,.6)}
.btn-outline:hover{background:rgba(255,255,255,.06);color:white}
.btn-primary{background:linear-gradient(135deg,#006847,#00875a);color:white}
.btn-primary:hover{background:linear-gradient(135deg,#007a54,#009966);box-shadow:0 4px 16px rgba(0,104,71,.35)}

/* ── Responsive ───────────────────────────────── */
@media(max-width:1100px){.kpi-strip{grid-template-columns:1fr 1fr}.form-grid-3{grid-template-columns:1fr 1fr}.isr-grid{grid-template-columns:1fr}.isr-col{border-right:none;border-bottom:1px solid rgba(255,255,255,.07);padding:12px 0;text-align:left}.isr-col:last-child{border-bottom:none}}
@media(max-width:640px){.content{padding:16px}.sidebar{display:none}.kpi-strip{grid-template-columns:1fr 1fr}.form-grid-2,.form-grid-3{grid-template-columns:1fr}.isr-grid{grid-template-columns:1fr}}
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
  <a href="{{ route('declaraciones.usuario') }}" class="nav-item active">
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
  <div class="page-title">Declaraciones</div>
  <div class="page-sub">Consulta el historial de tus declaraciones fiscales y presenta nuevas.</div>

  @if(session('success'))
   <div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
  @endif
  @if(session('error'))
   <div class="alert-error"><i class="fas fa-times-circle"></i> {{ session('error') }}</div>
  @endif
  @if($errors->any())
   <div class="alert-error"><i class="fas fa-times-circle"></i> Revisa los campos del formulario antes de continuar.</div>
  @endif

  {{-- ── KPI strip ──────────────────────────────── --}}
  <div class="kpi-strip">
   <div class="kpi-mini">
    <div class="kpi-mini-icon green"><i class="fas fa-check-circle"></i></div>
    <div>
     <div class="kpi-mini-val">{{ $declaraciones->where('estatus','presentada')->count() ?? 10 }}</div>
     <div class="kpi-mini-label">Presentadas</div>
    </div>
   </div>
   <div class="kpi-mini">
    <div class="kpi-mini-icon orange"><i class="fas fa-clock"></i></div>
    <div>
     <div class="kpi-mini-val">{{ $declaraciones->where('estatus','pendiente')->count() ?? 2 }}</div>
     <div class="kpi-mini-label">Pendientes</div>
    </div>
   </div>
   <div class="kpi-mini">
    <div class="kpi-mini-icon red"><i class="fas fa-exclamation-circle"></i></div>
    <div>
     <div class="kpi-mini-val">{{ $declaraciones->where('estatus','vencida')->count() ?? 1 }}</div>
     <div class="kpi-mini-label">Vencidas</div>
    </div>
   </div>
   <div class="kpi-mini">
    <div class="kpi-mini-icon blue"><i class="fas fa-calendar-alt"></i></div>
    <div>
     <div class="kpi-mini-val">{{ now()->format('Y') }}</div>
     <div class="kpi-mini-label">Ejercicio actual</div>
    </div>
   </div>
  </div>

  {{-- ══════════════════════════════════════════════
       TABLA DE DECLARACIONES
  ══════════════════════════════════════════════ --}}
  <div class="panel-card">
   <div class="panel-header">
    <div class="panel-title-wrap">
     <div class="panel-icon green"><i class="fas fa-list-alt"></i></div>
     <div>
      <div class="panel-title">Historial de Declaraciones</div>
      <div class="panel-subtitle">Todas las declaraciones registradas en tu cuenta</div>
     </div>
    </div>
    <div class="table-controls">
     <div class="tbl-search-wrap">
      <i class="fas fa-search tbl-search-icon"></i>
      <input type="text" class="tbl-search" id="tbl-buscar" placeholder="Buscar..." oninput="filtrarTabla()">
     </div>
     <select class="tbl-filter" id="tbl-tipo" onchange="filtrarTabla()">
      <option value="">Todos los tipos</option>
      <option value="Anual">Anual</option>
      <option value="Mensual">Mensual</option>
      <option value="Provisional">Provisional</option>
      <option value="DIOT">DIOT</option>
      <option value="Complementaria">Complementaria</option>
     </select>
     <select class="tbl-filter" id="tbl-estatus" onchange="filtrarTabla()">
      <option value="">Todos los estatus</option>
      <option value="presentada">Presentada</option>
      <option value="pendiente">Pendiente</option>
      <option value="vencida">Vencida</option>
      <option value="en-revision">En revisión</option>
      <option value="cancelada">Cancelada</option>
     </select>
     <a href="#form-declaracion" class="btn-new">
      <i class="fas fa-plus"></i> Nueva declaración
     </a>
    </div>
   </div>

   <div class="table-wrap">
    <table id="tabla-declaraciones">
     <thead>
      <tr>
       <th>Folio / Acuse</th>
       <th>Tipo</th>
       <th>Período</th>
       <th>Ejercicio</th>
       <th>ISR Determinado</th>
       <th>ISR Retenido</th>
       <th>Saldo a cargo/favor</th>
       <th>Estatus</th>
       <th>Fecha presentación</th>
       <th style="text-align:center">Acciones</th>
      </tr>
     </thead>
     <tbody id="tbody-declaraciones">

      @forelse($declaraciones ?? [] as $decl)
      <tr>
       <td class="td-mono">{{ $decl->no_operacion ?? '—' }}</td>
       <td class="td-bold">{{ $decl->tipo }}</td>
       <td>{{ $decl->periodo }}</td>
       <td>{{ $decl->ejercicio }}</td>
       <td class="td-bold">${{ number_format($decl->isr_determinado ?? 0, 2) }}</td>
       <td>${{ number_format($decl->isr_retenido ?? 0, 2) }}</td>
       <td class="{{ ($decl->saldo_cargo ?? 0) > 0 ? 'td-red' : 'td-green' }}">
        ${{ number_format($decl->saldo_cargo > 0 ? $decl->saldo_cargo : $decl->saldo_favor, 2) }}
        <span style="font-size:10px;opacity:.7">{{ ($decl->saldo_cargo ?? 0) > 0 ? 'cargo' : 'favor' }}</span>
       </td>
       <td>
        @switch($decl->estatus)
         @case('presentada') <span class="badge badge-presentada"><i class="fas fa-circle" style="font-size:6px"></i> Presentada</span> @break
         @case('pendiente')  <span class="badge badge-pendiente"><i class="fas fa-circle" style="font-size:6px"></i> Pendiente</span> @break
         @case('vencida')    <span class="badge badge-vencida"><i class="fas fa-circle" style="font-size:6px"></i> Vencida</span> @break
         @case('en-revision')<span class="badge badge-en-revision"><i class="fas fa-circle" style="font-size:6px"></i> En revisión</span> @break
         @case('cancelada')  <span class="badge badge-cancelada"><i class="fas fa-circle" style="font-size:6px"></i> Cancelada</span> @break
        @endswitch
       </td>
       <td>{{ $decl->fecha_presentacion ? \Carbon\Carbon::parse($decl->fecha_presentacion)->format('d/m/Y') : '—' }}</td>
       <td style="text-align:center">
        <div style="display:flex;gap:5px;justify-content:center">
         <a href="{{ route('declaraciones.show', $decl->id) }}" class="act-btn green" title="Ver detalle"><i class="fas fa-eye"></i></a>
         <a href="{{ route('declaraciones.acuse', $decl->id) }}" class="act-btn" title="Descargar acuse"><i class="fas fa-download"></i></a>
         @if($decl->estatus === 'pendiente')
          <a href="#form-declaracion" class="act-btn" title="Editar" onclick="cargarDeclaracion({{ $decl->id }})"><i class="fas fa-pen"></i></a>
         @endif
        </div>
       </td>
      </tr>
      @empty
      {{-- Datos de demo si no hay declaraciones --}}
      <tr data-tipo="Anual" data-estatus="presentada">
       <td class="td-mono">DEC-2025-0001</td>
       <td class="td-bold">Anual</td>
       <td>Enero – Diciembre</td>
       <td>2024</td>
       <td class="td-bold">$18,400.00</td>
       <td>$14,200.00</td>
       <td class="td-red">$4,200.00 <span style="font-size:10px;opacity:.7">cargo</span></td>
       <td><span class="badge badge-presentada"><i class="fas fa-circle" style="font-size:6px"></i> Presentada</span></td>
       <td>30 Abr 2025</td>
       <td style="text-align:center"><div style="display:flex;gap:5px;justify-content:center"><a href="#" class="act-btn green"><i class="fas fa-eye"></i></a><a href="#" class="act-btn"><i class="fas fa-download"></i></a></div></td>
      </tr>
      <tr data-tipo="Mensual" data-estatus="presentada">
       <td class="td-mono">DEC-2025-0024</td>
       <td class="td-bold">Mensual</td>
       <td>Diciembre</td>
       <td>2025</td>
       <td class="td-bold">$3,210.00</td>
       <td>$2,100.00</td>
       <td class="td-red">$1,110.00 <span style="font-size:10px;opacity:.7">cargo</span></td>
       <td><span class="badge badge-presentada"><i class="fas fa-circle" style="font-size:6px"></i> Presentada</span></td>
       <td>17 Ene 2026</td>
       <td style="text-align:center"><div style="display:flex;gap:5px;justify-content:center"><a href="#" class="act-btn green"><i class="fas fa-eye"></i></a><a href="#" class="act-btn"><i class="fas fa-download"></i></a></div></td>
      </tr>
      <tr data-tipo="Mensual" data-estatus="presentada">
       <td class="td-mono">DEC-2026-0002</td>
       <td class="td-bold">Mensual</td>
       <td>Enero</td>
       <td>2026</td>
       <td class="td-bold">$4,850.00</td>
       <td>$3,200.00</td>
       <td class="td-red">$1,650.00 <span style="font-size:10px;opacity:.7">cargo</span></td>
       <td><span class="badge badge-presentada"><i class="fas fa-circle" style="font-size:6px"></i> Presentada</span></td>
       <td>17 Feb 2026</td>
       <td style="text-align:center"><div style="display:flex;gap:5px;justify-content:center"><a href="#" class="act-btn green"><i class="fas fa-eye"></i></a><a href="#" class="act-btn"><i class="fas fa-download"></i></a></div></td>
      </tr>
      <tr data-tipo="DIOT" data-estatus="presentada">
       <td class="td-mono">DIOT-2026-0002</td>
       <td class="td-bold">DIOT</td>
       <td>Enero</td>
       <td>2026</td>
       <td class="td-bold">—</td>
       <td>—</td>
       <td style="color:rgba(255,255,255,.35)">N/A</td>
       <td><span class="badge badge-presentada"><i class="fas fa-circle" style="font-size:6px"></i> Presentada</span></td>
       <td>17 Feb 2026</td>
       <td style="text-align:center"><div style="display:flex;gap:5px;justify-content:center"><a href="#" class="act-btn green"><i class="fas fa-eye"></i></a><a href="#" class="act-btn"><i class="fas fa-download"></i></a></div></td>
      </tr>
      <tr data-tipo="Mensual" data-estatus="pendiente">
       <td class="td-mono" style="color:rgba(255,255,255,.3)">Pendiente</td>
       <td class="td-bold">Mensual</td>
       <td>Febrero</td>
       <td>2026</td>
       <td class="td-bold" style="color:rgba(255,255,255,.35)">—</td>
       <td style="color:rgba(255,255,255,.35)">—</td>
       <td style="color:rgba(255,255,255,.35)">—</td>
       <td><span class="badge badge-pendiente"><i class="fas fa-circle" style="font-size:6px"></i> Pendiente</span></td>
       <td style="color:#fb923c;font-weight:600">17 Mar 2026</td>
       <td style="text-align:center"><div style="display:flex;gap:5px;justify-content:center"><a href="#form-declaracion" class="act-btn green"><i class="fas fa-pen"></i></a></div></td>
      </tr>
      <tr data-tipo="Anual" data-estatus="pendiente">
       <td class="td-mono" style="color:rgba(255,255,255,.3)">Pendiente</td>
       <td class="td-bold">Anual</td>
       <td>Enero – Diciembre</td>
       <td>2025</td>
       <td class="td-bold" style="color:rgba(255,255,255,.35)">—</td>
       <td style="color:rgba(255,255,255,.35)">—</td>
       <td style="color:rgba(255,255,255,.35)">—</td>
       <td><span class="badge badge-pendiente"><i class="fas fa-circle" style="font-size:6px"></i> Pendiente</span></td>
       <td style="color:#fb923c;font-weight:600">30 Abr 2026</td>
       <td style="text-align:center"><div style="display:flex;gap:5px;justify-content:center"><a href="#form-declaracion" class="act-btn green" onclick="setTipoTab('anual')"><i class="fas fa-pen"></i></a></div></td>
      </tr>
      @endforelse

     </tbody>
    </table>
   </div>

   <div class="pagination">
    <span class="page-info" id="page-info">Mostrando 6 de 6 declaraciones</span>
    <div class="page-btns">
     <button class="page-btn" onclick="changePage(-1)"><i class="fas fa-chevron-left" style="font-size:11px"></i></button>
     <button class="page-btn active" id="pg-1">1</button>
     <button class="page-btn" onclick="changePage(1)"><i class="fas fa-chevron-right" style="font-size:11px"></i></button>
    </div>
   </div>
  </div>

  {{-- ══════════════════════════════════════════════
       FORMULARIO DE NUEVA DECLARACIÓN
  ══════════════════════════════════════════════ --}}
  <div id="form-declaracion" style="scroll-margin-top:80px">

   {{-- Tipo tabs --}}
   <div class="tipo-tabs">
    <button class="tipo-tab active" id="tab-mensual" onclick="setTipoTab('mensual')">
     <i class="fas fa-calendar-day" style="margin-right:6px"></i>Declaración Mensual
    </button>
    <button class="tipo-tab" id="tab-anual" onclick="setTipoTab('anual')">
     <i class="fas fa-calendar-alt" style="margin-right:6px"></i>Declaración Anual
    </button>
    <button class="tipo-tab" id="tab-diot" onclick="setTipoTab('diot')">
     <i class="fas fa-table" style="margin-right:6px"></i>DIOT
    </button>
    <button class="tipo-tab" id="tab-complementaria" onclick="setTipoTab('complementaria')">
     <i class="fas fa-copy" style="margin-right:6px"></i>Complementaria
    </button>
   </div>

   {{-- ── FORMULARIO MENSUAL ── --}}
   <div id="form-mensual-wrap">
    <div class="form-hero">
     <div class="form-hero-icon"><i class="fas fa-file-invoice-dollar"></i></div>
     <div class="form-hero-info">
      <div class="form-hero-title">Declaración Mensual</div>
      <div class="form-hero-sub">Pago provisional ISR / IVA del período</div>
     </div>
     <div class="form-hero-badge"><i class="fas fa-calendar-alt" style="margin-right:6px"></i>Ejercicio {{ now()->year }}</div>
    </div>

    <div class="panel-card">
     <form action="{{ route('declaraciones.store') }}" method="POST" id="form-mensual">
      @csrf
      <input type="hidden" name="tipo_declaracion" value="mensual">

      {{-- Identificación --}}
      <div class="form-section">
       <div class="form-section-title"><i class="fas fa-id-card" style="color:rgba(255,255,255,.3)"></i> Identificación</div>
       <div class="form-grid-3">
        <div class="form-group">
         <label class="form-label">RFC <span class="req">*</span></label>
         <input class="form-input" name="rfc" value="{{ Auth::user()->rfc ?? '' }}" placeholder="Tu RFC" readonly>
        </div>
        <div class="form-group">
         <label class="form-label">Período <span class="req">*</span></label>
         <select class="form-input" name="periodo" required>
          <option value="">Selecciona el mes</option>
          @foreach(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'] as $mes)
           <option value="{{ $mes }}" {{ $mes == now()->subMonth()->format('F') ? 'selected' : '' }}>{{ $mes }}</option>
          @endforeach
         </select>
        </div>
        <div class="form-group">
         <label class="form-label">Ejercicio <span class="req">*</span></label>
         <select class="form-input" name="ejercicio" required>
          @for($y = now()->year; $y >= now()->year - 5; $y--)
           <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}</option>
          @endfor
         </select>
        </div>
        <div class="form-group">
         <label class="form-label">Tipo</label>
         <select class="form-input" name="tipo_presentacion">
          <option value="normal">Normal</option>
          <option value="complementaria">Complementaria</option>
          <option value="extemporanea">Extemporánea</option>
         </select>
        </div>
        <div class="form-group">
         <label class="form-label">Régimen Fiscal</label>
         <input class="form-input" value="{{ Auth::user()->regimen_fiscal ?? 'Régimen Simplificado de Confianza' }}" readonly style="opacity:.5">
        </div>
       </div>
      </div>

      {{-- Ingresos --}}
      <div class="form-section">
       <div class="form-section-title"><i class="fas fa-chart-line" style="color:rgba(255,255,255,.3)"></i> Ingresos del período</div>
       <div class="form-grid-2">
        <div class="form-group">
         <label class="form-label">Ingresos cobrados del período <span class="req">*</span></label>
         <div class="form-input-wrap">
          <span class="input-prefix">$</span>
          <input class="form-input has-prefix" type="number" name="ingresos_cobrados" id="m_ing_cobrados" step="0.01" min="0" placeholder="0.00" oninput="calcularMensual()" required>
         </div>
        </div>
        <div class="form-group">
         <label class="form-label">Ingresos exentos</label>
         <div class="form-input-wrap">
          <span class="input-prefix">$</span>
          <input class="form-input has-prefix" type="number" name="ingresos_exentos" id="m_ing_exentos" step="0.01" min="0" placeholder="0.00" oninput="calcularMensual()">
         </div>
        </div>
        <div class="form-group">
         <label class="form-label">IVA trasladado cobrado</label>
         <div class="form-input-wrap">
          <span class="input-prefix">$</span>
          <input class="form-input has-prefix" type="number" name="iva_trasladado" id="m_iva_trasl" step="0.01" min="0" placeholder="0.00" oninput="calcularMensual()">
         </div>
        </div>
        <div class="form-group">
         <label class="form-label">IVA acreditable pagado</label>
         <div class="form-input-wrap">
          <span class="input-prefix">$</span>
          <input class="form-input has-prefix" type="number" name="iva_acreditable" id="m_iva_acred" step="0.01" min="0" placeholder="0.00" oninput="calcularMensual()">
         </div>
        </div>
       </div>
      </div>

      {{-- Deducciones --}}
      <div class="form-section">
       <div class="form-section-title"><i class="fas fa-minus-circle" style="color:rgba(255,255,255,.3)"></i> Deducciones autorizadas</div>
       <div class="form-grid-3">
        <div class="form-group">
         <label class="form-label">Gastos con comprobante</label>
         <div class="form-input-wrap">
          <span class="input-prefix">$</span>
          <input class="form-input has-prefix" type="number" name="deduc_gastos" id="m_ded_gastos" step="0.01" min="0" placeholder="0.00" oninput="calcularMensual()">
         </div>
        </div>
        <div class="form-group">
         <label class="form-label">Nómina pagada</label>
         <div class="form-input-wrap">
          <span class="input-prefix">$</span>
          <input class="form-input has-prefix" type="number" name="deduc_nomina" step="0.01" min="0" placeholder="0.00" oninput="calcularMensual()">
         </div>
        </div>
        <div class="form-group">
         <label class="form-label">Otras deducciones</label>
         <div class="form-input-wrap">
          <span class="input-prefix">$</span>
          <input class="form-input has-prefix" type="number" name="deduc_otras" step="0.01" min="0" placeholder="0.00" oninput="calcularMensual()">
         </div>
        </div>
       </div>
      </div>

      {{-- Retenciones --}}
      <div class="form-section">
       <div class="form-section-title"><i class="fas fa-hand-holding-usd" style="color:rgba(255,255,255,.3)"></i> Retenciones e impuestos</div>
       <div class="form-grid-3">
        <div class="form-group">
         <label class="form-label">ISR retenido por clientes</label>
         <div class="form-input-wrap">
          <span class="input-prefix">$</span>
          <input class="form-input has-prefix" type="number" name="isr_retenido" id="m_isr_ret" step="0.01" min="0" placeholder="0.00" oninput="calcularMensual()">
         </div>
        </div>
        <div class="form-group">
         <label class="form-label">IVA retenido por clientes</label>
         <div class="form-input-wrap">
          <span class="input-prefix">$</span>
          <input class="form-input has-prefix" type="number" name="iva_retenido" id="m_iva_ret" step="0.01" min="0" placeholder="0.00" oninput="calcularMensual()">
         </div>
        </div>
        <div class="form-group">
         <label class="form-label">Pagos provisionales anteriores</label>
         <div class="form-input-wrap">
          <span class="input-prefix">$</span>
          <input class="form-input has-prefix" type="number" name="pagos_provisionales" id="m_pag_prov" step="0.01" min="0" placeholder="0.00" oninput="calcularMensual()">
         </div>
        </div>
       </div>
      </div>

      {{-- Resumen ISR --}}
      <div class="form-section">
       <div class="form-section-title"><i class="fas fa-calculator" style="color:rgba(255,255,255,.3)"></i> Resumen de ISR / IVA</div>
       <div class="isr-resumen">
        <div class="isr-grid">
         <div class="isr-col">
          <div class="isr-label">ISR Determinado</div>
          <div class="isr-value" id="m_isr_det">$0.00</div>
          <input type="hidden" name="isr_determinado" id="m_isr_det_h" value="0">
         </div>
         <div class="isr-col" style="text-align:center">
          <div class="isr-label">IVA a pagar</div>
          <div class="isr-value" id="m_iva_pagar">$0.00</div>
          <input type="hidden" name="iva_pagar" id="m_iva_pagar_h" value="0">
         </div>
         <div class="isr-col-saldo" style="border-right:none">
          <div class="isr-label">Saldo a cargo / favor</div>
          <div class="isr-value positive" id="m_saldo">$0.00</div>
          <input type="hidden" name="saldo" id="m_saldo_h" value="0">
         </div>
        </div>
       </div>
      </div>

      {{-- Protesta --}}
      <div class="protest-wrap">
       <input type="checkbox" name="protesta" id="m_protesta" required>
       <label for="m_protesta" class="protest-text">
        <strong>Bajo protesta de decir verdad</strong>, declaro que los datos manifestados en esta declaración son correctos y que representan fielmente mi situación fiscal del período declarado. <span class="req" style="color:#f87171">*</span>
       </label>
      </div>

      <div class="form-footer">
       <div class="footer-deadline">
        <i class="fas fa-calendar-alt"></i>
        Fecha límite: <span>17 de {{ now()->format('F Y') }}</span>
       </div>
       <div class="footer-btns">
        <button type="button" class="btn btn-outline" onclick="calcularMensual()"><i class="fas fa-calculator"></i> Calcular</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Presentar Declaración</button>
       </div>
      </div>
     </form>
    </div>
   </div>

   {{-- ── FORMULARIO ANUAL ── --}}
   <div id="form-anual-wrap" style="display:none">
    <div class="form-hero">
     <div class="form-hero-icon"><i class="fas fa-file-invoice"></i></div>
     <div class="form-hero-info">
      <div class="form-hero-title">Declaración Anual</div>
      <div class="form-hero-sub">Ejercicio fiscal {{ now()->year - 1 }}</div>
     </div>
     <div class="form-hero-badge"><i class="fas fa-clock" style="margin-right:6px"></i>Vence 30 Abr {{ now()->year }}</div>
    </div>

    <div class="panel-card">
     <form action="{{ route('declaraciones.store') }}" method="POST" id="form-anual">
      @csrf
      <input type="hidden" name="tipo_declaracion" value="anual">

      <div class="form-section">
       <div class="form-section-title"><i class="fas fa-id-card" style="color:rgba(255,255,255,.3)"></i> Identificación</div>
       <div class="form-grid-3">
        <div class="form-group">
         <label class="form-label">RFC <span class="req">*</span></label>
         <input class="form-input" value="{{ Auth::user()->rfc ?? '' }}" placeholder="Tu RFC" readonly>
        </div>
        <div class="form-group">
         <label class="form-label">Ejercicio fiscal <span class="req">*</span></label>
         <select class="form-input" name="ejercicio" required>
          @for($y = now()->year - 1; $y >= now()->year - 6; $y--)
           <option value="{{ $y }}" {{ $y == now()->year - 1 ? 'selected' : '' }}>{{ $y }}</option>
          @endfor
         </select>
        </div>
        <div class="form-group">
         <label class="form-label">Tipo de declaración</label>
         <select class="form-input" name="tipo_presentacion_anual">
          <option value="normal">Normal</option>
          <option value="complementaria">Complementaria</option>
          <option value="extemporanea">Extemporánea</option>
         </select>
        </div>
       </div>
      </div>

      <div class="form-section">
       <div class="form-section-title"><i class="fas fa-chart-line" style="color:rgba(255,255,255,.3)"></i> Ingresos del ejercicio</div>
       <div class="form-grid-2">
        <div class="form-group">
         <label class="form-label">Total de ingresos acumulables <span class="req">*</span></label>
         <div class="form-input-wrap">
          <span class="input-prefix">$</span>
          <input class="form-input has-prefix" type="number" name="ingresos_acumulables" id="a_ing_acum" step="0.01" min="0" placeholder="0.00" oninput="calcularAnual()" required>
         </div>
        </div>
        <div class="form-group">
         <label class="form-label">Ingresos exentos</label>
         <div class="form-input-wrap">
          <span class="input-prefix">$</span>
          <input class="form-input has-prefix" type="number" name="ingresos_exentos_anual" id="a_ing_exent" step="0.01" min="0" placeholder="0.00" oninput="calcularAnual()">
         </div>
        </div>
       </div>
      </div>

      <div class="form-section">
       <div class="form-section-title"><i class="fas fa-minus-circle" style="color:rgba(255,255,255,.3)"></i> Deducciones autorizadas</div>
       <div class="form-grid-3">
        <div class="form-group">
         <label class="form-label">Honorarios médicos</label>
         <div class="form-input-wrap">
          <span class="input-prefix">$</span>
          <input class="form-input has-prefix" type="number" name="ded_honorarios_medicos" step="0.01" min="0" placeholder="0.00" oninput="calcularAnual()">
         </div>
        </div>
        <div class="form-group">
         <label class="form-label">Gastos hospitalarios</label>
         <div class="form-input-wrap">
          <span class="input-prefix">$</span>
          <input class="form-input has-prefix" type="number" name="ded_gastos_hospitalarios" step="0.01" min="0" placeholder="0.00" oninput="calcularAnual()">
         </div>
        </div>
        <div class="form-group">
         <label class="form-label">Primas de seguro médico</label>
         <div class="form-input-wrap">
          <span class="input-prefix">$</span>
          <input class="form-input has-prefix" type="number" name="ded_seguro_medico" step="0.01" min="0" placeholder="0.00" oninput="calcularAnual()">
         </div>
        </div>
        <div class="form-group">
         <label class="form-label">Colegiaturas</label>
         <div class="form-input-wrap">
          <span class="input-prefix">$</span>
          <input class="form-input has-prefix" type="number" name="ded_colegiaturas" step="0.01" min="0" placeholder="0.00" oninput="calcularAnual()">
         </div>
        </div>
        <div class="form-group">
         <label class="form-label">Intereses reales crédito hipotecario</label>
         <div class="form-input-wrap">
          <span class="input-prefix">$</span>
          <input class="form-input has-prefix" type="number" name="ded_intereses_hipotecarios" step="0.01" min="0" placeholder="0.00" oninput="calcularAnual()">
         </div>
        </div>
        <div class="form-group">
         <label class="form-label">Donativos</label>
         <div class="form-input-wrap">
          <span class="input-prefix">$</span>
          <input class="form-input has-prefix" type="number" name="ded_donativos" step="0.01" min="0" placeholder="0.00" oninput="calcularAnual()">
         </div>
        </div>
        <div class="form-group">
         <label class="form-label">Aportaciones a AFORE</label>
         <div class="form-input-wrap">
          <span class="input-prefix">$</span>
          <input class="form-input has-prefix" type="number" name="ded_afore" step="0.01" min="0" placeholder="0.00" oninput="calcularAnual()">
         </div>
        </div>
        <div class="form-group">
         <label class="form-label">Transporte escolar</label>
         <div class="form-input-wrap">
          <span class="input-prefix">$</span>
          <input class="form-input has-prefix" type="number" name="ded_transporte_escolar" step="0.01" min="0" placeholder="0.00" oninput="calcularAnual()">
         </div>
        </div>
        <div class="form-group">
         <label class="form-label">Otras deducciones</label>
         <div class="form-input-wrap">
          <span class="input-prefix">$</span>
          <input class="form-input has-prefix" type="number" name="ded_otras_anual" step="0.01" min="0" placeholder="0.00" oninput="calcularAnual()">
         </div>
        </div>
       </div>
      </div>

      <div class="form-section">
       <div class="form-section-title"><i class="fas fa-hand-holding-usd" style="color:rgba(255,255,255,.3)"></i> Pagos provisionales acumulados</div>
       <div class="form-grid-3">
        <div class="form-group">
         <label class="form-label">ISR retenido total del ejercicio</label>
         <div class="form-input-wrap">
          <span class="input-prefix">$</span>
          <input class="form-input has-prefix" type="number" name="isr_retenido_anual" id="a_isr_ret" step="0.01" min="0" placeholder="0.00" oninput="calcularAnual()">
         </div>
        </div>
        <div class="form-group">
         <label class="form-label">Pagos provisionales anuales</label>
         <div class="form-input-wrap">
          <span class="input-prefix">$</span>
          <input class="form-input has-prefix" type="number" name="pagos_provisionales_anual" id="a_pag_prov" step="0.01" min="0" placeholder="0.00" oninput="calcularAnual()">
         </div>
        </div>
        <div class="form-group">
         <label class="form-label">Estímulos fiscales</label>
         <div class="form-input-wrap">
          <span class="input-prefix">$</span>
          <input class="form-input has-prefix" type="number" name="estimulos_fiscales" step="0.01" min="0" placeholder="0.00" oninput="calcularAnual()">
         </div>
        </div>
       </div>
      </div>

      {{-- Resumen ISR anual --}}
      <div class="form-section">
       <div class="form-section-title"><i class="fas fa-calculator" style="color:rgba(255,255,255,.3)"></i> Resumen de ISR</div>
       <div class="isr-resumen">
        <div class="isr-grid">
         <div class="isr-col">
          <div class="isr-label">ISR Determinado</div>
          <div class="isr-value" id="a_isr_det">$0.00</div>
          <input type="hidden" name="isr_determinado" id="a_isr_det_h" value="0">
         </div>
         <div class="isr-col" style="text-align:center">
          <div class="isr-label" style="margin-bottom:10px">ISR Retenido (manual)</div>
          <input class="isr-input" type="number" name="isr_retenido_input" id="a_isr_ret_inp" step="0.01" min="0" placeholder="0.00" oninput="calcularAnual()">
          <input type="hidden" name="isr_retenido" id="a_isr_ret_h" value="0">
         </div>
         <div class="isr-col-saldo" style="border-right:none">
          <div class="isr-label">Saldo a cargo / favor</div>
          <div class="isr-value positive" id="a_saldo">$0.00</div>
          <input type="hidden" name="saldo" id="a_saldo_h" value="0">
         </div>
        </div>
       </div>
      </div>

      <div class="protest-wrap">
       <input type="checkbox" name="protesta_anual" id="a_protesta" required>
       <label for="a_protesta" class="protest-text">
        <strong>Bajo protesta de decir verdad</strong>, declaro que los datos manifestados son correctos y que representan fielmente mi situación fiscal del ejercicio. <span class="req" style="color:#f87171">*</span>
       </label>
      </div>

      <div class="form-footer">
       <div class="footer-deadline">
        <i class="fas fa-calendar-alt"></i>
        Fecha límite: <span>30 de Abril {{ now()->year }}</span>
       </div>
       <div class="footer-btns">
        <button type="button" class="btn btn-outline" onclick="calcularAnual()"><i class="fas fa-calculator"></i> Calcular</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Presentar Declaración</button>
       </div>
      </div>
     </form>
    </div>
   </div>

   {{-- ── FORMULARIO DIOT ── --}}
   <div id="form-diot-wrap" style="display:none">
    <div class="form-hero" style="background:linear-gradient(135deg,#1e3a5f 0%,#1a2f50 100%)">
     <div class="form-hero-icon"><i class="fas fa-table"></i></div>
     <div class="form-hero-info">
      <div class="form-hero-title">DIOT</div>
      <div class="form-hero-sub">Declaración Informativa de Operaciones con Terceros</div>
     </div>
     <div class="form-hero-badge" style="background:rgba(96,165,250,.15);border-color:rgba(96,165,250,.3)"><i class="fas fa-calendar-alt" style="margin-right:6px"></i>Ejercicio {{ now()->year }}</div>
    </div>

    <div class="panel-card">
     <form action="{{ route('declaraciones.store') }}" method="POST">
      @csrf
      <input type="hidden" name="tipo_declaracion" value="diot">

      <div class="form-section">
       <div class="form-section-title"><i class="fas fa-id-card" style="color:rgba(255,255,255,.3)"></i> Identificación</div>
       <div class="form-grid-3">
        <div class="form-group">
         <label class="form-label">RFC <span class="req">*</span></label>
         <input class="form-input" value="{{ Auth::user()->rfc ?? '' }}" readonly>
        </div>
        <div class="form-group">
         <label class="form-label">Período <span class="req">*</span></label>
         <select class="form-input" name="periodo_diot" required>
          <option value="">Selecciona el mes</option>
          @foreach(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'] as $mes)
           <option value="{{ $mes }}">{{ $mes }}</option>
          @endforeach
         </select>
        </div>
        <div class="form-group">
         <label class="form-label">Ejercicio <span class="req">*</span></label>
         <select class="form-input" name="ejercicio_diot" required>
          @for($y = now()->year; $y >= now()->year - 5; $y--)
           <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}</option>
          @endfor
         </select>
        </div>
       </div>
      </div>

      <div class="form-section">
       <div class="form-section-title"><i class="fas fa-users" style="color:rgba(255,255,255,.3)"></i> Operaciones con proveedores</div>
       <div class="form-grid-2">
        <div class="form-group">
         <label class="form-label">IVA pagado a proveedores nacionales</label>
         <div class="form-input-wrap"><span class="input-prefix">$</span>
          <input class="form-input has-prefix" type="number" name="iva_proveedores_nac" step="0.01" min="0" placeholder="0.00">
         </div>
        </div>
        <div class="form-group">
         <label class="form-label">IVA pagado a proveedores extranjeros</label>
         <div class="form-input-wrap"><span class="input-prefix">$</span>
          <input class="form-input has-prefix" type="number" name="iva_proveedores_ext" step="0.01" min="0" placeholder="0.00">
         </div>
        </div>
        <div class="form-group">
         <label class="form-label">IVA pagado en importaciones</label>
         <div class="form-input-wrap"><span class="input-prefix">$</span>
          <input class="form-input has-prefix" type="number" name="iva_importaciones" step="0.01" min="0" placeholder="0.00">
         </div>
        </div>
        <div class="form-group">
         <label class="form-label">Total de operaciones con terceros</label>
         <div class="form-input-wrap"><span class="input-prefix">$</span>
          <input class="form-input has-prefix" type="number" name="total_operaciones" step="0.01" min="0" placeholder="0.00">
         </div>
        </div>
       </div>
       <div class="field-hint" style="margin-top:8px"><i class="fas fa-info-circle" style="margin-right:4px"></i>Para DIOT con múltiples proveedores puedes cargar el archivo TXT generado por el software del SAT.</div>
      </div>

      <div class="protest-wrap">
       <input type="checkbox" name="protesta_diot" id="d_protesta" required>
       <label for="d_protesta" class="protest-text">
        <strong>Bajo protesta de decir verdad</strong>, declaro que los datos son correctos. <span class="req" style="color:#f87171">*</span>
       </label>
      </div>

      <div class="form-footer">
       <div class="footer-deadline">
        <i class="fas fa-calendar-alt"></i>
        Fecha límite: <span>17 del mes siguiente</span>
       </div>
       <div class="footer-btns">
        <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Presentar DIOT</button>
       </div>
      </div>
     </form>
    </div>
   </div>

   {{-- ── FORMULARIO COMPLEMENTARIA ── --}}
   <div id="form-complementaria-wrap" style="display:none">
    <div class="form-hero" style="background:linear-gradient(135deg,#7c2d12 0%,#6b2510 100%)">
     <div class="form-hero-icon"><i class="fas fa-copy"></i></div>
     <div class="form-hero-info">
      <div class="form-hero-title">Declaración Complementaria</div>
      <div class="form-hero-sub">Corrección de una declaración previamente presentada</div>
     </div>
    </div>

    <div class="panel-card">
     <form action="{{ route('declaraciones.store') }}" method="POST">
      @csrf
      <input type="hidden" name="tipo_declaracion" value="complementaria">

      <div class="form-section">
       <div class="form-section-title"><i class="fas fa-search" style="color:rgba(255,255,255,.3)"></i> Declaración a corregir</div>
       <div class="form-grid-3">
        <div class="form-group">
         <label class="form-label">Folio / número de operación <span class="req">*</span></label>
         <input class="form-input" name="folio_original" placeholder="Ej. DEC-2026-0001" required>
        </div>
        <div class="form-group">
         <label class="form-label">Tipo de declaración original</label>
         <select class="form-input" name="tipo_original">
          <option value="mensual">Mensual</option>
          <option value="anual">Anual</option>
          <option value="diot">DIOT</option>
         </select>
        </div>
        <div class="form-group">
         <label class="form-label">Motivo de corrección</label>
         <select class="form-input" name="motivo_correccion">
          <option value="error_datos">Error en datos</option>
          <option value="omision_ingresos">Omisión de ingresos</option>
          <option value="deduccion_incorrecta">Deducción incorrecta</option>
          <option value="otro">Otro</option>
         </select>
        </div>
        <div class="form-group full">
         <label class="form-label">Descripción de la corrección</label>
         <textarea class="form-input" name="descripcion_correccion" rows="3" placeholder="Describe brevemente qué se está corrigiendo..." style="resize:vertical"></textarea>
        </div>
       </div>
      </div>

      <div style="background:rgba(251,146,60,.08);border:1px solid rgba(251,146,60,.2);border-radius:10px;padding:14px 16px;margin-bottom:20px;font-size:13px;color:rgba(251,146,60,.9);display:flex;gap:10px;align-items:flex-start">
       <i class="fas fa-exclamation-triangle" style="margin-top:1px;flex-shrink:0"></i>
       <div>Una declaración complementaria reemplaza la declaración original. Pueden aplicarse recargos y actualizaciones si la nueva declaración resulta en un saldo mayor a cargo.</div>
      </div>

      <div class="protest-wrap">
       <input type="checkbox" name="protesta_comp" id="c_protesta" required>
       <label for="c_protesta" class="protest-text">
        <strong>Bajo protesta de decir verdad</strong>, declaro que los datos corregidos son correctos. <span class="req" style="color:#f87171">*</span>
       </label>
      </div>

      <div class="form-footer">
       <div class="footer-deadline">
        <i class="fas fa-info-circle"></i>
        Las complementarias no tienen fecha límite, pero se recomienda presentarlas a la brevedad.
       </div>
       <div class="footer-btns">
        <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Presentar Complementaria</button>
       </div>
      </div>
     </form>
    </div>
   </div>

  </div>{{-- /form-declaracion --}}

 </div>{{-- /content --}}
</div>{{-- /main --}}

<script>
// ── Sidebar ─────────────────────────────────────
function toggleSidebar(){
 document.getElementById('sidebar').classList.toggle('collapsed');
}

// ── Tipo tabs ────────────────────────────────────
function setTipoTab(tipo){
 ['mensual','anual','diot','complementaria'].forEach(t=>{
  document.getElementById('tab-'+t).classList.toggle('active', t===tipo);
  document.getElementById('form-'+t+'-wrap').style.display = t===tipo ? 'block' : 'none';
 });
 document.getElementById('form-declaracion').scrollIntoView({behavior:'smooth'});
}

// ── Table filter ─────────────────────────────────
function filtrarTabla(){
 const q     = document.getElementById('tbl-buscar').value.toLowerCase();
 const tipo  = document.getElementById('tbl-tipo').value.toLowerCase();
 const estat = document.getElementById('tbl-estatus').value.toLowerCase();
 let vis = 0;
 document.querySelectorAll('#tbody-declaraciones tr').forEach(tr=>{
  const text   = tr.textContent.toLowerCase();
  const tipoTd = (tr.dataset.tipo||tr.cells[1]?.textContent||'').toLowerCase();
  const estTd  = (tr.dataset.estatus||tr.cells[7]?.textContent||'').toLowerCase();
  const ok = text.includes(q) && (!tipo||tipoTd.includes(tipo)) && (!estat||estTd.includes(estat));
  tr.style.display = ok ? '' : 'none';
  if(ok) vis++;
 });
 document.getElementById('page-info').textContent = `Mostrando ${vis} declaraciones`;
}

// ── Pagination placeholder ───────────────────────
function changePage(dir){ /* implementar con backend */ }

// ── ISR Mensual calc ─────────────────────────────
function calcularMensual(){
 const ing  = parseFloat(document.getElementById('m_ing_cobrados')?.value)||0;
 const exen = parseFloat(document.getElementById('m_ing_exentos')?.value)||0;
 const ded  = parseFloat(document.getElementById('m_ded_gastos')?.value)||0;
 const ret  = parseFloat(document.getElementById('m_isr_ret')?.value)||0;
 const prov = parseFloat(document.getElementById('m_pag_prov')?.value)||0;
 const ivaTr= parseFloat(document.getElementById('m_iva_trasl')?.value)||0;
 const ivaAc= parseFloat(document.getElementById('m_iva_acred')?.value)||0;
 const ivaRe= parseFloat(document.getElementById('m_iva_ret')?.value)||0;

 const base     = Math.max(0, ing - exen - ded);
 const tasa     = 0.10;
 const isrDet   = base * tasa;
 const saldo    = isrDet - ret - prov;
 const ivaPagar = Math.max(0, ivaTr - ivaAc - ivaRe);

 const fmt = v => '$' + Math.abs(v).toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2});

 document.getElementById('m_isr_det').textContent   = fmt(isrDet);
 document.getElementById('m_iva_pagar').textContent  = fmt(ivaPagar);
 document.getElementById('m_saldo').textContent      = fmt(saldo) + (saldo < 0 ? ' (favor)' : '');
 document.getElementById('m_saldo').className        = 'isr-value ' + (saldo <= 0 ? 'positive' : 'negative');
 document.getElementById('m_isr_det_h').value  = isrDet.toFixed(2);
 document.getElementById('m_iva_pagar_h').value= ivaPagar.toFixed(2);
 document.getElementById('m_saldo_h').value    = saldo.toFixed(2);
}

// ── ISR Anual calc ───────────────────────────────
function calcularAnual(){
 const ing  = parseFloat(document.getElementById('a_ing_acum')?.value)||0;
 const exen = parseFloat(document.getElementById('a_ing_exent')?.value)||0;
 const ret  = parseFloat(document.getElementById('a_isr_ret')?.value)||0;
 const prov = parseFloat(document.getElementById('a_pag_prov')?.value)||0;
 const retInp = parseFloat(document.getElementById('a_isr_ret_inp')?.value)||0;

 // Suma todas las deducciones
 let dedTotal = 0;
 document.querySelectorAll('#form-anual input[name^="ded_"]').forEach(el=>{
  dedTotal += parseFloat(el.value)||0;
 });

 const base   = Math.max(0, ing - exen - dedTotal);
 const tasa   = 0.15;
 const isrDet = base * tasa;
 const totalRet= ret + retInp + prov;
 const saldo  = isrDet - totalRet;

 const fmt = v => '$' + Math.abs(v).toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2});

 document.getElementById('a_isr_det').textContent = fmt(isrDet);
 document.getElementById('a_saldo').textContent   = fmt(saldo) + (saldo < 0 ? ' (favor)' : '');
 document.getElementById('a_saldo').className     = 'isr-value ' + (saldo <= 0 ? 'positive' : 'negative');
 document.getElementById('a_isr_det_h').value = isrDet.toFixed(2);
 document.getElementById('a_isr_ret_h').value = totalRet.toFixed(2);
 document.getElementById('a_saldo_h').value   = saldo.toFixed(2);
}

// ── Auto dismiss alerts ──────────────────────────
setTimeout(()=>{
 document.querySelectorAll('.alert-success,.alert-error').forEach(el=>{
  el.style.transition='opacity .5s';el.style.opacity='0';
  setTimeout(()=>el.remove(),500);
 });
},4500);
</script>
</body>
</html>