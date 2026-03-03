@extends('layouts.app')

@section('title', 'Constancia de Situación Fiscal - SAT')

@section('content')

{{-- ── Page Header ─────────────────────────────────────── --}}
<div class="sat-page-header">
    <div class="container-sat">
        <div class="sat-breadcrumb">
            <a href="{{ route('home') }}">Inicio</a>
            <span class="sat-breadcrumb-sep"><i class="fas fa-chevron-right" style="font-size:10px"></i></span>
            <a href="{{ route('personas.index') }}">Personas</a>
            <span class="sat-breadcrumb-sep"><i class="fas fa-chevron-right" style="font-size:10px"></i></span>
            <span>Constancia de Situación Fiscal</span>
        </div>
        <h1 class="sat-page-title">
            <i class="fas fa-file-invoice" style="margin-right:12px"></i>Constancia de Situación Fiscal
        </h1>
        <p class="sat-page-subtitle">Genera e imprime tu Constancia de Situación Fiscal (CIF) de forma inmediata</p>
    </div>
</div>

<section class="sat-section">
    <div class="container-sat">

        {{-- ── Layout: formulario + info lateral ──────────── --}}
        <div class="cif-layout">

            {{-- ── Columna principal: formulario ───────────── --}}
            <div class="cif-main">

                {{-- ── Tabs de acceso ──────────────────────── --}}
                <div class="sat-tabs-container">
                    <div class="sat-tabs">
                        <div class="sat-tab active" data-tab="efirma">
                            <i class="fas fa-signature"></i> Con e.firma
                        </div>
                        <div class="sat-tab" data-tab="curp">
                            <i class="fas fa-id-card"></i> Con CURP
                        </div>
                    </div>

                    {{-- ══════════════════════════════════
                         TAB 1 — Acceso con e.firma
                    ══════════════════════════════════ --}}
                    <div class="sat-tab-content active" data-tab="efirma">

                        <div class="sat-info-box">
                            <p><i class="fas fa-info-circle" style="margin-right:8px"></i>
                            Ingresa tu RFC y adjunta los archivos de tu <strong>e.firma (.cer y .key)</strong>
                            para generar tu Constancia de Situación Fiscal de forma segura.</p>
                        </div>

                        <form action="{{ route('personas.cif') }}"
                              method="POST"
                              class="sat-form-ajax"
                              id="cifEfirmaForm"
                              enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="metodo" value="efirma">

                            <div class="sat-form-section">
                                <div class="sat-form-header">
                                    <div class="sat-form-header-icon">
                                        <i class="fas fa-user-shield"></i>
                                    </div>
                                    <div class="sat-form-header-text">
                                        <h2>Identificación del contribuyente</h2>
                                        <p>Verifica tu identidad con tu e.firma para descargar la constancia</p>
                                    </div>
                                </div>

                                <div class="sat-form-body">

                                    {{-- RFC --}}
                                    <div class="sat-form-group" style="margin-bottom:24px">
                                        <label for="rfc-efirma">
                                            RFC <span class="required">*</span>
                                        </label>
                                        <div class="rfc-input-wrap">
                                            <i class="fas fa-hashtag rfc-icon"></i>
                                            <input type="text"
                                                   name="rfc"
                                                   id="rfc-efirma"
                                                   class="sat-input rfc-input"
                                                   required
                                                   placeholder="Ej: GOML850101ABC"
                                                   maxlength="13"
                                                   data-validate="rfc"
                                                   autocomplete="off"
                                                   style="text-transform:uppercase">
                                            <span class="rfc-badge" id="rfc-efirma-badge" style="display:none">
                                                <i class="fas fa-check"></i>
                                            </span>
                                        </div>
                                        <span class="sat-input-hint">13 caracteres · personas físicas y morales</span>
                                        <span class="sat-input-error">El formato del RFC no es válido</span>
                                    </div>

                                    {{-- Archivos e.firma --}}
                                    <div class="cif-efirma-files">
                                        <h3 class="cif-efirma-title">
                                            <i class="fas fa-paperclip"></i> Archivos de tu e.firma
                                        </h3>
                                        <p class="cif-efirma-desc">
                                            Los archivos <strong>.cer</strong> (certificado) y <strong>.key</strong>
                                            (clave privada) se encuentran en la USB o carpeta donde guardaste tu e.firma.
                                        </p>

                                        <div class="cif-files-grid">

                                            {{-- .cer --}}
                                            <div class="cif-file-card" id="card-cer">
                                                <div class="cif-file-header">
                                                    <div class="cif-file-icon cer">
                                                        <i class="fas fa-certificate"></i>
                                                    </div>
                                                    <div>
                                                        <h4>Certificado <span class="file-ext">.cer</span></h4>
                                                        <p>Archivo público de tu e.firma</p>
                                                    </div>
                                                </div>
                                                <label class="file-label" for="f-cer">
                                                    <input type="file"
                                                           name="archivo_cer"
                                                           id="f-cer"
                                                           accept=".cer"
                                                           class="file-hidden"
                                                           required
                                                           onchange="handleFileSelect(this,'prev-cer','box-cer','card-cer')">
                                                    <div class="file-drop-zone" id="box-cer">
                                                        <i class="fas fa-cloud-upload-alt"></i>
                                                        <span class="fdz-main">Seleccionar .cer</span>
                                                        <span class="fdz-hint">o arrastra aquí</span>
                                                    </div>
                                                </label>
                                                <div id="prev-cer" class="cif-file-preview" style="display:none"></div>
                                                <span class="sat-input-error" style="display:none;margin-top:6px">
                                                    Archivo .cer requerido
                                                </span>
                                            </div>

                                            {{-- .key --}}
                                            <div class="cif-file-card" id="card-key">
                                                <div class="cif-file-header">
                                                    <div class="cif-file-icon key">
                                                        <i class="fas fa-key"></i>
                                                    </div>
                                                    <div>
                                                        <h4>Clave privada <span class="file-ext">.key</span></h4>
                                                        <p>Archivo privado — nunca lo compartas</p>
                                                    </div>
                                                </div>
                                                <label class="file-label" for="f-key">
                                                    <input type="file"
                                                           name="archivo_key"
                                                           id="f-key"
                                                           accept=".key"
                                                           class="file-hidden"
                                                           required
                                                           onchange="handleFileSelect(this,'prev-key','box-key','card-key')">
                                                    <div class="file-drop-zone" id="box-key">
                                                        <i class="fas fa-cloud-upload-alt"></i>
                                                        <span class="fdz-main">Seleccionar .key</span>
                                                        <span class="fdz-hint">o arrastra aquí</span>
                                                    </div>
                                                </label>
                                                <div id="prev-key" class="cif-file-preview" style="display:none"></div>
                                                <span class="sat-input-error" style="display:none;margin-top:6px">
                                                    Archivo .key requerido
                                                </span>
                                            </div>

                                        </div>{{-- /grid --}}

                                        {{-- Contraseña del .key --}}
                                        <div class="sat-form-group" style="margin-top:20px">
                                            <label for="pwd-key">
                                                Contraseña de la clave privada <span class="required">*</span>
                                            </label>
                                            <div class="input-eye-wrap">
                                                <input type="password"
                                                       name="contrasena_clave"
                                                       id="pwd-key"
                                                       class="sat-input"
                                                       required
                                                       placeholder="Contraseña con la que protegiste tu .key"
                                                       autocomplete="current-password">
                                                <button type="button" class="toggle-pwd" data-target="pwd-key">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            <span class="sat-input-hint">
                                                <i class="fas fa-shield-alt" style="color:var(--sat-green)"></i>
                                                Tu contraseña nunca se almacena en nuestros servidores
                                            </span>
                                            <span class="sat-input-error">Contraseña requerida</span>
                                        </div>

                                    </div>{{-- /cif-efirma-files --}}

                                    {{-- Seguridad notice --}}
                                    <div class="cif-security-notice">
                                        <i class="fas fa-lock-open" style="font-size:18px;color:var(--sat-green)"></i>
                                        <div>
                                            <strong>Conexión segura</strong>
                                            <p>Tu e.firma se usa únicamente para autenticarte. Los archivos no quedan almacenados y la sesión se cierra automáticamente.</p>
                                        </div>
                                    </div>

                                </div>{{-- /sat-form-body --}}

                                <div class="sat-form-footer">
                                    <p class="sat-form-note">
                                        <i class="fas fa-file-pdf"></i>
                                        La constancia se descargará en formato PDF
                                    </p>
                                    <button type="submit" class="btn-sat-green btn-cif-submit" id="btn-submit-efirma">
                                        <i class="fas fa-download"></i> Generar y descargar constancia
                                    </button>
                                </div>

                            </div>{{-- /sat-form-section --}}
                        </form>

                    </div>{{-- /tab efirma --}}


                    {{-- ══════════════════════════════════
                         TAB 2 — Acceso con CURP
                    ══════════════════════════════════ --}}
                    <div class="sat-tab-content" data-tab="curp">

                        <div class="sat-info-box">
                            <p><i class="fas fa-info-circle" style="margin-right:8px"></i>
                            Puedes obtener tu Constancia de Situación Fiscal ingresando solo tu
                            <strong>RFC y CURP</strong>, sin necesidad de tu e.firma.</p>
                        </div>

                        <form action="{{ route('personas.cif') }}"
                              method="POST"
                              class="sat-form-ajax"
                              id="cifCurpForm">
                            @csrf
                            <input type="hidden" name="metodo" value="curp">

                            <div class="sat-form-section">
                                <div class="sat-form-header">
                                    <div class="sat-form-header-icon">
                                        <i class="fas fa-id-badge"></i>
                                    </div>
                                    <div class="sat-form-header-text">
                                        <h2>Identificación con RFC y CURP</h2>
                                        <p>Acceso simplificado para personas físicas</p>
                                    </div>
                                </div>

                                <div class="sat-form-body">

                                    <div class="sat-form-group" style="margin-bottom:24px">
                                        <label for="rfc-curp">RFC <span class="required">*</span></label>
                                        <div class="rfc-input-wrap">
                                            <i class="fas fa-hashtag rfc-icon"></i>
                                            <input type="text"
                                                   name="rfc"
                                                   id="rfc-curp"
                                                   class="sat-input rfc-input"
                                                   required
                                                   placeholder="Ej: GOML850101ABC"
                                                   maxlength="13"
                                                   data-validate="rfc"
                                                   autocomplete="off"
                                                   style="text-transform:uppercase">
                                            <span class="rfc-badge" id="rfc-curp-badge" style="display:none">
                                                <i class="fas fa-check"></i>
                                            </span>
                                        </div>
                                        <span class="sat-input-hint">13 caracteres, sin guiones ni espacios</span>
                                        <span class="sat-input-error">El formato del RFC no es válido</span>
                                    </div>

                                    <div class="sat-form-group" style="margin-bottom:24px">
                                        <label for="curp-input">CURP <span class="required">*</span></label>
                                        <div class="rfc-input-wrap">
                                            <i class="fas fa-id-card rfc-icon"></i>
                                            <input type="text"
                                                   name="curp"
                                                   id="curp-input"
                                                   class="sat-input rfc-input"
                                                   required
                                                   placeholder="Ej: GOML850101HTCNRL09"
                                                   maxlength="18"
                                                   data-validate="curp"
                                                   autocomplete="off"
                                                   style="text-transform:uppercase">
                                            <span class="rfc-badge" id="curp-badge" style="display:none">
                                                <i class="fas fa-check"></i>
                                            </span>
                                        </div>
                                        <span class="sat-input-hint">
                                            18 caracteres ·
                                            <a href="https://www.gob.mx/curp" target="_blank"
                                               style="color:var(--sat-green)">Consulta tu CURP aquí</a>
                                        </span>
                                        <span class="sat-input-error">El formato del CURP no es válido</span>
                                    </div>

                                    {{-- Captcha simulado --}}
                                    <div class="cif-captcha-box">
                                        <label class="sat-checkbox">
                                            <input type="checkbox" name="no_soy_robot" id="captcha-check" required>
                                            <span class="sat-checkbox-label">No soy un robot</span>
                                        </label>
                                        <div class="captcha-logo">
                                            <i class="fas fa-shield-alt" style="font-size:22px;color:#4a90d9"></i>
                                            <span>reCAPTCHA</span>
                                            <small>Privacidad - Términos</small>
                                        </div>
                                    </div>

                                </div>{{-- /sat-form-body --}}

                                <div class="sat-form-footer">
                                    <p class="sat-form-note">
                                        <i class="fas fa-file-pdf"></i>
                                        La constancia se descargará en formato PDF
                                    </p>
                                    <button type="submit" class="btn-sat-green btn-cif-submit" id="btn-submit-curp">
                                        <i class="fas fa-download"></i> Generar y descargar constancia
                                    </button>
                                </div>

                            </div>{{-- /sat-form-section --}}
                        </form>

                    </div>{{-- /tab curp --}}

                </div>{{-- /tabs-container --}}

                {{-- ── Resultado de descarga (se muestra tras generar) ── --}}
                @if(isset($constanciaGenerada) && $constanciaGenerada)
                <div class="cif-success-card" id="cif-resultado">
                    <div class="cif-success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="cif-success-body">
                        <h3>¡Constancia generada exitosamente!</h3>
                        <p>Tu Constancia de Situación Fiscal está lista. Puedes descargarla o imprimirla directamente.</p>
                        <div class="cif-success-meta">
                            <span><i class="fas fa-user"></i> {{ $rfc ?? 'RFC' }}</span>
                            <span><i class="fas fa-calendar"></i> {{ now()->format('d/m/Y H:i') }} hrs</span>
                            <span><i class="fas fa-file-pdf" style="color:var(--sat-red)"></i> PDF · 1 página</span>
                        </div>
                        <div class="cif-success-actions">
                            <a href="{{ $pdfUrl ?? '#' }}" class="btn-sat-green" target="_blank">
                                <i class="fas fa-download"></i> Descargar PDF
                            </a>
                            <button type="button" class="btn-sat-outline" onclick="window.print()">
                                <i class="fas fa-print"></i> Imprimir
                            </button>
                            <button type="button" class="btn-sat-outline" onclick="compartirConstancia()">
                                <i class="fas fa-share-alt"></i> Compartir
                            </button>
                        </div>
                    </div>
                </div>
                @endif

            </div>{{-- /cif-main --}}


            {{-- ── Columna lateral: información ─────────────── --}}
            <aside class="cif-aside">

                {{-- ¿Qué es la CIF? --}}
                <div class="cif-aside-card">
                    <h3 class="cif-aside-title">
                        <i class="fas fa-question-circle"></i> ¿Qué es la CIF?
                    </h3>
                    <p class="cif-aside-text">
                        La <strong>Constancia de Situación Fiscal</strong> es un documento oficial emitido por el SAT
                        que acredita tu registro en el RFC y contiene tus datos fiscales actualizados.
                    </p>
                    <ul class="cif-aside-list">
                        <li><i class="fas fa-check"></i> Nombre o razón social</li>
                        <li><i class="fas fa-check"></i> RFC y CURP</li>
                        <li><i class="fas fa-check"></i> Régimen fiscal</li>
                        <li><i class="fas fa-check"></i> Domicilio fiscal</li>
                        <li><i class="fas fa-check"></i> Obligaciones fiscales</li>
                        <li><i class="fas fa-check"></i> Actividades económicas</li>
                    </ul>
                </div>

                {{-- ¿Para qué sirve? --}}
                <div class="cif-aside-card">
                    <h3 class="cif-aside-title">
                        <i class="fas fa-clipboard-check"></i> ¿Para qué sirve?
                    </h3>
                    <div class="cif-uso-list">
                        @foreach([
                            ['fas fa-building',      'Trámites empresariales y contratos'],
                            ['fas fa-university',    'Apertura de cuentas bancarias'],
                            ['fas fa-briefcase',     'Solicitudes de empleo'],
                            ['fas fa-file-contract', 'Emisión y recepción de CFDI'],
                            ['fas fa-hand-holding-usd','Devoluciones y compensaciones'],
                            ['fas fa-home',          'Trámites de crédito hipotecario'],
                        ] as [$ico, $uso])
                        <div class="cif-uso-item">
                            <i class="{{ $ico }}"></i>
                            <span>{{ $uso }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Vigencia --}}
                <div class="cif-aside-card cif-vigencia-card">
                    <div class="cif-vigencia-header">
                        <i class="fas fa-clock"></i>
                        <h3>Vigencia de la CIF</h3>
                    </div>
                    <p class="cif-aside-text">
                        La Constancia de Situación Fiscal <strong>no tiene fecha de vencimiento</strong>,
                        sin embargo algunas instituciones solicitan que no tenga más de
                        <strong>3 meses de antigüedad</strong>.
                    </p>
                    <div class="cif-vigencia-tip">
                        <i class="fas fa-lightbulb"></i>
                        Puedes generarla cuantas veces necesites, sin costo y de forma inmediata.
                    </div>
                </div>

                {{-- Ayuda --}}
                <div class="cif-aside-card cif-help-card">
                    <h3 class="cif-aside-title" style="color:white">
                        <i class="fas fa-headset"></i> ¿Necesitas ayuda?
                    </h3>
                    <p style="font-size:13px;color:rgba(255,255,255,.85);margin-bottom:16px">
                        Si tienes problemas para obtener tu constancia, contáctanos:
                    </p>
                    <a href="tel:5562722728" class="cif-help-tel">
                        <i class="fas fa-phone-alt"></i> 55 627 22 728
                    </a>
                    <p style="font-size:12px;color:rgba(255,255,255,.7);margin-top:8px;text-align:center">
                        Lunes a Viernes 8:00 – 21:00 hrs<br>Sábados 8:00 – 15:00 hrs
                    </p>
                    <a href="{{ route('contacto.index') }}" class="cif-help-chat">
                        <i class="fas fa-comments"></i> Chat en línea
                    </a>
                </div>

            </aside>
        </div>{{-- /cif-layout --}}

    </div>
