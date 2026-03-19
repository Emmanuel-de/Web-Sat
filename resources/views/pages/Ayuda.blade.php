@extends('layouts.dashboard-layout')

@section('title', 'Ayuda')

@push('styles')
<style>
.page-title{font-size:26px;font-weight:700;color:white;margin-bottom:4px}
.page-sub{font-size:14px;color:rgba(255,255,255,.45);margin-bottom:28px}

/* ── Hero ── */
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

/* ── Status ── */
.status-banner{background:rgba(0,104,71,.08);border:1px solid rgba(74,222,128,.15);border-radius:10px;padding:14px 18px;display:flex;align-items:center;gap:12px;margin-bottom:24px}
.status-dot{width:8px;height:8px;background:#4ade80;border-radius:50%;flex-shrink:0;box-shadow:0 0 8px rgba(74,222,128,.5);animation:pulse 2s infinite}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.5}}
.status-text{font-size:13px;color:rgba(255,255,255,.65)}
.status-text strong{color:#4ade80}

/* ── Panel ── */
.panel-card{background:#0d1520;border:1px solid rgba(255,255,255,.07);border-radius:14px;padding:24px;margin-bottom:24px}
.panel-header{display:flex;align-items:center;gap:10px;margin-bottom:20px}
.panel-icon{width:34px;height:34px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0}
.panel-icon.green{background:rgba(0,104,71,.2);color:#4ade80}
.panel-icon.blue{background:rgba(30,100,220,.15);color:#60a5fa}
.panel-icon.orange{background:rgba(200,80,20,.15);color:#fb923c}
.panel-icon.yellow{background:rgba(251,191,36,.1);color:#fbbf24}
.panel-title{font-size:15px;font-weight:700;color:white}
.panel-subtitle{font-size:12px;color:rgba(255,255,255,.35);margin-top:2px}

/* ── Quick links ── */
.quick-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px}
.quick-card{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:10px;padding:16px;text-align:center;transition:all .2s;text-decoration:none;display:block}
.quick-card:hover{background:rgba(255,255,255,.06);border-color:rgba(255,255,255,.1);transform:translateY(-1px)}
.quick-card i{font-size:20px;margin-bottom:10px;display:block}
.quick-card span{font-size:12px;font-weight:600;color:rgba(255,255,255,.6);display:block}

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

/* ── Two col ── */
.two-col{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px}

/* ── Info rows ── */
.info-row{display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid rgba(255,255,255,.04)}
.info-row:last-child{border-bottom:none}
.info-row-icon{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0}
.info-row-label{font-size:12px;color:rgba(255,255,255,.35);font-weight:600;text-transform:uppercase;letter-spacing:.3px}
.info-row-val{font-size:13px;color:rgba(255,255,255,.75);margin-top:2px}

/* ── Guides ── */
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

/* ── FAQ ── */
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

@media(max-width:1100px){.contact-grid{grid-template-columns:1fr 1fr}.guides-grid{grid-template-columns:1fr 1fr}.quick-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:640px){.contact-grid,.two-col{grid-template-columns:1fr}.guides-grid{grid-template-columns:1fr}.quick-grid{grid-template-columns:repeat(2,1fr)}}
</style>
@endpush

@section('content')

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
<div class="panel-card">
 <div class="panel-header">
  <div class="panel-icon green"><i class="fas fa-bolt"></i></div>
  <div><div class="panel-title">Accesos Rápidos</div><div class="panel-subtitle">Los trámites más solicitados</div></div>
 </div>
 <div class="quick-grid">
  <a href="{{ route('declaraciones.usuario') }}" class="quick-card"><i class="fas fa-file-invoice" style="color:#4ade80"></i><span>Presentar Declaración</span></a>
  <a href="{{ route('facturacion.mis_facturas') }}" class="quick-card"><i class="fas fa-file-invoice-dollar" style="color:#60a5fa"></i><span>Emitir CFDI</span></a>
  <a href="{{ route('personas.mi_constancia') }}" class="quick-card"><i class="fas fa-file-alt" style="color:#fb923c"></i><span>Constancia Fiscal</span></a>
  <a href="{{ route('contacto.citas.index') }}" class="quick-card"><i class="fas fa-calendar-check" style="color:#fbbf24"></i><span>Agendar Cita</span></a>
  <a href="{{ route('personas.mi_rfc') }}" class="quick-card"><i class="fas fa-id-card" style="color:#a78bfa"></i><span>Consultar RFC</span></a>
  <a href="{{ route('personas.mie_firma') }}" class="quick-card"><i class="fas fa-signature" style="color:#f87171"></i><span>e.firma</span></a>
  <a href="{{ route('perfil.index') }}" class="quick-card"><i class="fas fa-user-cog" style="color:#34d399"></i><span>Mi Perfil</span></a>
  <a href="#faq" class="quick-card"><i class="fas fa-question-circle" style="color:#60a5fa"></i><span>Preguntas Frecuentes</span></a>
 </div>
</div>

{{-- ── Contacto ── --}}
<div class="panel-header" style="margin-bottom:16px">
 <div class="panel-icon green"><i class="fas fa-headset"></i></div>
 <div><div class="panel-title" style="font-size:17px">Contacto y Atención al Contribuyente</div><div class="panel-subtitle">Agenda una cita, envía una consulta o contáctanos por teléfono</div></div>
</div>

<div class="contact-grid">
 <div class="contact-card phone">
  <div class="contact-card-icon red"><i class="fas fa-phone-alt"></i></div>
  <h3>SAT MarcaSAT</h3>
  <p>Atención telefónica directa con asesores del SAT</p>
  <div class="contact-phone">55 627 22 728</div>
  <div class="contact-hours">Lunes a Viernes<br>8:00 a 21:00 hrs<br><span style="color:rgba(255,255,255,.25);font-size:11px;margin-top:4px;display:block">Sábados 9:00 a 13:00 hrs</span></div>
 </div>
 <div class="contact-card chat">
  <div class="contact-card-icon green"><i class="fas fa-comments"></i></div>
  <h3>Chat en línea</h3>
  <p>Atención inmediata por chat con un asesor del SAT</p>
  <a href="https://chat.sat.gob.mx" target="_blank" class="btn-contact green"><i class="fas fa-comments"></i> Iniciar chat</a>
  <div class="contact-hours" style="margin-top:12px">Lunes a Viernes<br>8:00 a 21:00 hrs</div>
 </div>
 <div class="contact-card module">
  <div class="contact-card-icon yellow"><i class="fas fa-map-marker-alt"></i></div>
  <h3>Módulos de servicio</h3>
  <p>Encuentra el módulo SAT más cercano a tu domicilio</p>
  <a href="{{ route('contacto.index') }}" class="btn-contact outline"><i class="fas fa-search-location"></i> Buscar módulo</a>
  <div class="contact-hours" style="margin-top:12px">Atención presencial<br>con cita previa</div>
 </div>
</div>

{{-- ── Horarios + Canales digitales ── --}}
<div class="two-col">
 <div class="panel-card" style="margin-bottom:0">
  <div class="panel-header">
   <div class="panel-icon orange"><i class="fas fa-clock"></i></div>
   <div><div class="panel-title">Horarios de Atención</div><div class="panel-subtitle">Todos los canales disponibles</div></div>
  </div>
  <div class="info-row"><div class="info-row-icon" style="background:rgba(200,16,46,.1)"><i class="fas fa-phone" style="color:#f87171;font-size:12px"></i></div><div><div class="info-row-label">MarcaSAT</div><div class="info-row-val">Lunes a Viernes 8:00–21:00 · Sábados 9:00–13:00</div></div></div>
  <div class="info-row"><div class="info-row-icon" style="background:rgba(0,104,71,.15)"><i class="fas fa-comments" style="color:#4ade80;font-size:12px"></i></div><div><div class="info-row-label">Chat en línea</div><div class="info-row-val">Lunes a Viernes 8:00–21:00</div></div></div>
  <div class="info-row"><div class="info-row-icon" style="background:rgba(251,191,36,.08)"><i class="fas fa-building" style="color:#fbbf24;font-size:12px"></i></div><div><div class="info-row-label">Módulos presenciales</div><div class="info-row-val">Lunes a Viernes 9:00–16:00 (con cita)</div></div></div>
  <div class="info-row"><div class="info-row-icon" style="background:rgba(96,165,250,.1)"><i class="fas fa-globe" style="color:#60a5fa;font-size:12px"></i></div><div><div class="info-row-label">Portal en línea</div><div class="info-row-val">Disponible las 24 horas, los 365 días</div></div></div>
  <div class="info-row"><div class="info-row-icon" style="background:rgba(167,139,250,.1)"><i class="fas fa-envelope" style="color:#a78bfa;font-size:12px"></i></div><div><div class="info-row-label">Correo electrónico</div><div class="info-row-val">orientacion@sat.gob.mx</div></div></div>
 </div>
 <div class="panel-card" style="margin-bottom:0">
  <div class="panel-header">
   <div class="panel-icon blue"><i class="fas fa-mobile-alt"></i></div>
   <div><div class="panel-title">Canales Digitales Oficiales</div><div class="panel-subtitle">Plataformas del SAT</div></div>
  </div>
  <div class="info-row"><div class="info-row-icon" style="background:rgba(96,165,250,.1)"><i class="fas fa-globe" style="color:#60a5fa;font-size:12px"></i></div><div><div class="info-row-label">Portal oficial SAT</div><div class="info-row-val"><a href="https://sat.gob.mx" target="_blank" style="color:#4ade80;text-decoration:none">www.sat.gob.mx</a></div></div></div>
  <div class="info-row"><div class="info-row-icon" style="background:rgba(29,161,242,.1)"><i class="fab fa-twitter" style="color:#1da1f2;font-size:12px"></i></div><div><div class="info-row-label">Twitter / X</div><div class="info-row-val"><a href="https://twitter.com/SATMX" target="_blank" style="color:#4ade80;text-decoration:none">@SATMX</a></div></div></div>
  <div class="info-row"><div class="info-row-icon" style="background:rgba(24,119,242,.1)"><i class="fab fa-facebook" style="color:#1877f2;font-size:12px"></i></div><div><div class="info-row-label">Facebook</div><div class="info-row-val"><a href="https://facebook.com/SATMX" target="_blank" style="color:#4ade80;text-decoration:none">SAT México Oficial</a></div></div></div>
  <div class="info-row"><div class="info-row-icon" style="background:rgba(255,0,0,.08)"><i class="fab fa-youtube" style="color:#f87171;font-size:12px"></i></div><div><div class="info-row-label">YouTube</div><div class="info-row-val"><a href="https://youtube.com/@SATMX" target="_blank" style="color:#4ade80;text-decoration:none">Canal SAT México</a></div></div></div>
  <div class="info-row"><div class="info-row-icon" style="background:rgba(167,139,250,.1)"><i class="fas fa-mobile" style="color:#a78bfa;font-size:12px"></i></div><div><div class="info-row-label">App SAT Móvil</div><div class="info-row-val">Disponible en App Store y Google Play</div></div></div>
 </div>
</div>

{{-- ── Guías de uso ── --}}
<div class="panel-card">
 <div class="panel-header">
  <div class="panel-icon blue"><i class="fas fa-book-open"></i></div>
  <div><div class="panel-title">Guías de Uso del Portal</div><div class="panel-subtitle">Aprende a usar los servicios paso a paso</div></div>
 </div>
 <div class="guides-grid">
  <a href="{{ route('declaraciones.usuario') }}" class="guide-card">
   <div class="guide-card-icon green"><i class="fas fa-file-invoice"></i></div>
   <div class="guide-card-info"><h4>Cómo presentar tu declaración mensual</h4><p>Guía paso a paso para el pago provisional de ISR e IVA</p><span class="guide-tag popular"><i class="fas fa-fire" style="margin-right:3px"></i> Popular</span></div>
  </a>
  <a href="{{ route('declaraciones.usuario') }}" class="guide-card">
   <div class="guide-card-icon blue"><i class="fas fa-calendar-alt"></i></div>
   <div class="guide-card-info"><h4>Declaración Anual de personas físicas</h4><p>Deducciones, ingresos acumulables y cálculo de ISR anual</p><span class="guide-tag popular"><i class="fas fa-fire" style="margin-right:3px"></i> Popular</span></div>
  </a>
  <a href="{{ route('facturacion.index') }}" class="guide-card">
   <div class="guide-card-icon orange"><i class="fas fa-file-invoice-dollar"></i></div>
   <div class="guide-card-info"><h4>Emitir un CFDI 4.0</h4><p>Crea y timbra facturas electrónicas con los nuevos requisitos</p><span class="guide-tag new"><i class="fas fa-star" style="margin-right:3px"></i> Actualizado</span></div>
  </a>
  <a href="{{ route('personas.cif') }}" class="guide-card">
   <div class="guide-card-icon purple"><i class="fas fa-file-alt"></i></div>
   <div class="guide-card-info"><h4>Obtener Constancia de Situación Fiscal</h4><p>Descarga tu constancia actualizada en minutos</p></div>
  </a>
  <a href="{{ route('personas.e_firma') }}" class="guide-card">
   <div class="guide-card-icon yellow"><i class="fas fa-signature"></i></div>
   <div class="guide-card-info"><h4>Activar y renovar tu e.firma</h4><p>Firma electrónica avanzada para trámites fiscales</p></div>
  </a>
  <a href="{{ route('contacto.index') }}" class="guide-card">
   <div class="guide-card-icon red"><i class="fas fa-calendar-check"></i></div>
   <div class="guide-card-info"><h4>Agendar cita en módulo SAT</h4><p>Reserva tu espacio para atención presencial</p></div>
  </a>
 </div>
</div>

{{-- ── FAQ ── --}}
<div class="panel-card" id="faq">
 <div class="panel-header">
  <div class="panel-icon yellow"><i class="fas fa-question-circle"></i></div>
  <div><div class="panel-title">Preguntas Frecuentes</div><div class="panel-subtitle">Las dudas más comunes de los contribuyentes</div></div>
 </div>
 <div class="faq-list" id="faq-list">

  <div class="faq-item" data-q="¿cuándo debo presentar mi declaración mensual?">
   <div class="faq-q" onclick="toggleFaq(this)"><span class="faq-q-text">¿Cuándo debo presentar mi declaración mensual?</span><span class="faq-arrow"><i class="fas fa-chevron-down"></i></span></div>
   <div class="faq-a"><div class="faq-a-inner">La declaración mensual debe presentarse a más tardar el <strong>día 17 del mes siguiente</strong> al período que se declara. Si el día 17 cae en día inhábil, el plazo se extiende al siguiente día hábil.</div></div>
  </div>

  <div class="faq-item" data-q="¿qué es el régimen simplificado de confianza resico?">
   <div class="faq-q" onclick="toggleFaq(this)"><span class="faq-q-text">¿Qué es el Régimen Simplificado de Confianza (RESICO)?</span><span class="faq-arrow"><i class="fas fa-chevron-down"></i></span></div>
   <div class="faq-a"><div class="faq-a-inner">El RESICO es para personas físicas con ingresos anuales de hasta <strong>3.5 millones de pesos</strong>. Sus ventajas:<ul><li>Tasas de ISR entre 1% y 2.5% sobre ingresos cobrados</li><li>Sin contabilidad compleja</li><li>Declaración mensual simplificada</li><li>Sin presentar DIOT</li></ul></div></div>
  </div>

  <div class="faq-item" data-q="¿cómo obtengo mi constancia de situación fiscal?">
   <div class="faq-q" onclick="toggleFaq(this)"><span class="faq-q-text">¿Cómo obtengo mi Constancia de Situación Fiscal?</span><span class="faq-arrow"><i class="fas fa-chevron-down"></i></span></div>
   <div class="faq-a"><div class="faq-a-inner">Puedes obtenerla de tres formas:<ul><li><strong>En este portal:</strong> Ve a <a href="{{ route('personas.cif') }}">Constancia Fiscal</a>.</li><li><strong>SAT ID:</strong> Desde la app SAT ID en tu celular.</li><li><strong>Módulo SAT:</strong> Presencialmente con identificación oficial.</li></ul></div></div>
  </div>

  <div class="faq-item" data-q="¿qué es el cfdi 4.0 y qué cambió?">
   <div class="faq-q" onclick="toggleFaq(this)"><span class="faq-q-text">¿Qué es el CFDI 4.0 y qué cambió respecto al 3.3?</span><span class="faq-arrow"><i class="fas fa-chevron-down"></i></span></div>
   <div class="faq-a"><div class="faq-a-inner">Cambios principales del CFDI 4.0:<ul><li>Nombre y domicilio fiscal del receptor <strong>obligatorios</strong></li><li>Nuevo campo <strong>"Exportación"</strong> requerido</li><li>Nuevos campos de <strong>"Objetos de impuestos"</strong> en conceptos</li><li><strong>RegimenFiscalReceptor</strong> obligatorio</li></ul></div></div>
  </div>

  <div class="faq-item" data-q="¿cómo recupero mi contraseña del sat?">
   <div class="faq-q" onclick="toggleFaq(this)"><span class="faq-q-text">¿Cómo recupero mi contraseña o acceso al portal?</span><span class="faq-arrow"><i class="fas fa-chevron-down"></i></span></div>
   <div class="faq-a"><div class="faq-a-inner">Opciones para recuperar tu contraseña:<ul><li><strong>Con e.firma:</strong> Usa la opción "Generación de contraseña con e.firma" en el portal SAT.</li><li><strong>Con SAT ID:</strong> Descarga la app y usa tu información biométrica.</li><li><strong>Módulo SAT:</strong> Presencialmente con identificación (requiere cita).</li></ul>Llama al <strong>55 627 22 728</strong> para orientación adicional.</div></div>
  </div>

  <div class="faq-item" data-q="¿qué deducciones personales puedo aplicar en mi declaración anual?">
   <div class="faq-q" onclick="toggleFaq(this)"><span class="faq-q-text">¿Qué deducciones personales puedo aplicar en mi declaración anual?</span><span class="faq-arrow"><i class="fas fa-chevron-down"></i></span></div>
   <div class="faq-a"><div class="faq-a-inner">Deducciones permitidas:<ul><li>Honorarios médicos, dentales y gastos hospitalarios</li><li>Primas de seguros de gastos médicos</li><li>Gastos funerarios</li><li>Donativos a instituciones autorizadas</li><li>Intereses reales de crédito hipotecario</li><li>Aportaciones voluntarias a AFORE</li><li>Colegiaturas (preescolar a bachillerato)</li><li>Transporte escolar obligatorio</li></ul>Límite: <strong>15% del ingreso anual</strong> o <strong>5 UMAs anuales</strong>, lo menor. Todos con CFDI.</div></div>
  </div>

  <div class="faq-item" data-q="¿qué pasa si no presento mi declaración a tiempo?">
   <div class="faq-q" onclick="toggleFaq(this)"><span class="faq-q-text">¿Qué pasa si no presento mi declaración a tiempo?</span><span class="faq-arrow"><i class="fas fa-chevron-down"></i></span></div>
   <div class="faq-a"><div class="faq-a-inner">Presentar fuera de plazo genera:<ul><li><strong>Recargos:</strong> 1.47% mensual sobre el impuesto no pagado</li><li><strong>Actualización:</strong> El monto se actualiza con el INPC</li><li><strong>Multa:</strong> Entre $1,810 y $22,400 pesos</li><li>Posible requerimiento del SAT con multas adicionales</li></ul>Puedes presentar una declaración extemporánea en cualquier momento.</div></div>
  </div>

  <div class="faq-item" data-q="¿cómo cancelo una factura cfdi?">
   <div class="faq-q" onclick="toggleFaq(this)"><span class="faq-q-text">¿Cómo cancelo una factura CFDI?</span><span class="faq-arrow"><i class="fas fa-chevron-down"></i></span></div>
   <div class="faq-a"><div class="faq-a-inner">Motivos de cancelación disponibles:<ul><li><strong>01:</strong> Errores con relación (requiere UUID sustituto)</li><li><strong>02:</strong> Errores sin relación</li><li><strong>03:</strong> No se llevó a cabo la operación</li><li><strong>04:</strong> Operación nominativa en factura global</li></ul>El receptor tiene <strong>72 horas</strong> para aceptar o rechazar. Si no responde, se cancela automáticamente.</div></div>
  </div>

 </div>
</div>

<div style="text-align:center;padding:16px 0 8px;font-size:12px;color:rgba(255,255,255,.2)">
 Portal SAT — Servicio de Administración Tributaria · Gobierno de México<br>
 Para trámites oficiales visita <a href="https://sat.gob.mx" target="_blank" style="color:rgba(74,222,128,.5);text-decoration:none">www.sat.gob.mx</a>
</div>

@endsection

@push('scripts')
<script>
function toggleFaq(el){
 const item = el.closest('.faq-item');
 const isOpen = item.classList.contains('open');
 document.querySelectorAll('.faq-item.open').forEach(i => i.classList.remove('open'));
 if(!isOpen) item.classList.add('open');
}

function filterFaq(q){
 const term = q.toLowerCase().trim();
 document.querySelectorAll('.faq-item').forEach(item => {
  const text = (item.dataset.q || '') + ' ' + item.textContent.toLowerCase();
  item.style.display = !term || text.includes(term) ? '' : 'none';
 });
}
</script>
@endpush