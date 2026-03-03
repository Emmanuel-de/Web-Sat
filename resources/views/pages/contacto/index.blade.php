@extends('layouts.app')

@section('title', 'Contacto y Citas - SAT')

@section('content')

<div class="sat-page-header">
    <div class="container-sat">
        <div class="sat-breadcrumb">
            <a href="{{ route('home') }}">Inicio</a>
            <span class="sat-breadcrumb-sep"><i class="fas fa-chevron-right" style="font-size:10px"></i></span>
            <span>Contacto</span>
        </div>
        <h1 class="sat-page-title"><i class="fas fa-headset" style="margin-right:12px"></i>Contacto y Atención al Contribuyente</h1>
        <p class="sat-page-subtitle">Agenda una cita, envía una consulta o contáctanos por teléfono</p>
    </div>
</div>

<section class="sat-section">
    <div class="container-sat">

        <!-- Opciones de contacto rápido -->
        <div class="sat-grid-3" style="margin-bottom:40px">
            <div class="sat-card" style="text-align:center;cursor:default">
                <div class="sat-card-icon icon-red" style="margin:0 auto 16px"><i class="fas fa-phone"></i></div>
                <h3>SAT MarcaSAT</h3>
                <p style="font-size:22px;font-weight:700;color:var(--sat-red);margin:10px 0">55 627 22 728</p>
                <p>Lunes a Viernes<br>8:00 a 21:00 hrs</p>
            </div>
            <div class="sat-card" style="text-align:center;cursor:default">
                <div class="sat-card-icon icon-green" style="margin:0 auto 16px"><i class="fas fa-comments"></i></div>
                <h3>Chat en línea</h3>
                <p style="margin-bottom:16px">Atención inmediata por chat con un asesor del SAT</p>
                <a href="#" class="btn-sat-green" style="display:inline-flex">
                    <i class="fas fa-comment"></i> Iniciar chat
                </a>
            </div>
            <div class="sat-card" style="text-align:center;cursor:default">
                <div class="sat-card-icon icon-gold" style="margin:0 auto 16px"><i class="fas fa-map-marker-alt"></i></div>
                <h3>Módulos de servicio</h3>
                <p style="margin-bottom:16px">Encuentra el módulo SAT más cercano a tu domicilio</p>
                <a href="#modulos" class="btn-sat-outline" style="display:inline-flex">
                    <i class="fas fa-search"></i> Buscar módulo
                </a>
            </div>
        </div>

        <div class="sat-tabs-container">
            <div class="sat-tabs">
                <div class="sat-tab active" data-tab="cita">
                    <i class="fas fa-calendar-plus"></i> Agendar cita
                </div>
                <div class="sat-tab" data-tab="mensaje">
                    <i class="fas fa-envelope"></i> Enviar mensaje
                </div>
                <div class="sat-tab" data-tab="quejas">
                    <i class="fas fa-flag"></i> Quejas y sugerencias
                </div>
                <div class="sat-tab" data-tab="modulos" id="modulos">
                    <i class="fas fa-map-marker-alt"></i> Módulos SAT
                </div>
            </div>

            <!-- Agendar Cita -->
            <div class="sat-tab-content active" data-tab="cita">
                <div class="sat-form-section">
                    <div class="sat-form-header">
                        <div class="sat-form-header-icon"><i class="fas fa-calendar-check"></i></div>
                        <div class="sat-form-header-text">
                            <h2>Agenda tu cita en línea</h2>
                            <p>Programa tu visita a cualquier módulo SAT en México</p>
                        </div>
                    </div>
                    <div class="sat-form-body">
                        <form action="{{ route('tramites.citas.store') }}" method="POST" class="sat-form-ajax">
                            @csrf
                            <div class="sat-form-row cols-2">
                                <div class="sat-form-group">
                                    <label>RFC <span class="required">*</span></label>
                                    <input type="text" name="rfc" class="sat-input" required data-validate="rfc" placeholder="Tu RFC">
                                </div>
                                <div class="sat-form-group">
                                    <label>CURP <span class="required">*</span></label>
                                    <input type="text" name="curp" class="sat-input" required data-validate="curp" placeholder="Tu CURP">
                                </div>
                            </div>
                            <div class="sat-form-row cols-2">
                                <div class="sat-form-group">
                                    <label>Nombre completo <span class="required">*</span></label>
                                    <input type="text" name="nombre" class="sat-input" required placeholder="Como aparece en tu identificación">
                                </div>
                                <div class="sat-form-group">
                                    <label>Correo electrónico <span class="required">*</span></label>
                                    <input type="email" name="email" class="sat-input" required placeholder="correo@ejemplo.com">
                                </div>
                            </div>
                            <div class="sat-form-row cols-3">
                                <div class="sat-form-group">
                                    <label>Estado <span class="required">*</span></label>
                                    <select name="estado" class="sat-select" required id="estadoCita">
                                        <option value="">Seleccionar estado...</option>
                                        @foreach(['Aguascalientes','Baja California','Baja California Sur','Campeche','Chiapas','Chihuahua','Ciudad de México','Coahuila','Colima','Durango','Estado de México','Guanajuato','Guerrero','Hidalgo','Jalisco','Michoacán','Morelos','Nayarit','Nuevo León','Oaxaca','Puebla','Querétaro','Quintana Roo','San Luis Potosí','Sinaloa','Sonora','Tabasco','Tamaulipas','Tlaxcala','Veracruz','Yucatán','Zacatecas'] as $est)
                                        <option value="{{ $est }}">{{ $est }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="sat-form-group">
                                    <label>Módulo SAT <span class="required">*</span></label>
                                    <select name="modulo" class="sat-select" required id="moduloCita">
                                        <option value="">Primero selecciona tu estado</option>
                                    </select>
                                </div>
                                <div class="sat-form-group">
                                    <label>Trámite a realizar <span class="required">*</span></label>
                                    <select name="tramite" class="sat-select" required>
                                        <option value="">Seleccionar trámite...</option>
                                        <option value="RFC">RFC e inscripción</option>
                                        <option value="EFIRMA">e.firma (firma electrónica)</option>
                                        <option value="CIF">Constancia de Situación Fiscal</option>
                                        <option value="DEVOLUCION">Devoluciones</option>
                                        <option value="DECLARACIONES">Orientación declaraciones</option>
                                        <option value="ACLARACIONES">Aclaraciones</option>
                                        <option value="OTRO">Otro trámite</option>
                                    </select>
                                </div>
                            </div>
                            <div class="sat-form-row cols-2">
                                <div class="sat-form-group">
                                    <label>Fecha preferida <span class="required">*</span></label>
                                    <input type="date" name="fecha" class="sat-input" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" max="{{ date('Y-m-d', strtotime('+60 days')) }}">
                                    <span class="sat-input-hint">Lunes a viernes (días hábiles)</span>
                                </div>
                                <div class="sat-form-group">
                                    <label>Horario preferido <span class="required">*</span></label>
                                    <select name="horario" class="sat-select" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="08:00">08:00 - 09:00</option>
                                        <option value="09:00">09:00 - 10:00</option>
                                        <option value="10:00">10:00 - 11:00</option>
                                        <option value="11:00">11:00 - 12:00</option>
                                        <option value="12:00">12:00 - 13:00</option>
                                        <option value="13:00">13:00 - 14:00</option>
                                        <option value="15:00">15:00 - 16:00</option>
                                        <option value="16:00">16:00 - 17:00</option>
                                        <option value="17:00">17:00 - 18:00</option>
                                    </select>
                                </div>
                            </div>
                            <div class="sat-form-group">
                                <label>Observaciones adicionales</label>
                                <textarea name="observaciones" class="sat-textarea" placeholder="Indica algún requerimiento especial o información adicional para tu cita..."></textarea>
                            </div>
                            <div style="margin-top:20px">
                                <button type="submit" class="btn-sat-green">
                                    <i class="fas fa-calendar-check"></i> Confirmar cita
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Enviar Mensaje -->
            <div class="sat-tab-content" data-tab="mensaje">
                <div class="sat-form-section">
                    <div class="sat-form-header">
                        <div class="sat-form-header-icon"><i class="fas fa-envelope"></i></div>
                        <div class="sat-form-header-text">
                            <h2>Enviar Consulta</h2>
                            <p>Nuestro equipo responderá en un plazo máximo de 5 días hábiles</p>
                        </div>
                    </div>
                    <div class="sat-form-body">
                        <form action="{{ route('contacto.mensaje.store') }}" method="POST" class="sat-form-ajax">
                            @csrf
                            <div class="sat-form-row cols-2">
                                <div class="sat-form-group">
                                    <label>Nombre completo <span class="required">*</span></label>
                                    <input type="text" name="nombre" class="sat-input" required placeholder="Tu nombre">
                                </div>
                                <div class="sat-form-group">
                                    <label>RFC</label>
                                    <input type="text" name="rfc" class="sat-input" placeholder="Opcional" data-validate="rfc">
                                </div>
                            </div>
                            <div class="sat-form-row cols-2">
                                <div class="sat-form-group">
                                    <label>Correo electrónico <span class="required">*</span></label>
                                    <input type="email" name="email" class="sat-input" required placeholder="correo@ejemplo.com">
                                </div>
                                <div class="sat-form-group">
                                    <label>Teléfono</label>
                                    <input type="tel" name="telefono" class="sat-input" placeholder="10 dígitos" maxlength="10">
                                </div>
                            </div>
                            <div class="sat-form-group" style="margin-bottom:20px">
                                <label>Tema de la consulta <span class="required">*</span></label>
                                <select name="tema" class="sat-select" required>
                                    <option value="">Seleccionar tema...</option>
                                    <option value="RFC">RFC e inscripción</option>
                                    <option value="DECLARACIONES">Declaraciones</option>
                                    <option value="FACTURACION">Facturación electrónica</option>
                                    <option value="DEVOLUCIONES">Devoluciones y compensaciones</option>
                                    <option value="EFIRMA">e.firma</option>
                                    <option value="OTROS">Otros</option>
                                </select>
                            </div>
                            <div class="sat-form-group" style="margin-bottom:20px">
                                <label>Mensaje <span class="required">*</span></label>
                                <textarea name="mensaje" class="sat-textarea" required placeholder="Describe tu consulta con el mayor detalle posible..." style="min-height:140px"></textarea>
                            </div>
                            <div class="sat-checkbox-group" style="margin-bottom:20px">
                                <label class="sat-checkbox">
                                    <input type="checkbox" name="acepta_privacidad" required>
                                    <span class="sat-checkbox-label">Acepto el <a href="#" style="color:var(--sat-green)">Aviso de Privacidad</a> del SAT para el tratamiento de mis datos personales. <span class="required">*</span></span>
                                </label>
                            </div>
                            <button type="submit" class="btn-sat-green">
                                <i class="fas fa-paper-plane"></i> Enviar consulta
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Quejas -->
            <div class="sat-tab-content" data-tab="quejas">
                <div class="sat-form-section">
                    <div class="sat-form-header" style="background:var(--sat-red)">
                        <div class="sat-form-header-icon"><i class="fas fa-flag"></i></div>
                        <div class="sat-form-header-text">
                            <h2>Quejas y Sugerencias</h2>
                            <p>Tu opinión es importante para mejorar nuestros servicios</p>
                        </div>
                    </div>
                    <div class="sat-form-body">
                        <form action="{{ route('contacto.queja.store') }}" method="POST" class="sat-form-ajax">
                            @csrf
                            <div class="sat-form-row cols-2">
                                <div class="sat-form-group">
                                    <label>Tipo <span class="required">*</span></label>
                                    <select name="tipo" class="sat-select" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="QUEJA">Queja</option>
                                        <option value="SUGERENCIA">Sugerencia</option>
                                        <option value="RECONOCIMIENTO">Reconocimiento</option>
                                        <option value="DENUNCIA">Denuncia de corrupción</option>
                                    </select>
                                </div>
                                <div class="sat-form-group">
                                    <label>Área involucrada</label>
                                    <select name="area" class="sat-select">
                                        <option value="">Seleccionar...</option>
                                        <option value="MODULO">Módulo de atención</option>
                                        <option value="PORTAL">Portal en línea</option>
                                        <option value="TELEFONO">Atención telefónica</option>
                                        <option value="OTRO">Otro</option>
                                    </select>
                                </div>
                            </div>
                            <div class="sat-form-group" style="margin-bottom:20px">
                                <label>Descripción <span class="required">*</span></label>
                                <textarea name="descripcion" class="sat-textarea" required placeholder="Describe detalladamente tu queja, sugerencia o denuncia..." style="min-height:140px"></textarea>
                            </div>
                            <div class="sat-form-row cols-2">
                                <div class="sat-form-group">
                                    <label>Nombre (opcional)</label>
                                    <input type="text" name="nombre" class="sat-input" placeholder="Puedes enviar de forma anónima">
                                </div>
                                <div class="sat-form-group">
                                    <label>Correo (para seguimiento)</label>
                                    <input type="email" name="email" class="sat-input" placeholder="correo@ejemplo.com">
                                </div>
                            </div>
                            <button type="submit" class="btn-sat-primary">
                                <i class="fas fa-flag"></i> Enviar reporte
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Módulos -->
            <div class="sat-tab-content" data-tab="modulos">
                <div class="sat-form-section">
                    <div class="sat-form-header">
                        <div class="sat-form-header-icon"><i class="fas fa-map-marked-alt"></i></div>
                        <div class="sat-form-header-text">
                            <h2>Módulos de Servicio SAT</h2>
                            <p>Encuentra el módulo más cercano a tu domicilio</p>
                        </div>
                    </div>
                    <div class="sat-form-body">
                        <div class="sat-form-row cols-2" style="margin-bottom:24px">
                            <div class="sat-form-group">
                                <label>Buscar por estado</label>
                                <select class="sat-select" id="filtroEstadoModulo">
                                    <option value="">Todos los estados</option>
                                    @foreach(['Aguascalientes','Baja California','Chihuahua','Ciudad de México','Jalisco','Nuevo León','Puebla','Querétaro','San Luis Potosí','Tamaulipas','Veracruz','Yucatán'] as $est)
                                    <option value="{{ $est }}">{{ $est }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sat-form-group">
                                <label>Buscar por nombre</label>
                                <input type="text" class="sat-input" placeholder="Nombre del módulo..." id="filtroNombreModulo">
                            </div>
                        </div>

                        <table class="sat-table" id="tablaModulos">
                            <thead>
                                <tr>
                                    <th>Módulo</th>
                                    <th>Estado</th>
                                    <th>Dirección</th>
                                    <th>Horario</th>
                                    <th>Teléfono</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($modulos ?? [] as $modulo)
                                <tr>
                                    <td><strong>{{ $modulo->nombre }}</strong></td>
                                    <td>{{ $modulo->estado }}</td>
                                    <td>{{ $modulo->direccion }}</td>
                                    <td>{{ $modulo->horario }}</td>
                                    <td>{{ $modulo->telefono }}</td>
                                </tr>
                                @endforeach
                                <!-- Datos de ejemplo si no hay datos -->
                                @if(empty($modulos) || count($modulos) === 0)
                                <tr>
                                    <td><strong>SAT Hidalgo</strong></td>
                                    <td>Ciudad de México</td>
                                    <td>Av. Hidalgo 77, Col. Guerrero, CDMX</td>
                                    <td>L-V 8:00-21:00</td>
                                    <td>55 5628 1354</td>
                                </tr>
                                <tr>
                                    <td><strong>SAT San Pedro</strong></td>
                                    <td>Nuevo León</td>
                                    <td>Calzada del Valle 400, San Pedro Garza García</td>
                                    <td>L-V 9:00-18:00</td>
                                    <td>81 8153 1600</td>
                                </tr>
                                <tr>
                                    <td><strong>SAT Guadalajara</strong></td>
                                    <td>Jalisco</td>
                                    <td>Av. Vallarta 4150, Col. Camino Real, Guadalajara</td>
                                    <td>L-V 8:30-20:00</td>
                                    <td>33 3678 2300</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection