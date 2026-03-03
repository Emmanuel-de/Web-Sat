@extends('layouts.app')

@section('title', 'Declaraciones - SAT')

@section('content')

<div class="sat-page-header">
    <div class="container-sat">
        <div class="sat-breadcrumb">
            <a href="{{ route('home') }}">Inicio</a>
            <span class="sat-breadcrumb-sep"><i class="fas fa-chevron-right" style="font-size:10px"></i></span>
            <span>Declaraciones</span>
        </div>
        <h1 class="sat-page-title"><i class="fas fa-file-invoice-dollar" style="margin-right:12px"></i>Declaraciones Fiscales</h1>
        <p class="sat-page-subtitle">Presenta tus declaraciones anuales, provisionales y complementarias</p>
    </div>
</div>

<section class="sat-section">
    <div class="container-sat">
        <div class="sat-tabs-container">
            <div class="sat-tabs">
                <div class="sat-tab active" data-tab="anual">
                    <i class="fas fa-calendar-alt"></i> Anual
                </div>
                <div class="sat-tab" data-tab="provisional">
                    <i class="fas fa-calendar-week"></i> Provisional / Definitiva
                </div>
                <div class="sat-tab" data-tab="complementaria">
                    <i class="fas fa-file-medical"></i> Complementaria
                </div>
                <div class="sat-tab" data-tab="historial">
                    <i class="fas fa-history"></i> Historial
                </div>
            </div>

            <!-- Declaración Anual -->
            <div class="sat-tab-content active" data-tab="anual">
                <div class="sat-info-box">
                    <p><i class="fas fa-info-circle" style="margin-right:8px"></i>
                    La Declaración Anual se presenta en el mes de abril para personas físicas y en marzo para personas morales, correspondiente al ejercicio fiscal del año anterior.</p>
                </div>
                <div class="sat-form-section">
                    <div class="sat-form-header">
                        <div class="sat-form-header-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                        <div class="sat-form-header-text">
                            <h2>Declaración Anual</h2>
                            <p>Ejercicio fiscal {{ date('Y') - 1 }}</p>
                        </div>
                    </div>
                    <div class="sat-form-body">
                        <form action="{{ route('declaraciones.anual.store') }}" method="POST" class="sat-form-ajax">
                            @csrf
                            <div class="sat-form-row cols-3">
                                <div class="sat-form-group">
                                    <label>RFC <span class="required">*</span></label>
                                    <input type="text" name="rfc" class="sat-input" required placeholder="Tu RFC" data-validate="rfc">
                                </div>
                                <div class="sat-form-group">
                                    <label>Ejercicio fiscal <span class="required">*</span></label>
                                    <select name="ejercicio" class="sat-select" required>
                                        @for($y = date('Y')-1; $y >= date('Y')-5; $y--)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="sat-form-group">
                                    <label>Tipo de declaración</label>
                                    <select name="tipo" class="sat-select">
                                        <option value="normal">Normal</option>
                                        <option value="complementaria">Complementaria</option>
                                    </select>
                                </div>
                            </div>

                            <hr style="margin:24px 0;border:none;border-top:1px solid var(--sat-gray-border)">
                            <h3 style="font-size:16px;font-weight:700;margin-bottom:20px;color:var(--sat-dark)">Ingresos del ejercicio</h3>

                            <div class="sat-form-row cols-2">
                                <div class="sat-form-group">
                                    <label>Total de ingresos acumulables</label>
                                    <div style="position:relative">
                                        <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--sat-gray);font-weight:600">$</span>
                                        <input type="number" name="ingresos_acumulables" class="sat-input" placeholder="0.00" step="0.01" min="0" style="padding-left:26px">
                                    </div>
                                </div>
                                <div class="sat-form-group">
                                    <label>Ingresos exentos</label>
                                    <div style="position:relative">
                                        <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--sat-gray);font-weight:600">$</span>
                                        <input type="number" name="ingresos_exentos" class="sat-input" placeholder="0.00" step="0.01" min="0" style="padding-left:26px">
                                    </div>
                                </div>
                            </div>

                            <h3 style="font-size:16px;font-weight:700;margin:20px 0;color:var(--sat-dark)">Deducciones autorizadas</h3>

                            <div class="sat-form-row cols-3">
                                <div class="sat-form-group">
                                    <label>Honorarios médicos</label>
                                    <div style="position:relative">
                                        <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--sat-gray);font-weight:600">$</span>
                                        <input type="number" name="honorarios_medicos" class="sat-input" placeholder="0.00" step="0.01" min="0" style="padding-left:26px">
                                    </div>
                                </div>
                                <div class="sat-form-group">
                                    <label>Gastos hospitalarios</label>
                                    <div style="position:relative">
                                        <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--sat-gray);font-weight:600">$</span>
                                        <input type="number" name="gastos_hospitalarios" class="sat-input" placeholder="0.00" step="0.01" min="0" style="padding-left:26px">
                                    </div>
                                </div>
                                <div class="sat-form-group">
                                    <label>Primas de seguro médico</label>
                                    <div style="position:relative">
                                        <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--sat-gray);font-weight:600">$</span>
                                        <input type="number" name="primas_seguro" class="sat-input" placeholder="0.00" step="0.01" min="0" style="padding-left:26px">
                                    </div>
                                </div>
                            </div>

                            <div class="sat-form-row cols-3">
                                <div class="sat-form-group">
                                    <label>Colegiaturas</label>
                                    <div style="position:relative">
                                        <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--sat-gray);font-weight:600">$</span>
                                        <input type="number" name="colegiaturas" class="sat-input" placeholder="0.00" step="0.01" min="0" style="padding-left:26px">
                                    </div>
                                </div>
                                <div class="sat-form-group">
                                    <label>Intereses reales de crédito hipotecario</label>
                                    <div style="position:relative">
                                        <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--sat-gray);font-weight:600">$</span>
                                        <input type="number" name="intereses_hipotecarios" class="sat-input" placeholder="0.00" step="0.01" min="0" style="padding-left:26px">
                                    </div>
                                </div>
                                <div class="sat-form-group">
                                    <label>Donativos</label>
                                    <div style="position:relative">
                                        <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--sat-gray);font-weight:600">$</span>
                                        <input type="number" name="donativos" class="sat-input" placeholder="0.00" step="0.01" min="0" style="padding-left:26px">
                                    </div>
                                </div>
                            </div>

                            <hr style="margin:24px 0;border:none;border-top:1px solid var(--sat-gray-border)">

                            <div style="background:var(--sat-gray-light);border-radius:8px;padding:20px;margin-bottom:20px">
                                <h3 style="font-size:15px;font-weight:700;margin-bottom:16px">Resumen de ISR</h3>
                                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px">
                                    <div style="text-align:center">
                                        <p style="font-size:12px;color:var(--sat-text-muted)">ISR Determinado</p>
                                        <p style="font-size:22px;font-weight:700;color:var(--sat-dark)" id="isr-determinado">$0.00</p>
                                    </div>
                                    <div style="text-align:center">
                                        <p style="font-size:12px;color:var(--sat-text-muted)">ISR Retenido</p>
                                        <input type="number" name="isr_retenido" class="sat-input" placeholder="0.00" step="0.01" min="0" id="isr-retenido" style="text-align:center">
                                    </div>
                                    <div style="text-align:center;background:white;border-radius:6px;padding:12px;border:2px solid var(--sat-green)">
                                        <p style="font-size:12px;color:var(--sat-text-muted)">Saldo a cargo / favor</p>
                                        <p style="font-size:22px;font-weight:700;color:var(--sat-green)" id="saldo-final">$0.00</p>
                                    </div>
                                </div>
                            </div>

                            <div class="sat-checkbox-group">
                                <label class="sat-checkbox">
                                    <input type="checkbox" name="bajo_protesta" required>
                                    <span class="sat-checkbox-label">Bajo protesta de decir verdad, declaro que los datos manifestados son correctos y que representan fielmente mi situación fiscal del ejercicio. <span class="required">*</span></span>
                                </label>
                            </div>
                        </form>
                    </div>
                    <div class="sat-form-footer">
                        <p class="sat-form-note"><i class="fas fa-calendar"></i> Fecha límite: 30 de abril {{ date('Y') }}</p>
                        <div style="display:flex;gap:12px">
                            <button type="button" class="btn-sat-outline" onclick="calcularISR()">
                                <i class="fas fa-calculator"></i> Calcular
                            </button>
                            <button type="submit" form="declaracionAnualForm" class="btn-sat-green">
                                <i class="fas fa-paper-plane"></i> Presentar Declaración
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagos Provisionales -->
            <div class="sat-tab-content" data-tab="provisional">
                <div class="sat-form-section">
                    <div class="sat-form-header">
                        <div class="sat-form-header-icon"><i class="fas fa-calendar-week"></i></div>
                        <div class="sat-form-header-text">
                            <h2>Pago Provisional / Definitivo</h2>
                            <p>ISR, IVA, IEPS y otros impuestos del periodo</p>
                        </div>
                    </div>
                    <div class="sat-form-body">
                        <form action="{{ route('declaraciones.provisional.store') }}" method="POST" class="sat-form-ajax">
                            @csrf
                            <div class="sat-form-row cols-3">
                                <div class="sat-form-group">
                                    <label>RFC <span class="required">*</span></label>
                                    <input type="text" name="rfc" class="sat-input" required data-validate="rfc" placeholder="Tu RFC">
                                </div>
                                <div class="sat-form-group">
                                    <label>Impuesto <span class="required">*</span></label>
                                    <select name="impuesto" class="sat-select" required id="tipoImpuesto">
                                        <option value="">Seleccionar...</option>
                                        <option value="ISR">ISR - Impuesto Sobre la Renta</option>
                                        <option value="IVA">IVA - Impuesto al Valor Agregado</option>
                                        <option value="IEPS">IEPS</option>
                                    </select>
                                </div>
                                <div class="sat-form-group">
                                    <label>Periodo <span class="required">*</span></label>
                                    <select name="periodo" class="sat-select" required>
                                        <option value="">Seleccionar...</option>
                                        @foreach(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'] as $mes)
                                        <option value="{{ $loop->iteration }}">{{ $mes }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="sat-form-row cols-2">
                                <div class="sat-form-group">
                                    <label>Ejercicio</label>
                                    <select name="ejercicio" class="sat-select">
                                        @for($y = date('Y'); $y >= date('Y')-3; $y--)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="sat-form-group">
                                    <label>Tipo de pago</label>
                                    <select name="tipo_pago" class="sat-select">
                                        <option value="normal">Normal</option>
                                        <option value="complementaria">Complementaria</option>
                                        <option value="extemporanea">Extemporánea</option>
                                    </select>
                                </div>
                            </div>
                            <div class="sat-form-row cols-3">
                                <div class="sat-form-group">
                                    <label>Base del impuesto</label>
                                    <div style="position:relative">
                                        <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--sat-gray);font-weight:600">$</span>
                                        <input type="number" name="base_impuesto" class="sat-input" placeholder="0.00" step="0.01" min="0" style="padding-left:26px">
                                    </div>
                                </div>
                                <div class="sat-form-group">
                                    <label>Tasa o cuota</label>
                                    <input type="number" name="tasa" class="sat-input" placeholder="0.00" step="0.01" min="0" max="100">
                                </div>
                                <div class="sat-form-group">
                                    <label>Importe a pagar</label>
                                    <div style="position:relative">
                                        <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--sat-gray);font-weight:600">$</span>
                                        <input type="number" name="importe" class="sat-input" placeholder="0.00" step="0.01" min="0" style="padding-left:26px;background:#f0f9f4">
                                    </div>
                                </div>
                            </div>
                            <div style="margin-top:16px">
                                <button type="submit" class="btn-sat-green">
                                    <i class="fas fa-paper-plane"></i> Presentar Pago Provisional
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Complementaria -->
            <div class="sat-tab-content" data-tab="complementaria">
                <div class="sat-form-section">
                    <div class="sat-form-header">
                        <div class="sat-form-header-icon"><i class="fas fa-file-medical"></i></div>
                        <div class="sat-form-header-text">
                            <h2>Declaración Complementaria</h2>
                            <p>Corrige o modifica una declaración presentada anteriormente</p>
                        </div>
                    </div>
                    <div class="sat-form-body">
                        <div class="sat-warning-box">
                            <p><i class="fas fa-exclamation-triangle" style="margin-right:8px"></i>
                            La declaración complementaria sustituye a la declaración original o la anterior complementaria. Puedes presentar hasta 3 complementarias por periodo.</p>
                        </div>
                        <form action="{{ route('declaraciones.complementaria.store') }}" method="POST" class="sat-form-ajax">
                            @csrf
                            <div class="sat-form-row cols-2">
                                <div class="sat-form-group">
                                    <label>RFC <span class="required">*</span></label>
                                    <input type="text" name="rfc" class="sat-input" required data-validate="rfc">
                                </div>
                                <div class="sat-form-group">
                                    <label>No. de operación a corregir <span class="required">*</span></label>
                                    <input type="text" name="no_operacion" class="sat-input" required placeholder="Ej: 2024012345678">
                                    <span class="sat-input-hint">Aparece en el acuse de la declaración original</span>
                                </div>
                            </div>
                            <div class="sat-form-group">
                                <label>Motivo de la complementaria</label>
                                <textarea name="motivo" class="sat-textarea" placeholder="Describe el motivo de la corrección..."></textarea>
                            </div>
                            <button type="submit" class="btn-sat-green">
                                <i class="fas fa-search"></i> Buscar declaración
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Historial -->
            <div class="sat-tab-content" data-tab="historial">
                <div class="sat-form-section">
                    <div class="sat-form-header">
                        <div class="sat-form-header-icon"><i class="fas fa-history"></i></div>
                        <div class="sat-form-header-text">
                            <h2>Historial de Declaraciones</h2>
                            <p>Consulta y descarga tus declaraciones presentadas</p>
                        </div>
                    </div>
                    <div class="sat-form-body">
                        @if(isset($declaraciones) && count($declaraciones) > 0)
                        <table class="sat-table">
                            <thead>
                                <tr>
                                    <th>No. Operación</th>
                                    <th>Tipo</th>
                                    <th>Periodo</th>
                                    <th>Fecha presentación</th>
                                    <th>Importe</th>
                                    <th>Estatus</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($declaraciones as $dec)
                                <tr>
                                    <td>{{ $dec->no_operacion }}</td>
                                    <td>{{ $dec->tipo }}</td>
                                    <td>{{ $dec->periodo }}</td>
                                    <td>{{ $dec->fecha_presentacion->format('d/m/Y H:i') }}</td>
                                    <td>${{ number_format($dec->importe, 2) }}</td>
                                    <td><span class="sat-table-badge badge-success">Presentada</span></td>
                                    <td>
                                        <a href="{{ route('declaraciones.acuse', $dec->id) }}" class="btn-sat-outline" style="padding:6px 12px;font-size:12px">
                                            <i class="fas fa-download"></i> Acuse
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div style="text-align:center;padding:48px;color:var(--sat-text-muted)">
                            <i class="fas fa-file-alt" style="font-size:48px;opacity:0.3;margin-bottom:16px;display:block"></i>
                            <p>No hay declaraciones registradas. Ingresa con tu RFC para ver tu historial.</p>
                            <a href="{{ route('login') }}" class="btn-sat-green" style="margin-top:16px;display:inline-flex">
                                <i class="fas fa-sign-in-alt"></i> Iniciar sesión
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection