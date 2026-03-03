<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SAT - Servicio de Administración Tributaria')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/sat-favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/sat.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>

<!-- Barra superior del gobierno -->
<div class="gob-bar">
    <div class="container-sat">
        <div class="gob-bar-inner">
            <div class="gob-logo-group">
                <img src="{{ asset('img/gob-mx.svg') }}" alt="gob.mx" class="gob-logo" onerror="this.style.display='none'">
                <span class="gob-text">gob.mx</span>
                <span class="gob-sep">|</span>
                <span class="gob-slogan">La <strong>versión</strong> de <strong>confianza</strong> de México</span>
            </div>
            <div class="gob-links">
                <a href="#" class="gob-link">Trámites</a>
                <a href="#" class="gob-link">Gobierno</a>
                <a href="#" class="gob-link">Mapa del sitio</a>
            </div>
        </div>
    </div>
</div>

<!-- Header SAT -->
<header class="sat-header">
    <div class="container-sat">
        <div class="sat-header-inner">
            <a href="{{ route('home') }}" class="sat-brand">
                <div class="sat-logo-container">
                    <div class="sat-logo-box">
                        <span class="sat-logo-text">SAT</span>
                    </div>
                    <div class="sat-brand-text">
                        <span class="sat-brand-main">Servicio de Administración Tributaria</span>
                        <span class="sat-brand-sub">Secretaría de Hacienda y Crédito Público</span>
                    </div>
                </div>
            </a>
            <div class="sat-header-actions">
                <div class="sat-search-box">
                    <input type="text" placeholder="Buscar en SAT..." class="sat-search-input" id="searchInput">
                    <button class="sat-search-btn"><i class="fas fa-search"></i></button>
                </div>
                <div class="sat-auth-buttons">
                    <a href="{{ route('login') }}" class="btn-sat-outline">
                        <i class="fas fa-sign-in-alt"></i> Iniciar sesión
                    </a>
                    <a href="{{ route('registro') }}" class="btn-sat-primary">
                        <i class="fas fa-user-plus"></i> Regístrate
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Navegación principal -->
<nav class="sat-nav">
    <div class="container-sat">
        <ul class="sat-nav-menu" id="navMenu">
            <li class="sat-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                <a href="{{ route('home') }}" class="sat-nav-link">Inicio</a>
            </li>
            <li class="sat-nav-item has-dropdown {{ request()->routeIs('personas.*') ? 'active' : '' }}">
                <a href="{{ route('personas.index') }}" class="sat-nav-link">
                    Personas <i class="fas fa-chevron-down sat-nav-arrow"></i>
                </a>
                <div class="sat-dropdown">
                    <div class="sat-dropdown-inner">
                        <div class="sat-dropdown-col">
                            <h4 class="sat-dropdown-title">RFC e Identificación</h4>
                            <a href="{{ route('personas.rfc') }}" class="sat-dropdown-link">Obtén tu RFC</a>
                            <a href="{{ route('personas.e_firma') }}" class="sat-dropdown-link">e.firma (firma electrónica)</a>
                            <a href="{{ route('personas.cif') }}" class="sat-dropdown-link">Constancia de Situación Fiscal</a>
                        </div>
                        <div class="sat-dropdown-col">
                            <h4 class="sat-dropdown-title">Declaraciones</h4>
                            <a href="{{ route('personas.declaracion_anual') }}" class="sat-dropdown-link">Declaración Anual</a>
                            <a href="{{ route('personas.declaracion_provisional') }}" class="sat-dropdown-link">Pagos Provisionales</a>
                            <a href="{{ route('personas.isr') }}" class="sat-dropdown-link">ISR Personas Físicas</a>
                        </div>
                        <div class="sat-dropdown-col">
                            <h4 class="sat-dropdown-title">Facturación</h4>
                            <a href="{{ route('personas.facturacion') }}" class="sat-dropdown-link">Genera tu factura</a>
                            <a href="{{ route('personas.verificar_cfdi') }}" class="sat-dropdown-link">Verifica tu CFDI</a>
                            <a href="{{ route('personas.buzontributario') }}" class="sat-dropdown-link">Buzón Tributario</a>
                        </div>
                    </div>
                </div>
            </li>
            <li class="sat-nav-item has-dropdown {{ request()->routeIs('empresas.*') ? 'active' : '' }}">
                <a href="{{ route('empresas.index') }}" class="sat-nav-link">
                    Empresas <i class="fas fa-chevron-down sat-nav-arrow"></i>
                </a>
                <div class="sat-dropdown">
                    <div class="sat-dropdown-inner">
                        <div class="sat-dropdown-col">
                            <h4 class="sat-dropdown-title">Inscripción y Avisos</h4>
                            <a href="{{ route('empresas.inscripcion') }}" class="sat-dropdown-link">Inscripción al RFC</a>
                            <a href="{{ route('empresas.actualizacion') }}" class="sat-dropdown-link">Actualización de Datos</a>
                            <a href="{{ route('empresas.cancelacion') }}" class="sat-dropdown-link">Cancelación al RFC</a>
                        </div>
                        <div class="sat-dropdown-col">
                            <h4 class="sat-dropdown-title">Impuestos</h4>
                            <a href="{{ route('empresas.iva') }}" class="sat-dropdown-link">IVA</a>
                            <a href="{{ route('empresas.isr') }}" class="sat-dropdown-link">ISR Personas Morales</a>
                            <a href="{{ route('empresas.ieps') }}" class="sat-dropdown-link">IEPS</a>
                        </div>
                        <div class="sat-dropdown-col">
                            <h4 class="sat-dropdown-title">Factura Electrónica</h4>
                            <a href="{{ route('empresas.cfdi') }}" class="sat-dropdown-link">CFDI 4.0</a>
                            <a href="{{ route('empresas.complementos') }}" class="sat-dropdown-link">Complementos CFDI</a>
                            <a href="{{ route('empresas.nomina') }}" class="sat-dropdown-link">Comprobante de Nómina</a>
                        </div>
                    </div>
                </div>
            </li>
            <li class="sat-nav-item has-dropdown {{ request()->routeIs('tramites.*') ? 'active' : '' }}">
                <a href="{{ route('tramites.index') }}" class="sat-nav-link">
                    Trámites y servicios <i class="fas fa-chevron-down sat-nav-arrow"></i>
                </a>
                <div class="sat-dropdown">
                    <div class="sat-dropdown-inner">
                        <div class="sat-dropdown-col">
                            <h4 class="sat-dropdown-title">En línea</h4>
                            <a href="{{ route('tramites.citas') }}" class="sat-dropdown-link">Citas en línea</a>
                            <a href="{{ route('tramites.consultas') }}" class="sat-dropdown-link">Consultas y solicitudes</a>
                            <a href="{{ route('tramites.opiniones') }}" class="sat-dropdown-link">Opinión de cumplimiento</a>
                        </div>
                        <div class="sat-dropdown-col">
                            <h4 class="sat-dropdown-title">Devoluciones y Compensaciones</h4>
                            <a href="{{ route('tramites.devoluciones') }}" class="sat-dropdown-link">Solicitud de Devolución</a>
                            <a href="{{ route('tramites.compensaciones') }}" class="sat-dropdown-link">Compensaciones</a>
                            <a href="{{ route('tramites.saldo_favor') }}" class="sat-dropdown-link">Saldo a Favor</a>
                        </div>
                    </div>
                </div>
            </li>
            <li class="sat-nav-item {{ request()->routeIs('declaraciones.*') ? 'active' : '' }}">
                <a href="{{ route('declaraciones.index') }}" class="sat-nav-link">Declaraciones</a>
            </li>
            <li class="sat-nav-item {{ request()->routeIs('facturacion.*') ? 'active' : '' }}">
                <a href="{{ route('facturacion.index') }}" class="sat-nav-link">Facturación</a>
            </li>
            <li class="sat-nav-item {{ request()->routeIs('contacto.*') ? 'active' : '' }}">
                <a href="{{ route('contacto.index') }}" class="sat-nav-link">Contacto</a>
            </li>
        </ul>
        <button class="sat-nav-toggle" id="navToggle">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</nav>

