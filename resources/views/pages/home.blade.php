@extends('layouts.app')

@section('title', 'SAT - Servicio de Administración Tributaria')

@section('content')

<!-- Hero -->
<section class="sat-hero">
    <div class="container-sat">
        <div class="sat-hero-inner">
            <div class="sat-hero-content">
                <div class="sat-hero-badge">
                    <i class="fas fa-shield-alt"></i>
                    Portal Oficial del SAT
                </div>
                <h1>Tus trámites fiscales<br>más <span>fáciles que nunca</span></h1>
                <p>Cumple con tus obligaciones fiscales, genera facturas, obtén tu RFC y realiza todos tus trámites desde un solo lugar.</p>
                <div class="sat-hero-buttons">
                    <a href="{{ route('personas.rfc') }}" class="btn-hero-white">
                        <i class="fas fa-id-card"></i> Obtén tu RFC
                    </a>
                    <a href="{{ route('declaraciones.index') }}" class="btn-hero-outline">
                        <i class="fas fa-file-invoice"></i> Declaraciones
                    </a>
                </div>
            </div>
            <div class="sat-hero-stats">
                <div class="sat-hero-stat">
                    <span class="sat-hero-stat-num" data-count="{{ $totalContribuyentes }}" data-suffix="">{{ $totalContribuyentes }}</span>
                    <div class="sat-hero-stat-label">Contribuyentes</div>
                </div>
                <div class="sat-hero-stat">
                    <span class="sat-hero-stat-num" data-count="4.2" data-suffix="T">0</span>
                    <div class="sat-hero-stat-label">Facturas emitidas</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Servicios rápidos -->
<section class="sat-section sat-section-gray">
    <div class="container-sat">
        <div class="sat-section-header">
            <h2 class="sat-section-title">Servicios <span>más utilizados</span></h2>
            <p class="sat-section-subtitle">Accede rápidamente a los trámites más frecuentes</p>
        </div>
        <div class="sat-grid-4">
            <a href="{{ route('personas.rfc') }}" class="sat-card" style="text-decoration:none">
                <div class="sat-card-icon icon-red"><i class="fas fa-id-card"></i></div>
                <h3>RFC e.firma</h3>
                <p>Obtén o actualiza tu Registro Federal de Contribuyentes y firma electrónica.</p>
                <i class="fas fa-arrow-right sat-card-arrow"></i>
            </a>
            <a href="{{ route('declaraciones.index') }}" class="sat-card">
                <div class="sat-card-icon icon-green"><i class="fas fa-file-invoice-dollar"></i></div>
                <h3>Declaraciones</h3>
                <p>Presenta tu declaración anual o pagos provisionales de forma sencilla.</p>
                <i class="fas fa-arrow-right sat-card-arrow"></i>
            </a>
            <a href="{{ route('facturacion.index') }}" class="sat-card">
                <div class="sat-card-icon icon-gold"><i class="fas fa-receipt"></i></div>
                <h3>Facturación CFDI</h3>
                <p>Emite y verifica comprobantes fiscales digitales por internet (CFDI 4.0).</p>
                <i class="fas fa-arrow-right sat-card-arrow"></i>
            </a>
            <a href="{{ route('tramites.citas') }}" class="sat-card">
                <div class="sat-card-icon icon-blue"><i class="fas fa-calendar-check"></i></div>
                <h3>Citas en línea</h3>
                <p>Agenda tu cita en el SAT para trámites que requieren atención presencial.</p>
                <i class="fas fa-arrow-right sat-card-arrow"></i>
            </a>
        </div>
    </div>
</section>

