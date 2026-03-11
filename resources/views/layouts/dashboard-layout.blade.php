<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Portal SAT') – Portal SAT</title>
<link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@stack('head-scripts')
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

/* ── Search dropdown ──────────────────────────── */
.search-dropdown{position:absolute;top:calc(100% + 8px);left:0;width:100%;background:#0d1520;border:1px solid rgba(255,255,255,.1);border-radius:12px;overflow:hidden;box-shadow:0 16px 40px rgba(0,0,0,.5);z-index:999;display:none}
.search-dropdown.active{display:block}
.sd-section{padding:10px 14px 6px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:rgba(255,255,255,.25)}
.sd-item{display:flex;align-items:center;gap:12px;padding:10px 14px;cursor:pointer;transition:background .15s;text-decoration:none;color:inherit}
.sd-item:hover{background:rgba(255,255,255,.06)}
.sd-icon{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0}
.sd-icon.green{background:rgba(0,104,71,.2);color:#4ade80}
.sd-icon.blue{background:rgba(30,100,220,.2);color:#60a5fa}
.sd-icon.orange{background:rgba(200,80,20,.2);color:#fb923c}
.sd-item-info{flex:1;min-width:0}
.sd-item-name{font-size:13px;font-weight:600;color:white}
.sd-item-sub{font-size:11px;color:rgba(255,255,255,.35);margin-top:1px}
.sd-item-badge{font-size:11px;color:rgba(255,255,255,.3);flex-shrink:0}
.sd-empty{padding:20px 14px;text-align:center;font-size:13px;color:rgba(255,255,255,.3)}
.sd-loading{padding:16px 14px;text-align:center;font-size:13px;color:rgba(255,255,255,.3)}

/* ── Content ──────────────────────────────────── */
.content{flex:1;padding:28px;overflow-y:auto}

@media(max-width:1100px){.charts-row,.bottom-row{grid-template-columns:1fr}}
@media(max-width:640px){.kpi-grid{grid-template-columns:1fr}.content{padding:16px}.sidebar{display:none}}

</style>
@stack('styles')
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
  <a href="{{ route('dashboard') }}"
     class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
   <i class="fas fa-home ni"></i><span class="nav-label">Inicio</span>
  </a>
  <a href="{{ route('declaraciones.usuario') }}"
     class="nav-item {{ request()->routeIs('declaraciones.*') ? 'active' : '' }}">
   <i class="fas fa-file-invoice ni"></i><span class="nav-label">Mis Declaraciones</span>
  </a>
  <a href="{{ route('facturacion.mis_facturas') }}"
     class="nav-item {{ request()->routeIs('facturacion.*') ? 'active' : '' }}">
   <i class="fas fa-file-invoice-dollar ni"></i><span class="nav-label">Facturas CFDI</span>
   <span class="sb-badge">3</span>
  </a>

  <div class="sb-section">Trámites</div>
  <a href="{{ route('personas.mi_rfc') }}"
     class="nav-item {{ request()->routeIs('personas.mi_rfc') ? 'active' : '' }}">
   <i class="fas fa-id-card ni"></i><span class="nav-label">Mi RFC</span>
  </a>
  <a href="{{ route('personas.cif') }}"
     class="nav-item {{ request()->routeIs('personas.cif') ? 'active' : '' }}">
   <i class="fas fa-file-alt ni"></i><span class="nav-label">Constancia Fiscal</span>
  </a>
  <a href="{{ route('personas.e_firma') }}"
     class="nav-item {{ request()->routeIs('personas.e_firma') ? 'active' : '' }}">
   <i class="fas fa-signature ni"></i><span class="nav-label">e.firma</span>
  </a>
  <a href="{{ route('contacto.citas.index') }}"
     class="nav-item {{ request()->routeIs('contacto.citas.*') ? 'active' : '' }}">
   <i class="fas fa-calendar-check ni"></i><span class="nav-label">Citas</span>
  </a>

  <div class="sb-section">Cuenta</div>
  <a href="#"
     class="nav-item {{ request()->routeIs('calendario.*') ? 'active' : '' }}">
   <i class="fas fa-calendar ni"></i><span class="nav-label">Calendario fiscal</span>
  </a>
  <a href="{{ route('perfil.index') }}"
     class="nav-item {{ request()->routeIs('perfil.*') ? 'active' : '' }}">
   <i class="fas fa-user-circle ni"></i><span class="nav-label">Mi Perfil</span>
  </a>
 </div>

 <div>
  <a href="{{ route('ayuda') }}"
     class="nav-item {{ request()->routeIs('ayuda') ? 'active' : '' }}"
     style="margin:0 10px 6px">
   <i class="fas fa-question-circle ni"></i><span class="nav-label">Ayuda</span>
  </a>
  <form action="{{ route('logout') }}" method="POST">
   @csrf
   <button type="submit" class="nav-item"
     style="width:calc(100% - 20px);margin:0 10px 10px;background:none;border:none;cursor:pointer;color:rgba(255,255,255,.45);font-size:14px;font-weight:600;font-family:inherit">
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
  {{-- Buscador global --}}
  <div class="search-wrap" id="searchWrap">
   <i class="fas fa-search search-icon"></i>
   <input type="text"
          id="buscadorGlobal"
          class="search-input"
          placeholder="Buscar trámites, facturas, declaraciones..."
          autocomplete="off">
   <div class="search-dropdown" id="searchDropdown"></div>
  </div>

  <div class="topbar-right">
   <a href="{{ route('facturacion.index') }}" class="tb-icon-btn" title="Notificaciones">
    <i class="fas fa-bell"></i>
    <span class="tb-notif-dot"></span>
   </a>
   <div class="tb-user">
    <div class="tb-av">{{ strtoupper(substr(Auth::user()->nombres ?? 'U', 0, 2)) }}</div>
    <div class="tb-uinfo">
     <div class="tb-uname">{{ Auth::user()->nombres ?? 'Usuario' }} {{ Auth::user()->primer_apellido ?? '' }} {{ Auth::user()->segundo_apellido ?? '' }}</div>
     <div class="tb-urfc">{{ Auth::user()->rfc ?? 'RFC' }} · CDMX</div>
    </div>
   </div>
  </div>
 </div>

 {{-- ── Contenido de cada página ──────────────── --}}
 <div class="content">
  @yield('content')
 </div>

</div>{{-- /main --}}

<script>
// ── Sidebar toggle ──────────────────────────────
function toggleSidebar(){
 document.getElementById('sidebar').classList.toggle('collapsed');
}

// ── Buscador global ─────────────────────────────
(function(){
 const input    = document.getElementById('buscadorGlobal');
 const dropdown = document.getElementById('searchDropdown');
 const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
 let timer;

 // Trámites estáticos (secciones del portal)
 const tramitesEstaticos = [
  { nombre:'Inicio',             url:'{{ route("dashboard") }}',                icono:'fa-home',              color:'green' },
  { nombre:'Mis Declaraciones',  url:'{{ route("declaraciones.usuario") }}',    icono:'fa-file-invoice',      color:'blue'  },
  { nombre:'Facturas CFDI',      url:'{{ route("facturacion.mis_facturas") }}', icono:'fa-file-invoice-dollar',color:'blue' },
  { nombre:'Mi RFC',             url:'{{ route("personas.mi_rfc") }}',          icono:'fa-id-card',           color:'green' },
  { nombre:'Constancia Fiscal',  url:'{{ route("personas.cif") }}',             icono:'fa-file-alt',          color:'green' },
  { nombre:'e.firma',            url:'{{ route("personas.e_firma") }}',         icono:'fa-signature',         color:'orange'},
  { nombre:'Citas',              url:'{{ route("contacto.citas.index") }}',     icono:'fa-calendar-check',    color:'blue'  },
  { nombre:'Calendario Fiscal',  url:'#',                                        icono:'fa-calendar',          color:'blue'  },
  { nombre:'Mi Perfil',          url:'{{ route("perfil.index") }}',             icono:'fa-user-circle',       color:'green' },
  { nombre:'Ayuda',              url:'{{ route("ayuda") }}',                    icono:'fa-question-circle',   color:'blue'  },
 ];

 function renderTramite(t){
  return `<a href="${t.url}" class="sd-item">
   <div class="sd-icon ${t.color}"><i class="fas ${t.icono}"></i></div>
   <div class="sd-item-info">
    <div class="sd-item-name">${t.nombre}</div>
    <div class="sd-item-sub">Trámite</div>
   </div>
   <span class="sd-item-badge"><i class="fas fa-arrow-right"></i></span>
  </a>`;
 }

 function renderFactura(f){
  return `<a href="{{ route('facturacion.mis_facturas') }}" class="sd-item">
   <div class="sd-icon blue"><i class="fas fa-file-invoice-dollar"></i></div>
   <div class="sd-item-info">
    <div class="sd-item-name">${f.folio ?? f.uuid ?? 'Factura'}</div>
    <div class="sd-item-sub">${f.receptor_nombre ?? ''} · $${parseFloat(f.total ?? 0).toLocaleString('es-MX')}</div>
   </div>
   <span class="sd-item-badge">CFDI</span>
  </a>`;
 }

 function renderDeclaracion(d){
  return `<a href="{{ route('declaraciones.usuario') }}" class="sd-item">
   <div class="sd-icon orange"><i class="fas fa-file-alt"></i></div>
   <div class="sd-item-info">
    <div class="sd-item-name">${d.tipo ?? 'Declaración'}</div>
    <div class="sd-item-sub">${d.periodo ?? ''}</div>
   </div>
   <span class="sd-item-badge">Decl.</span>
  </a>`;
 }

 async function buscar(q){
  dropdown.innerHTML = `<div class="sd-loading"><i class="fas fa-spinner fa-spin"></i> Buscando...</div>`;
  dropdown.classList.add('active');

  // Filtrar trámites estáticos localmente (sin petición al servidor)
  const tramitesFiltrados = tramitesEstaticos.filter(t =>
   t.nombre.toLowerCase().includes(q.toLowerCase())
  );

  try {
   const res = await fetch(`/portal/buscar?q=${encodeURIComponent(q)}`, {
    headers:{
     'X-Requested-With':'XMLHttpRequest',
     'X-CSRF-TOKEN': csrfToken
    }
   });
   const data = await res.json();

   let html = '';

   if(tramitesFiltrados.length){
    html += `<div class="sd-section">Trámites</div>`;
    html += tramitesFiltrados.map(renderTramite).join('');
   }

   if(data.facturas && data.facturas.length){
    html += `<div class="sd-section">Facturas</div>`;
    html += data.facturas.map(renderFactura).join('');
   }

   if(data.declaraciones && data.declaraciones.length){
    html += `<div class="sd-section">Declaraciones</div>`;
    html += data.declaraciones.map(renderDeclaracion).join('');
   }

   if(!html){
    html = `<div class="sd-empty"><i class="fas fa-search" style="margin-bottom:6px;display:block;opacity:.4"></i>Sin resultados para "${q}"</div>`;
   }

   dropdown.innerHTML = html;

  } catch(e){
   // Si la ruta aún no existe, solo mostramos trámites estáticos
   let html = '';
   if(tramitesFiltrados.length){
    html += `<div class="sd-section">Trámites</div>`;
    html += tramitesFiltrados.map(renderTramite).join('');
   } else {
    html = `<div class="sd-empty"><i class="fas fa-search" style="margin-bottom:6px;display:block;opacity:.4"></i>Sin resultados para "${q}"</div>`;
   }
   dropdown.innerHTML = html;
  }
 }

 input.addEventListener('input', () => {
  clearTimeout(timer);
  const q = input.value.trim();
  if(q.length < 2){ dropdown.classList.remove('active'); return; }
  timer = setTimeout(() => buscar(q), 300);
 });

 input.addEventListener('focus', () => {
  if(input.value.trim().length >= 2) dropdown.classList.add('active');
 });

 // Cierra al hacer click afuera
 document.addEventListener('click', (e) => {
  if(!document.getElementById('searchWrap').contains(e.target)){
   dropdown.classList.remove('active');
  }
 });

 // Cierra con Escape
 input.addEventListener('keydown', (e) => {
  if(e.key === 'Escape') dropdown.classList.remove('active');
 });
})();
</script>

@stack('scripts')
</body>
</html>