<!-- Contenido principal -->
<main class="sat-main">
    @if(session('success'))
        <div class="sat-alert sat-alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button class="sat-alert-close" onclick="this.parentElement.remove()">×</button>
        </div>
    @endif
    @if(session('error'))
        <div class="sat-alert sat-alert-error">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button class="sat-alert-close" onclick="this.parentElement.remove()">×</button>
        </div>
    @endif

    @yield('content')
</main>

<!-- Footer -->
<footer class="sat-footer">
    <div class="sat-footer-top">
        <div class="container-sat">
            <div class="sat-footer-grid">
                <div class="sat-footer-col">
                    <div class="sat-logo-box sat-footer-logo">
                        <span class="sat-logo-text">SAT</span>
                    </div>
                    <p class="sat-footer-desc">Servicio de Administración Tributaria</p>
                    <p class="sat-footer-desc">Secretaría de Hacienda y Crédito Público</p>
                    <div class="sat-social-links">
                        <a href="#" class="sat-social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="sat-social-link"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="sat-social-link"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="sat-social-link"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="sat-footer-col">
                    <h4 class="sat-footer-title">Personas</h4>
                    <a href="#" class="sat-footer-link">Obtén tu RFC</a>
                    <a href="#" class="sat-footer-link">Declaración Anual</a>
                    <a href="#" class="sat-footer-link">e.firma</a>
                    <a href="#" class="sat-footer-link">Facturación Electrónica</a>
                </div>
                <div class="sat-footer-col">
                    <h4 class="sat-footer-title">Empresas</h4>
                    <a href="#" class="sat-footer-link">Inscripción al RFC</a>
                    <a href="#" class="sat-footer-link">CFDI 4.0</a>
                    <a href="#" class="sat-footer-link">ISR Personas Morales</a>
                    <a href="#" class="sat-footer-link">Nómina</a>
                </div>
                <div class="sat-footer-col">
                    <h4 class="sat-footer-title">Contacto</h4>
                    <p class="sat-footer-contact"><i class="fas fa-phone"></i> 55 627 22 728</p>
                    <p class="sat-footer-contact"><i class="fas fa-clock"></i> Lunes a Viernes 8:00 - 21:00</p>
                    <p class="sat-footer-contact"><i class="fas fa-map-marker-alt"></i> Av. Hidalgo 77, CDMX</p>
                </div>
            </div>
        </div>
    </div>
    <div class="sat-footer-bottom">
        <div class="container-sat">
            <div class="sat-footer-bottom-inner">
                <span>© {{ date('Y') }} Servicio de Administración Tributaria</span>
                <div class="sat-footer-bottom-links">
                    <a href="#">Aviso de privacidad</a>
                    <a href="#">Términos de uso</a>
                    <a href="#">Accesibilidad</a>
                    <a href="#">Mapa del sitio</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="{{ asset('js/sat.js') }}"></script>
@stack('scripts')
</body>
</html>
