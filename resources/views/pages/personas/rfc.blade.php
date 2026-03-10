@extends('layouts.app')

@section('title', 'Obtén tu RFC - SAT')

@php $tab = $tabActiva ?? 'inscripcion'; @endphp

@section('content')

<div class="sat-page-header">
    <div class="container-sat">
        <div class="sat-breadcrumb">
            <a href="{{ route('home') }}">Inicio</a>
            <span class="sat-breadcrumb-sep"><i class="fas fa-chevron-right" style="font-size:10px"></i></span>
            <a href="{{ route('personas.index') }}">Personas</a>
            <span class="sat-breadcrumb-sep"><i class="fas fa-chevron-right" style="font-size:10px"></i></span>
            <span>Obtén tu RFC</span>
        </div>
        <h1 class="sat-page-title"><i class="fas fa-id-card" style="margin-right:12px"></i>Registro Federal de Contribuyentes</h1>
        <p class="sat-page-subtitle">Inscripción, consulta y actualización de tu RFC</p>
    </div>
</div>

<section class="sat-section">
    <div class="container-sat">

        {{-- Panel de éxito (oculto hasta submit AJAX) --}}
        <div id="rfcExitoPanel" style="display:none">
            <div style="text-align:center;padding:48px 24px 32px">
                <div style="width:80px;height:80px;background:var(--sat-green);border-radius:50%;
                            display:inline-flex;align-items:center;justify-content:center;
                            margin-bottom:20px;box-shadow:0 8px 32px rgba(26,122,66,.35)">
                    <i class="fas fa-check" style="font-size:36px;color:#fff"></i>
                </div>
                <h2 style="font-size:28px;font-weight:800;color:var(--sat-dark);margin-bottom:8px">
                    ¡Felicidades, tu RFC ha sido registrado!
                </h2>
                <p style="color:var(--sat-text-muted);font-size:16px;max-width:520px;margin:0 auto 32px">
                    Tu solicitud fue procesada exitosamente. Guarda tu RFC en un lugar seguro.
                </p>
                <div style="display:inline-block;background:var(--sat-green);color:#fff;
                            border-radius:12px;padding:20px 48px;margin-bottom:36px;
                            box-shadow:0 6px 24px rgba(26,122,66,.3)">
                    <p style="font-size:13px;letter-spacing:2px;text-transform:uppercase;opacity:.85;margin-bottom:6px">Tu RFC</p>
                    <p id="rfcGenerado" style="font-size:32px;font-weight:900;letter-spacing:4px;font-family:monospace;margin:0">—</p>
                </div>
            </div>
            <div style="background:var(--sat-gray-light);border-radius:12px;padding:28px;
                        border:1px solid var(--sat-gray-border);margin-bottom:28px">
                <h3 style="font-size:15px;font-weight:700;color:var(--sat-dark);margin-bottom:20px">
                    <i class="fas fa-clipboard-list" style="margin-right:8px;color:var(--sat-green)"></i>
                    Resumen de tu registro
                </h3>
                <div id="exitoResumen" style="display:grid;gap:20px"></div>
            </div>
            <div style="display:flex;gap:14px;flex-wrap:wrap;justify-content:center;padding-bottom:40px">
                <button id="btnDescargarRfc" class="btn-sat-green" style="font-size:15px;padding:14px 32px">
                    <i class="fas fa-download"></i> Descargar constancia PDF
                </button>
                <a href="{{ route('home') }}" class="btn-sat-outline" style="font-size:15px;padding:14px 32px">
                    <i class="fas fa-home"></i> Volver al inicio
                </a>
            </div>
        </div>

        {{-- Formulario principal --}}
        <div id="rfcFormPanel">
            <div class="sat-tabs-container">

                {{-- ── TABS (controladas por $tab desde el servidor) ── --}}
                <div class="sat-tabs">
                    <div class="sat-tab {{ $tab === 'inscripcion' ? 'active' : '' }}" data-tab="inscripcion">
                        <i class="fas fa-user-plus"></i> Inscripción
                    </div>
                    <div class="sat-tab {{ $tab === 'consulta' ? 'active' : '' }}" data-tab="consulta">
                        <i class="fas fa-search"></i> Consulta RFC
                    </div>
                    <div class="sat-tab {{ $tab === 'actualizacion' ? 'active' : '' }}" data-tab="actualizacion">
                        <i class="fas fa-edit"></i> Actualización
                    </div>
                    <div class="sat-tab {{ $tab === 'reimpresion' ? 'active' : '' }}" data-tab="reimpresion">
                        <i class="fas fa-print"></i> Reimpresión
                    </div>
                </div>

                {{-- ── TAB: Inscripción ── --}}
                <div class="sat-tab-content {{ $tab === 'inscripcion' ? 'active' : '' }}" data-tab="inscripcion">
                    <div class="sat-info-box">
                        <p><i class="fas fa-info-circle" style="margin-right:8px"></i>
                        Para inscribirte al RFC necesitas tu CURP, identificación oficial vigente y comprobante de domicilio no mayor a 3 meses.</p>
                    </div>

                    <div class="sat-steps">
                        <div class="sat-step active">
                            <div class="sat-step-num">1</div>
                            <div class="sat-step-label">Datos personales</div>
                        </div>
                        <div class="sat-step-line"></div>
                        <div class="sat-step">
                            <div class="sat-step-num">2</div>
                            <div class="sat-step-label">Domicilio fiscal</div>
                        </div>
                        <div class="sat-step-line"></div>
                        <div class="sat-step">
                            <div class="sat-step-num">3</div>
                            <div class="sat-step-label">Actividad económica</div>
                        </div>
                        <div class="sat-step-line"></div>
                        <div class="sat-step">
                            <div class="sat-step-num">4</div>
                            <div class="sat-step-label">Confirmación</div>
                        </div>
                    </div>

                    <form action="{{ route('personas.rfc.store') }}" method="POST" id="rfcForm">
                        @csrf

                        <!-- Step 1 -->
                        <div class="sat-step-content active" data-step="1">
                            <div class="sat-form-section">
                                <div class="sat-form-header">
                                    <div class="sat-form-header-icon"><i class="fas fa-user"></i></div>
                                    <div class="sat-form-header-text">
                                        <h2>Datos Personales</h2>
                                        <p>Ingresa tu información personal tal como aparece en tu CURP</p>
                                    </div>
                                </div>
                                <div class="sat-form-body">
                                    <div class="sat-form-row cols-3">
                                        <div class="sat-form-group">
                                            <label>Primer apellido <span class="required">*</span></label>
                                            <input type="text" name="primer_apellido" class="sat-input" required placeholder="Ej: García" maxlength="50">
                                            <span class="sat-input-error">Campo requerido</span>
                                        </div>
                                        <div class="sat-form-group">
                                            <label>Segundo apellido</label>
                                            <input type="text" name="segundo_apellido" class="sat-input" placeholder="Ej: López" maxlength="50">
                                        </div>
                                        <div class="sat-form-group">
                                            <label>Nombre(s) <span class="required">*</span></label>
                                            <input type="text" name="nombres" class="sat-input" required placeholder="Ej: Juan Carlos" maxlength="80">
                                            <span class="sat-input-error">Campo requerido</span>
                                        </div>
                                    </div>
                                    <div class="sat-form-row cols-3">
                                        <div class="sat-form-group">
                                            <label>Fecha de nacimiento <span class="required">*</span></label>
                                            <input type="date" name="fecha_nacimiento" class="sat-input" required max="{{ date('Y-m-d') }}">
                                            <span class="sat-input-error">Campo requerido</span>
                                        </div>
                                        <div class="sat-form-group">
                                            <label>Sexo <span class="required">*</span></label>
                                            <select name="sexo" class="sat-select" required>
                                                <option value="">Seleccionar...</option>
                                                <option value="Hombre">Hombre</option>
                                                <option value="Mujer">Mujer</option>
                                            </select>
                                            <span class="sat-input-error">Seleccione una opción</span>
                                        </div>
                                        <div class="sat-form-group">
                                            <label>Estado de nacimiento <span class="required">*</span></label>
                                            <select name="estado_nacimiento" class="sat-select" required>
                                                <option value="">Seleccionar...</option>
                                                @foreach(['Aguascalientes','Baja California','Baja California Sur','Campeche','Chiapas','Chihuahua','Ciudad de México','Coahuila','Colima','Durango','Estado de México','Guanajuato','Guerrero','Hidalgo','Jalisco','Michoacán','Morelos','Nayarit','Nuevo León','Oaxaca','Puebla','Querétaro','Quintana Roo','San Luis Potosí','Sinaloa','Sonora','Tabasco','Tamaulipas','Tlaxcala','Veracruz','Yucatán','Zacatecas','Extranjero'] as $estado)
                                                <option value="{{ $estado }}">{{ $estado }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="sat-form-row cols-2">
                                        <div class="sat-form-group">
                                            <label>CURP <span class="required">*</span></label>
                                            <input type="text" name="curp" class="sat-input" required placeholder="AAAA######HHHHHHXX" maxlength="18" style="text-transform:uppercase">
                                            <span class="sat-input-hint">18 caracteres. Ej: GOML850101HMCRPN09</span>
                                            <span class="sat-input-error">CURP inválido</span>
                                        </div>
                                        <div class="sat-form-group">
                                            <label>Correo electrónico <span class="required">*</span></label>
                                            <input type="email" name="email" class="sat-input" required placeholder="correo@ejemplo.com">
                                            <span class="sat-input-hint">Se usará para enviar notificaciones</span>
                                            <span class="sat-input-error">Correo inválido</span>
                                        </div>
                                    </div>
                                    <div class="sat-form-row cols-2">
                                        <div class="sat-form-group">
                                            <label>Teléfono celular <span class="required">*</span></label>
                                            <input type="tel" name="telefono" class="sat-input" required placeholder="10 dígitos" maxlength="10">
                                            <span class="sat-input-error">Teléfono requerido</span>
                                        </div>
                                        <div class="sat-form-group">
                                            <label>Identificación oficial <span class="required">*</span></label>
                                            <select name="tipo_identificacion" class="sat-select" required>
                                                <option value="">Seleccionar...</option>
                                                <option value="INE">INE / IFE</option>
                                                <option value="PASAPORTE">Pasaporte</option>
                                                <option value="CEDULA">Cédula profesional</option>
                                                <option value="CARTILLA">Cartilla militar</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="sat-form-footer">
                                    <p class="sat-form-note"><i class="fas fa-lock"></i> Tus datos están protegidos con cifrado SSL</p>
                                    <button type="button" class="btn-sat-green btn-next-step">
                                        Siguiente <i class="fas fa-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="sat-step-content" data-step="2">
                            <div class="sat-form-section">
                                <div class="sat-form-header">
                                    <div class="sat-form-header-icon"><i class="fas fa-map-marker-alt"></i></div>
                                    <div class="sat-form-header-text">
                                        <h2>Domicilio Fiscal</h2>
                                        <p>Dirección donde realizas tus actividades económicas</p>
                                    </div>
                                </div>
                                <div class="sat-form-body">
                                    <div class="sat-form-row cols-3">
                                        <div class="sat-form-group">
                                            <label>Código Postal <span class="required">*</span></label>
                                            <input type="text" name="codigo_postal" class="sat-input" required placeholder="00000" maxlength="5" id="cpInput">
                                            <span class="sat-input-error">Campo requerido</span>
                                        </div>
                                        <div class="sat-form-group">
                                            <label>Estado <span class="required">*</span></label>
                                            <input type="text" name="estado" class="sat-input" required placeholder="Se llena automáticamente" id="estadoInput" readonly>
                                        </div>
                                        <div class="sat-form-group">
                                            <label>Municipio / Alcaldía <span class="required">*</span></label>
                                            <input type="text" name="municipio" class="sat-input" required placeholder="Se llena automáticamente" id="municipioInput" readonly>
                                        </div>
                                    </div>
                                    <div class="sat-form-row cols-3">
                                        <div class="sat-form-group">
                                            <label>Colonia <span class="required">*</span></label>
                                            <select name="colonia" class="sat-select" required id="coloniaSelect">
                                                <option value="">Primero ingresa tu CP</option>
                                            </select>
                                        </div>
                                        <div class="sat-form-group">
                                            <label>Calle <span class="required">*</span></label>
                                            <input type="text" name="calle" class="sat-input" required placeholder="Nombre de la calle">
                                        </div>
                                        <div class="sat-form-group">
                                            <label>No. Exterior <span class="required">*</span></label>
                                            <input type="text" name="no_exterior" class="sat-input" required placeholder="Ej: 123">
                                        </div>
                                    </div>
                                    <div class="sat-form-row cols-2">
                                        <div class="sat-form-group">
                                            <label>No. Interior</label>
                                            <input type="text" name="no_interior" class="sat-input" placeholder="Depto, piso, etc.">
                                        </div>
                                        <div class="sat-form-group">
                                            <label>Entre calles</label>
                                            <input type="text" name="entre_calles" class="sat-input" placeholder="Referencia del domicilio">
                                        </div>
                                    </div>
                                </div>
                                <div class="sat-form-footer">
                                    <button type="button" class="btn-sat-outline btn-prev-step">
                                        <i class="fas fa-arrow-left"></i> Anterior
                                    </button>
                                    <button type="button" class="btn-sat-green btn-next-step">
                                        Siguiente <i class="fas fa-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="sat-step-content" data-step="3">
                            <div class="sat-form-section">
                                <div class="sat-form-header">
                                    <div class="sat-form-header-icon"><i class="fas fa-briefcase"></i></div>
                                    <div class="sat-form-header-text">
                                        <h2>Actividad Económica</h2>
                                        <p>Indica el régimen fiscal y actividad que realizarás</p>
                                    </div>
                                </div>
                                <div class="sat-form-body">
                                    <div class="sat-form-row">
                                        <div class="sat-form-group">
                                            <label>Régimen fiscal <span class="required">*</span></label>
                                            <select name="regimen_fiscal" class="sat-select" required>
                                                <option value="">Seleccionar régimen...</option>
                                                <option value="621">Régimen de Incorporación Fiscal (RIF)</option>
                                                <option value="612">Personas Físicas con Actividades Empresariales</option>
                                                <option value="606">Arrendamiento</option>
                                                <option value="611">Ingresos por Dividendos</option>
                                                <option value="614">Ingresos por intereses</option>
                                                <option value="607">Régimen de Enajenación o Adquisición de Bienes</option>
                                                <option value="615">Régimen de los ingresos por obtención de premios</option>
                                                <option value="625">RESICO - Régimen Simplificado de Confianza</option>
                                            </select>
                                            <span class="sat-input-error">Seleccione un régimen</span>
                                        </div>
                                    </div>
                                    <div class="sat-form-row cols-2">
                                        <div class="sat-form-group">
                                            <label>Actividad principal <span class="required">*</span></label>
                                            <input type="text" name="actividad_principal" class="sat-input" required placeholder="Ej: Venta de ropa al menudeo">
                                        </div>
                                        <div class="sat-form-group">
                                            <label>Fecha de inicio de actividades <span class="required">*</span></label>
                                            <input type="date" name="fecha_inicio_actividades" class="sat-input" required>
                                        </div>
                                    </div>
                                    <div class="sat-form-row">
                                        <div class="sat-form-group">
                                            <label>Descripción de la actividad</label>
                                            <textarea name="descripcion_actividad" class="sat-textarea" placeholder="Describe brevemente la actividad económica que realizarás..."></textarea>
                                        </div>
                                    </div>
                                    <div class="sat-warning-box">
                                        <p><i class="fas fa-exclamation-triangle" style="margin-right:8px;color:var(--sat-gold)"></i>
                                        Al inscribirte al RFC adquieres obligaciones fiscales. Asegúrate de elegir el régimen correcto.</p>
                                    </div>
                                    <div class="sat-checkbox-group">
                                        <label class="sat-checkbox">
                                            <input type="checkbox" name="acepta_terminos" required>
                                            <span class="sat-checkbox-label">Acepto los <a href="#" style="color:var(--sat-green)">Términos y Condiciones</a> del SAT. <span class="required">*</span></span>
                                        </label>
                                        <label class="sat-checkbox">
                                            <input type="checkbox" name="acepta_privacidad" required>
                                            <span class="sat-checkbox-label">He leído y acepto el <a href="#" style="color:var(--sat-green)">Aviso de Privacidad</a> del SAT. <span class="required">*</span></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="sat-form-footer">
                                    <button type="button" class="btn-sat-outline btn-prev-step">
                                        <i class="fas fa-arrow-left"></i> Anterior
                                    </button>
                                    <button type="button" class="btn-sat-green btn-next-step">
                                        Revisar solicitud <i class="fas fa-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4 -->
                        <div class="sat-step-content" data-step="4">
                            <div class="sat-form-section">
                                <div class="sat-form-header">
                                    <div class="sat-form-header-icon"><i class="fas fa-check-circle"></i></div>
                                    <div class="sat-form-header-text">
                                        <h2>Confirmación de Datos</h2>
                                        <p>Revisa que tu información sea correcta antes de enviar</p>
                                    </div>
                                </div>
                                <div class="sat-form-body">
                                    <div class="sat-info-box">
                                        <p><i class="fas fa-info-circle" style="margin-right:8px"></i>
                                        Verifica que todos tus datos sean correctos antes de enviar tu solicitud.</p>
                                    </div>
                                    <div id="resumenDatos" style="background:var(--sat-gray-light);border-radius:8px;padding:24px;border:1px solid var(--sat-gray-border)">
                                        <p style="color:var(--sat-text-muted);font-size:14px;text-align:center">
                                            <i class="fas fa-spinner fa-spin" style="margin-right:8px"></i>
                                            El resumen se mostrará aquí...
                                        </p>
                                    </div>
                                </div>
                                <div class="sat-form-footer">
                                    <button type="button" class="btn-sat-outline btn-prev-step">
                                        <i class="fas fa-arrow-left"></i> Corregir datos
                                    </button>
                                    <button type="submit" class="btn-sat-green" id="btnEnviar">
                                        <i class="fas fa-paper-plane"></i> Enviar solicitud de RFC
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- ── TAB: Consulta ── --}}
                <div class="sat-tab-content {{ $tab === 'consulta' ? 'active' : '' }}" data-tab="consulta">
                    <div class="sat-form-section">
                        <div class="sat-form-header">
                            <div class="sat-form-header-icon"><i class="fas fa-search"></i></div>
                            <div class="sat-form-header-text">
                                <h2>Consultar RFC</h2>
                                <p>Verifica la existencia y vigencia de un RFC en el padrón del SAT</p>
                            </div>
                        </div>
                        <div class="sat-form-body">
                            <form action="{{ route('personas.rfc.consulta') }}" method="POST">
                                @csrf
                                <div class="sat-form-row cols-2">
                                    <div class="sat-form-group">
                                        <label>RFC a consultar <span class="required">*</span></label>
                                        <input type="text" name="rfc" class="sat-input" required
                                               placeholder="Ej: GOML850101ABC" maxlength="13"
                                               value="{{ old('rfc') }}">
                                        <span class="sat-input-hint">Sin guiones ni espacios</span>
                                    </div>
                                    <div class="sat-form-group">
                                        <label>Tipo de consulta</label>
                                        <select name="tipo_consulta" class="sat-select">
                                            <option value="validacion">Validación básica</option>
                                            <option value="situacion">Situación fiscal</option>
                                            <option value="lco">Lista de Control de Obligaciones</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn-sat-green">
                                    <i class="fas fa-search"></i> Consultar
                                </button>
                            </form>

                            @if(isset($resultadoConsulta))
                            <div style="margin-top:28px;padding:24px;background:var(--sat-gray-light);border-radius:8px;border:1px solid var(--sat-gray-border)">
                                <h3 style="font-size:16px;font-weight:700;margin-bottom:16px;color:var(--sat-dark)">Resultado de la consulta</h3>
                                <table class="sat-table">
                                    <tbody>
                                        <tr><th style="width:200px">RFC</th><td>{{ $resultadoConsulta->rfc }}</td></tr>
                                        <tr><th>Nombre / Razón Social</th><td>{{ $resultadoConsulta->nombre }}</td></tr>
                                        <tr><th>Tipo de persona</th><td>{{ $resultadoConsulta->tipo }}</td></tr>
                                        <tr><th>Estatus</th><td>
                                            <span class="sat-table-badge {{ $resultadoConsulta->activo ? 'badge-success' : 'badge-danger' }}">
                                                {{ $resultadoConsulta->activo ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </td></tr>
                                        <tr><th>Fecha de inscripción</th><td>{{ $resultadoConsulta->fecha_inscripcion }}</td></tr>
                                    </tbody>
                                </table>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ── TAB: Actualización ── --}}
                <div class="sat-tab-content {{ $tab === 'actualizacion' ? 'active' : '' }}" data-tab="actualizacion">
                    <div class="sat-form-section">
                        <div class="sat-form-header">
                            <div class="sat-form-header-icon"><i class="fas fa-edit"></i></div>
                            <div class="sat-form-header-text">
                                <h2>Actualización de Datos</h2>
                                <p>Actualiza tu información fiscal como domicilio, correo o teléfono</p>
                            </div>
                        </div>
                        <div class="sat-form-body">
                            <div class="sat-warning-box">
                                <p><i class="fas fa-lock" style="margin-right:8px"></i>
                                Para actualizar tus datos necesitas iniciar sesión con tu RFC y contraseña o e.firma.</p>
                            </div>
                            <div style="display:flex;gap:14px;flex-wrap:wrap">
                                <a href="{{ route('login') }}" class="btn-sat-green">
                                    <i class="fas fa-key"></i> Acceder con contraseña
                                </a>
                                <a href="{{ route('login') }}?tipo=efirma" class="btn-sat-outline">
                                    <i class="fas fa-signature"></i> Acceder con e.firma
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── TAB: Reimpresión ── --}}
                <div class="sat-tab-content {{ $tab === 'reimpresion' ? 'active' : '' }}" data-tab="reimpresion">
                    <div class="sat-form-section">
                        <div class="sat-form-header">
                            <div class="sat-form-header-icon"><i class="fas fa-print"></i></div>
                            <div class="sat-form-header-text">
                                <h2>Reimpresión de RFC</h2>
                                <p>Imprime o descarga tu Constancia de Situación Fiscal</p>
                            </div>
                        </div>
                        <div class="sat-form-body">
                            <form action="{{ route('personas.rfc.reimpresion') }}" method="POST">
                                @csrf
                                <div class="sat-form-row cols-2">
                                    <div class="sat-form-group">
                                        <label>RFC <span class="required">*</span></label>
                                        <input type="text" name="rfc" class="sat-input" required placeholder="Tu RFC" maxlength="13">
                                    </div>
                                    <div class="sat-form-group">
                                        <label>CURP <span class="required">*</span></label>
                                        <input type="text" name="curp" class="sat-input" required placeholder="Tu CURP" maxlength="18">
                                    </div>
                                </div>
                                <button type="submit" class="btn-sat-green">
                                    <i class="fas fa-download"></i> Descargar Constancia
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>{{-- fin #rfcFormPanel --}}

    </div>
</section>

@endsection

@push('scripts')
<script>
// ── Consulta CP automática ──────────────────────────────────────────────────
document.getElementById('cpInput')?.addEventListener('input', async function () {
    const estadoInput    = document.getElementById('estadoInput');
    const municipioInput = document.getElementById('municipioInput');
    const coloniaSelect  = document.getElementById('coloniaSelect');
    if (this.value.length === 5) {
        try {
            const res  = await fetch(`/api/codigos-postales/${this.value}`);
            const data = await res.json();
            if (data.estado) {
                estadoInput.value    = data.estado;
                municipioInput.value = data.municipio;
                estadoInput.setAttribute('readonly', true);
                municipioInput.setAttribute('readonly', true);
                coloniaSelect.innerHTML = '<option value="">Seleccionar colonia...</option>';
                data.colonias.forEach(c => coloniaSelect.innerHTML += `<option value="${c}">${c}</option>`);
            } else {
                estadoInput.removeAttribute('readonly'); municipioInput.removeAttribute('readonly');
                estadoInput.value = municipioInput.value = '';
                estadoInput.placeholder = 'Escribe tu estado'; municipioInput.placeholder = 'Escribe tu municipio';
                coloniaSelect.innerHTML = '<option value="">No disponible - escribe abajo</option>';
            }
        } catch (e) {
            estadoInput.removeAttribute('readonly'); municipioInput.removeAttribute('readonly');
            estadoInput.placeholder = 'Escribe tu estado'; municipioInput.placeholder = 'Escribe tu municipio';
        }
    } else {
        estadoInput.setAttribute('readonly', true); municipioInput.setAttribute('readonly', true);
        estadoInput.value = municipioInput.value = '';
        estadoInput.placeholder = 'Se llena automáticamente'; municipioInput.placeholder = 'Se llena automáticamente';
        coloniaSelect.innerHTML = '<option value="">Primero ingresa tu CP</option>';
    }
});

document.addEventListener('DOMContentLoaded', function () {

    // ── TABS ────────────────────────────────────────────────────────────────
    document.querySelectorAll('.sat-tab').forEach(tab => {
        tab.addEventListener('click', function () {
            document.querySelectorAll('.sat-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.sat-tab-content').forEach(tc => tc.classList.remove('active'));
            this.classList.add('active');
            document.querySelector(`.sat-tab-content[data-tab="${this.dataset.tab}"]`)?.classList.add('active');
        });
    });

    // ── WIZARD ──────────────────────────────────────────────────────────────
    let currentStep = 1;

    function goToStep(n) {
        document.querySelectorAll('.sat-step-content').forEach(sc => sc.classList.remove('active'));
        document.querySelector(`.sat-step-content[data-step="${n}"]`)?.classList.add('active');
        document.querySelectorAll('.sat-step').forEach((sb, i) => {
            sb.classList.remove('active', 'completed');
            if (i + 1 < n)  sb.classList.add('completed');
            if (i + 1 === n) sb.classList.add('active');
        });
        currentStep = n;
        if (n === 4) buildResumen();
        document.querySelector('.sat-tabs-container')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function val(name) {
        const el = document.querySelector(`#rfcForm [name="${name}"]`);
        if (!el) return '—';
        if (el.tagName === 'SELECT') return el.options[el.selectedIndex]?.text || '—';
        return el.value || '—';
    }

    function buildResumen() {
        const box = document.getElementById('resumenDatos');
        if (!box) return;
        box.innerHTML = `
        <div style="display:grid;gap:16px">
            <div>
                <p style="font-size:13px;font-weight:700;color:var(--sat-green);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">
                    <i class="fas fa-user" style="margin-right:6px"></i>Datos Personales</p>
                <table class="sat-table" style="font-size:14px"><tbody>
                    <tr><th style="width:200px">Nombre completo</th><td>${val('nombres')} ${val('primer_apellido')} ${val('segundo_apellido')}</td></tr>
                    <tr><th>Fecha de nacimiento</th><td>${val('fecha_nacimiento')}</td></tr>
                    <tr><th>Sexo</th><td>${val('sexo')}</td></tr>
                    <tr><th>Estado de nacimiento</th><td>${val('estado_nacimiento')}</td></tr>
                    <tr><th>CURP</th><td>${val('curp')}</td></tr>
                    <tr><th>Correo</th><td>${val('email')}</td></tr>
                    <tr><th>Teléfono</th><td>${val('telefono')}</td></tr>
                    <tr><th>Identificación</th><td>${val('tipo_identificacion')}</td></tr>
                </tbody></table>
            </div>
            <div>
                <p style="font-size:13px;font-weight:700;color:var(--sat-green);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">
                    <i class="fas fa-map-marker-alt" style="margin-right:6px"></i>Domicilio Fiscal</p>
                <table class="sat-table" style="font-size:14px"><tbody>
                    <tr><th style="width:200px">Código Postal</th><td>${val('codigo_postal')}</td></tr>
                    <tr><th>Estado</th><td>${val('estado')}</td></tr>
                    <tr><th>Municipio</th><td>${val('municipio')}</td></tr>
                    <tr><th>Colonia</th><td>${val('colonia')}</td></tr>
                    <tr><th>Calle</th><td>${val('calle')} ${val('no_exterior')}</td></tr>
                </tbody></table>
            </div>
            <div>
                <p style="font-size:13px;font-weight:700;color:var(--sat-green);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">
                    <i class="fas fa-briefcase" style="margin-right:6px"></i>Actividad Económica</p>
                <table class="sat-table" style="font-size:14px"><tbody>
                    <tr><th style="width:200px">Régimen fiscal</th><td>${val('regimen_fiscal')}</td></tr>
                    <tr><th>Actividad principal</th><td>${val('actividad_principal')}</td></tr>
                    <tr><th>Inicio de actividades</th><td>${val('fecha_inicio_actividades')}</td></tr>
                </tbody></table>
            </div>
        </div>`;
    }

    function validateStep(stepNum) {
        const container = document.querySelector(`.sat-step-content[data-step="${stepNum}"]`);
        if (!container) return true;
        let valid = true;
        container.querySelectorAll('[required]').forEach(field => {
            const group = field.closest('.sat-form-group');
            const empty = field.type === 'checkbox' ? !field.checked : !field.value.trim();
            if (empty) { valid = false; group?.classList.add('has-error'); }
            else        { group?.classList.remove('has-error'); }
        });
        if (!valid) showToast('Por favor completa todos los campos requeridos.', 'error');
        return valid;
    }

    document.querySelectorAll('.sat-input, .sat-select, .sat-textarea').forEach(f => {
        f.addEventListener('input',  () => f.closest('.sat-form-group')?.classList.remove('has-error'));
        f.addEventListener('change', () => f.closest('.sat-form-group')?.classList.remove('has-error'));
    });

    document.querySelectorAll('.btn-next-step').forEach(btn =>
        btn.addEventListener('click', e => { e.preventDefault(); if (validateStep(currentStep)) goToStep(currentStep + 1); })
    );
    document.querySelectorAll('.btn-prev-step').forEach(btn =>
        btn.addEventListener('click', e => { e.preventDefault(); goToStep(currentStep - 1); })
    );

    // ── SUBMIT AJAX ─────────────────────────────────────────────────────────
    document.getElementById('rfcForm')?.addEventListener('submit', async function (e) {
        e.preventDefault();
        if (currentStep !== 4) return;
        const btn = document.getElementById('btnEnviar');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
        try {
            const res  = await fetch(this.action, {
                method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: new FormData(this),
            });
            const data = await res.json();
            if (!res.ok) {
                const errores = data.errors ? Object.values(data.errors).flat().join(' | ') : (data.message || 'Error al procesar la solicitud.');
                showToast(errores, 'error');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-paper-plane"></i> Enviar solicitud de RFC';
                return;
            }
            mostrarExito(data);
        } catch (err) {
            showToast('Error de conexión. Intenta nuevamente.', 'error');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane"></i> Enviar solicitud de RFC';
        }
    });

    function mostrarExito(data) {
        document.getElementById('rfcFormPanel').style.display = 'none';
        document.getElementById('rfcExitoPanel').style.display = 'block';
        document.getElementById('rfcGenerado').textContent = data.rfc;
        document.getElementById('exitoResumen').innerHTML = `
        <div>
            <p style="font-size:13px;font-weight:700;color:var(--sat-green);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">
                <i class="fas fa-user" style="margin-right:6px"></i>Datos Personales</p>
            <table class="sat-table" style="font-size:14px"><tbody>
                <tr><th style="width:200px">Nombre completo</th><td>${val('nombres')} ${val('primer_apellido')} ${val('segundo_apellido')}</td></tr>
                <tr><th>CURP</th><td>${val('curp')}</td></tr>
                <tr><th>RFC asignado</th><td><strong style="color:var(--sat-green);font-size:16px">${data.rfc}</strong></td></tr>
                <tr><th>Correo</th><td>${val('email')}</td></tr>
                <tr><th>Teléfono</th><td>${val('telefono')}</td></tr>
                <tr><th>Folio</th><td>${data.folio ?? '—'}</td></tr>
            </tbody></table>
        </div>
        <div>
            <p style="font-size:13px;font-weight:700;color:var(--sat-green);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">
                <i class="fas fa-map-marker-alt" style="margin-right:6px"></i>Domicilio Fiscal</p>
            <table class="sat-table" style="font-size:14px"><tbody>
                <tr><th style="width:200px">Dirección</th><td>${val('calle')} ${val('no_exterior')}, Col. ${val('colonia')}</td></tr>
                <tr><th>C.P.</th><td>${val('codigo_postal')}</td></tr>
                <tr><th>Municipio / Estado</th><td>${val('municipio')}, ${val('estado')}</td></tr>
            </tbody></table>
        </div>
        <div>
            <p style="font-size:13px;font-weight:700;color:var(--sat-green);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">
                <i class="fas fa-briefcase" style="margin-right:6px"></i>Actividad Económica</p>
            <table class="sat-table" style="font-size:14px"><tbody>
                <tr><th style="width:200px">Régimen fiscal</th><td>${val('regimen_fiscal')}</td></tr>
                <tr><th>Actividad principal</th><td>${val('actividad_principal')}</td></tr>
                <tr><th>Inicio de actividades</th><td>${val('fecha_inicio_actividades')}</td></tr>
            </tbody></table>
        </div>`;
        document.getElementById('btnDescargarRfc')?.addEventListener('click', () => {
            window.location.href = `/personas/rfc/constancia/${data.rfc}`;
        });
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // ── ESTILOS ─────────────────────────────────────────────────────────────
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeInUp { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }
        .sat-form-group.has-error .sat-input,
        .sat-form-group.has-error .sat-select { border-color:#c0392b!important; box-shadow:0 0 0 3px rgba(192,57,43,.15)!important; }
        .sat-form-group.has-error .sat-input-error { display:block!important; color:#c0392b; font-size:12px; margin-top:4px; }
        .sat-step.completed .sat-step-num { background:var(--sat-green)!important; color:#fff!important; }
        #rfcExitoPanel { animation: fadeInUp .5s ease; }
    `;
    document.head.appendChild(style);

    // ── TOAST ───────────────────────────────────────────────────────────────
    function showToast(msg, type = 'info') {
        document.getElementById('sat-toast')?.remove();
        const t = document.createElement('div');
        t.id = 'sat-toast';
        t.style.cssText = `position:fixed;bottom:24px;right:24px;z-index:9999;padding:14px 20px;
            border-radius:8px;font-size:14px;font-weight:500;display:flex;align-items:center;gap:10px;
            box-shadow:0 4px 20px rgba(0,0,0,.18);background:${type==='error'?'#c0392b':'#1a7a42'};
            color:#fff;animation:fadeInUp .3s ease;`;
        t.innerHTML = `<i class="fas fa-${type==='error'?'exclamation-circle':'check-circle'}"></i> ${msg}`;
        document.body.appendChild(t);
        setTimeout(() => t.remove(), 5000);
    }
});
</script>
@endpush