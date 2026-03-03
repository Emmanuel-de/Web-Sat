@extends('layouts.app')

@section('title', 'Facturación CFDI 4.0 - SAT')

@section('content')

<div class="sat-page-header">
    <div class="container-sat">
        <div class="sat-breadcrumb">
            <a href="{{ route('home') }}">Inicio</a>
            <span class="sat-breadcrumb-sep"><i class="fas fa-chevron-right" style="font-size:10px"></i></span>
            <span>Facturación electrónica</span>
        </div>
        <h1 class="sat-page-title"><i class="fas fa-receipt" style="margin-right:12px"></i>Facturación Electrónica CFDI 4.0</h1>
        <p class="sat-page-subtitle">Emite, verifica y cancela tus comprobantes fiscales digitales</p>
    </div>
</div>

<section class="sat-section">
    <div class="container-sat">
        <div class="sat-tabs-container">
            <div class="sat-tabs">
                <div class="sat-tab active" data-tab="emitir">
                    <i class="fas fa-plus-circle"></i> Emitir CFDI
                </div>
                <div class="sat-tab" data-tab="verificar">
                    <i class="fas fa-check-circle"></i> Verificar CFDI
                </div>
                <div class="sat-tab" data-tab="historial">
                    <i class="fas fa-list-alt"></i> Mis facturas
                </div>
                <div class="sat-tab" data-tab="cancelar">
                    <i class="fas fa-times-circle"></i> Cancelar CFDI
                </div>
            </div>

            <!-- Emitir CFDI -->
            <div class="sat-tab-content active" data-tab="emitir">
                <form action="{{ route('facturacion.store') }}" method="POST" class="sat-form-ajax" id="cfdiForm">
                    @csrf
                    <div class="sat-form-section" style="margin-bottom:24px">
                        <div class="sat-form-header">
                            <div class="sat-form-header-icon"><i class="fas fa-user-circle"></i></div>
                            <div class="sat-form-header-text">
                                <h2>Datos del Emisor</h2>
                                <p>Información del contribuyente que emite la factura</p>
                            </div>
                        </div>
                        <div class="sat-form-body">
                            <div class="sat-form-row cols-3">
                                <div class="sat-form-group">
                                    <label>RFC Emisor <span class="required">*</span></label>
                                    <input type="text" name="rfc_emisor" class="sat-input" required data-validate="rfc" placeholder="Tu RFC">
                                </div>
                                <div class="sat-form-group">
                                    <label>Nombre / Razón Social <span class="required">*</span></label>
                                    <input type="text" name="nombre_emisor" class="sat-input" required placeholder="Nombre como aparece en el SAT">
                                </div>
                                <div class="sat-form-group">
                                    <label>Régimen Fiscal <span class="required">*</span></label>
                                    <select name="regimen_emisor" class="sat-select" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="621">621 - Régimen de Incorporación Fiscal</option>
                                        <option value="612">612 - Actividades Empresariales</option>
                                        <option value="625">625 - RESICO</option>
                                        <option value="601">601 - General de Ley Personas Morales</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="sat-form-section" style="margin-bottom:24px">
                        <div class="sat-form-header">
                            <div class="sat-form-header-icon"><i class="fas fa-user-tag"></i></div>
                            <div class="sat-form-header-text">
                                <h2>Datos del Receptor</h2>
                                <p>Información de quien recibe la factura</p>
                            </div>
                        </div>
                        <div class="sat-form-body">
                            <div class="sat-form-row cols-3">
                                <div class="sat-form-group">
                                    <label>RFC Receptor <span class="required">*</span></label>
                                    <input type="text" name="rfc_receptor" class="sat-input" required data-validate="rfc" placeholder="RFC del cliente">
                                    <span class="sat-input-hint">Usa XAXX010101000 para público en general</span>
                                </div>
                                <div class="sat-form-group">
                                    <label>Nombre / Razón Social <span class="required">*</span></label>
                                    <input type="text" name="nombre_receptor" class="sat-input" required placeholder="Nombre del receptor">
                                </div>
                                <div class="sat-form-group">
                                    <label>Código Postal del receptor <span class="required">*</span></label>
                                    <input type="text" name="cp_receptor" class="sat-input" required placeholder="CP domicilio fiscal" maxlength="5">
                                </div>
                            </div>
                            <div class="sat-form-row cols-2">
                                <div class="sat-form-group">
                                    <label>Régimen fiscal del receptor <span class="required">*</span></label>
                                    <select name="regimen_receptor" class="sat-select" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="616">616 - Sin obligaciones fiscales</option>
                                        <option value="621">621 - RIF</option>
                                        <option value="625">625 - RESICO</option>
                                        <option value="612">612 - Actividades Empresariales</option>
                                        <option value="601">601 - General Personas Morales</option>
                                    </select>
                                </div>
                                <div class="sat-form-group">
                                    <label>Uso del CFDI <span class="required">*</span></label>
                                    <select name="uso_cfdi" class="sat-select" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="G01">G01 - Adquisición de mercancias</option>
                                        <option value="G02">G02 - Devoluciones o descuentos</option>
                                        <option value="G03">G03 - Gastos en general</option>
                                        <option value="I01">I01 - Construcciones</option>
                                        <option value="I02">I02 - Mobilario</option>
                                        <option value="I03">I03 - Equipo de transporte</option>
                                        <option value="I04">I04 - Equipo de cómputo</option>
                                        <option value="P01">P01 - Por definir</option>
                                        <option value="S01">S01 - Sin efectos fiscales</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="sat-form-section" style="margin-bottom:24px">
                        <div class="sat-form-header">
                            <div class="sat-form-header-icon"><i class="fas fa-box"></i></div>
                            <div class="sat-form-header-text">
                                <h2>Conceptos</h2>
                                <p>Productos o servicios que se facturan</p>
                            </div>
                        </div>
                        <div class="sat-form-body">
                            <div id="conceptos-container">
                                <div class="concepto-row" style="background:var(--sat-gray-light);border-radius:8px;padding:16px;margin-bottom:12px;border:1px solid var(--sat-gray-border)">
                                    <div class="sat-form-row cols-3" style="margin-bottom:12px">
                                        <div class="sat-form-group">
                                            <label>Clave SAT (ClaveProdServ) <span class="required">*</span></label>
                                            <input type="text" name="conceptos[0][clave_prod]" class="sat-input" required placeholder="Ej: 01010101">
                                        </div>
                                        <div class="sat-form-group">
                                            <label>Descripción <span class="required">*</span></label>
                                            <input type="text" name="conceptos[0][descripcion]" class="sat-input" required placeholder="Descripción del producto o servicio">
                                        </div>
                                        <div class="sat-form-group">
                                            <label>Clave Unidad <span class="required">*</span></label>
                                            <select name="conceptos[0][clave_unidad]" class="sat-select" required>
                                                <option value="H87">H87 - Pieza</option>
                                                <option value="E48">E48 - Unidad de servicio</option>
                                                <option value="KGM">KGM - Kilogramo</option>
                                                <option value="LTR">LTR - Litro</option>
                                                <option value="MTR">MTR - Metro</option>
                                                <option value="ACT">ACT - Actividad</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="sat-form-row cols-3">
                                        <div class="sat-form-group">
                                            <label>Cantidad <span class="required">*</span></label>
                                            <input type="number" name="conceptos[0][cantidad]" class="sat-input concepto-cantidad" required placeholder="1" min="0.001" step="0.001">
                                        </div>
                                        <div class="sat-form-group">
                                            <label>Valor Unitario <span class="required">*</span></label>
                                            <div style="position:relative">
                                                <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--sat-gray);font-weight:600">$</span>
                                                <input type="number" name="conceptos[0][valor_unitario]" class="sat-input concepto-unitario" required placeholder="0.00" step="0.01" min="0" style="padding-left:26px">
                                            </div>
                                        </div>
                                        <div class="sat-form-group">
                                            <label>Importe</label>
                                            <div style="position:relative">
                                                <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--sat-gray);font-weight:600">$</span>
                                                <input type="number" name="conceptos[0][importe]" class="sat-input concepto-importe" readonly placeholder="0.00" step="0.01" style="padding-left:26px;background:#f0f9f4">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="addConcepto" class="btn-sat-outline" style="margin-top:8px">
                                <i class="fas fa-plus"></i> Agregar concepto
                            </button>

                            <!-- Totales -->
                            <div style="margin-top:24px;background:var(--sat-gray-light);border-radius:8px;padding:20px">
                                <div style="display:grid;grid-template-columns:1fr 200px;gap:8px;max-width:400px;margin-left:auto">
                                    <span style="font-size:14px;color:var(--sat-text-muted)">Subtotal:</span>
                                    <span id="subtotal-display" style="font-size:15px;font-weight:600;text-align:right">$0.00</span>
                                    <span style="font-size:14px;color:var(--sat-text-muted)">IVA (16%):</span>
                                    <span id="iva-display" style="font-size:15px;font-weight:600;text-align:right">$0.00</span>
                                    <span style="font-size:16px;font-weight:700;color:var(--sat-dark);border-top:2px solid var(--sat-gray-border);padding-top:8px">TOTAL:</span>
                                    <span id="total-display" style="font-size:20px;font-weight:700;color:var(--sat-green);text-align:right;border-top:2px solid var(--sat-gray-border);padding-top:8px">$0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="sat-form-section">
                        <div class="sat-form-header">
                            <div class="sat-form-header-icon"><i class="fas fa-cog"></i></div>
                            <div class="sat-form-header-text">
                                <h2>Datos generales del comprobante</h2>
                            </div>
                        </div>
                        <div class="sat-form-body">
                            <div class="sat-form-row cols-3">
                                <div class="sat-form-group">
                                    <label>Tipo de comprobante <span class="required">*</span></label>
                                    <select name="tipo_comprobante" class="sat-select" required>
                                        <option value="I">I - Ingreso</option>
                                        <option value="E">E - Egreso</option>
                                        <option value="T">T - Traslado</option>
                                        <option value="N">N - Nómina</option>
                                    </select>
                                </div>
                                <div class="sat-form-group">
                                    <label>Método de pago <span class="required">*</span></label>
                                    <select name="metodo_pago" class="sat-select" required>
                                        <option value="PUE">PUE - Pago en una sola exhibición</option>
                                        <option value="PPD">PPD - Pago en parcialidades o diferido</option>
                                    </select>
                                </div>
                                <div class="sat-form-group">
                                    <label>Forma de pago <span class="required">*</span></label>
                                    <select name="forma_pago" class="sat-select" required>
                                        <option value="01">01 - Efectivo</option>
                                        <option value="02">02 - Cheque nominativo</option>
                                        <option value="03">03 - Transferencia electrónica</option>
                                        <option value="04">04 - Tarjeta de crédito</option>
                                        <option value="28">28 - Tarjeta de débito</option>
                                        <option value="99">99 - Por definir</option>
                                    </select>
                                </div>
                            </div>
                            <div class="sat-form-row cols-2">
                                <div class="sat-form-group">
                                    <label>Moneda</label>
                                    <select name="moneda" class="sat-select">
                                        <option value="MXN">MXN - Peso Mexicano</option>
                                        <option value="USD">USD - Dólar Americano</option>
                                        <option value="EUR">EUR - Euro</option>
                                    </select>
                                </div>
                                <div class="sat-form-group">
                                    <label>Exportación</label>
                                    <select name="exportacion" class="sat-select">
                                        <option value="01">01 - No aplica</option>
                                        <option value="02">02 - Definitiva</option>
                                        <option value="03">03 - Temporal</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="sat-form-footer">
                            <p class="sat-form-note"><i class="fas fa-shield-alt"></i> El CFDI se sellará con tu e.firma o certificado de sello digital</p>
                            <div style="display:flex;gap:12px">
                                <button type="button" class="btn-sat-outline" id="previewBtn">
                                    <i class="fas fa-eye"></i> Vista previa
                                </button>
                                <button type="submit" class="btn-sat-green">
                                    <i class="fas fa-file-invoice"></i> Timbrar CFDI
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Verificar -->
            <div class="sat-tab-content" data-tab="verificar">
                <div class="sat-form-section">
                    <div class="sat-form-header">
                        <div class="sat-form-header-icon"><i class="fas fa-check-circle"></i></div>
                        <div class="sat-form-header-text">
                            <h2>Verificar CFDI</h2>
                            <p>Comprueba la validez y vigencia de un comprobante fiscal</p>
                        </div>
                    </div>
                    <div class="sat-form-body">
                        <form action="{{ route('facturacion.verificar.store') }}" method="POST" class="sat-form-ajax">
                            @csrf
                            <div class="sat-form-group" style="margin-bottom:20px">
                                <label>UUID del CFDI <span class="required">*</span></label>
                                <input type="text" name="uuid" class="sat-input" required placeholder="Ej: 6128396f-c09b-4ec6-8699-43c5f7e3b230" style="font-family:monospace">
                                <span class="sat-input-hint">Formato: 8-4-4-4-12 caracteres hexadecimales</span>
                            </div>
                            <div class="sat-form-row cols-3">
                                <div class="sat-form-group">
                                    <label>RFC Emisor</label>
                                    <input type="text" name="rfc_emisor" class="sat-input" placeholder="Opcional" data-validate="rfc">
                                </div>
                                <div class="sat-form-group">
                                    <label>RFC Receptor</label>
                                    <input type="text" name="rfc_receptor" class="sat-input" placeholder="Opcional" data-validate="rfc">
                                </div>
                                <div class="sat-form-group">
                                    <label>Total del comprobante</label>
                                    <div style="position:relative">
                                        <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--sat-gray);font-weight:600">$</span>
                                        <input type="number" name="total" class="sat-input" placeholder="0.00" step="0.01" style="padding-left:26px">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn-sat-green">
                                <i class="fas fa-search"></i> Verificar comprobante
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Historial de facturas -->
            <div class="sat-tab-content" data-tab="historial">
                <div class="sat-form-section">
                    <div class="sat-form-header">
                        <div class="sat-form-header-icon"><i class="fas fa-list-alt"></i></div>
                        <div class="sat-form-header-text">
                            <h2>Mis Facturas Emitidas</h2>
                            <p>Consulta, descarga y administra tus CFDI emitidos</p>
                        </div>
                    </div>
                    <div class="sat-form-body">
                        <!-- Filtros -->
                        <div class="sat-form-row cols-3" style="margin-bottom:20px">
                            <div class="sat-form-group">
                                <label>Fecha inicio</label>
                                <input type="date" class="sat-input" name="fecha_inicio">
                            </div>
                            <div class="sat-form-group">
                                <label>Fecha fin</label>
                                <input type="date" class="sat-input" name="fecha_fin">
                            </div>
                            <div class="sat-form-group">
                                <label>Estatus</label>
                                <select class="sat-select">
                                    <option value="">Todos</option>
                                    <option value="vigente">Vigente</option>
                                    <option value="cancelado">Cancelado</option>
                                </select>
                            </div>
                        </div>

                        @if(isset($facturas) && count($facturas) > 0)
                        <table class="sat-table">
                            <thead>
                                <tr>
                                    <th>UUID</th>
                                    <th>Fecha</th>
                                    <th>Receptor</th>
                                    <th>Total</th>
                                    <th>Estatus</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($facturas as $factura)
                                <tr>
                                    <td style="font-family:monospace;font-size:12px">{{ Str::limit($factura->uuid, 20) }}</td>
                                    <td>{{ $factura->fecha->format('d/m/Y') }}</td>
                                    <td>{{ $factura->receptor }}</td>
                                    <td>${{ number_format($factura->total, 2) }}</td>
                                    <td>
                                        <span class="sat-table-badge {{ $factura->estatus === 'vigente' ? 'badge-success' : 'badge-danger' }}">
                                            {{ ucfirst($factura->estatus) }}
                                        </span>
                                    </td>
                                    <td style="display:flex;gap:6px">
                                        <a href="{{ route('facturacion.pdf', $factura->id) }}" class="btn-sat-outline" style="padding:5px 10px;font-size:11px">
                                            PDF
                                        </a>
                                        <a href="{{ route('facturacion.xml', $factura->id) }}" class="btn-sat-outline" style="padding:5px 10px;font-size:11px">
                                            XML
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div style="text-align:center;padding:48px;color:var(--sat-text-muted)">
                            <i class="fas fa-file-invoice" style="font-size:48px;opacity:0.3;display:block;margin-bottom:16px"></i>
                            <p>No tienes facturas emitidas. Inicia sesión para ver tu historial.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Cancelar CFDI -->
            <div class="sat-tab-content" data-tab="cancelar">
                <div class="sat-form-section">
                    <div class="sat-form-header" style="background:var(--sat-red)">
                        <div class="sat-form-header-icon"><i class="fas fa-times-circle"></i></div>
                        <div class="sat-form-header-text">
                            <h2>Cancelar CFDI</h2>
                            <p>Solicita la cancelación de un comprobante fiscal</p>
                        </div>
                    </div>
                    <div class="sat-form-body">
                        <div class="sat-warning-box">
                            <p><i class="fas fa-exclamation-triangle" style="margin-right:8px"></i>
                            La cancelación de facturas requiere aceptación del receptor. Una vez cancelada, no puede revertirse. Asegúrate de tener el consentimiento de ambas partes.</p>
                        </div>
                        <form action="{{ route('facturacion.cancelar.store', ':id') }}" method="POST" class="sat-form-ajax">
                            @csrf
                            <div class="sat-form-group" style="margin-bottom:20px">
                                <label>UUID del CFDI a cancelar <span class="required">*</span></label>
                                <input type="text" name="uuid" class="sat-input" required placeholder="UUID del comprobante" style="font-family:monospace">
                            </div>
                            <div class="sat-form-row cols-2">
                                <div class="sat-form-group">
                                    <label>Motivo de cancelación <span class="required">*</span></label>
                                    <select name="motivo" class="sat-select" required>
                                        <option value="">Seleccionar motivo...</option>
                                        <option value="01">01 - Comprobante emitido con errores con relación</option>
                                        <option value="02">02 - Comprobante emitido con errores sin relación</option>
                                        <option value="03">03 - No se llevó a cabo la operación</option>
                                        <option value="04">04 - Operación nominativa relacionada en una factura global</option>
                                    </select>
                                </div>
                                <div class="sat-form-group">
                                    <label>UUID sustituto (si aplica)</label>
                                    <input type="text" name="uuid_sustituto" class="sat-input" placeholder="UUID de la factura que sustituye" style="font-family:monospace">
                                    <span class="sat-input-hint">Solo para motivo 01</span>
                                </div>
                            </div>
                            <button type="submit" class="btn-sat-primary">
                                <i class="fas fa-times-circle"></i> Solicitar cancelación
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
// Cálculo automático de importes en CFDI
let conceptoIndex = 0;

