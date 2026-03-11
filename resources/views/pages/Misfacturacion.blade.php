@extends('layouts.dashboard-layout')

@section('title', 'Facturas CFDI')

@push('styles')
<style>
.page-title{font-size:26px;font-weight:700;color:white;margin-bottom:4px}
.page-sub{font-size:14px;color:rgba(255,255,255,.45);margin-bottom:28px}

/* ── Alert ── */
.alert-success{background:rgba(0,104,71,.15);border:1px solid rgba(74,222,128,.25);border-radius:10px;padding:12px 16px;display:flex;align-items:center;gap:10px;font-size:13px;color:#4ade80;margin-bottom:20px;animation:slideDown .3s ease}
.alert-error{background:rgba(200,16,46,.12);border:1px solid rgba(248,113,113,.25);border-radius:10px;padding:12px 16px;display:flex;align-items:center;gap:10px;font-size:13px;color:#f87171;margin-bottom:20px;animation:slideDown .3s ease}
@keyframes slideDown{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:translateY(0)}}

/* ── Stepper ── */
.stepper{display:flex;align-items:center;gap:0;margin-bottom:32px;background:#0d1520;border:1px solid rgba(255,255,255,.07);border-radius:14px;padding:6px;overflow-x:auto}
.step{display:flex;align-items:center;gap:10px;padding:12px 20px;border-radius:10px;cursor:pointer;transition:all .2s;flex:1;min-width:140px;white-space:nowrap;border:none;background:transparent;font-family:inherit;color:rgba(255,255,255,.4);font-size:13px;font-weight:600}
.step:hover:not(.disabled){background:rgba(255,255,255,.04);color:rgba(255,255,255,.7)}
.step.active{background:rgba(0,104,71,.2);color:#4ade80}
.step.completed{color:rgba(255,255,255,.6)}
.step.disabled{cursor:not-allowed;opacity:.4}
.step-num{width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;background:rgba(255,255,255,.08);color:rgba(255,255,255,.4);transition:all .2s}
.step.active .step-num{background:rgba(0,104,71,.4);color:#4ade80}
.step.completed .step-num{background:rgba(74,222,128,.15);color:#4ade80}
.step-divider{width:1px;height:30px;background:rgba(255,255,255,.07);flex-shrink:0}

/* ── Panel ── */
.panel-card{background:#0d1520;border:1px solid rgba(255,255,255,.07);border-radius:14px;padding:24px;margin-bottom:24px}
.panel-hero{background:linear-gradient(135deg,#006847 0%,#004d35 100%);border-radius:12px;padding:22px 24px;margin-bottom:24px;display:flex;align-items:center;gap:16px;position:relative;overflow:hidden}
.panel-hero::after{content:'';position:absolute;right:-30px;top:-30px;width:140px;height:140px;background:rgba(255,255,255,.05);border-radius:50%}
.panel-hero-icon{width:46px;height:46px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;color:white;flex-shrink:0;z-index:1}
.panel-hero-info{z-index:1}
.panel-hero-title{font-size:18px;font-weight:700;color:white}
.panel-hero-sub{font-size:13px;color:rgba(255,255,255,.65);margin-top:3px}

/* ── Form ── */
.form-section{margin-bottom:26px}
.form-section-title{font-size:13px;font-weight:700;color:rgba(255,255,255,.55);text-transform:uppercase;letter-spacing:.5px;margin-bottom:16px;display:flex;align-items:center;gap:8px}
.form-section-title::after{content:'';flex:1;height:1px;background:rgba(255,255,255,.06)}
.form-grid-3{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
.form-grid-2{display:grid;grid-template-columns:repeat(2,1fr);gap:16px}
.form-group{display:flex;flex-direction:column;gap:6px}
.form-label{font-size:12px;font-weight:600;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:.4px}
.form-label .req,.req{color:#f87171;margin-left:2px}
.form-input{width:100%;font-size:14px;color:white;padding:10px 14px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:8px;outline:none;transition:all .2s;font-family:inherit}
.form-input::placeholder{color:rgba(255,255,255,.2)}
.form-input:focus{background:rgba(255,255,255,.08);border-color:rgba(0,104,71,.5);box-shadow:0 0 0 3px rgba(0,104,71,.1)}
.form-input:read-only{opacity:.5;cursor:not-allowed}
select.form-input{cursor:pointer}
select.form-input option{background:#1a2535;color:white}
.field-hint{font-size:11px;color:rgba(255,255,255,.25);margin-top:2px}
.input-prefix{position:absolute;left:13px;top:50%;transform:translateY(-50%);font-size:14px;font-weight:600;color:rgba(255,255,255,.3);pointer-events:none}
.form-input-wrap{position:relative}
.form-input.has-prefix{padding-left:28px}

/* ── Conceptos ── */
.conceptos-wrap{border:1px solid rgba(255,255,255,.07);border-radius:10px;overflow:hidden;margin-bottom:16px}
.conceptos-head{display:grid;grid-template-columns:2fr 3fr 1.5fr 1fr 1.5fr 1.5fr 40px;gap:12px;padding:10px 14px;background:rgba(255,255,255,.04);font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:rgba(255,255,255,.3)}
.concepto-row{display:grid;grid-template-columns:2fr 3fr 1.5fr 1fr 1.5fr 1.5fr 40px;gap:12px;padding:12px 14px;border-top:1px solid rgba(255,255,255,.04);align-items:center}
.concepto-row .form-input{padding:8px 10px;font-size:13px}
.concepto-row .form-input.has-prefix{padding-left:22px}
.concepto-row .input-prefix{font-size:13px;left:8px}
.importe-cell{font-size:14px;font-weight:700;color:white;font-family:monospace}
.btn-remove-row{width:30px;height:30px;background:rgba(248,113,113,.08);border:1px solid rgba(248,113,113,.15);border-radius:6px;color:rgba(248,113,113,.6);cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:12px;transition:all .2s;flex-shrink:0}
.btn-remove-row:hover{background:rgba(248,113,113,.15);color:#f87171}

/* ── Totales ── */
.totales-wrap{display:flex;justify-content:flex-end}
.totales-box{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:10px;padding:16px 20px;min-width:280px}
.totales-row{display:flex;justify-content:space-between;align-items:center;padding:6px 0;font-size:14px}
.totales-row.total{border-top:1px solid rgba(255,255,255,.08);margin-top:4px;padding-top:12px;font-size:18px;font-weight:700;color:#4ade80}
.totales-label{color:rgba(255,255,255,.45)}
.totales-val{font-family:monospace;color:white;font-weight:600}

/* ── Buttons ── */
.btn{font-size:13px;font-weight:700;padding:10px 22px;border-radius:8px;border:none;cursor:pointer;display:inline-flex;align-items:center;gap:7px;transition:all .2s;font-family:inherit;text-decoration:none}
.btn-outline{background:transparent;border:1px solid rgba(255,255,255,.15);color:rgba(255,255,255,.6)}
.btn-outline:hover{background:rgba(255,255,255,.06);color:white}
.btn-primary{background:linear-gradient(135deg,#006847,#00875a);color:white}
.btn-primary:hover{background:linear-gradient(135deg,#007a54,#009966);box-shadow:0 4px 16px rgba(0,104,71,.35)}
.btn-danger{background:linear-gradient(135deg,#c8102e,#a00d24);color:white}
.btn-danger:hover{background:linear-gradient(135deg,#e01235,#c8102e);box-shadow:0 4px 16px rgba(200,16,46,.3)}
.btn-add{display:inline-flex;align-items:center;gap:7px;padding:8px 16px;background:rgba(0,104,71,.15);border:1px dashed rgba(74,222,128,.2);border-radius:8px;color:#4ade80;font-size:13px;font-weight:600;cursor:pointer;transition:all .2s;font-family:inherit}
.btn-add:hover{background:rgba(0,104,71,.25);border-color:rgba(74,222,128,.35)}

/* ── Step footer ── */
.step-footer{display:flex;align-items:center;justify-content:space-between;padding:20px 0 0;border-top:1px solid rgba(255,255,255,.07);margin-top:8px;flex-wrap:wrap;gap:12px}
.step-footer-left{display:flex;align-items:center;gap:10px}

/* ── Vista previa ── */
.preview-card{background:white;border-radius:12px;padding:32px;color:#1a1a1a;margin-bottom:20px}
.preview-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:24px;padding-bottom:20px;border-bottom:2px solid #006847}
.preview-logo{font-size:22px;font-weight:700;color:#006847}
.preview-logo small{display:block;font-size:11px;color:#666;font-weight:400;margin-top:2px}
.preview-folio{text-align:right}
.preview-folio .num{font-size:24px;font-weight:700;color:#1a1a1a;font-family:monospace}
.preview-folio small{display:block;font-size:11px;color:#999;margin-top:2px}
.preview-parties{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px}
.preview-party h4{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#006847;margin-bottom:8px}
.preview-party p{font-size:13px;color:#333;margin-bottom:3px}
.preview-party .rfc{font-family:monospace;font-size:12px;color:#666}
.preview-table{width:100%;border-collapse:collapse;margin-bottom:20px;font-size:13px}
.preview-table th{background:#f8faf8;padding:8px 12px;text-align:left;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:#006847;border-bottom:2px solid #e0ede7}
.preview-table td{padding:10px 12px;border-bottom:1px solid #f0f0f0;color:#333}
.preview-table tr:last-child td{border-bottom:none}
.preview-totales{display:flex;justify-content:flex-end}
.preview-totales-box{min-width:220px}
.preview-total-row{display:flex;justify-content:space-between;padding:5px 0;font-size:13px;color:#555}
.preview-total-row.final{border-top:2px solid #006847;margin-top:4px;padding-top:10px;font-size:16px;font-weight:700;color:#006847}
.preview-uuid{margin-top:16px;padding:10px 14px;background:#f8faf8;border-radius:6px;font-size:11px;color:#666;font-family:monospace;word-break:break-all}
.preview-uuid strong{color:#006847;display:block;margin-bottom:2px;font-family:'Source Sans Pro',sans-serif;font-size:10px;text-transform:uppercase;letter-spacing:.5px}

/* ── Tabla mis facturas ── */
.table-wrap{overflow-x:auto}
table{width:100%;border-collapse:collapse;min-width:600px}
thead tr th{padding:10px 14px;text-align:left;font-size:11px;font-weight:700;color:rgba(255,255,255,.3);text-transform:uppercase;letter-spacing:.5px;border-bottom:1px solid rgba(255,255,255,.06)}
tbody tr{border-bottom:1px solid rgba(255,255,255,.04);transition:background .15s}
tbody tr:last-child{border-bottom:none}
tbody tr:hover{background:rgba(255,255,255,.03)}
tbody td{padding:13px 14px;font-size:13px;color:rgba(255,255,255,.75)}
.td-mono{font-family:monospace;font-size:12px;color:rgba(255,255,255,.5)}
.td-bold{font-weight:700;color:white}
.td-green{color:#4ade80;font-weight:700}
.badge{font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;display:inline-flex;align-items:center;gap:5px;white-space:nowrap}
.badge-vigente{background:rgba(74,222,128,.12);color:#4ade80;border:1px solid rgba(74,222,128,.2)}
.badge-cancelado{background:rgba(248,113,113,.12);color:#f87171;border:1px solid rgba(248,113,113,.2)}
.act-btn{width:30px;height:30px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.07);border-radius:6px;display:inline-flex;align-items:center;justify-content:center;cursor:pointer;transition:all .2s;text-decoration:none;color:rgba(255,255,255,.4);font-size:12px}
.act-btn:hover{background:rgba(255,255,255,.1);color:white}
.act-btn.green:hover{background:rgba(74,222,128,.15);color:#4ade80;border-color:rgba(74,222,128,.2)}
.empty-state{text-align:center;padding:48px 20px;color:rgba(255,255,255,.25)}
.empty-state i{font-size:40px;margin-bottom:14px;display:block;opacity:.3}

/* ── Cancelar ── */
.cancel-confirm{background:rgba(200,16,46,.08);border:1px solid rgba(248,113,113,.2);border-radius:12px;padding:28px;text-align:center;margin-bottom:24px}
.cancel-confirm i{font-size:48px;color:#f87171;margin-bottom:16px;display:block}
.cancel-confirm h3{font-size:20px;font-weight:700;color:white;margin-bottom:8px}
.cancel-confirm p{font-size:14px;color:rgba(255,255,255,.5);line-height:1.6;max-width:480px;margin:0 auto 24px}
.cancel-confirm-btns{display:flex;gap:12px;justify-content:center;flex-wrap:wrap}

/* ── Spinner ── */
.spinner{display:inline-block;width:16px;height:16px;border:2px solid rgba(255,255,255,.3);border-top-color:white;border-radius:50%;animation:spin .7s linear infinite}
@keyframes spin{to{transform:rotate(360deg)}}

@media(max-width:900px){.form-grid-3{grid-template-columns:1fr 1fr}.conceptos-head,.concepto-row{grid-template-columns:1fr 2fr 1fr 1fr 1fr 1fr 36px}}
@media(max-width:640px){.form-grid-2,.form-grid-3{grid-template-columns:1fr}.stepper{flex-direction:column;gap:4px}.step-divider{display:none}}
</style>
@endpush

@section('content')

<div class="page-title">Facturas CFDI</div>
<div class="page-sub">Emite, verifica y gestiona tus comprobantes fiscales digitales.</div>

@if(session('success'))
 <div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
 <div class="alert-error"><i class="fas fa-times-circle"></i> {{ session('error') }}</div>
@endif

{{-- ── Stepper ── --}}
<div class="stepper">
 <button class="step active" id="step-btn-1" onclick="goToStep(1)">
  <span class="step-num" id="step-num-1">1</span>
  <span class="nav-label"><i class="fas fa-file-invoice" style="margin-right:6px"></i>Emitir CFDI</span>
 </button>
 <div class="step-divider"></div>
 <button class="step disabled" id="step-btn-2" onclick="goToStep(2)">
  <span class="step-num" id="step-num-2">2</span>
  <span class="nav-label"><i class="fas fa-eye" style="margin-right:6px"></i>Verificar CFDI</span>
 </button>
 <div class="step-divider"></div>
 <button class="step disabled" id="step-btn-3" onclick="goToStep(3)">
  <span class="step-num" id="step-num-3">3</span>
  <span class="nav-label"><i class="fas fa-list" style="margin-right:6px"></i>Mis Facturas</span>
 </button>
 <div class="step-divider"></div>
 <button class="step disabled" id="step-btn-4" onclick="goToStep(4)">
  <span class="step-num" id="step-num-4">4</span>
  <span class="nav-label"><i class="fas fa-ban" style="margin-right:6px"></i>Cancelar CFDI</span>
 </button>
</div>

{{-- ── PASO 1 — EMITIR ── --}}
<div id="step-1">
 <div class="panel-hero">
  <div class="panel-hero-icon"><i class="fas fa-file-invoice-dollar"></i></div>
  <div class="panel-hero-info">
   <div class="panel-hero-title">Emitir Comprobante Fiscal Digital (CFDI 4.0)</div>
   <div class="panel-hero-sub">Llena los datos del emisor, receptor y conceptos</div>
  </div>
 </div>
 <div class="panel-card">
  {{-- Emisor --}}
  <div class="form-section">
   <div class="form-section-title"><i class="fas fa-user-tie" style="color:rgba(255,255,255,.3)"></i> Datos del Emisor</div>
   <div class="form-grid-3">
    <div class="form-group"><label class="form-label">RFC Emisor <span class="req">*</span></label><input class="form-input" id="rfc_emisor" value="{{ Auth::user()->rfc ?? '' }}" readonly></div>
    <div class="form-group"><label class="form-label">Nombre / Razón Social <span class="req">*</span></label><input class="form-input" id="nombre_emisor" value="{{ Auth::user()->nombre ?? '' }} {{ Auth::user()->primer_apellido ?? '' }}" placeholder="Nombre como aparece en el SAT"></div>
    <div class="form-group"><label class="form-label">Régimen Fiscal <span class="req">*</span></label>
     <select class="form-input" id="regimen_emisor">
      <option value="">Seleccionar...</option>
      <option value="601">601 - General de Ley Personas Morales</option>
      <option value="612" selected>612 - Personas Físicas con Actividades Empresariales</option>
      <option value="621">621 - Incorporación Fiscal</option>
      <option value="625">625 - Régimen Simplificado de Confianza</option>
     </select>
    </div>
   </div>
  </div>
  {{-- Receptor --}}
  <div class="form-section">
   <div class="form-section-title"><i class="fas fa-user" style="color:rgba(255,255,255,.3)"></i> Datos del Receptor</div>
   <div class="form-grid-3">
    <div class="form-group"><label class="form-label">RFC Receptor <span class="req">*</span></label><input class="form-input" id="rfc_receptor" placeholder="RFC del cliente"><span class="field-hint">Usa XAXX010101000 para público en general</span></div>
    <div class="form-group"><label class="form-label">Nombre / Razón Social <span class="req">*</span></label><input class="form-input" id="nombre_receptor" placeholder="Nombre del receptor"></div>
    <div class="form-group"><label class="form-label">Código Postal <span class="req">*</span></label><input class="form-input" id="cp_receptor" placeholder="CP domicilio fiscal" maxlength="5"></div>
    <div class="form-group"><label class="form-label">Régimen Fiscal Receptor <span class="req">*</span></label>
     <select class="form-input" id="regimen_receptor">
      <option value="">Seleccionar...</option>
      <option value="601">601 - General de Ley Personas Morales</option>
      <option value="612">612 - Personas Físicas con Actividades Empresariales</option>
      <option value="616">616 - Sin Obligaciones Fiscales</option>
      <option value="621">621 - Incorporación Fiscal</option>
      <option value="625">625 - Régimen Simplificado de Confianza</option>
     </select>
    </div>
    <div class="form-group"><label class="form-label">Uso del CFDI <span class="req">*</span></label>
     <select class="form-input" id="uso_cfdi">
      <option value="">Seleccionar...</option>
      <option value="G01">G01 - Adquisición de mercancias</option>
      <option value="G03">G03 - Gastos en general</option>
      <option value="I01">I01 - Construcciones</option>
      <option value="P01">P01 - Por definir</option>
      <option value="S01">S01 - Sin efectos fiscales</option>
     </select>
    </div>
   </div>
  </div>
  {{-- Conceptos --}}
  <div class="form-section">
   <div class="form-section-title"><i class="fas fa-box" style="color:rgba(255,255,255,.3)"></i> Conceptos</div>
   <div class="conceptos-wrap">
    <div class="conceptos-head">
     <span>Clave SAT</span><span>Descripción</span><span>Clave Unidad</span><span>Cantidad</span><span>Valor Unit.</span><span>Importe</span><span></span>
    </div>
    <div id="conceptos-body">
     <div class="concepto-row" id="row-0">
      <input class="form-input" placeholder="Ej. 01010101" oninput="calcTotales()">
      <input class="form-input" placeholder="Descripción del producto o servicio" oninput="calcTotales()">
      <select class="form-input" oninput="calcTotales()">
       <option value="H87">H87 - Pieza</option><option value="E48">E48 - Servicio</option><option value="KGM">KGM - Kilogramo</option><option value="LTR">LTR - Litro</option><option value="MTR">MTR - Metro</option>
      </select>
      <input class="form-input cantidad" type="number" placeholder="1" min="0.001" step="0.001" value="1" oninput="calcTotales()">
      <div class="form-input-wrap"><span class="input-prefix">$</span><input class="form-input has-prefix valor_unitario" type="number" placeholder="0.00" min="0" step="0.01" value="0" oninput="calcTotales()"></div>
      <span class="importe-cell" id="imp-0">$0.00</span>
      <button class="btn-remove-row" onclick="removeRow(0)" title="Eliminar"><i class="fas fa-times"></i></button>
     </div>
    </div>
   </div>
   <button class="btn-add" onclick="addRow()"><i class="fas fa-plus"></i> Agregar concepto</button>
  </div>
  {{-- Totales --}}
  <div class="totales-wrap" style="margin-bottom:24px">
   <div class="totales-box">
    <div class="totales-row"><span class="totales-label">Subtotal:</span><span class="totales-val" id="tot-subtotal">$0.00</span></div>
    <div class="totales-row"><span class="totales-label">IVA (16%):</span><span class="totales-val" id="tot-iva">$0.00</span></div>
    <div class="totales-row total"><span>TOTAL:</span><span id="tot-total">$0.00</span></div>
   </div>
  </div>
  {{-- Datos generales --}}
  <div class="form-section">
   <div class="form-section-title"><i class="fas fa-cog" style="color:rgba(255,255,255,.3)"></i> Datos Generales del Comprobante</div>
   <div class="form-grid-3">
    <div class="form-group"><label class="form-label">Tipo de Comprobante <span class="req">*</span></label>
     <select class="form-input" id="tipo_comprobante"><option value="I">I - Ingreso</option><option value="E">E - Egreso</option><option value="T">T - Traslado</option><option value="N">N - Nómina</option></select>
    </div>
    <div class="form-group"><label class="form-label">Método de Pago <span class="req">*</span></label>
     <select class="form-input" id="metodo_pago"><option value="PUE">PUE - Pago en una sola exhibición</option><option value="PPD">PPD - Pago en parcialidades o diferido</option></select>
    </div>
    <div class="form-group"><label class="form-label">Forma de Pago <span class="req">*</span></label>
     <select class="form-input" id="forma_pago">
      <option value="01">01 - Efectivo</option><option value="02">02 - Cheque nominativo</option><option value="03">03 - Transferencia electrónica</option><option value="04">04 - Tarjeta de crédito</option><option value="28">28 - Tarjeta de débito</option><option value="99">99 - Por definir</option>
     </select>
    </div>
    <div class="form-group"><label class="form-label">Moneda <span class="req">*</span></label>
     <select class="form-input" id="moneda"><option value="MXN">MXN - Peso Mexicano</option><option value="USD">USD - Dólar Americano</option><option value="EUR">EUR - Euro</option></select>
    </div>
    <div class="form-group"><label class="form-label">Exportación <span class="req">*</span></label>
     <select class="form-input" id="exportacion"><option value="01">01 - No aplica</option><option value="02">02 - Definitiva</option><option value="03">03 - Temporal</option></select>
    </div>
   </div>
  </div>
  <div class="step-footer">
   <div class="step-footer-left"><span style="font-size:12px;color:rgba(255,255,255,.3)"><i class="fas fa-shield-alt" style="margin-right:5px"></i>El CFDI se sellará con tu e.firma o certificado de sello digital</span></div>
   <div style="display:flex;gap:10px">
    <button class="btn btn-outline" onclick="previewFactura()"><i class="fas fa-eye"></i> Vista previa</button>
    <button class="btn btn-primary" onclick="timbrarCFDI()" id="btn-timbrar"><i class="fas fa-paper-plane"></i> Timbrar CFDI</button>
   </div>
  </div>
 </div>
</div>

{{-- ── PASO 2 — VERIFICAR ── --}}
<div id="step-2" style="display:none">
 <div class="panel-hero" style="background:linear-gradient(135deg,#1e3a5f 0%,#1a2f50 100%)">
  <div class="panel-hero-icon" style="background:rgba(96,165,250,.2)"><i class="fas fa-eye" style="color:#60a5fa"></i></div>
  <div class="panel-hero-info"><div class="panel-hero-title">Verificación del CFDI</div><div class="panel-hero-sub">Revisa los datos de tu comprobante antes de guardarlo</div></div>
 </div>
 <div class="panel-card">
  <div id="preview-container"></div>
  <div class="step-footer">
   <button class="btn btn-outline" onclick="goToStep(1)"><i class="fas fa-arrow-left"></i> Atrás</button>
   <button class="btn btn-primary" onclick="goToStep(3)">Siguiente <i class="fas fa-arrow-right"></i></button>
  </div>
 </div>
</div>

{{-- ── PASO 3 — MIS FACTURAS ── --}}
<div id="step-3" style="display:none">
 <div class="panel-hero" style="background:linear-gradient(135deg,#2d1b69 0%,#1a1040 100%)">
  <div class="panel-hero-icon" style="background:rgba(167,139,250,.2)"><i class="fas fa-list" style="color:#a78bfa"></i></div>
  <div class="panel-hero-info"><div class="panel-hero-title">Mis Facturas</div><div class="panel-hero-sub">Historial de comprobantes fiscales emitidos</div></div>
 </div>
 <div class="panel-card">
  <div class="table-wrap">
   <table>
    <thead><tr>
     <th>UUID / Folio</th><th>Receptor</th><th>Fecha</th><th>Subtotal</th><th>IVA</th><th>Total</th><th>Estatus</th><th style="text-align:center">Acciones</th>
    </tr></thead>
    <tbody id="facturas-tbody">
     @forelse($facturas ?? [] as $f)
     <tr>
      <td><div class="td-mono" style="font-size:11px">{{ $f->uuid ?? '—' }}</div><div style="font-size:12px;color:rgba(255,255,255,.5);margin-top:2px">Folio: {{ $f->folio ?? '—' }}</div></td>
      <td><div class="td-bold">{{ $f->nombre_receptor ?? '—' }}</div><div class="td-mono">{{ $f->rfc_receptor ?? '' }}</div></td>
      <td>{{ $f->fecha_timbrado ? \Carbon\Carbon::parse($f->fecha_timbrado)->format('d/m/Y') : '—' }}</td>
      <td>${{ number_format($f->subtotal ?? 0, 2) }}</td>
      <td>${{ number_format($f->iva ?? 0, 2) }}</td>
      <td class="td-green">${{ number_format($f->total ?? 0, 2) }}</td>
      <td>
       @if(($f->estatus ?? '') === 'vigente')
        <span class="badge badge-vigente"><i class="fas fa-circle" style="font-size:6px"></i> Vigente</span>
       @else
        <span class="badge badge-cancelado"><i class="fas fa-circle" style="font-size:6px"></i> Cancelado</span>
       @endif
      </td>
      <td style="text-align:center"><div style="display:flex;gap:5px;justify-content:center">
       <a href="{{ route('facturacion.pdf', $f->id) }}" class="act-btn green" title="PDF"><i class="fas fa-file-pdf"></i></a>
       <a href="{{ route('facturacion.xml', $f->id) }}" class="act-btn" title="XML"><i class="fas fa-code"></i></a>
      </div></td>
     </tr>
     @empty
     <tr><td colspan="8"><div class="empty-state"><i class="fas fa-file-invoice"></i><p>No tienes facturas emitidas aún. La nueva factura aparecerá aquí al guardarla.</p></div></td></tr>
     @endforelse
    </tbody>
   </table>
  </div>
  <div class="step-footer">
   <button class="btn btn-outline" onclick="goToStep(2)"><i class="fas fa-arrow-left"></i> Atrás</button>
   <button class="btn btn-primary" onclick="goToStep(4)">Siguiente <i class="fas fa-arrow-right"></i></button>
  </div>
 </div>
</div>

{{-- ── PASO 4 — CANCELAR ── --}}
<div id="step-4" style="display:none">
 <div class="panel-hero" style="background:linear-gradient(135deg,#7c2d12 0%,#6b2510 100%)">
  <div class="panel-hero-icon" style="background:rgba(251,146,60,.2)"><i class="fas fa-ban" style="color:#fb923c"></i></div>
  <div class="panel-hero-info"><div class="panel-hero-title">Cancelar CFDI</div><div class="panel-hero-sub">Confirma si deseas guardar o cancelar la factura generada</div></div>
 </div>
 <div class="panel-card">
  <div class="cancel-confirm" id="cancel-confirm-box">
   <i class="fas fa-question-circle"></i>
   <h3>¿Deseas guardar la factura?</h3>
   <p id="cancel-confirm-desc">La factura con UUID <strong id="cancel-uuid" style="color:#4ade80;font-family:monospace">—</strong> será guardada en tu historial y podrás descargarla en cualquier momento.</p>
   <div class="cancel-confirm-btns">
    <button class="btn btn-primary" onclick="guardarFactura()" id="btn-guardar"><i class="fas fa-save"></i> Sí, guardar factura</button>
    <button class="btn btn-danger" onclick="cancelarTodo()"><i class="fas fa-times"></i> No, cancelar todo</button>
   </div>
  </div>
  <div id="cancel-result" style="display:none"></div>
  <div class="step-footer">
   <button class="btn btn-outline" onclick="goToStep(3)"><i class="fas fa-arrow-left"></i> Atrás</button>
   <button class="btn btn-outline" id="btn-nueva" style="display:none" onclick="resetTodo()"><i class="fas fa-plus"></i> Nueva Factura</button>
  </div>
 </div>
</div>

@endsection

@push('scripts')
<script>
// ── Stepper ──────────────────────────────────────────
let currentStep = 1;

function goToStep(n){
 const btnEl = document.getElementById('step-btn-' + n);
 if(btnEl && btnEl.classList.contains('disabled')) return;
 document.getElementById('step-' + currentStep).style.display = 'none';
 document.getElementById('step-btn-' + currentStep).classList.remove('active');
 currentStep = n;
 document.getElementById('step-' + currentStep).style.display = 'block';
 document.getElementById('step-btn-' + currentStep).classList.add('active');
 document.getElementById('step-btn-' + currentStep).classList.remove('disabled');
 window.scrollTo({top:0, behavior:'smooth'});
}

function unlockStep(n){ document.getElementById('step-btn-' + n)?.classList.remove('disabled'); }

function markCompleted(n){
 const numEl = document.getElementById('step-num-' + n);
 const btnEl = document.getElementById('step-btn-' + n);
 if(numEl) numEl.innerHTML = '<i class="fas fa-check" style="font-size:10px"></i>';
 if(btnEl) btnEl.classList.add('completed');
}

// ── Conceptos ────────────────────────────────────────
let rowCount = 1;

function addRow(){
 const idx = rowCount++;
 const div = document.createElement('div');
 div.className = 'concepto-row'; div.id = 'row-' + idx;
 div.innerHTML = `
  <input class="form-input" placeholder="Ej. 01010101" oninput="calcTotales()">
  <input class="form-input" placeholder="Descripción del producto o servicio" oninput="calcTotales()">
  <select class="form-input" oninput="calcTotales()">
   <option value="H87">H87 - Pieza</option><option value="E48">E48 - Servicio</option><option value="KGM">KGM - Kilogramo</option><option value="LTR">LTR - Litro</option><option value="MTR">MTR - Metro</option>
  </select>
  <input class="form-input cantidad" type="number" placeholder="1" min="0.001" step="0.001" value="1" oninput="calcTotales()">
  <div class="form-input-wrap"><span class="input-prefix">$</span><input class="form-input has-prefix valor_unitario" type="number" placeholder="0.00" min="0" step="0.01" value="0" oninput="calcTotales()"></div>
  <span class="importe-cell" id="imp-${idx}">$0.00</span>
  <button class="btn-remove-row" onclick="removeRow(${idx})"><i class="fas fa-times"></i></button>`;
 document.getElementById('conceptos-body').appendChild(div);
 calcTotales();
}

function removeRow(idx){ document.getElementById('row-' + idx)?.remove(); calcTotales(); }

function calcTotales(){
 let subtotal = 0;
 document.querySelectorAll('.concepto-row').forEach(row => {
  const cant = parseFloat(row.querySelector('.cantidad')?.value) || 0;
  const val  = parseFloat(row.querySelector('.valor_unitario')?.value) || 0;
  const imp  = cant * val;
  subtotal  += imp;
  const impEl = row.querySelector('.importe-cell');
  if(impEl) impEl.textContent = '$' + imp.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2});
 });
 const iva = subtotal * 0.16, total = subtotal + iva;
 const fmt = v => '$' + v.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2});
 document.getElementById('tot-subtotal').textContent = fmt(subtotal);
 document.getElementById('tot-iva').textContent      = fmt(iva);
 document.getElementById('tot-total').textContent    = fmt(total);
}

// ── Form data ────────────────────────────────────────
function getFormData(){
 const conceptos = [];
 document.querySelectorAll('.concepto-row').forEach(row => {
  conceptos.push({
   clave   : row.querySelectorAll('input')[0]?.value || '',
   desc    : row.querySelectorAll('input')[1]?.value || '',
   unidad  : row.querySelector('select')?.value || 'H87',
   cantidad: parseFloat(row.querySelector('.cantidad')?.value) || 0,
   valor   : parseFloat(row.querySelector('.valor_unitario')?.value) || 0,
  });
 });
 return {
  rfc_emisor: document.getElementById('rfc_emisor')?.value,
  nombre_emisor: document.getElementById('nombre_emisor')?.value,
  regimen_emisor: document.getElementById('regimen_emisor')?.value,
  rfc_receptor: document.getElementById('rfc_receptor')?.value,
  nombre_receptor: document.getElementById('nombre_receptor')?.value,
  cp_receptor: document.getElementById('cp_receptor')?.value,
  regimen_receptor: document.getElementById('regimen_receptor')?.value,
  uso_cfdi: document.getElementById('uso_cfdi')?.value,
  tipo_comprobante: document.getElementById('tipo_comprobante')?.value,
  metodo_pago: document.getElementById('metodo_pago')?.value,
  forma_pago: document.getElementById('forma_pago')?.value,
  moneda: document.getElementById('moneda')?.value,
  exportacion: document.getElementById('exportacion')?.value,
  conceptos,
 };
}

// ── Vista previa ─────────────────────────────────────
function previewFactura(){ buildPreview(); unlockStep(2); goToStep(2); }

function buildPreview(){
 const d = getFormData();
 let subtotal = 0, rowsHtml = '';
 d.conceptos.forEach(c => {
  const imp = c.cantidad * c.valor; subtotal += imp;
  rowsHtml += `<tr><td>${c.clave}</td><td>${c.desc}</td><td>${c.unidad}</td><td style="text-align:right">${c.cantidad}</td><td style="text-align:right">$${c.valor.toLocaleString('es-MX',{minimumFractionDigits:2})}</td><td style="text-align:right;font-weight:600">$${imp.toLocaleString('es-MX',{minimumFractionDigits:2})}</td></tr>`;
 });
 const iva = subtotal * 0.16, total = subtotal + iva;
 const fmt = v => '$' + v.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2});
 const fakeUUID = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, c => { const r=Math.random()*16|0,v=c==='x'?r:(r&0x3|0x8); return v.toString(16); });
 window._previewData = { ...d, subtotal, iva, total, uuid: fakeUUID };
 document.getElementById('preview-container').innerHTML = `
  <div class="preview-card">
   <div class="preview-header">
    <div class="preview-logo"><i class="fas fa-landmark" style="margin-right:8px;color:#006847"></i>Portal SAT<small>CFDI 4.0 – Comprobante Fiscal Digital</small></div>
    <div class="preview-folio"><div class="num">#${String(Math.floor(Math.random()*9000)+1000).padStart(6,'0')}</div><small>${new Date().toLocaleDateString('es-MX',{day:'2-digit',month:'long',year:'numeric'})}</small></div>
   </div>
   <div class="preview-parties">
    <div class="preview-party"><h4>Emisor</h4><p><strong>${d.nombre_emisor||'—'}</strong></p><p class="rfc">${d.rfc_emisor||'—'}</p><p style="font-size:12px;color:#888;margin-top:4px">Régimen: ${d.regimen_emisor||'—'}</p></div>
    <div class="preview-party"><h4>Receptor</h4><p><strong>${d.nombre_receptor||'—'}</strong></p><p class="rfc">${d.rfc_receptor||'—'}</p><p style="font-size:12px;color:#888;margin-top:4px">CP: ${d.cp_receptor||'—'} · Uso: ${d.uso_cfdi||'—'}</p></div>
   </div>
   <table class="preview-table"><thead><tr><th>Clave</th><th>Descripción</th><th>Unidad</th><th style="text-align:right">Cantidad</th><th style="text-align:right">Val. Unit.</th><th style="text-align:right">Importe</th></tr></thead><tbody>${rowsHtml}</tbody></table>
   <div class="preview-totales"><div class="preview-totales-box"><div class="preview-total-row"><span>Subtotal</span><span>${fmt(subtotal)}</span></div><div class="preview-total-row"><span>IVA 16%</span><span>${fmt(iva)}</span></div><div class="preview-total-row final"><span>Total</span><span>${fmt(total)}</span></div></div></div>
   <div class="preview-uuid"><strong>UUID (Folio Fiscal)</strong>${fakeUUID}</div>
  </div>`;
 document.getElementById('cancel-uuid').textContent = fakeUUID;
}

// ── Timbrar ──────────────────────────────────────────
async function timbrarCFDI(){
 const d = getFormData();
 if(!d.rfc_receptor || !d.nombre_receptor || !d.cp_receptor){ showToast('Completa los datos del receptor antes de timbrar.','error'); return; }
 if(!d.conceptos.length || d.conceptos[0].valor === 0){ showToast('Agrega al menos un concepto con valor unitario.','error'); return; }
 const btn = document.getElementById('btn-timbrar');
 btn.disabled = true; btn.innerHTML = '<span class="spinner"></span> Timbrando...';
 try {
  const resp = await fetch('/facturacion/emitir', {
   method:'POST',
   headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'},
   body: JSON.stringify({ ...d, conceptos: d.conceptos.map(c=>({clave_prod:c.clave,descripcion:c.desc,clave_unidad:c.unidad,cantidad:c.cantidad,valor_unitario:c.valor})) }),
  });
  const data = await resp.json();
  if(resp.ok){
   window._previewData = { ...(window._previewData||{}), uuid:data.uuid, folio:data.folio, total:data.total, guardada:true };
   window._facturaId = data.uuid;
   buildPreview();
   [2,3,4].forEach(n=>unlockStep(n));
   markCompleted(1);
   document.getElementById('cancel-uuid').textContent = data.uuid;
   goToStep(2);
  } else { showToast(data.message || 'Error al timbrar el CFDI.','error'); }
 } catch(e){ showToast('Error de conexión. Intenta de nuevo.','error'); }
 finally { btn.disabled = false; btn.innerHTML = '<i class="fas fa-paper-plane"></i> Timbrar CFDI'; }
}

// ── Guardar / cancelar ───────────────────────────────
function guardarFactura(){
 document.getElementById('cancel-confirm-box').style.display = 'none';
 document.getElementById('cancel-result').style.display = 'block';
 document.getElementById('cancel-result').innerHTML = `
  <div style="text-align:center;padding:32px 20px">
   <div style="width:64px;height:64px;background:rgba(74,222,128,.15);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px"><i class="fas fa-check" style="font-size:28px;color:#4ade80"></i></div>
   <h3 style="font-size:20px;font-weight:700;color:white;margin-bottom:8px">¡Factura guardada correctamente!</h3>
   <p style="font-size:14px;color:rgba(255,255,255,.5);margin-bottom:20px">La factura ha sido guardada en tu historial.<br>Puedes descargarla en PDF o XML desde "Mis Facturas".</p>
   <div style="background:rgba(74,222,128,.08);border:1px solid rgba(74,222,128,.2);border-radius:8px;padding:12px 16px;display:inline-block;font-family:monospace;font-size:12px;color:#4ade80">UUID: ${window._previewData?.uuid||'—'}</div>
  </div>`;
 document.getElementById('btn-nueva').style.display = 'flex';
 markCompleted(4);
 location.reload();
}

function cancelarTodo(){ if(confirm('¿Estás seguro de que deseas cancelar? Se perderán todos los datos ingresados.')){ resetTodo(); } }

function resetTodo(){
 document.getElementById('rfc_receptor').value = '';
 document.getElementById('nombre_receptor').value = '';
 document.getElementById('cp_receptor').value = '';
 document.getElementById('regimen_receptor').value = '';
 document.getElementById('uso_cfdi').value = '';
 document.getElementById('conceptos-body').innerHTML = `
  <div class="concepto-row" id="row-0">
   <input class="form-input" placeholder="Ej. 01010101" oninput="calcTotales()">
   <input class="form-input" placeholder="Descripción del producto o servicio" oninput="calcTotales()">
   <select class="form-input" oninput="calcTotales()"><option value="H87">H87 - Pieza</option><option value="E48">E48 - Servicio</option></select>
   <input class="form-input cantidad" type="number" placeholder="1" min="0.001" step="0.001" value="1" oninput="calcTotales()">
   <div class="form-input-wrap"><span class="input-prefix">$</span><input class="form-input has-prefix valor_unitario" type="number" placeholder="0.00" min="0" step="0.01" value="0" oninput="calcTotales()"></div>
   <span class="importe-cell" id="imp-0">$0.00</span>
   <button class="btn-remove-row" onclick="removeRow(0)"><i class="fas fa-times"></i></button>
  </div>`;
 calcTotales();
 [2,3,4].forEach(n=>{
  const b = document.getElementById('step-btn-'+n);
  b?.classList.add('disabled'); b?.classList.remove('completed','active');
  const num = document.getElementById('step-num-'+n); if(num) num.innerHTML = n;
 });
 document.getElementById('cancel-confirm-box').style.display = 'block';
 document.getElementById('cancel-result').style.display = 'none';
 document.getElementById('btn-nueva').style.display = 'none';
 window._previewData = null; rowCount = 1;
 goToStep(1);
}

// ── Toast ────────────────────────────────────────────
function showToast(msg, type='success'){
 const t = document.createElement('div');
 t.className = type === 'error' ? 'alert-error' : 'alert-success';
 t.innerHTML = `<i class="fas fa-${type==='error'?'times':'check'}-circle"></i> ${msg}`;
 t.style.cssText = 'position:fixed;top:80px;right:24px;z-index:9999;min-width:300px;max-width:400px';
 document.body.appendChild(t);
 setTimeout(()=>{ t.style.opacity='0'; t.style.transition='opacity .5s'; setTimeout(()=>t.remove(),500); }, 4000);
}

setTimeout(()=>{
 document.querySelectorAll('.alert-success,.alert-error').forEach(el=>{
  el.style.transition='opacity .5s'; el.style.opacity='0'; setTimeout(()=>el.remove(),500);
 });
},4500);
</script>
@endpush