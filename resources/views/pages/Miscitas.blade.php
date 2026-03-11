@extends('layouts.dashboard-layout')

@section('title', 'Mis Citas')

@push('styles')
<style>
.page-title{font-size:26px;font-weight:700;color:white;margin-bottom:4px}
.page-sub{font-size:14px;color:rgba(255,255,255,.45);margin-bottom:28px}

/* ── Layout ── */
.citas-layout{display:grid;grid-template-columns:1fr 380px;gap:24px;align-items:start}

/* ── Panel ── */
.panel-card{background:#0d1520;border:1px solid rgba(255,255,255,.07);border-radius:14px;padding:26px}
.panel-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:22px}
.panel-title{font-size:16px;font-weight:700;color:white;display:flex;align-items:center;gap:10px}
.panel-title i{color:#4ade80;font-size:15px}

/* ── Banner ── */
.cita-banner{background:linear-gradient(135deg,#006847 0%,#004d35 100%);border-radius:14px;padding:22px 26px;margin-bottom:24px;display:flex;align-items:center;gap:18px;border:1px solid rgba(0,104,71,.3)}
.cita-banner-icon{width:52px;height:52px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;color:white;flex-shrink:0}
.cita-banner-text h2{font-size:18px;font-weight:700;color:white;margin-bottom:3px}
.cita-banner-text p{font-size:13px;color:rgba(255,255,255,.7)}

/* ── Form ── */
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}
.form-group{display:flex;flex-direction:column;gap:6px}
.form-group.full{grid-column:1/-1}
.form-label{font-size:12px;font-weight:700;color:rgba(255,255,255,.5);text-transform:uppercase;letter-spacing:.5px}
.form-label span{color:#f87171;margin-left:2px}
.form-control{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:10px;padding:11px 14px;font-size:14px;color:white;font-family:inherit;outline:none;transition:all .2s;appearance:none;-webkit-appearance:none}
.form-control::placeholder{color:rgba(255,255,255,.25)}
.form-control:focus{background:rgba(255,255,255,.09);border-color:rgba(0,104,71,.6);box-shadow:0 0 0 3px rgba(0,104,71,.15)}
.form-control:disabled{opacity:.4;cursor:not-allowed}
select.form-control{background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='rgba(255,255,255,0.4)' d='M6 8L1 3h10z'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 14px center;padding-right:36px}
select.form-control option{background:#0d1520;color:white}
textarea.form-control{resize:vertical;min-height:100px}
.form-hint{font-size:11px;color:rgba(255,255,255,.3);margin-top:2px}
.form-divider{height:1px;background:rgba(255,255,255,.06);margin:4px 0 20px;grid-column:1/-1}
.section-label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:rgba(255,255,255,.25);grid-column:1/-1;margin-top:4px}

/* ── Botón submit ── */
.btn-cita{display:inline-flex;align-items:center;gap:10px;padding:13px 28px;background:linear-gradient(135deg,#006847,#00834f);border:none;border-radius:10px;color:white;font-size:15px;font-weight:700;font-family:inherit;cursor:pointer;transition:all .2s;margin-top:8px}
.btn-cita:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(0,104,71,.4)}
.btn-cita:disabled{opacity:.5;cursor:not-allowed;transform:none}

/* ── Alerts ── */
.alert{padding:14px 18px;border-radius:10px;font-size:14px;font-weight:600;margin-bottom:20px;display:flex;align-items:center;gap:10px}
.alert-success{background:rgba(74,222,128,.1);border:1px solid rgba(74,222,128,.25);color:#4ade80}
.alert-error{background:rgba(248,113,113,.1);border:1px solid rgba(248,113,113,.25);color:#f87171}

/* ── Lista de citas ── */
.cita-item{padding:16px;border-radius:10px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06);margin-bottom:10px;transition:background .2s}
.cita-item:hover{background:rgba(255,255,255,.06)}
.cita-item:last-child{margin-bottom:0}
.cita-folio{font-size:11px;font-weight:700;color:rgba(255,255,255,.35);font-family:monospace;margin-bottom:6px}
.cita-tramite{font-size:14px;font-weight:700;color:white;margin-bottom:4px}
.cita-modulo{font-size:12px;color:rgba(255,255,255,.45);margin-bottom:8px;display:flex;align-items:center;gap:5px}
.cita-footer{display:flex;align-items:center;justify-content:space-between}
.cita-fecha{font-size:13px;font-weight:600;color:rgba(255,255,255,.6);display:flex;align-items:center;gap:6px}
.cita-fecha i{color:#4ade80;font-size:11px}
.cita-status{font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px}
.cita-status.confirmada{background:rgba(74,222,128,.15);color:#4ade80}
.cita-status.pendiente{background:rgba(251,146,60,.15);color:#fb923c}
.cita-status.cancelada{background:rgba(248,113,113,.15);color:#f87171}
.cita-status.atendida{background:rgba(96,165,250,.15);color:#60a5fa}
.btn-cancelar{font-size:11px;color:rgba(248,113,113,.6);background:none;border:none;cursor:pointer;font-family:inherit;font-weight:600;padding:0;transition:color .2s}
.btn-cancelar:hover{color:#f87171}

/* ── Empty / Info ── */
.empty-state{text-align:center;padding:36px 20px;color:rgba(255,255,255,.3)}
.empty-state i{font-size:36px;margin-bottom:12px;display:block;color:rgba(255,255,255,.15)}
.empty-state p{font-size:13px}
.info-box{background:rgba(96,165,250,.07);border:1px solid rgba(96,165,250,.15);border-radius:10px;padding:14px 16px;margin-top:20px}
.info-box p{font-size:12px;color:rgba(255,255,255,.45);line-height:1.6}
.info-box p strong{color:rgba(96,165,250,.8)}

@media(max-width:1100px){.citas-layout{grid-template-columns:1fr}}
@media(max-width:640px){.form-grid{grid-template-columns:1fr}}
</style>
@endpush

@section('content')

<div class="page-title">Mis Citas</div>
<div class="page-sub">Agenda y gestiona tus citas en los módulos SAT de toda la República.</div>

@if(session('success'))
 <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
 <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
@endif

<div class="citas-layout">

 {{-- ── Columna izquierda: Formulario ── --}}
 <div>
  <div class="cita-banner">
   <div class="cita-banner-icon"><i class="fas fa-calendar-check"></i></div>
   <div class="cita-banner-text">
    <h2>Agenda tu cita en línea</h2>
    <p>Programa tu visita a cualquier módulo SAT en México</p>
   </div>
  </div>

  <div class="panel-card">
   <div class="panel-header">
    <div class="panel-title"><i class="fas fa-plus-circle"></i> Nueva Cita</div>
   </div>

   <form action="{{ route('contacto.cita.store') }}" method="POST" id="formCita">
    @csrf
    <div class="form-grid">

     <div class="section-label">Datos Personales</div>

     <div class="form-group">
      <label class="form-label">RFC <span>*</span></label>
      <input type="text" name="rfc" class="form-control" placeholder="Tu RFC" value="{{ old('rfc', Auth::user()->rfc ?? '') }}" maxlength="13" style="text-transform:uppercase" required>
      @error('rfc')<span style="font-size:12px;color:#f87171">{{ $message }}</span>@enderror
     </div>

     <div class="form-group">
      <label class="form-label">CURP <span>*</span></label>
      <input type="text" name="curp" class="form-control" placeholder="Tu CURP" value="{{ old('curp', Auth::user()->curp ?? '') }}" maxlength="18" style="text-transform:uppercase" required>
      @error('curp')<span style="font-size:12px;color:#f87171">{{ $message }}</span>@enderror
     </div>

     <div class="form-group">
      <label class="form-label">Nombre completo <span>*</span></label>
      <input type="text" name="nombre" class="form-control" placeholder="Como aparece en tu identificación" value="{{ old('nombre', Auth::user()->nombres.' '.(Auth::user()->primer_apellido ?? '').' '.(Auth::user()->segundo_apellido ?? '')) }}" required>
      @error('nombre')<span style="font-size:12px;color:#f87171">{{ $message }}</span>@enderror
     </div>

     <div class="form-group">
      <label class="form-label">Correo electrónico <span>*</span></label>
      <input type="email" name="email" class="form-control" placeholder="correo@ejemplo.com" value="{{ old('email', Auth::user()->email ?? '') }}" required>
      @error('email')<span style="font-size:12px;color:#f87171">{{ $message }}</span>@enderror
     </div>

     <div class="form-group">
      <label class="form-label">Teléfono</label>
      <input type="tel" name="telefono" class="form-control" placeholder="10 dígitos" value="{{ old('telefono', Auth::user()->telefono ?? '') }}" maxlength="10">
      @error('telefono')<span style="font-size:12px;color:#f87171">{{ $message }}</span>@enderror
     </div>

     <div class="form-divider"></div>
     <div class="section-label">Módulo y Trámite</div>

     <div class="form-group">
      <label class="form-label">Estado <span>*</span></label>
      <select name="estado_filtro" id="selectEstado" class="form-control" required onchange="cargarModulos(this.value)">
       <option value="">Seleccionar estado...</option>
       @foreach($modulos->pluck('estado')->unique()->sort() as $estado)
        <option value="{{ $estado }}" {{ old('estado_filtro') == $estado ? 'selected' : '' }}>{{ $estado }}</option>
       @endforeach
      </select>
     </div>

     <div class="form-group">
      <label class="form-label">Módulo SAT <span>*</span></label>
      <select name="modulo_sat_id" id="selectModulo" class="form-control" required disabled>
       <option value="">Primero selecciona tu estado</option>
      </select>
      @error('modulo_sat_id')<span style="font-size:12px;color:#f87171">{{ $message }}</span>@enderror
     </div>

     <div class="form-group full">
      <label class="form-label">Trámite a realizar <span>*</span></label>
      <select name="tramite" class="form-control" required>
       <option value="">Seleccionar trámite...</option>
       <option value="RFC"         {{ old('tramite')=='RFC'         ? 'selected':'' }}>Inscripción / Actualización de RFC</option>
       <option value="EFIRMA"      {{ old('tramite')=='EFIRMA'      ? 'selected':'' }}>e.firma (Obtención / Renovación)</option>
       <option value="CIF"         {{ old('tramite')=='CIF'         ? 'selected':'' }}>Constancia de Situación Fiscal</option>
       <option value="DECLARACION" {{ old('tramite')=='DECLARACION' ? 'selected':'' }}>Declaración anual / provisional</option>
       <option value="DEVOLUCION"  {{ old('tramite')=='DEVOLUCION'  ? 'selected':'' }}>Devolución de impuestos</option>
       <option value="OPINION"     {{ old('tramite')=='OPINION'     ? 'selected':'' }}>Opinión de cumplimiento</option>
       <option value="FACTURACION" {{ old('tramite')=='FACTURACION' ? 'selected':'' }}>Facturación electrónica (CFDI)</option>
       <option value="OTROS"       {{ old('tramite')=='OTROS'       ? 'selected':'' }}>Otro trámite</option>
      </select>
      @error('tramite')<span style="font-size:12px;color:#f87171">{{ $message }}</span>@enderror
     </div>

     <div class="form-divider"></div>
     <div class="section-label">Fecha y Horario</div>

     <div class="form-group">
      <label class="form-label">Fecha preferida <span>*</span></label>
      <input type="date" name="fecha" id="inputFecha" class="form-control" value="{{ old('fecha') }}" min="{{ now()->addDay()->format('Y-m-d') }}" required>
      <span class="form-hint">Lunes a viernes (días hábiles)</span>
      @error('fecha')<span style="font-size:12px;color:#f87171">{{ $message }}</span>@enderror
     </div>

     <div class="form-group">
      <label class="form-label">Horario preferido <span>*</span></label>
      <select name="horario" class="form-control" required>
       <option value="">Seleccionar...</option>
       @foreach(['09:00 - 09:30','09:30 - 10:00','10:00 - 10:30','10:30 - 11:00','11:00 - 11:30','11:30 - 12:00','12:00 - 12:30','12:30 - 13:00','13:00 - 13:30','13:30 - 14:00','16:00 - 16:30','16:30 - 17:00'] as $h)
        <option value="{{ $h }}" {{ old('horario')==$h ? 'selected':'' }}>{{ str_replace(' - ', ' – ', $h) }}</option>
       @endforeach
      </select>
      @error('horario')<span style="font-size:12px;color:#f87171">{{ $message }}</span>@enderror
     </div>

     <div class="form-group full">
      <label class="form-label">Observaciones adicionales</label>
      <textarea name="observaciones" class="form-control" placeholder="Indica algún requerimiento especial o información adicional para tu cita...">{{ old('observaciones') }}</textarea>
     </div>

    </div>{{-- /form-grid --}}

    <button type="submit" class="btn-cita" id="btnSubmit">
     <i class="fas fa-calendar-check"></i> Confirmar cita
    </button>
   </form>
  </div>
 </div>

 {{-- ── Columna derecha: Mis citas ── --}}
 <div>
  <div class="panel-card">
   <div class="panel-header">
    <div class="panel-title"><i class="fas fa-list-ul"></i> Citas Agendadas</div>
    <span style="font-size:12px;color:rgba(255,255,255,.35)">{{ $misCitas->total() }} total</span>
   </div>

   @if($misCitas->count())
    @foreach($misCitas as $cita)
     <div class="cita-item">
      <div class="cita-folio">{{ $cita->folio }}</div>
      <div class="cita-tramite">{{ $cita->tramite }}</div>
      <div class="cita-modulo">
       <i class="fas fa-map-marker-alt" style="color:#4ade80;font-size:10px"></i>
       {{ $cita->modulo->nombre ?? '—' }}, {{ $cita->modulo->municipio ?? '' }}
      </div>
      <div class="cita-footer">
       <div class="cita-fecha">
        <i class="fas fa-clock"></i>
        {{ \Carbon\Carbon::parse($cita->fecha)->translatedFormat('d M Y') }} · {{ $cita->horario }}
       </div>
       <span class="cita-status {{ $cita->estatus }}">{{ ucfirst($cita->estatus) }}</span>
      </div>
      @if(in_array($cita->estatus, ['confirmada','pendiente']))
       <div style="margin-top:10px;text-align:right">
        <form action="{{ route('tramites.citas.cancelar', $cita->folio) }}" method="GET" style="display:inline">
         <button type="submit" class="btn-cancelar" onclick="return confirm('¿Cancelar esta cita?')">
          <i class="fas fa-times-circle"></i> Cancelar cita
         </button>
        </form>
       </div>
      @endif
     </div>
    @endforeach
    <div style="margin-top:16px">{{ $misCitas->links() }}</div>
   @else
    <div class="empty-state">
     <i class="fas fa-calendar-times"></i>
     <p>No tienes citas agendadas aún.<br>Usa el formulario para programar tu primera cita.</p>
    </div>
   @endif

   <div class="info-box">
    <p>
     <strong>¿Necesitas cancelar?</strong> Puedes cancelar tu cita hasta 24 horas antes de la fecha programada.<br><br>
     <strong>Código de confirmación:</strong> Se envía a tu correo al confirmar la cita.
    </p>
   </div>
  </div>
 </div>

</div>{{-- /citas-layout --}}

@endsection

@push('scripts')
<script>
document.getElementById('inputFecha')?.addEventListener('input', function(){
 const d = new Date(this.value + 'T00:00:00');
 if(d.getDay() === 0 || d.getDay() === 6){
  this.setCustomValidity('Solo días hábiles (lunes a viernes).');
  this.reportValidity();
  this.value = '';
 } else {
  this.setCustomValidity('');
 }
});

const modulosPorEstado = @json($modulos->groupBy('estado'));

function cargarModulos(estado){
 const sel = document.getElementById('selectModulo');
 sel.disabled = true;
 const lista = modulosPorEstado[estado] ?? [];
 sel.innerHTML = '<option value="">Selecciona un módulo</option>';
 lista.forEach(m => {
  const opt = document.createElement('option');
  opt.value = m.id;
  opt.textContent = m.nombre + ' – ' + m.municipio;
  sel.appendChild(opt);
 });
 sel.disabled = lista.length === 0;
}

@if(old('estado_filtro'))
 document.getElementById('selectEstado').value = "{{ old('estado_filtro') }}";
 cargarModulos("{{ old('estado_filtro') }}");
 setTimeout(() => {
  document.getElementById('selectModulo').value = "{{ old('modulo_sat_id') }}";
 }, 50);
@endif

document.getElementById('formCita')?.addEventListener('submit', function(){
 const btn = document.getElementById('btnSubmit');
 btn.disabled = true;
 btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
});
</script>
@endpush