function calcularImportes() {
    let subtotal = 0;
    document.querySelectorAll('.concepto-row').forEach(row => {
        const cant = parseFloat(row.querySelector('.concepto-cantidad').value) || 0;
        const unit = parseFloat(row.querySelector('.concepto-unitario').value) || 0;
        const importe = cant * unit;
        const importeInput = row.querySelector('.concepto-importe');
        if (importeInput) importeInput.value = importe.toFixed(2);
        subtotal += importe;
    });
    const iva = subtotal * 0.16;
    const total = subtotal + iva;
    document.getElementById('subtotal-display').textContent = '$' + subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    document.getElementById('iva-display').textContent = '$' + iva.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    document.getElementById('total-display').textContent = '$' + total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

document.querySelectorAll('.concepto-cantidad, .concepto-unitario').forEach(input => {
    input.addEventListener('input', calcularImportes);
});

document.getElementById('addConcepto')?.addEventListener('click', function() {
    conceptoIndex++;
    const container = document.getElementById('conceptos-container');
    const row = document.querySelector('.concepto-row').cloneNode(true);
    row.querySelectorAll('input, select').forEach(el => {
        el.name = el.name.replace('[0]', `[${conceptoIndex}]`);
        el.value = '';
    });
    // Agregar botón eliminar
    const delBtn = document.createElement('button');
    delBtn.type = 'button';
    delBtn.className = 'btn-sat-outline';
    delBtn.style.cssText = 'margin-top:8px;border-color:var(--sat-red);color:var(--sat-red);padding:6px 12px;font-size:12px';
    delBtn.innerHTML = '<i class="fas fa-trash"></i> Eliminar';
    delBtn.addEventListener('click', () => { row.remove(); calcularImportes(); });
    row.appendChild(delBtn);
    row.querySelectorAll('.concepto-cantidad, .concepto-unitario').forEach(i => i.addEventListener('input', calcularImportes));
    container.appendChild(row);
});
</script>
@endpush