@extends('layouts.app')

@section('title', 'e.firma (Firma Electrónica) - SAT')

@section('content')

{{-- ── Page Header ─────────────────────────────────────── --}}
<div class="sat-page-header">
    <div class="container-sat">
        <div class="sat-breadcrumb">
            <a href="{{ route('home') }}">Inicio</a>
            <span class="sat-breadcrumb-sep"><i class="fas fa-chevron-right" style="font-size:10px"></i></span>
            <a href="{{ route('personas.index') }}">Personas</a>
            <span class="sat-breadcrumb-sep"><i class="fas fa-chevron-right" style="font-size:10px"></i></span>
            <span>e.firma</span>
        </div>
        <h1 class="sat-page-title">
            <i class="fas fa-signature" style="margin-right:12px"></i>e.firma (Firma Electrónica Avanzada)
        </h1>
        <p class="sat-page-subtitle">Obtén, renueva o revoca tu firma electrónica avanzada de forma segura</p>
    </div>
</div>

<section class="sat-section">
    <div class="container-sat">

        {{-- ── Info cards ──────────────────────────────────── --}}
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-bottom:40px">
            <div class="efirma-info-card" style="background:#f0f9f4;border-color:#b8ddd0">
                <div class="efirma-info-icon" style="background:var(--sat-green)"><i class="fas fa-shield-alt"></i></div>
                <div>
                    <h3>Identificación segura</h3>
                    <p>Acredita tu identidad ante el SAT y otras instituciones del gobierno federal</p>
                </div>
            </div>
            <div class="efirma-info-card" style="background:#fff8e6;border-color:#f5dea0">
                <div class="efirma-info-icon" style="background:var(--sat-gold)"><i class="fas fa-file-signature"></i></div>
                <div>
                    <h3>Firma documentos</h3>
                    <p>Firma contratos, trámites y declaraciones fiscales con plena validez legal</p>
                </div>
            </div>
            <div class="efirma-info-card" style="background:#fdf0f2;border-color:#f0b8c0">
                <div class="efirma-info-icon" style="background:var(--sat-red)"><i class="fas fa-clock"></i></div>
                <div>
                    <h3>Vigencia 4 años</h3>
                    <p>La e.firma tiene vigencia de 4 años. Renuévala antes de que expire</p>
                </div>
            </div>
        </div>

        {{-- ── Tabs ────────────────────────────────────────── --}}
        <div class="sat-tabs-container">
            <div class="sat-tabs">
                <div class="sat-tab active" data-tab="nueva">
                    <i class="fas fa-plus-circle"></i> Obtener nueva
                </div>
                <div class="sat-tab" data-tab="renovacion">
                    <i class="fas fa-sync-alt"></i> Renovar
                </div>
                <div class="sat-tab" data-tab="revocacion">
                    <i class="fas fa-ban"></i> Revocar
                </div>
                <div class="sat-tab" data-tab="verificar">
                    <i class="fas fa-check-circle"></i> Verificar vigencia
                </div>
            </div>

            {{-- ══════════════════════════════════
                 TAB 1 — NUEVA e.firma
            ══════════════════════════════════ --}}
            <div class="sat-tab-content active" data-tab="nueva">
                <div class="sat-info-box">
                    <p><i class="fas fa-info-circle" style="margin-right:8px"></i>
                    La obtención de la e.firma <strong>requiere presencia física</strong> en un módulo SAT con cita previa.
                    Completa esta pre-solicitud y lleva los documentos el día de tu cita.</p>
                </div>

                {{-- Wizard --}}
                <div class="sat-steps">
                    <div class="sat-step active">
                        <div class="sat-step-num">1</div>
                        <div class="sat-step-label">Datos personales</div>
                    </div>
                    <div class="sat-step-line"></div>
                    <div class="sat-step">
                        <div class="sat-step-num">2</div>
                        <div class="sat-step-label">Documentación</div>
                    </div>
                    <div class="sat-step-line"></div>
                    <div class="sat-step">
                        <div class="sat-step-num">3</div>
                        <div class="sat-step-label">Cita en módulo</div>
                    </div>
                    <div class="sat-step-line"></div>
                    <div class="sat-step">
                        <div class="sat-step-num">4</div>
                        <div class="sat-step-label">Confirmación</div>
                    </div>
                </div>

                <form action="{{ route('personas.e_firma.store') }}" method="POST"
                      class="sat-form-ajax" id="efirmaForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="tipo" value="nueva">

                    {{-- Step 1: Datos personales --}}
                    <div class="sat-step-content active" data-step="1">
                        <div class="sat-form-section">
                            <div class="sat-form-header">
                                <div class="sat-form-header-icon"><i class="fas fa-user"></i></div>
                                <div class="sat-form-header-text">
                                    <h2>Datos Personales</h2>
                                    <p>Información del contribuyente que solicita la e.firma</p>
                                </div>
                            </div>
                            <div class="sat-form-body">
                                <div class="sat-form-row cols-3">
                                    <div class="sat-form-group">
                                        <label>RFC <span class="required">*</span></label>
                                        <input type="text" name="rfc" class="sat-input" required
                                               placeholder="Ej: GOML850101ABC" maxlength="13"
                                               data-validate="rfc" id="rfc-nueva">
                                        <span class="sat-input-hint">13 caracteres, sin guiones</span>
                                        <span class="sat-input-error">RFC inválido</span>
                                    </div>
                                    <div class="sat-form-group">
                                        <label>CURP <span class="required">*</span></label>
                                        <input type="text" name="curp" class="sat-input" required
                                               placeholder="18 caracteres" maxlength="18"
                                               data-validate="curp" style="text-transform:uppercase">
                                        <span class="sat-input-error">CURP inválido</span>
                                    </div>
                                    <div class="sat-form-group">
                                        <label>Fecha de nacimiento <span class="required">*</span></label>
                                        <input type="date" name="fecha_nacimiento" class="sat-input"
                                               required max="{{ date('Y-m-d') }}">
                                        <span class="sat-input-error">Campo requerido</span>
                                    </div>
                                </div>
                                <div class="sat-form-row cols-3">
                                    <div class="sat-form-group">
                                        <label>Primer apellido <span class="required">*</span></label>
                                        <input type="text" name="primer_apellido" class="sat-input"
                                               required placeholder="Apellido paterno" maxlength="50">
                                        <span class="sat-input-error">Campo requerido</span>
                                    </div>
                                    <div class="sat-form-group">
                                        <label>Segundo apellido</label>
                                        <input type="text" name="segundo_apellido" class="sat-input"
                                               placeholder="Apellido materno" maxlength="50">
                                    </div>
                                    <div class="sat-form-group">
                                        <label>Nombre(s) <span class="required">*</span></label>
                                        <input type="text" name="nombres" class="sat-input"
                                               required placeholder="Nombre(s)" maxlength="80">
                                        <span class="sat-input-error">Campo requerido</span>
                                    </div>
                                </div>
                                <div class="sat-form-row cols-2">
                                    <div class="sat-form-group">
                                        <label>Correo electrónico <span class="required">*</span></label>
                                        <input type="email" name="email" class="sat-input"
                                               required placeholder="correo@ejemplo.com" id="email-nueva">
                                        <span class="sat-input-hint">Para notificaciones de tu e.firma</span>
                                        <span class="sat-input-error">Correo inválido</span>
                                    </div>
                                    <div class="sat-form-group">
                                        <label>Confirmar correo <span class="required">*</span></label>
                                        <input type="email" name="email_confirmation" class="sat-input"
                                               required placeholder="correo@ejemplo.com" id="email-confirm-nueva">
                                        <span class="sat-input-error">Los correos no coinciden</span>
                                    </div>
                                </div>
                                <div class="sat-form-row cols-2">
                                    <div class="sat-form-group">
                                        <label>Teléfono celular <span class="required">*</span></label>
                                        <input type="tel" name="telefono" class="sat-input"
                                               required placeholder="10 dígitos" maxlength="10">
                                        <span class="sat-input-error">Teléfono requerido</span>
                                    </div>
                                    <div class="sat-form-group">
                                        <label>Identificación oficial <span class="required">*</span></label>
                                        <select name="tipo_identificacion" class="sat-select" required>
                                            <option value="">Seleccionar...</option>
                                            <option value="INE">INE / IFE vigente</option>
                                            <option value="PASAPORTE">Pasaporte mexicano vigente</option>
                                            <option value="CEDULA">Cédula profesional con fotografía</option>
                                            <option value="CARTILLA">Cartilla militar</option>
                                        </select>
                                        <span class="sat-input-error">Seleccione una opción</span>
                                    </div>
                                </div>

                                {{-- Contraseña de clave privada --}}
                                <div class="efirma-pwd-box">
                                    <h3 class="efirma-pwd-title">
                                        <i class="fas fa-lock"></i> Contraseña de la clave privada
                                    </h3>
                                    <p class="efirma-pwd-desc">
                                        Protege tu archivo <code>.key</code>. Guárdala en un lugar seguro,
                                        <strong>no se puede recuperar</strong> si la olvidas.
                                    </p>
                                    <div class="sat-form-row cols-2">
                                        <div class="sat-form-group">
                                            <label>Contraseña <span class="required">*</span></label>
                                            <div class="input-eye-wrap">
                                                <input type="password" name="contrasena_clave" class="sat-input"
                                                       required id="pwd-clave" placeholder="Mínimo 8 caracteres">
                                                <button type="button" class="toggle-pwd" data-target="pwd-clave">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            <div class="pwd-bar"><div id="pwd-bar-fill"></div></div>
                                            <span class="sat-input-hint" id="pwd-bar-label">Ingresa una contraseña segura</span>
                                            <span class="sat-input-error">Mínimo 8 caracteres</span>
                                        </div>
                                        <div class="sat-form-group">
                                            <label>Confirmar contraseña <span class="required">*</span></label>
                                            <div class="input-eye-wrap">
                                                <input type="password" name="contrasena_clave_confirmation"
                                                       class="sat-input" required id="pwd-clave-confirm"
                                                       placeholder="Repite la contraseña">
                                                <button type="button" class="toggle-pwd" data-target="pwd-clave-confirm">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            <span class="sat-input-error">Las contraseñas no coinciden</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="sat-form-footer">
                                <p class="sat-form-note"><i class="fas fa-lock"></i> Conexión cifrada SSL</p>
                                <button type="button" class="btn-sat-green btn-next-step">
                                    Siguiente <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>{{-- /step1 --}}

                    {{-- Step 2: Documentación --}}
                    <div class="sat-step-content" data-step="2">
                        <div class="sat-form-section">
                            <div class="sat-form-header">
                                <div class="sat-form-header-icon"><i class="fas fa-folder-open"></i></div>
                                <div class="sat-form-header-text">
                                    <h2>Documentación requerida</h2>
                                    <p>Adjunta copias digitales para agilizar el trámite. Los originales se verifican en módulo.</p>
                                </div>
                            </div>
                            <div class="sat-form-body">
                                <div class="sat-warning-box">
                                    <p><i class="fas fa-exclamation-triangle" style="margin-right:8px;color:var(--sat-gold)"></i>
                                    Los documentos originales <strong>deben presentarse físicamente</strong> en el módulo el día de tu cita.</p>
                                </div>
                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
                                    {{-- INE --}}
                                    <div class="doc-card">
                                        <div class="doc-card-header">
                                            <div class="doc-icon" style="background:rgba(200,16,46,.1);color:var(--sat-red)"><i class="fas fa-id-card"></i></div>
                                            <div>
                                                <h4>Identificación oficial <span class="required">*</span></h4>
                                                <p>INE, Pasaporte o Cédula profesional vigente</p>
                                            </div>
                                        </div>
                                        <label class="file-label" for="f-ine">
                                            <input type="file" name="archivo_ine" id="f-ine" accept=".pdf,.jpg,.jpeg,.png" class="file-hidden" onchange="previewFile(this,'prev-ine','box-ine')">
                                            <div class="file-box" id="box-ine">
                                                <i class="fas fa-cloud-upload-alt"></i>
                                                <p>Arrastra o haz clic</p>
                                                <small>PDF, JPG, PNG · máx. 5 MB</small>
                                            </div>
                                        </label>
                                        <div id="prev-ine" class="file-preview" style="display:none"></div>
                                    </div>
                                    {{-- Domicilio --}}
                                    <div class="doc-card">
                                        <div class="doc-card-header">
                                            <div class="doc-icon" style="background:rgba(0,104,71,.1);color:var(--sat-green)"><i class="fas fa-home"></i></div>
                                            <div>
                                                <h4>Comprobante de domicilio <span class="required">*</span></h4>
                                                <p>No mayor a 3 meses (CFE, agua, teléfono)</p>
                                            </div>
                                        </div>
                                        <label class="file-label" for="f-dom">
                                            <input type="file" name="archivo_domicilio" id="f-dom" accept=".pdf,.jpg,.jpeg,.png" class="file-hidden" onchange="previewFile(this,'prev-dom','box-dom')">
                                            <div class="file-box" id="box-dom">
                                                <i class="fas fa-cloud-upload-alt"></i>
                                                <p>Arrastra o haz clic</p>
                                                <small>PDF, JPG, PNG · máx. 5 MB</small>
                                            </div>
                                        </label>
                                        <div id="prev-dom" class="file-preview" style="display:none"></div>
                                    </div>
                                    {{-- CURP --}}
                                    <div class="doc-card">
                                        <div class="doc-card-header">
                                            <div class="doc-icon" style="background:rgba(245,168,0,.12);color:var(--sat-gold)"><i class="fas fa-file-alt"></i></div>
                                            <div>
                                                <h4>CURP impreso <span class="required">*</span></h4>
                                                <p>Descárgalo en <a href="https://www.gob.mx/curp" target="_blank" style="color:var(--sat-green)">gob.mx/curp</a></p>
                                            </div>
                                        </div>
                                        <label class="file-label" for="f-curp">
                                            <input type="file" name="archivo_curp" id="f-curp" accept=".pdf,.jpg,.jpeg,.png" class="file-hidden" onchange="previewFile(this,'prev-curp','box-curp')">
                                            <div class="file-box" id="box-curp">
                                                <i class="fas fa-cloud-upload-alt"></i>
                                                <p>Arrastra o haz clic</p>
                                                <small>PDF, JPG, PNG · máx. 5 MB</small>
                                            </div>
                                        </label>
                                        <div id="prev-curp" class="file-preview" style="display:none"></div>
                                    </div>
                                    {{-- Foto (opcional) --}}
                                    <div class="doc-card">
                                        <div class="doc-card-header">
                                            <div class="doc-icon" style="background:rgba(0,100,200,.1);color:#0064c8"><i class="fas fa-camera"></i></div>
                                            <div>
                                                <h4>Fotografía reciente</h4>
                                                <p>Fondo blanco, sin lentes · 300 dpi (opcional)</p>
                                            </div>
                                        </div>
                                        <label class="file-label" for="f-foto">
                                            <input type="file" name="archivo_foto" id="f-foto" accept=".jpg,.jpeg,.png" class="file-hidden" onchange="previewFile(this,'prev-foto','box-foto')">
                                            <div class="file-box" id="box-foto">
                                                <i class="fas fa-cloud-upload-alt"></i>
                                                <p>Arrastra o haz clic</p>
                                                <small>JPG, PNG · máx. 2 MB</small>
                                            </div>
                                        </label>
                                        <div id="prev-foto" class="file-preview" style="display:none"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="sat-form-footer">
                                <button type="button" class="btn-sat-outline btn-prev-step"><i class="fas fa-arrow-left"></i> Anterior</button>
                                <button type="button" class="btn-sat-green btn-next-step">Siguiente <i class="fas fa-arrow-right"></i></button>
                            </div>
                        </div>
                    </div>{{-- /step2 --}}

                    {{-- Step 3: Cita en módulo --}}
                    <div class="sat-step-content" data-step="3">
                        <div class="sat-form-section">
                            <div class="sat-form-header">
                                <div class="sat-form-header-icon"><i class="fas fa-calendar-check"></i></div>
                                <div class="sat-form-header-text">
                                    <h2>Agenda tu cita en módulo SAT</h2>
                                    <p>La e.firma requiere presencia física para toma de datos biométricos</p>
                                </div>
                            </div>
                            <div class="sat-form-body">
                                <div class="sat-form-row cols-2">
                                    <div class="sat-form-group">
                                        <label>Estado <span class="required">*</span></label>
                                        <select name="estado_modulo" class="sat-select" required id="estado-efirma">
                                            <option value="">Seleccionar estado...</option>
                                            @foreach(['Aguascalientes','Baja California','Baja California Sur','Campeche','Chiapas','Chihuahua','Ciudad de México','Coahuila','Colima','Durango','Estado de México','Guanajuato','Guerrero','Hidalgo','Jalisco','Michoacán','Morelos','Nayarit','Nuevo León','Oaxaca','Puebla','Querétaro','Quintana Roo','San Luis Potosí','Sinaloa','Sonora','Tabasco','Tamaulipas','Tlaxcala','Veracruz','Yucatán','Zacatecas'] as $est)
                                            <option value="{{ $est }}">{{ $est }}</option>
                                            @endforeach
                                        </select>
                                        <span class="sat-input-error">Seleccione su estado</span>
                                    </div>
                                    <div class="sat-form-group">
                                        <label>Módulo SAT <span class="required">*</span></label>
                                        <select name="modulo_efirma" class="sat-select" required id="modulo-efirma">
                                            <option value="">Primero selecciona tu estado</option>
                                        </select>
                                        <span class="sat-input-error">Seleccione un módulo</span>
                                    </div>
                                </div>
                                <div class="sat-form-row cols-2">
                                    <div class="sat-form-group">
                                        <label>Fecha preferida <span class="required">*</span></label>
                                        <input type="date" name="fecha_cita" class="sat-input" required
                                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                               max="{{ date('Y-m-d', strtotime('+60 days')) }}" id="fecha-efirma">
                                        <span class="sat-input-hint">Solo días hábiles (Lunes a Viernes)</span>
                                        <span class="sat-input-error">Seleccione una fecha válida</span>
                                    </div>
                                    <div class="sat-form-group">
                                        <label>Horario <span class="required">*</span></label>
                                        <select name="horario_cita" class="sat-select" required>
                                            <option value="">Seleccionar horario...</option>
                                            @foreach(['08:00','09:00','10:00','11:00','12:00','13:00','15:00','16:00','17:00'] as $h)
                                            <option value="{{ $h }}">{{ $h }} – {{ date('H:i', strtotime($h.' +1 hour')) }} hrs</option>
                                            @endforeach
                                        </select>
                                        <span class="sat-input-error">Seleccione un horario</span>
                                    </div>
                                </div>

                                <div class="checklist-box">
                                    <h4><i class="fas fa-clipboard-list" style="color:var(--sat-green);margin-right:8px"></i>
                                        Documentos que deberás llevar el día de tu cita</h4>
                                    <div class="checklist-grid">
                                        @foreach([
                                            ['fas fa-id-card','Identificación oficial vigente (original y copia)'],
                                            ['fas fa-home','Comprobante de domicilio (no mayor a 3 meses)'],
                                            ['fas fa-file-alt','CURP impreso en original'],
                                            ['fas fa-usb','Unidad USB para guardar tu e.firma (.cer y .key)'],
                                            ['fas fa-mobile-alt','Teléfono celular para verificación por SMS'],
                                            ['fas fa-envelope-open','Acceso a tu correo electrónico ese día'],
                                        ] as [$ico, $doc])
                                        <div class="checklist-item"><i class="{{ $ico }}"></i> {{ $doc }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="sat-form-footer">
                                <button type="button" class="btn-sat-outline btn-prev-step"><i class="fas fa-arrow-left"></i> Anterior</button>
                                <button type="button" class="btn-sat-green btn-next-step" id="btn-to-step4">
                                    Revisar solicitud <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>{{-- /step3 --}}

                    {{-- Step 4: Confirmación --}}
                    <div class="sat-step-content" data-step="4">
                        <div class="sat-form-section">
                            <div class="sat-form-header">
                                <div class="sat-form-header-icon"><i class="fas fa-check-double"></i></div>
                                <div class="sat-form-header-text">
                                    <h2>Resumen de la solicitud</h2>
                                    <p>Verifica tu información antes de enviar</p>
                                </div>
                            </div>
                            <div class="sat-form-body">
                                <div class="sat-info-box">
                                    <p><i class="fas fa-info-circle" style="margin-right:8px"></i>
                                    Recibirás un correo con el folio y detalles de tu cita. Lleva los documentos originales al módulo SAT.</p>
                                </div>
                                <div class="resumen-grid">
                                    <div class="resumen-panel">
                                        <h4>Datos personales</h4>
                                        <div class="resumen-row"><span>RFC:</span><span id="r-rfc">—</span></div>
                                        <div class="resumen-row"><span>CURP:</span><span id="r-curp">—</span></div>
                                        <div class="resumen-row"><span>Nombre:</span><span id="r-nombre">—</span></div>
                                        <div class="resumen-row"><span>Correo:</span><span id="r-email">—</span></div>
                                        <div class="resumen-row"><span>Teléfono:</span><span id="r-tel">—</span></div>
                                        <div class="resumen-row"><span>ID oficial:</span><span id="r-id">—</span></div>
                                    </div>
                                    <div class="resumen-panel">
                                        <h4>Cita agendada</h4>
                                        <div class="resumen-row"><span>Estado:</span><span id="r-estado">—</span></div>
                                        <div class="resumen-row"><span>Módulo:</span><span id="r-modulo">—</span></div>
                                        <div class="resumen-row"><span>Fecha:</span><span id="r-fecha">—</span></div>
                                        <div class="resumen-row"><span>Horario:</span><span id="r-horario">—</span></div>
                                        <div class="resumen-row"><span>Documentos:</span><span id="r-docs">—</span></div>
                                    </div>
                                </div>
                                <div class="sat-checkbox-group" style="margin-top:24px">
                                    <label class="sat-checkbox">
                                        <input type="checkbox" name="acepta_terminos" required>
                                        <span class="sat-checkbox-label">
                                            Declaro bajo protesta de decir verdad que los datos son correctos y acepto los
                                            <a href="#" style="color:var(--sat-green)">Términos y Condiciones</a>
                                            del SAT para el uso de la e.firma. <span class="required">*</span>
                                        </span>
                                    </label>
                                    <label class="sat-checkbox" style="margin-top:10px">
                                        <input type="checkbox" name="acepta_privacidad" required>
                                        <span class="sat-checkbox-label">
                                            He leído y acepto el <a href="#" style="color:var(--sat-green)">Aviso de Privacidad</a>
                                            del Servicio de Administración Tributaria. <span class="required">*</span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="sat-form-footer">
                                <button type="button" class="btn-sat-outline btn-prev-step"><i class="fas fa-arrow-left"></i> Corregir datos</button>
                                <button type="submit" class="btn-sat-green">
                                    <i class="fas fa-paper-plane"></i> Enviar solicitud
                                </button>
                            </div>
                        </div>
                    </div>{{-- /step4 --}}

                </form>
            </div>{{-- /tab nueva --}}


            {{-- ══════════════════════════════════
                 TAB 2 — RENOVAR
            ══════════════════════════════════ --}}
            <div class="sat-tab-content" data-tab="renovacion">
                <div class="sat-info-box">
                    <p><i class="fas fa-info-circle" style="margin-right:8px"></i>
                    Puedes renovar <strong>hasta 24 h antes</strong> de que expire, o bien hasta
                    <strong>1 año después</strong> de vencida sin ir al módulo usando SAT ID.</p>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:28px">
                    <div class="via-card active" id="via-satid" onclick="selectVia('satid')">
                        <div class="via-icon" style="background:rgba(0,104,71,.1);color:var(--sat-green)"><i class="fas fa-mobile-alt"></i></div>
                        <div class="via-info">
                            <h3>SAT ID <span style="font-size:11px;background:var(--sat-green);color:white;padding:2px 8px;border-radius:10px;margin-left:4px">Recomendado</span></h3>
                            <p>Sin ir al módulo. Solo si tu e.firma no tiene más de 1 año vencida.</p>
                        </div>
                        <i class="fas fa-check-circle via-check active" id="chk-satid"></i>
                    </div>
                    <div class="via-card" id="via-modulo-ren" onclick="selectVia('modulo')">
                        <div class="via-icon" style="background:rgba(200,16,46,.1);color:var(--sat-red)"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="via-info">
                            <h3>Módulo SAT</h3>
                            <p>Presencial con cita. Para e.firmas vencidas hace más de 1 año.</p>
                        </div>
                        <i class="fas fa-circle via-check" id="chk-modulo"></i>
                    </div>
                </div>

                <form action="{{ route('personas.e_firma.store') }}" method="POST" class="sat-form-ajax">
                    @csrf
                    <input type="hidden" name="tipo" value="renovacion">
                    <input type="hidden" name="via_renovacion" id="via-input" value="satid">
                    <div class="sat-form-section">
                        <div class="sat-form-header">
                            <div class="sat-form-header-icon"><i class="fas fa-sync-alt"></i></div>
                            <div class="sat-form-header-text">
                                <h2>Datos para la renovación</h2>
                                <p>Verifica tu identidad para renovar tu certificado</p>
                            </div>
                        </div>
                        <div class="sat-form-body">
                            <div class="sat-form-row cols-2">
                                <div class="sat-form-group">
                                    <label>RFC <span class="required">*</span></label>
                                    <input type="text" name="rfc" class="sat-input" required data-validate="rfc" placeholder="Tu RFC">
                                    <span class="sat-input-error">RFC inválido</span>
                                </div>
                                <div class="sat-form-group">
                                    <label>CURP <span class="required">*</span></label>
                                    <input type="text" name="curp" class="sat-input" required data-validate="curp" placeholder="18 caracteres" style="text-transform:uppercase">
                                    <span class="sat-input-error">CURP inválido</span>
                                </div>
                            </div>
                            <div class="sat-form-row cols-2">
                                <div class="sat-form-group">
                                    <label>Correo registrado <span class="required">*</span></label>
                                    <input type="email" name="email" class="sat-input" required placeholder="El correo con el que te registraste">
                                </div>
                                <div class="sat-form-group">
                                    <label>Teléfono celular <span class="required">*</span></label>
                                    <input type="tel" name="telefono" class="sat-input" required placeholder="10 dígitos" maxlength="10">
                                </div>
                            </div>
                            <div class="efirma-pwd-box">
                                <h3 class="efirma-pwd-title"><i class="fas fa-key"></i> Certificado actual y nueva contraseña</h3>
                                <div class="sat-form-row cols-2">
                                    <div class="sat-form-group">
                                        <label>Adjuntar .cer actual (opcional)</label>
                                        <label class="file-label" for="f-cer-ren">
                                            <input type="file" name="archivo_cer" id="f-cer-ren" accept=".cer" class="file-hidden" onchange="previewFile(this,'prev-cer-ren','box-cer-ren')">
                                            <div class="file-box" id="box-cer-ren" style="background:white">
                                                <i class="fas fa-certificate" style="color:var(--sat-gold)"></i>
                                                <p>Adjuntar .cer</p><small>Solo .cer · máx. 1 MB</small>
                                            </div>
                                        </label>
                                        <div id="prev-cer-ren" class="file-preview" style="display:none"></div>
                                    </div>
                                    <div>
                                        <div class="sat-form-group" style="margin-bottom:16px">
                                            <label>Nueva contraseña <span class="required">*</span></label>
                                            <div class="input-eye-wrap">
                                                <input type="password" name="contrasena_clave" class="sat-input" required id="pwd-ren" placeholder="Mínimo 8 caracteres">
                                                <button type="button" class="toggle-pwd" data-target="pwd-ren"><i class="fas fa-eye"></i></button>
                                            </div>
                                        </div>
                                        <div class="sat-form-group">
                                            <label>Confirmar contraseña <span class="required">*</span></label>
                                            <div class="input-eye-wrap">
                                                <input type="password" name="contrasena_clave_confirmation" class="sat-input" required id="pwd-ren-c" placeholder="Repite la contraseña">
                                                <button type="button" class="toggle-pwd" data-target="pwd-ren-c"><i class="fas fa-eye"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sat-form-footer">
                            <p class="sat-form-note"><i class="fas fa-shield-alt"></i> Proceso seguro</p>
                            <button type="submit" class="btn-sat-green"><i class="fas fa-sync-alt"></i> Solicitar renovación</button>
                        </div>
                    </div>
                </form>
            </div>{{-- /tab renovacion --}}


            {{-- ══════════════════════════════════
                 TAB 3 — REVOCAR
            ══════════════════════════════════ --}}
            <div class="sat-tab-content" data-tab="revocacion">
                <div class="sat-warning-box">
                    <p><i class="fas fa-exclamation-triangle" style="margin-right:8px;color:var(--sat-gold)"></i>
                    <strong>Atención:</strong> La revocación es <strong>irreversible</strong>. Úsala solo si sospechas que tu e.firma fue comprometida.</p>
                </div>
                <div class="sat-form-section">
                    <div class="sat-form-header" style="background:var(--sat-red)">
                        <div class="sat-form-header-icon"><i class="fas fa-ban"></i></div>
                        <div class="sat-form-header-text">
                            <h2>Revocar e.firma</h2>
                            <p>Cancela de forma permanente tu firma electrónica avanzada</p>
                        </div>
                    </div>
                    <div class="sat-form-body">
                        <form action="{{ route('personas.e_firma.store') }}" method="POST" class="sat-form-ajax">
                            @csrf
                            <input type="hidden" name="tipo" value="revocacion">
                            <div class="sat-form-row cols-2">
                                <div class="sat-form-group">
                                    <label>RFC <span class="required">*</span></label>
                                    <input type="text" name="rfc" class="sat-input" required data-validate="rfc" placeholder="Tu RFC">
                                    <span class="sat-input-error">RFC inválido</span>
                                </div>
                                <div class="sat-form-group">
                                    <label>No. de serie del certificado <span class="required">*</span></label>
                                    <input type="text" name="no_serie" class="sat-input" required placeholder="Ej: 20001000000300022323" maxlength="20" style="font-family:monospace">
                                    <span class="sat-input-hint">Lo encuentras abriendo tu archivo .cer</span>
                                </div>
                            </div>
                            <div class="sat-form-group" style="margin-bottom:20px">
                                <label>Motivo de revocación <span class="required">*</span></label>
                                <select name="motivo_revocacion" class="sat-select" required>
                                    <option value="">Seleccionar motivo...</option>
                                    <option value="ROBO">Robo o extravío del dispositivo de almacenamiento</option>
                                    <option value="COMPROMISO">Sospecha de uso no autorizado o compromiso</option>
                                    <option value="CAMBIO_DATOS">Cambio de datos del contribuyente</option>
                                    <option value="FALLECIMIENTO">Fallecimiento del titular</option>
                                    <option value="OTRO">Otro motivo</option>
                                </select>
                            </div>
                            <div class="sat-form-group" style="margin-bottom:20px">
                                <label>Descripción adicional</label>
                                <textarea name="descripcion_revocacion" class="sat-textarea" placeholder="Describe brevemente la situación..."></textarea>
                            </div>
                            <div class="sat-form-group" style="margin-bottom:20px">
                                <label>Correo para acuse <span class="required">*</span></label>
                                <input type="email" name="email" class="sat-input" required placeholder="correo@ejemplo.com">
                            </div>
                            <div class="sat-checkbox-group" style="margin-bottom:20px">
                                <label class="sat-checkbox">
                                    <input type="checkbox" name="confirma_revocacion" required>
                                    <span class="sat-checkbox-label" style="color:var(--sat-red)">
                                        <strong>Entiendo que esta acción es irreversible</strong> y que deberé tramitar
                                        una nueva e.firma en un módulo SAT. <span class="required">*</span>
                                    </span>
                                </label>
                            </div>
                            <button type="submit" class="btn-sat-primary"><i class="fas fa-ban"></i> Confirmar revocación</button>
                        </form>
                    </div>
                </div>
            </div>{{-- /tab revocacion --}}


            {{-- ══════════════════════════════════
                 TAB 4 — VERIFICAR VIGENCIA
            ══════════════════════════════════ --}}
            <div class="sat-tab-content" data-tab="verificar">
                <div class="sat-form-section">
                    <div class="sat-form-header">
                        <div class="sat-form-header-icon"><i class="fas fa-check-circle"></i></div>
                        <div class="sat-form-header-text">
                            <h2>Verificar vigencia de e.firma</h2>
                            <p>Consulta si tu certificado sigue activo o ya expiró</p>
                        </div>
                    </div>
                    <div class="sat-form-body">
                        <form action="{{ route('personas.e_firma.verificar') }}" method="POST" class="sat-form-ajax">
                            @csrf
                            <div class="sat-form-row cols-2">
                                <div class="sat-form-group">
                                    <label>RFC <span class="required">*</span></label>
                                    <input type="text" name="rfc" class="sat-input" required data-validate="rfc" placeholder="Tu RFC">
                                    <span class="sat-input-error">RFC inválido</span>
                                </div>
                                <div class="sat-form-group">
                                    <label>No. de serie (opcional)</label>
                                    <input type="text" name="no_serie" class="sat-input" placeholder="Si no lo sabes, solo ingresa tu RFC" style="font-family:monospace">
                                </div>
                            </div>
                            <div class="sat-form-group" style="margin-bottom:20px">
                                <label>O adjunta tu archivo <code>.cer</code></label>
                                <label class="file-label" for="f-cer-v">
                                    <input type="file" name="archivo_cer_verificar" id="f-cer-v" accept=".cer" class="file-hidden" onchange="previewFile(this,'prev-cer-v','box-cer-v')">
                                    <div class="file-box" id="box-cer-v" style="max-width:380px">
                                        <i class="fas fa-certificate" style="color:var(--sat-gold)"></i>
                                        <p>Adjuntar .cer</p><small>Solo .cer · máx. 1 MB</small>
                                    </div>
                                </label>
                                <div id="prev-cer-v" class="file-preview" style="display:none;margin-top:10px"></div>
                            </div>
                            <button type="submit" class="btn-sat-green"><i class="fas fa-search"></i> Verificar certificado</button>
                        </form>

                        @if(isset($resultadoVerificacion))
                        <div style="margin-top:28px;padding:24px;border-radius:10px;border:2px solid {{ $resultadoVerificacion->vigente ? 'var(--sat-green)' : 'var(--sat-red)' }};background:{{ $resultadoVerificacion->vigente ? '#f0f9f4' : '#fdf0f2' }}">
                            <div style="display:flex;align-items:center;gap:14px;margin-bottom:16px">
                                <i class="fas fa-{{ $resultadoVerificacion->vigente ? 'check-circle' : 'times-circle' }}" style="font-size:36px;color:{{ $resultadoVerificacion->vigente ? 'var(--sat-green)' : 'var(--sat-red)' }}"></i>
                                <div>
                                    <h3 style="font-size:18px;font-weight:700;color:var(--sat-dark)">
                                        Certificado {{ $resultadoVerificacion->vigente ? 'VIGENTE' : 'VENCIDO' }}
                                    </h3>
                                    <p style="font-size:13px;color:var(--sat-text-muted)">
                                        {{ $resultadoVerificacion->vigente ? 'Tu e.firma está activa' : 'Debes renovar tu e.firma' }}
                                    </p>
                                </div>
                            </div>
                            <table class="sat-table">
                                <tbody>
                                    <tr><th style="width:200px">RFC</th><td>{{ $resultadoVerificacion->rfc }}</td></tr>
                                    <tr><th>No. de serie</th><td style="font-family:monospace;font-size:13px">{{ $resultadoVerificacion->no_serie }}</td></tr>
                                    <tr><th>Fecha de emisión</th><td>{{ $resultadoVerificacion->fecha_emision }}</td></tr>
                                    <tr><th>Fecha de vencimiento</th><td>{{ $resultadoVerificacion->fecha_vencimiento }}</td></tr>
                                    <tr><th>Estatus</th><td>
                                        <span class="sat-table-badge {{ $resultadoVerificacion->vigente ? 'badge-success' : 'badge-danger' }}">
                                            {{ $resultadoVerificacion->vigente ? 'Vigente' : 'Vencido' }}
                                        </span>
                                    </td></tr>
                                </tbody>
                            </table>
                            @if(!$resultadoVerificacion->vigente)
                            <div style="margin-top:16px">
                                <button type="button" class="btn-sat-green" onclick="document.querySelector('[data-tab=renovacion]').click()">
                                    <i class="fas fa-sync-alt"></i> Renovar ahora
                                </button>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>{{-- /tab verificar --}}

        </div>{{-- /tabs-container --}}
    </div>
</section>

@endsection


@push('styles')
<style>
.efirma-info-card{border:1px solid;border-radius:10px;padding:22px;display:flex;gap:14px;align-items:flex-start}
.efirma-info-card h3{font-size:14px;font-weight:700;color:var(--sat-dark);margin-bottom:4px}
.efirma-info-card p{font-size:13px;color:var(--sat-text-muted)}
.efirma-info-icon{width:42px;height:42px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;color:white}
.efirma-pwd-box{background:#f0f9f4;border:1px solid #b8ddd0;border-radius:8px;padding:20px;margin-top:8px}
.efirma-pwd-title{font-size:14px;font-weight:700;color:var(--sat-green);margin-bottom:6px;display:flex;align-items:center;gap:8px}
.efirma-pwd-desc{font-size:13px;color:var(--sat-text-muted);margin-bottom:16px}
.input-eye-wrap{position:relative}
.input-eye-wrap .sat-input{padding-right:44px}
.toggle-pwd{position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--sat-gray);cursor:pointer;font-size:15px;padding:0}
.pwd-bar{height:4px;border-radius:2px;background:var(--sat-gray-border);margin-top:6px;overflow:hidden}
.pwd-bar div{height:100%;width:0;border-radius:2px;transition:width .3s,background .3s}
.doc-card{background:white;border:1px solid var(--sat-gray-border);border-radius:10px;padding:20px;transition:border-color .2s}
.doc-card:hover{border-color:var(--sat-green)}
.doc-card-header{display:flex;align-items:center;gap:12px;margin-bottom:12px}
.doc-card-header h4{font-size:14px;font-weight:700;color:var(--sat-dark);margin-bottom:2px}
.doc-card-header p{font-size:12px;color:var(--sat-text-muted)}
.doc-icon{width:38px;height:38px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0}
.file-label{display:block;cursor:pointer}
.file-hidden{display:none}
.file-box{border:2px dashed var(--sat-gray-border);border-radius:8px;padding:18px;text-align:center;background:var(--sat-gray-light);transition:border-color .2s,background .2s}
.file-box i{font-size:26px;color:var(--sat-gray);display:block;margin-bottom:6px}
.file-box p{font-size:13px;font-weight:600;color:var(--sat-dark)}
.file-box small{font-size:11px;color:var(--sat-text-muted)}
.file-label:hover .file-box,.file-box.drag{border-color:var(--sat-green);background:#f0f9f4}
.file-preview{display:flex;align-items:center;gap:10px;background:#f0f9f4;border:1px solid #b8ddd0;border-radius:6px;padding:10px 14px;margin-top:10px;font-size:13px;color:var(--sat-green-dark)}
.file-preview i{font-size:18px;color:var(--sat-green)}
.file-preview .fn{font-weight:600;flex:1;word-break:break-all}
.file-preview .frm{background:none;border:none;color:var(--sat-red);cursor:pointer;font-size:14px;flex-shrink:0}
.checklist-box{background:#f4f6f8;border-radius:8px;padding:20px;margin-top:16px}
.checklist-box h4{font-size:14px;font-weight:700;color:var(--sat-dark);margin-bottom:14px}
.checklist-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.checklist-item{display:flex;align-items:center;gap:10px;font-size:13px;color:var(--sat-text)}
.checklist-item i{color:var(--sat-green);width:18px;text-align:center}
.resumen-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.resumen-panel{background:#f4f6f8;border-radius:8px;padding:18px}
.resumen-panel h4{font-size:12px;font-weight:700;text-transform:uppercase;color:var(--sat-green);letter-spacing:.5px;margin-bottom:12px;padding-bottom:8px;border-bottom:1px solid rgba(0,0,0,.06)}
.resumen-row{display:flex;gap:8px;font-size:13px;padding:5px 0;border-bottom:1px solid rgba(0,0,0,.04)}
.resumen-row span:first-child{font-weight:600;color:var(--sat-dark);min-width:80px;flex-shrink:0}
.resumen-row span:last-child{color:var(--sat-text-muted);word-break:break-all}
.via-card{display:flex;align-items:center;gap:16px;border:2px solid var(--sat-gray-border);border-radius:10px;padding:20px;cursor:pointer;transition:border-color .2s}
.via-card.active,.via-card:hover{border-color:var(--sat-green)}
.via-icon{width:48px;height:48px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0}
.via-info{flex:1}
.via-info h3{font-size:15px;font-weight:700;color:var(--sat-dark);margin-bottom:4px;display:flex;align-items:center;gap:6px;flex-wrap:wrap}
.via-info p{font-size:13px;color:var(--sat-text-muted)}
.via-check{font-size:20px;color:var(--sat-gray-border);flex-shrink:0;transition:color .2s}
.via-check.active{color:var(--sat-green)}
@media(max-width:768px){.resumen-grid,.checklist-grid{grid-template-columns:1fr}.efirma-info-card{flex-direction:column}.via-card{flex-wrap:wrap}}
</style>
@endpush


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Mostrar/ocultar contraseña
    document.querySelectorAll('.toggle-pwd').forEach(btn => {
        btn.addEventListener('click', function () {
            const inp  = document.getElementById(this.dataset.target);
            const icon = this.querySelector('i');
            if (!inp) return;
            inp.type = inp.type === 'password' ? 'text' : 'password';
            icon.classList.toggle('fa-eye', inp.type === 'password');
            icon.classList.toggle('fa-eye-slash', inp.type === 'text');
        });
    });

    // Indicador fuerza de contraseña
    document.getElementById('pwd-clave')?.addEventListener('input', function () {
        const v = this.value;
        let s = 0;
        if (v.length >= 8) s++;
        if (/[A-Z]/.test(v)) s++;
        if (/[0-9]/.test(v)) s++;
        if (/[^A-Za-z0-9]/.test(v)) s++;
        const fill  = document.getElementById('pwd-bar-fill');
        const label = document.getElementById('pwd-bar-label');
        const c = ['#e74c3c','#e67e22','#f1c40f','#2ecc71'];
        const t = ['Muy débil','Débil','Regular','Segura'];
        if (fill)  { fill.style.width = (s * 25) + '%'; fill.style.background = c[s-1] || '#eee'; }
        if (label) label.textContent = s > 0 ? t[s-1] : 'Ingresa una contraseña segura';
    });

    // Validar que emails coincidan
    document.getElementById('email-confirm-nueva')?.addEventListener('blur', function () {
        const orig  = document.getElementById('email-nueva');
        const group = this.closest('.sat-form-group');
        if (orig && this.value && this.value !== orig.value) {
            group?.classList.add('has-error');
        } else {
            group?.classList.remove('has-error');
        }
    });

    // Cargar módulos por estado (Ajax)
    document.getElementById('estado-efirma')?.addEventListener('change', async function () {
        const sel = document.getElementById('modulo-efirma');
        sel.innerHTML = '<option value="">Cargando...</option>';
        try {
            const res  = await fetch(`/api/modulos/${encodeURIComponent(this.value)}`);
            const data = await res.json();
            sel.innerHTML = '<option value="">Seleccionar módulo...</option>';
            (Array.isArray(data) ? data : []).forEach(m => {
                sel.innerHTML += `<option value="${m.nombre}">${m.nombre} — ${m.municipio}</option>`;
            });
            if (!data.length) sel.innerHTML = '<option value="">Sin módulos disponibles</option>';
        } catch {
            sel.innerHTML = '<option value="">Error al cargar módulos</option>';
        }
    });

    // Bloquear fines de semana
    document.getElementById('fecha-efirma')?.addEventListener('change', function () {
        const d = new Date(this.value + 'T12:00:00');
        if (d.getDay() === 0 || d.getDay() === 6) {
            const g = this.closest('.sat-form-group');
            g?.classList.add('has-error');
            const e = g?.querySelector('.sat-input-error');
            if (e) { e.textContent = 'Solo días hábiles (Lunes a Viernes)'; e.style.display = 'block'; }
            this.value = '';
        }
    });

    // Rellenar resumen paso 4
    document.getElementById('btn-to-step4')?.addEventListener('click', function () {
        const f = document.getElementById('efirmaForm');
        if (!f) return;
        const v = n => f.querySelector(`[name="${n}"]`)?.value || '—';
        const map = {
            'r-rfc'    : v('rfc').toUpperCase(),
            'r-curp'   : v('curp').toUpperCase(),
            'r-nombre' : [v('primer_apellido'), v('segundo_apellido'), v('nombres')].filter(x => x !== '—').join(' ') || '—',
            'r-email'  : v('email'),
            'r-tel'    : v('telefono'),
            'r-id'     : f.querySelector('[name="tipo_identificacion"] option:checked')?.text || '—',
            'r-estado' : f.querySelector('[name="estado_modulo"] option:checked')?.text || '—',
            'r-modulo' : v('modulo_efirma'),
            'r-fecha'  : v('fecha_cita'),
            'r-horario': v('horario_cita'),
        };
        Object.entries(map).forEach(([id, val]) => {
            const el = document.getElementById(id);
            if (el) el.textContent = val;
        });
        const docs = ['f-ine','f-dom','f-curp','f-foto']
            .filter(id => document.getElementById(id)?.files?.length > 0)
            .map(id => id.replace('f-','').toUpperCase());
        const rd = document.getElementById('r-docs');
        if (rd) rd.textContent = docs.length ? docs.join(', ') : 'Ninguno';
    });

    // Drag & drop
    document.querySelectorAll('.file-box').forEach(box => {
        box.addEventListener('dragover',  e => { e.preventDefault(); box.classList.add('drag'); });
        box.addEventListener('dragleave', () => box.classList.remove('drag'));
        box.addEventListener('drop', e => {
            e.preventDefault(); box.classList.remove('drag');
            const inp = box.closest('label')?.querySelector('input[type=file]');
            if (inp && e.dataTransfer.files.length) {
                const dt = new DataTransfer();
                dt.items.add(e.dataTransfer.files[0]);
                inp.files = dt.files;
                inp.dispatchEvent(new Event('change'));
            }
        });
    });
});

// Selector de vía de renovación
function selectVia(tipo) {
    document.getElementById('via-input').value = tipo;
    const isSatid = tipo === 'satid';
    ['satid', 'modulo-ren'].forEach(id => {
        const isActive = (id === 'satid') === isSatid;
        document.getElementById('via-' + id)?.classList.toggle('active', isActive);
    });
    const cs = document.getElementById('chk-satid');
    const cm = document.getElementById('chk-modulo');
    if (cs) { cs.className = (isSatid ? 'fas fa-check-circle via-check active' : 'fas fa-circle via-check'); }
    if (cm) { cm.className = (!isSatid ? 'fas fa-check-circle via-check active' : 'fas fa-circle via-check'); }
}

// Preview de archivos
function previewFile(input, previewId, boxId) {
    const preview = document.getElementById(previewId);
    const box     = document.getElementById(boxId);
    if (!preview || !input.files?.[0]) return;
    const file   = input.files[0];
    const sizeMB = (file.size / 1024 / 1024).toFixed(2);
    const ext    = file.name.split('.').pop().toUpperCase();
    const icons  = {PDF:'fa-file-pdf', CER:'fa-certificate', JPG:'fa-file-image', JPEG:'fa-file-image', PNG:'fa-file-image'};
    const icon   = icons[ext] || 'fa-file';
    preview.style.display = 'flex';
    preview.innerHTML = `
        <i class="fas ${icon}"></i>
        <span class="fn">${file.name}</span>
        <span style="font-size:11px;color:var(--sat-text-muted);flex-shrink:0">${sizeMB} MB</span>
        <button type="button" class="frm" onclick="removeFile('${input.id}','${previewId}','${boxId}')">
            <i class="fas fa-times"></i>
        </button>`;
    if (box) {
        box.style.borderColor = 'var(--sat-green)';
        box.style.background  = '#f0f9f4';
        box.innerHTML = `<i class="fas fa-check-circle" style="font-size:26px;color:var(--sat-green);display:block;margin-bottom:6px"></i>
                         <p style="font-size:13px;font-weight:600;color:var(--sat-green)">Archivo seleccionado</p>`;
    }
}

function removeFile(inputId, previewId, boxId) {
    const inp  = document.getElementById(inputId);
    const prev = document.getElementById(previewId);
    const box  = document.getElementById(boxId);
    if (inp)  inp.value = '';
    if (prev) { prev.style.display = 'none'; prev.innerHTML = ''; }
    if (box)  {
        box.style.borderColor = '';
        box.style.background  = '';
        box.innerHTML = `<i class="fas fa-cloud-upload-alt" style="font-size:26px;display:block;margin-bottom:6px"></i>
                         <p style="font-size:13px;font-weight:600">Arrastra o haz clic</p>
                         <small>PDF, JPG, PNG · máx. 5 MB</small>`;
    }
}
</script>
@endpush