<!-- Para personas y empresas -->
<section class="sat-section">
    <div class="container-sat">
        <div class="sat-grid-2" style="gap:32px; align-items:start">
            <!-- Personas -->
            <div>
                <div class="sat-section-header">
                    <h2 class="sat-section-title"><i class="fas fa-user" style="color:var(--sat-red);margin-right:10px;font-size:22px"></i>Personas Físicas</h2>
                    <p class="sat-section-subtitle">Trámites y servicios para contribuyentes individuales</p>
                </div>
                <div style="display:flex;flex-direction:column;gap:12px">
                    <a href="{{ route('personas.rfc') }}" class="sat-mini-card">
                        <div class="sat-mini-card-icon icon-red"><i class="fas fa-id-card"></i></div>
                        <div class="sat-mini-card-text">
                            <h4>Obtén tu RFC</h4>
                            <p>Inscripción o actualización al Registro Federal de Contribuyentes</p>
                        </div>
                        <i class="fas fa-chevron-right" style="color:var(--sat-gray);font-size:12px"></i>
                    </a>
                    <a href="{{ route('personas.declaracion_anual') }}" class="sat-mini-card">
                        <div class="sat-mini-card-icon icon-green"><i class="fas fa-file-contract"></i></div>
                        <div class="sat-mini-card-text">
                            <h4>Declaración Anual</h4>
                            <p>Presenta tu declaración del ejercicio fiscal correspondiente</p>
                        </div>
                        <i class="fas fa-chevron-right" style="color:var(--sat-gray);font-size:12px"></i>
                    </a>
                    <a href="{{ route('personas.e_firma') }}" class="sat-mini-card">
                        <div class="sat-mini-card-icon icon-gold"><i class="fas fa-signature"></i></div>
                        <div class="sat-mini-card-text">
                            <h4>e.firma (FIEL)</h4>
                            <p>Obtén o renueva tu firma electrónica avanzada</p>
                        </div>
                        <i class="fas fa-chevron-right" style="color:var(--sat-gray);font-size:12px"></i>
                    </a>
                    <a href="{{ route('personas.cif') }}" class="sat-mini-card">
                        <div class="sat-mini-card-icon icon-blue"><i class="fas fa-file-alt"></i></div>
                        <div class="sat-mini-card-text">
                            <h4>Constancia de Situación Fiscal</h4>
                            <p>Genera tu constancia con código QR al instante</p>
                        </div>
                        <i class="fas fa-chevron-right" style="color:var(--sat-gray);font-size:12px"></i>
                    </a>
                </div>
                <div style="margin-top:20px">
                    <a href="{{ route('personas.index') }}" class="btn-sat-outline">Ver todos los trámites <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <!-- Empresas -->
            <div>
                <div class="sat-section-header">
                    <h2 class="sat-section-title"><i class="fas fa-building" style="color:var(--sat-green);margin-right:10px;font-size:22px"></i>Personas Morales</h2>
                    <p class="sat-section-subtitle">Trámites y servicios para empresas y organizaciones</p>
                </div>
                <div style="display:flex;flex-direction:column;gap:12px">
                    <a href="{{ route('empresas.inscripcion') }}" class="sat-mini-card">
                        <div class="sat-mini-card-icon icon-green"><i class="fas fa-building"></i></div>
                        <div class="sat-mini-card-text">
                            <h4>Inscripción de Persona Moral</h4>
                            <p>Registra tu empresa ante el SAT y obtén tu RFC</p>
                        </div>
                        <i class="fas fa-chevron-right" style="color:var(--sat-gray);font-size:12px"></i>
                    </a>
                    <a href="{{ route('empresas.cfdi') }}" class="sat-mini-card">
                        <div class="sat-mini-card-icon icon-red"><i class="fas fa-file-invoice"></i></div>
                        <div class="sat-mini-card-text">
                            <h4>CFDI 4.0</h4>
                            <p>Emite facturas electrónicas con los nuevos requisitos</p>
                        </div>
                        <i class="fas fa-chevron-right" style="color:var(--sat-gray);font-size:12px"></i>
                    </a>
                    <a href="{{ route('empresas.nomina') }}" class="sat-mini-card">
                        <div class="sat-mini-card-icon icon-gold"><i class="fas fa-users"></i></div>
                        <div class="sat-mini-card-text">
                            <h4>Comprobante de Nómina</h4>
                            <p>Genera recibos de nómina digitales para tus empleados</p>
                        </div>
                        <i class="fas fa-chevron-right" style="color:var(--sat-gray);font-size:12px"></i>
                    </a>
                    <a href="{{ route('tramites.opiniones') }}" class="sat-mini-card">
                        <div class="sat-mini-card-icon icon-blue"><i class="fas fa-check-double"></i></div>
                        <div class="sat-mini-card-text">
                            <h4>Opinión de Cumplimiento</h4>
                            <p>Verifica tu situación fiscal para contratos gubernamentales</p>
                        </div>
                        <i class="fas fa-chevron-right" style="color:var(--sat-gray);font-size:12px"></i>
                    </a>
                </div>
                <div style="margin-top:20px">
                    <a href="{{ route('empresas.index') }}" class="btn-sat-outline">Ver todos los trámites <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Noticias -->
<section class="sat-section sat-section-gray">
    <div class="container-sat">
        <div class="sat-section-header">
            <h2 class="sat-section-title">Noticias y <span>avisos</span></h2>
            <p class="sat-section-subtitle">Mantente informado sobre cambios fiscales y nuevas disposiciones</p>
        </div>
        <div class="sat-grid-3">
            @foreach($noticias as $noticia)
            <div class="sat-news-card">
                <div class="sat-news-img">
                    <i class="fas fa-newspaper"></i>
                </div>
                <div class="sat-news-body">
                    <p class="sat-news-date">
                        <i class="fas fa-calendar-alt"></i>
                        {{ $noticia->fecha->format('d/m/Y') }}
                    </p>
                    <h3 class="sat-news-title">{{ $noticia->titulo }}</h3>
                    <p class="sat-news-excerpt">{{ Str::limit($noticia->resumen, 110) }}</p>
                    <a href="{{ route('noticias.show', $noticia->id) }}" class="sat-news-link">
                        Leer más <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Contacto rápido -->
<section class="sat-section" style="background:var(--sat-green);color:white;padding:40px 0">
    <div class="container-sat">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:24px">
            <div>
                <h2 style="font-size:24px;font-weight:700;margin-bottom:8px">¿Necesitas ayuda?</h2>
                <p style="opacity:0.85">Nuestros especialistas están listos para atenderte</p>
            </div>
            <div style="display:flex;gap:16px;flex-wrap:wrap">
                <a href="tel:5562722728" class="btn-hero-white">
                    <i class="fas fa-phone"></i> 55 627 22 728
                </a>
                <a href="{{ route('contacto.index') }}" class="btn-hero-outline">
                    <i class="fas fa-envelope"></i> Enviar mensaje
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