</section>

@endsection


@push('styles')
<style>
/* ── Layout ──────────────────────────────────────── */
.cif-layout {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 32px;
    align-items: start;
}

/* ── RFC input con ícono ─────────────────────────── */
.rfc-input-wrap {
    position: relative;
    display: flex;
    align-items: center;
}
.rfc-icon {
    position: absolute;
    left: 14px;
    color: var(--sat-gray);
    font-size: 14px;
    pointer-events: none;
    z-index: 1;
}
.rfc-input {
    padding-left: 38px !important;
    padding-right: 44px !important;
    font-family: 'Courier New', monospace;
    font-size: 16px !important;
    letter-spacing: 1px;
    font-weight: 600;
}
.rfc-badge {
    position: absolute;
    right: 12px;
    width: 24px;
    height: 24px;
    background: var(--sat-green);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    animation: popIn .2s ease;
}
@keyframes popIn { from { transform: scale(0); } to { transform: scale(1); } }

/* ── e.firma files section ───────────────────────── */
.cif-efirma-files {
    background: #f4f6f8;
    border-radius: 10px;
    padding: 22px;
    margin-top: 4px;
}
.cif-efirma-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--sat-dark);
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.cif-efirma-desc {
    font-size: 13px;
    color: var(--sat-text-muted);
    margin-bottom: 18px;
}
.cif-files-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
.cif-file-card {
    background: white;
    border: 1.5px solid var(--sat-gray-border);
    border-radius: 10px;
    padding: 18px;
    transition: border-color .2s, box-shadow .2s;
}
.cif-file-card.has-file {
    border-color: var(--sat-green);
    box-shadow: 0 2px 10px rgba(0,104,71,.08);
}
.cif-file-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 14px;
}
.cif-file-header h4 {
    font-size: 14px;
    font-weight: 700;
    color: var(--sat-dark);
    margin-bottom: 2px;
}
.cif-file-header p {
    font-size: 12px;
    color: var(--sat-text-muted);
}
.cif-file-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}
.cif-file-icon.cer { background: rgba(245,168,0,.12); color: var(--sat-gold); }
.cif-file-icon.key { background: rgba(0,104,71,.1);   color: var(--sat-green); }
.file-ext {
    font-family: monospace;
    font-size: 13px;
    background: var(--sat-gray-light);
    border: 1px solid var(--sat-gray-border);
    padding: 1px 6px;
    border-radius: 4px;
    color: var(--sat-text-muted);
    margin-left: 4px;
}

