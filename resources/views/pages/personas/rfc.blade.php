@extends('layouts.app')

@section('title', 'Obtén tu RFC - SAT')

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

        <div class="sat-tabs-container">
            <!-- Tabs -->
            <div class="sat-tabs">
                <div class="sat-tab active" data-tab="inscripcion">
                    <i class="fas fa-user-plus"></i> Inscripción
                </div>
                <div class="sat-tab" data-tab="consulta">
                    <i class="fas fa-search"></i> Consulta RFC
                </div>
                <div class="sat-tab" data-tab="actualizacion">
                    <i class="fas fa-edit"></i> Actualización
                </div>
                <div class="sat-tab" data-tab="reimpresion">
                    <i class="fas fa-print"></i> Reimpresión
                </div>
            </div>

            <!-- Tab: Inscripción -->
            <div class="sat-tab-content active" data-tab="inscripcion">
                <div class="sat-info-box">
                    <p><i class="fas fa-info-circle" style="margin-right:8px"></i>
                    Para inscribirte al RFC necesitas tu CURP, identificación oficial vigente y comprobante de domicilio no mayor a 3 meses.</p>
                </div>

                <!-- Wizard Steps -->
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

                <form action="{{ route('personas.rfc.store') }}" method="POST" class="sat-form-ajax" id="rfcForm">
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
                                            <option value="H">Hombre</option>
                                            <option value="M">Mujer</option>
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
                                        <input type="text" name="curp" class="sat-input" required placeholder="AAAA######HHHHHHXX" maxlength="18" data-validate="curp" style="text-transform:uppercase">
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
                                    Al inscribirte al RFC adquieres obligaciones fiscales. Asegúrate de elegir el régimen correcto. Puedes consultar a un contador para mayor información.</p>
                                </div>
                                <div class="sat-checkbox-group">
                                    <label class="sat-checkbox">
                                        <input type="checkbox" name="acepta_terminos" required>
                                        <span class="sat-checkbox-label">Acepto los <a href="#" style="color:var(--sat-green)">Términos y Condiciones</a> del SAT y confirmo que los datos proporcionados son verídicos. <span class="required">*</span></span>
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
                                    Verifica que todos tus datos sean correctos. Una vez enviada la solicitud, recibirás tu RFC en el correo registrado en un plazo de 24 a 72 horas hábiles.</p>
                                </div>
                                <div id="resumenDatos" style="background:var(--sat-gray-light);border-radius:8px;padding:24px;border:1px solid var(--sat-gray-border)">
                                    <p style="color:var(--sat-text-muted);font-size:14px;text-align:center">
                                        <i class="fas fa-spinner fa-spin" style="margin-right:8px"></i>
                                        El resumen se mostrará aquí al avanzar desde el paso anterior...
                                    </p>
                                </div>
                            </div>
                            <div class="sat-form-footer">
                                <button type="button" class="btn-sat-outline btn-prev-step">
                                    <i class="fas fa-arrow-left"></i> Corregir datos
                                </button>
                                <button type="submit" class="btn-sat-green">
                                    <i class="fas fa-paper-plane"></i> Enviar solicitud de RFC
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tab: Consulta -->
            <div class="sat-tab-content" data-tab="consulta">
                <div class="sat-form-section">
                    <div class="sat-form-header">
                        <div class="sat-form-header-icon"><i class="fas fa-search"></i></div>
                        <div class="sat-form-header-text">
                            <h2>Consultar RFC</h2>
                            <p>Verifica la existencia y vigencia de un RFC en el padrón del SAT</p>
                        </div>
                    </div>
                    <div class="sat-form-body">
                        <form action="{{ route('personas.rfc.consulta') }}" method="POST" class="sat-form-ajax">
                            @csrf
                            <div class="sat-form-row cols-2">
                                <div class="sat-form-group">
                                    <label>RFC a consultar <span class="required">*</span></label>
                                    <input type="text" name="rfc" class="sat-input" required placeholder="Ej: GOML850101ABC" maxlength="13" data-validate="rfc">
                                    <span class="sat-input-hint">Sin guiones ni espacios</span>
                                    <span class="sat-input-error">RFC inválido</span>
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
                            <div style="margin-top:8px">
                                <button type="submit" class="btn-sat-green">
                                    <i class="fas fa-search"></i> Consultar
                                </button>
                            </div>
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

            <!-- Tab: Actualización -->
            <div class="sat-tab-content" data-tab="actualizacion">
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

            <!-- Tab: Reimpresión -->
            <div class="sat-tab-content" data-tab="reimpresion">
                <div class="sat-form-section">
                    <div class="sat-form-header">
                        <div class="sat-form-header-icon"><i class="fas fa-print"></i></div>
                        <div class="sat-form-header-text">
                            <h2>Reimpresión de RFC</h2>
                            <p>Imprime o descarga tu Constancia de Situación Fiscal</p>
                        </div>
                    </div>
                    <div class="sat-form-body">
                        <form action="{{ route('personas.rfc.reimpresion') }}" method="POST" class="sat-form-ajax">
                            @csrf
                            <div class="sat-form-row cols-2">
                                <div class="sat-form-group">
                                    <label>RFC <span class="required">*</span></label>
                                    <input type="text" name="rfc" class="sat-input" required placeholder="Tu RFC" maxlength="13" data-validate="rfc">
                                </div>
                                <div class="sat-form-group">
                                    <label>CURP <span class="required">*</span></label>
                                    <input type="text" name="curp" class="sat-input" required placeholder="Tu CURP" maxlength="18" data-validate="curp">
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

    </div>
</section>

@endsection

@push('scripts')
<script>
// Consulta CP automática
document.getElementById('cpInput')?.addEventListener('input', async function() {
    if (this.value.length === 5) {
        try {
            const res = await fetch(`/api/codigos-postales/${this.value}`);
            const data = await res.json();
            if (data.estado) {
                document.getElementById('estadoInput').value = data.estado;
                document.getElementById('municipioInput').value = data.municipio;
                const sel = document.getElementById('coloniaSelect');
                sel.innerHTML = '<option value="">Seleccionar colonia...</option>';
                data.colonias.forEach(c => {
                    sel.innerHTML += `<option value="${c}">${c}</option>`;
                });
            }
        } catch(e) {}
    }
});
</script>
@endpush