/* ── Drop zone ───────────────────────────────────── */
.file-label { display: block; cursor: pointer; }
.file-hidden { display: none; }
.file-drop-zone {
    border: 2px dashed var(--sat-gray-border);
    border-radius: 8px;
    padding: 16px;
    text-align: center;
    background: var(--sat-gray-light);
    transition: border-color .2s, background .2s;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
}
.file-drop-zone i {
    font-size: 24px;
    color: var(--sat-gray);
    margin-bottom: 4px;
}
.fdz-main { font-size: 13px; font-weight: 600; color: var(--sat-dark); }
.fdz-hint  { font-size: 12px; color: var(--sat-text-muted); }
.file-label:hover .file-drop-zone,
.file-drop-zone.drag {
    border-color: var(--sat-green);
    background: #f0f9f4;
}
.file-drop-zone.drag { background: #e0f5ea; }

/* ── File preview ────────────────────────────────── */
.cif-file-preview {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f0f9f4;
    border: 1px solid #b8ddd0;
    border-radius: 6px;
    padding: 8px 12px;
    margin-top: 10px;
    font-size: 12px;
    color: var(--sat-dark);
}
.cif-file-preview i { color: var(--sat-green); font-size: 16px; flex-shrink: 0; }
.cif-file-preview .pfn { font-weight: 600; flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.cif-file-preview .psz { color: var(--sat-text-muted); flex-shrink: 0; }
.cif-file-preview .prm { background: none; border: none; color: var(--sat-red); cursor: pointer; font-size: 13px; flex-shrink: 0; padding: 0; }

/* ── Password input ──────────────────────────────── */
.input-eye-wrap { position: relative; }
.input-eye-wrap .sat-input { padding-right: 44px; }
.toggle-pwd {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--sat-gray);
    cursor: pointer;
    font-size: 15px;
    padding: 0;
    line-height: 1;
}

/* ── Security notice ─────────────────────────────── */
.cif-security-notice {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    background: #f0f9f4;
    border: 1px solid #b8ddd0;
    border-radius: 8px;
    padding: 14px 18px;
    margin-top: 20px;
    font-size: 13px;
    color: var(--sat-text);
}
.cif-security-notice strong { font-size: 13px; font-weight: 700; color: var(--sat-dark); display: block; margin-bottom: 2px; }
.cif-security-notice p { margin: 0; color: var(--sat-text-muted); }

/* ── CAPTCHA box ─────────────────────────────────── */
.cif-captcha-box {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #f8f9fa;
    border: 1px solid var(--sat-gray-border);
    border-radius: 4px;
    padding: 14px 18px;
    margin-top: 4px;
}
.captcha-logo {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
    font-size: 11px;
    color: var(--sat-text-muted);
    font-weight: 600;
}
.captcha-logo small { font-size: 10px; font-weight: 400; }

/* ── Submit btn ──────────────────────────────────── */
.btn-cif-submit {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 15px;
    padding: 12px 28px;
    min-width: 260px;
    justify-content: center;
    position: relative;
    overflow: hidden;
}
.btn-cif-submit .spinner {
    display: none;
    width: 16px;
    height: 16px;
    border: 2px solid rgba(255,255,255,.4);
    border-top-color: white;
    border-radius: 50%;
    animation: spin .6s linear infinite;
}
.btn-cif-submit.loading .spinner { display: block; }
.btn-cif-submit.loading .btn-icon { display: none; }
@keyframes spin { to { transform: rotate(360deg); } }

/* ── Success card ────────────────────────────────── */
.cif-success-card {
    display: flex;
    gap: 20px;
    background: white;
    border: 2px solid var(--sat-green);
    border-radius: 12px;
    padding: 28px;
    margin-top: 24px;
    animation: slideIn .3s ease;
}
@keyframes slideIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
.cif-success-icon {
    font-size: 48px;
    color: var(--sat-green);
    flex-shrink: 0;
    line-height: 1;
}
.cif-success-body h3 {
    font-size: 18px;
    font-weight: 700;
    color: var(--sat-dark);
    margin-bottom: 6px;
}
.cif-success-body p { font-size: 14px; color: var(--sat-text-muted); margin-bottom: 14px; }
.cif-success-meta {
    display: flex;
    gap: 18px;
    flex-wrap: wrap;
    font-size: 13px;
    color: var(--sat-text);
    background: var(--sat-gray-light);
    border-radius: 6px;
    padding: 10px 14px;
    margin-bottom: 18px;
}
.cif-success-meta span { display: flex; align-items: center; gap: 6px; }
.cif-success-actions { display: flex; gap: 10px; flex-wrap: wrap; }

/* ── Aside ───────────────────────────────────────── */
.cif-aside { display: flex; flex-direction: column; gap: 20px; }

.cif-aside-card {
    background: white;
    border: 1px solid var(--sat-gray-border);
    border-radius: 12px;
    padding: 22px;
}
.cif-aside-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--sat-dark);
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.cif-aside-title i { color: var(--sat-green); }
.cif-aside-text { font-size: 13px; color: var(--sat-text-muted); line-height: 1.6; margin-bottom: 14px; }
.cif-aside-list {
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 7px;
    font-size: 13px;
    color: var(--sat-text);
}
.cif-aside-list li { display: flex; align-items: center; gap: 8px; }
.cif-aside-list i { color: var(--sat-green); font-size: 11px; width: 14px; flex-shrink: 0; }

.cif-uso-list { display: flex; flex-direction: column; gap: 9px; }
.cif-uso-item { display: flex; align-items: center; gap: 10px; font-size: 13px; color: var(--sat-text); }
.cif-uso-item i { color: var(--sat-green); width: 18px; text-align: center; flex-shrink: 0; }

.cif-vigencia-card { background: #f0f9f4; border-color: #b8ddd0; }
.cif-vigencia-header { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
.cif-vigencia-header i { font-size: 18px; color: var(--sat-green); }
.cif-vigencia-header h3 { font-size: 14px; font-weight: 700; color: var(--sat-dark); }
.cif-vigencia-tip {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    background: white;
    border-radius: 6px;
    padding: 10px 14px;
    font-size: 12px;
    color: var(--sat-text-muted);
    margin-top: 10px;
    border: 1px solid #b8ddd0;
}
.cif-vigencia-tip i { color: var(--sat-gold); flex-shrink: 0; margin-top: 1px; }

.cif-help-card {
    background: linear-gradient(135deg, var(--sat-green) 0%, #004d35 100%);
    border: none;
}
.cif-help-tel {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    background: white;
    color: var(--sat-green);
    font-size: 18px;
    font-weight: 700;
    border-radius: 8px;
    padding: 12px;
    text-decoration: none;
    transition: background .2s;
}
.cif-help-tel:hover { background: #e8f7f0; }
.cif-help-chat {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: rgba(255,255,255,.15);
    color: white;
    font-size: 14px;
    font-weight: 600;
    border-radius: 8px;
    padding: 10px;
    text-decoration: none;
    margin-top: 10px;
    transition: background .2s;
    border: 1px solid rgba(255,255,255,.25);
}
.cif-help-chat:hover { background: rgba(255,255,255,.25); }

/* ── Responsive ──────────────────────────────────── */
@media (max-width: 900px) {
    .cif-layout { grid-template-columns: 1fr; }
    .cif-aside  { order: -1; display: grid; grid-template-columns: 1fr 1fr; }
    .cif-help-card { grid-column: span 2; }
}
@media (max-width: 640px) {
    .cif-files-grid { grid-template-columns: 1fr; }
    .cif-aside      { grid-template-columns: 1fr; }
    .cif-help-card  { grid-column: span 1; }
    .cif-success-card { flex-direction: column; gap: 14px; }
    .btn-cif-submit { min-width: unset; width: 100%; }
}
</style>
@endpush


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Validación en tiempo real del RFC ───────────────────
    const rfcRegex = /^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/i;
    const curpRegex = /^[A-Z]{4}\d{6}[HM][A-Z]{5}[A-Z0-9]\d$/i;

    ['rfc-efirma', 'rfc-curp'].forEach(inputId => {
        const inp   = document.getElementById(inputId);
        const badge = document.getElementById(inputId + '-badge');
        if (!inp) return;

        inp.addEventListener('input', function () {
            this.value = this.value.toUpperCase().replace(/[^A-ZÑ&0-9]/g, '');
            const valid = rfcRegex.test(this.value);
            const group = this.closest('.sat-form-group');
            if (badge) badge.style.display = valid ? 'flex' : 'none';
            if (group) {
                group.classList.toggle('has-error',   this.value.length > 0 && !valid);
                group.classList.toggle('has-success',  valid);
            }
        });
    });

    // ── Validación del CURP ─────────────────────────────────
    const curpInp   = document.getElementById('curp-input');
    const curpBadge = document.getElementById('curp-badge');
    if (curpInp) {
        curpInp.addEventListener('input', function () {
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            const valid = curpRegex.test(this.value);
            const group = this.closest('.sat-form-group');
            if (curpBadge) curpBadge.style.display = valid ? 'flex' : 'none';
            if (group) {
                group.classList.toggle('has-error',  this.value.length > 0 && !valid);
                group.classList.toggle('has-success', valid);
            }
        });
    }

    // ── Mostrar/ocultar contraseña ──────────────────────────
    document.querySelectorAll('.toggle-pwd').forEach(btn => {
        btn.addEventListener('click', function () {
            const inp  = document.getElementById(this.dataset.target);
            const icon = this.querySelector('i');
            if (!inp) return;
            inp.type = inp.type === 'password' ? 'text' : 'password';
            icon.classList.toggle('fa-eye',       inp.type === 'password');
            icon.classList.toggle('fa-eye-slash', inp.type === 'text');
        });
    });

    // ── Drag & drop en drop zones ───────────────────────────
    document.querySelectorAll('.file-drop-zone').forEach(zone => {
        zone.addEventListener('dragover', e => {
            e.preventDefault();
            zone.classList.add('drag');
        });
        zone.addEventListener('dragleave', () => zone.classList.remove('drag'));
        zone.addEventListener('drop', e => {
            e.preventDefault();
            zone.classList.remove('drag');
            const inp = zone.closest('label')?.querySelector('input[type=file]');
            if (inp && e.dataTransfer.files.length) {
                const dt = new DataTransfer();
                dt.items.add(e.dataTransfer.files[0]);
                inp.files = dt.files;
                inp.dispatchEvent(new Event('change'));
            }
        });
    });

    // ── Botón de loading al enviar ──────────────────────────
    ['cifEfirmaForm', 'cifCurpForm'].forEach(formId => {
        const form = document.getElementById(formId);
        if (!form) return;
        form.addEventListener('submit', function () {
            const btn = this.querySelector('.btn-cif-submit');
            if (btn) {
                btn.classList.add('loading');
                btn.disabled = true;
                const icon = btn.querySelector('.btn-icon');
                if (!btn.querySelector('.spinner')) {
                    btn.insertAdjacentHTML('afterbegin',
                        '<span class="spinner"></span>');
                }
                // Restaurar si hay error (fallback 8 seg)
                setTimeout(() => {
                    btn.classList.remove('loading');
                    btn.disabled = false;
                }, 8000);
            }
        });
    });

    // ── Scroll al resultado si existe ──────────────────────
    const resultado = document.getElementById('cif-resultado');
    if (resultado) {
        resultado.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});

// ── Manejo de selección/previsualización de archivo ──────
function handleFileSelect(input, previewId, boxId, cardId) {
    const preview = document.getElementById(previewId);
    const box     = document.getElementById(boxId);
    const card    = document.getElementById(cardId);

    if (!input.files?.[0]) return;

    const file   = input.files[0];
    const sizeMB = (file.size / 1024 / 1024).toFixed(2);
    const ext    = file.name.split('.').pop().toUpperCase();
    const icon   = ext === 'CER' ? 'fa-certificate' : ext === 'KEY' ? 'fa-key' : 'fa-file';

    // Preview
    if (preview) {
        preview.style.display = 'flex';
        preview.innerHTML = `
            <i class="fas ${icon}"></i>
            <span class="pfn" title="${file.name}">${file.name}</span>
            <span class="psz">${sizeMB} MB</span>
            <button type="button" class="prm"
                    onclick="removeFile('${input.id}','${previewId}','${boxId}','${cardId}')"
                    title="Quitar archivo">
                <i class="fas fa-times"></i>
            </button>`;
    }

    // Actualizar drop zone
    if (box) {
        box.style.borderColor = 'var(--sat-green)';
        box.style.background  = '#f0f9f4';
        box.innerHTML = `
            <i class="fas fa-check-circle" style="color:var(--sat-green);font-size:24px;margin-bottom:4px"></i>
            <span class="fdz-main" style="color:var(--sat-green)">Archivo cargado</span>
            <span class="fdz-hint">${file.name}</span>`;
    }

    // Marcar card con borde verde
    if (card) card.classList.add('has-file');
}

function removeFile(inputId, previewId, boxId, cardId) {
    const inp     = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    const box     = document.getElementById(boxId);
    const card    = document.getElementById(cardId);

    if (inp)  inp.value = '';
    if (preview) { preview.style.display = 'none'; preview.innerHTML = ''; }
    if (card) card.classList.remove('has-file');

    if (box) {
        box.style.borderColor = '';
        box.style.background  = '';
        const isCer = inputId === 'f-cer';
        box.innerHTML = `
            <i class="fas fa-cloud-upload-alt"></i>
            <span class="fdz-main">Seleccionar .${isCer ? 'cer' : 'key'}</span>
            <span class="fdz-hint">o arrastra aquí</span>`;
    }
}

// ── Compartir constancia (Web Share API) ─────────────────
function compartirConstancia() {
    if (navigator.share) {
        navigator.share({
            title: 'Constancia de Situación Fiscal',
            text:  'Mi Constancia de Situación Fiscal del SAT',
            url:   window.location.href,
        }).catch(() => {});
    } else {
        // Fallback: copiar URL
        navigator.clipboard?.writeText(window.location.href).then(() => {
            alert('Enlace copiado al portapapeles');
        });
    }
}
</script>
@endpush