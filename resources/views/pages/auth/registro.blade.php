@extends('layouts.app')
@section('title', 'Crear cuenta - Portal SAT')

@push('styles')
<style>
.registro-page{min-height:calc(100vh - 120px);display:flex;align-items:center;justify-content:center;padding:40px 20px;background:linear-gradient(135deg,#f0f9f4 0%,#f8fafc 50%,#fdf0f2 100%);position:relative;overflow:hidden}
.registro-page::before{content:'';position:absolute;top:-120px;right:-120px;width:400px;height:400px;background:radial-gradient(circle,rgba(0,104,71,.07) 0%,transparent 70%);pointer-events:none}
.registro-card{background:white;border-radius:16px;box-shadow:0 4px 40px rgba(0,0,0,.10),0 1px 4px rgba(0,0,0,.06);width:100%;max-width:780px;overflow:hidden;position:relative;z-index:1}
.registro-header{background:linear-gradient(135deg,#006847 0%,#004d35 100%);padding:32px 40px 28px;position:relative;overflow:hidden}
.registro-header::after{content:'';position:absolute;right:-40px;top:-40px;width:180px;height:180px;border:30px solid rgba(255,255,255,.06);border-radius:50%}
.reg-header-logo{display:flex;align-items:center;gap:10px;margin-bottom:18px}
.reg-logo-box{width:36px;height:36px;background:rgba(255,255,255,.15);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:16px;color:white}
.reg-header-logo span{font-size:14px;font-weight:700;color:rgba(255,255,255,.9);letter-spacing:.5px}
.registro-header h1{font-size:26px;font-weight:700;color:white;margin-bottom:6px}
.registro-header p{font-size:14px;color:rgba(255,255,255,.75)}
.reg-progress{display:flex;align-items:center;padding:22px 40px;background:#f8fafc;border-bottom:1px solid var(--sat-gray-border)}
.rp-step{display:flex;align-items:center;gap:10px;flex:1;position:relative}
.rp-step:not(:last-child)::after{content:'';position:absolute;left:calc(50% + 24px);right:calc(-50% + 24px);top:17px;height:2px;background:var(--sat-gray-border);transition:background .3s}
.rp-step.done::after{background:var(--sat-green)}
.rp-num{width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;border:2px solid var(--sat-gray-border);background:white;color:var(--sat-text-muted);flex-shrink:0;transition:all .3s;position:relative;z-index:1}
.rp-step.active .rp-num{border-color:var(--sat-green);background:var(--sat-green);color:white;box-shadow:0 0 0 4px rgba(0,104,71,.15)}
.rp-step.done .rp-num{border-color:var(--sat-green);background:var(--sat-green);color:white}
.rp-lbl{font-size:12px;font-weight:600;color:var(--sat-text-muted)}
.rp-step.active .rp-lbl,.rp-step.done .rp-lbl{color:var(--sat-green)}
.reg-body{padding:36px 40px}
.reg-step{display:none;animation:fadeStep .3s ease}
.reg-step.active{display:block}
@keyframes fadeStep{from{opacity:0;transform:translateX(10px)}to{opacity:1;transform:translateX(0)}}
.reg-step-title{font-size:18px;font-weight:700;color:var(--sat-dark);margin-bottom:4px}
.reg-step-sub{font-size:13px;color:var(--sat-text-muted);margin-bottom:28px}
.fi-wrap{position:relative;display:flex;align-items:center}
.fi-wrap .fi{position:absolute;left:14px;color:var(--sat-gray);font-size:13px;pointer-events:none;z-index:1}
.fi-wrap .sat-input,.fi-wrap .sat-select{padding-left:40px!important}
.tipo-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:24px}
.tipo-card{border:2px solid var(--sat-gray-border);border-radius:10px;padding:20px;cursor:pointer;transition:all .2s;text-align:center;position:relative}
.tipo-card:hover,.tipo-card.selected{border-color:var(--sat-green)}
.tipo-card.selected{background:#f0f9f4;box-shadow:0 2px 12px rgba(0,104,71,.12)}
.tipo-card input{position:absolute;opacity:0;pointer-events:none}
.t-icon{width:52px;height:52px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:22px;margin:0 auto 12px;transition:all .2s}
.tipo-card:not(.selected) .t-icon{background:var(--sat-gray-light);color:var(--sat-gray)}
.tipo-card.selected .t-icon{background:var(--sat-green);color:white}
.tipo-card h4{font-size:14px;font-weight:700;color:var(--sat-dark);margin-bottom:4px}
.tipo-card p{font-size:12px;color:var(--sat-text-muted);line-height:1.4}
.t-check{position:absolute;top:10px;right:10px;width:20px;height:20px;background:var(--sat-green);color:white;border-radius:50%;display:none;align-items:center;justify-content:center;font-size:10px}
.tipo-card.selected .t-check{display:flex}
.rfc-box{background:#f0f9f4;border:1px solid #b8ddd0;border-radius:8px;padding:12px 18px;display:none;align-items:center;gap:14px;margin-top:12px}
.rfc-box.show{display:flex}
.rfc-val{font-family:monospace;font-size:20px;font-weight:700;color:var(--sat-green);letter-spacing:2px;flex:1}
.pwd-bar{height:4px;background:var(--sat-gray-border);border-radius:2px;overflow:hidden;margin-top:7px}
.pwd-fill{height:100%;width:0;border-radius:2px;transition:width .3s,background .3s}
.pwd-lbl{font-size:12px;color:var(--sat-text-muted);margin-top:4px}
.eye-wrap{position:relative}
.eye-wrap .sat-input{padding-right:44px}
.eye-btn{position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--sat-gray);cursor:pointer;font-size:15px;padding:0}
.terms-box{background:#f8fafc;border:1px solid var(--sat-gray-border);border-radius:8px;padding:16px 18px;margin-bottom:24px}
.res-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:24px}
.res-item{background:#f4f6f8;border-radius:8px;padding:12px 16px}
.res-item .lbl{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--sat-text-muted);margin-bottom:4px}
.res-item .val{font-size:15px;font-weight:600;color:var(--sat-dark);word-break:break-all}
.reg-footer{display:flex;align-items:center;justify-content:space-between;padding:24px 40px;border-top:1px solid var(--sat-gray-border);background:#fafbfc}
.reg-footer-note{font-size:13px;color:var(--sat-text-muted)}
.reg-footer-note a{color:var(--sat-green);font-weight:600}
@media(max-width:640px){.registro-header,.reg-body,.reg-footer{padding-left:20px;padding-right:20px}.reg-progress{padding:16px 20px}.res-grid,.tipo-grid{grid-template-columns:1fr}.rp-lbl{display:none}}
</style>
@endpush

@section('content')
<div class="registro-page">
 <div class="registro-card">

  <div class="registro-header">
   <div class="reg-header-logo">
    <div class="reg-logo-box"><i class="fas fa-landmark"></i></div>
    <span>SAT · Servicio de Administración Tributaria</span>
   </div>
   <h1>Crear cuenta</h1>
   <p>Regístrate para acceder a todos los servicios fiscales en línea</p>
  </div>

  <div class="reg-progress" id="reg-prog">
   <div class="rp-step active" id="rp-1"><div class="rp-num">1</div><div class="rp-lbl">Tipo</div></div>
   <div class="rp-step" id="rp-2"><div class="rp-num">2</div><div class="rp-lbl">Datos</div></div>
   <div class="rp-step" id="rp-3"><div class="rp-num">3</div><div class="rp-lbl">Acceso</div></div>
   <div class="rp-step" id="rp-4"><div class="rp-num"><i class="fas fa-check" style="font-size:11px"></i></div><div class="rp-lbl">Confirmar</div></div>
  </div>

  

<form action="{{ route('registro.post') }}" method="POST" id="regForm">
   @csrf
   <div class="reg-body">

    {{-- Paso 1 --}}
    <div class="reg-step active" id="step-1">
     <h2 class="reg-step-title">¿Cómo te registrarás?</h2>
     <p class="reg-step-sub">Selecciona el tipo de contribuyente que corresponde a tu situación</p>
     <div class="tipo-grid">
      <label class="tipo-card selected" id="card-fisica" onclick="selectTipo('fisica')">
       <input type="radio" name="tipo" value="fisica" checked>
       <div class="t-check"><i class="fas fa-check"></i></div>
       <div class="t-icon"><i class="fas fa-user"></i></div>
       <h4>Persona Física</h4>
       <p>Individuo que realiza actividades económicas por cuenta propia</p>
      </label>
      <label class="tipo-card" id="card-moral" onclick="selectTipo('moral')">
       <input type="radio" name="tipo" value="moral">
       <div class="t-check"><i class="fas fa-check"></i></div>
       <div class="t-icon"><i class="fas fa-building"></i></div>
       <h4>Persona Moral</h4>
       <p>Empresas, sociedades y organizaciones constituidas legalmente</p>
      </label>
     </div>
     <div class="sat-info-box" id="info-fisica">
      <p><i class="fas fa-info-circle" style="margin-right:8px"></i>Necesitarás tu <strong>RFC, CURP</strong> e identificación oficial vigente.</p>
     </div>
     <div class="sat-info-box" id="info-moral" style="display:none">
      <p><i class="fas fa-info-circle" style="margin-right:8px"></i>Necesitarás el <strong>RFC de la empresa</strong>, razón social y datos del representante legal.</p>
     </div>
    </div>

    {{-- Paso 2 --}}
    <div class="reg-step" id="step-2">
     <h2 class="reg-step-title">Datos de identificación</h2>
     <p class="reg-step-sub">Ingresa tu información tal como aparece en tu identificación oficial</p>

     <div id="campos-fisica">
      <div class="sat-form-row cols-3">
       <div class="sat-form-group">
        <label>Primer apellido <span class="required">*</span></label>
        <div class="fi-wrap"><i class="fas fa-user fi"></i><input type="text" name="primer_apellido" class="sat-input" placeholder="Apellido paterno" maxlength="50"></div>
        <span class="sat-input-error">Campo requerido</span>
       </div>
       <div class="sat-form-group">
        <label>Segundo apellido</label>
        <div class="fi-wrap"><i class="fas fa-user fi"></i><input type="text" name="segundo_apellido" class="sat-input" placeholder="Apellido materno" maxlength="50"></div>
       </div>
       <div class="sat-form-group">
        <label>Nombre(s) <span class="required">*</span></label>
        <div class="fi-wrap"><i class="fas fa-signature fi"></i><input type="text" name="nombres" class="sat-input" placeholder="Nombre(s)" maxlength="80"></div>
        <span class="sat-input-error">Campo requerido</span>
       </div>
      </div>
      <div class="sat-form-row cols-2">
       <div class="sat-form-group">
        <label>CURP <span class="required">*</span></label>
        <div class="fi-wrap"><i class="fas fa-id-card fi"></i><input type="text" name="curp" id="curp-reg" class="sat-input" placeholder="18 caracteres" maxlength="18" style="text-transform:uppercase;font-family:monospace;letter-spacing:1px"></div>
        <span class="sat-input-hint"><a href="https://www.gob.mx/curp" target="_blank" style="color:var(--sat-green)"><i class="fas fa-external-link-alt" style="font-size:10px"></i> Consulta tu CURP</a></span>
        <span class="sat-input-error">CURP inválido</span>
       </div>
       <div class="sat-form-group">
        <label>Fecha de nacimiento <span class="required">*</span></label>
        <div class="fi-wrap"><i class="fas fa-birthday-cake fi"></i><input type="date" name="fecha_nacimiento" class="sat-input" max="{{ date('Y-m-d') }}"></div>
        <span class="sat-input-error">Fecha requerida</span>
       </div>
      </div>
     </div>

     <div id="campos-moral" style="display:none">
      <div class="sat-form-row cols-2">
       <div class="sat-form-group">
        <label>Razón social <span class="required">*</span></label>
        <div class="fi-wrap"><i class="fas fa-building fi"></i><input type="text" name="razon_social" class="sat-input" placeholder="Nombre oficial de la empresa" maxlength="200"></div>
       </div>
       <div class="sat-form-group">
        <label>Tipo de sociedad <span class="required">*</span></label>
        <div class="fi-wrap"><i class="fas fa-file-contract fi"></i>
         <select name="tipo_sociedad" class="sat-select">
          <option value="">Seleccionar...</option>
          <option>S.A. de C.V.</option><option>S. de R.L. de C.V.</option>
          <option>S.A.S.</option><option>S.C.</option><option>A.C.</option><option>Otro</option>
         </select>
        </div>
       </div>
      </div>
      <div class="sat-form-row cols-2">
       <div class="sat-form-group">
        <label>Representante legal <span class="required">*</span></label>
        <div class="fi-wrap"><i class="fas fa-user-tie fi"></i><input type="text" name="rep_nombre" class="sat-input" placeholder="Nombre completo" maxlength="150"></div>
       </div>
       <div class="sat-form-group">
        <label>RFC del representante <span class="required">*</span></label>
        <div class="fi-wrap"><i class="fas fa-hashtag fi"></i><input type="text" name="rep_rfc" class="sat-input" placeholder="13 caracteres" maxlength="13" style="text-transform:uppercase;font-family:monospace"></div>
       </div>
      </div>
     </div>

     <div class="sat-form-row cols-2">
      <div class="sat-form-group">
       <label>RFC <span class="required">*</span></label>
       <div class="fi-wrap"><i class="fas fa-hashtag fi"></i><input type="text" name="rfc" id="rfc-reg" class="sat-input" placeholder="Ej: GOML850101ABC" maxlength="13" style="text-transform:uppercase;font-family:monospace;letter-spacing:1px" autocomplete="off"></div>
       <span class="sat-input-error">RFC inválido</span>
      </div>
      <div class="sat-form-group">
       <label>Teléfono celular <span class="required">*</span></label>
       <div class="fi-wrap"><i class="fas fa-mobile-alt fi"></i><input type="tel" name="telefono" class="sat-input" placeholder="10 dígitos" maxlength="10"></div>
       <span class="sat-input-error">Teléfono requerido</span>
      </div>
     </div>
     <div class="rfc-box" id="rfc-preview">
      <i class="fas fa-id-badge" style="color:var(--sat-green);font-size:20px"></i>
      <div style="flex:1"><div style="font-size:11px;color:var(--sat-text-muted);margin-bottom:2px">RFC ingresado</div><div class="rfc-val" id="rfc-val-text">—</div></div>
      <div style="font-size:12px;color:var(--sat-text-muted);text-align:right" id="rfc-tipo-lbl">—</div>
     </div>
    </div>

    {{-- Paso 3 --}}
    <div class="reg-step" id="step-3">
     <h2 class="reg-step-title">Datos de acceso</h2>
     <p class="reg-step-sub">Configura tu correo y contraseña para ingresar al portal</p>
     <div class="sat-form-row cols-2">
      <div class="sat-form-group">
       <label>Correo electrónico <span class="required">*</span></label>
       <div class="fi-wrap"><i class="fas fa-envelope fi"></i><input type="email" name="email" id="email-reg" class="sat-input" placeholder="correo@ejemplo.com"></div>
       <span class="sat-input-hint">Recibirás notificaciones y avisos fiscales</span>
       <span class="sat-input-error">Correo inválido</span>
      </div>
      <div class="sat-form-group">
       <label>Confirmar correo <span class="required">*</span></label>
       <div class="fi-wrap"><i class="fas fa-envelope fi"></i><input type="email" name="email_confirmation" id="email-c-reg" class="sat-input" placeholder="correo@ejemplo.com" autocomplete="off"></div>
       <span class="sat-input-error">Los correos no coinciden</span>
      </div>
     </div>
     <div class="sat-form-row cols-2">
      <div class="sat-form-group">
       <label>Contraseña <span class="required">*</span></label>
       <div class="eye-wrap"><input type="password" name="password" id="pwd-reg" class="sat-input" placeholder="Mínimo 8 caracteres"><button type="button" class="eye-btn" data-target="pwd-reg"><i class="fas fa-eye"></i></button></div>
       <div class="pwd-bar"><div class="pwd-fill" id="pwd-fill"></div></div>
       <div class="pwd-lbl" id="pwd-lbl">Ingresa una contraseña</div>
       <span class="sat-input-error">Mínimo 8 caracteres</span>
      </div>
      <div class="sat-form-group">
       <label>Confirmar contraseña <span class="required">*</span></label>
       <div class="eye-wrap"><input type="password" name="password_confirmation" id="pwd-c" class="sat-input" placeholder="Repite la contraseña"><button type="button" class="eye-btn" data-target="pwd-c"><i class="fas fa-eye"></i></button></div>
       <span class="sat-input-error">Las contraseñas no coinciden</span>
      </div>
     </div>
     <div class="terms-box">
      <label class="sat-checkbox">
       <input type="checkbox" name="acepta_terminos" id="check-terms" required>
       <span class="sat-checkbox-label">Acepto los <a href="#" style="color:var(--sat-green);font-weight:600">Términos y Condiciones</a> y el <a href="#" style="color:var(--sat-green);font-weight:600">Aviso de Privacidad</a> del SAT. <span class="required">*</span></span>
      </label>
      <label class="sat-checkbox" style="margin-top:10px">
       <input type="checkbox" name="acepta_notificaciones">
       <span class="sat-checkbox-label">Deseo recibir notificaciones y avisos fiscales del SAT por correo.</span>
      </label>
     </div>
    </div>

    {{-- Paso 4 --}}
    <div class="reg-step" id="step-4">
     <h2 class="reg-step-title">Confirma tu información</h2>
     <p class="reg-step-sub">Revisa que todo esté correcto antes de crear tu cuenta</p>
     <div class="res-grid">
      <div class="res-item"><div class="lbl">Tipo de contribuyente</div><div class="val" id="r-tipo">—</div></div>
      <div class="res-item"><div class="lbl">RFC</div><div class="val" style="font-family:monospace;letter-spacing:1px" id="r-rfc">—</div></div>
      <div class="res-item" id="r-curp-box"><div class="lbl">CURP</div><div class="val" style="font-family:monospace;font-size:13px" id="r-curp">—</div></div>
      <div class="res-item"><div class="lbl">Nombre completo</div><div class="val" id="r-nombre">—</div></div>
      <div class="res-item"><div class="lbl">Correo electrónico</div><div class="val" style="font-size:13px" id="r-email">—</div></div>
      <div class="res-item"><div class="lbl">Teléfono</div><div class="val" id="r-tel">—</div></div>
     </div>
     <div class="sat-info-box">
      <p><i class="fas fa-info-circle" style="margin-right:8px"></i>Al crear tu cuenta aceptas que el SAT puede enviarte notificaciones fiscales. Podrás actualizar tus datos en cualquier momento desde tu perfil.</p>
     </div>
    </div>

   </div>{{-- /reg-body --}}

   <div class="reg-footer">
    <div class="reg-footer-note">¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión aquí</a></div>
    <div style="display:flex;gap:12px">
     <button type="button" class="btn-sat-outline" id="btn-prev" style="display:none" onclick="prevStep()"><i class="fas fa-arrow-left"></i> Anterior</button>
     <button type="button" class="btn-sat-green" id="btn-next" onclick="nextStep()">Continuar <i class="fas fa-arrow-right"></i></button>
     <button type="submit" class="btn-sat-green" id="btn-submit" style="display:none"><i class="fas fa-user-plus"></i> Crear cuenta</button>
    </div>
   </div>
  </form>
 </div>
</div>
@endsection

@push('scripts')
<script>
let step=1,tipo='fisica';
function selectTipo(t){
 tipo=t;
 ['fisica','moral'].forEach(x=>{
  document.getElementById('card-'+x).classList.toggle('selected',x===t);
  document.getElementById('info-'+x).style.display=x===t?'':'none';
  document.getElementById('campos-'+x).style.display=x===t?'':'none';
 });
 document.querySelector('[name="tipo"][value="'+t+'"]').checked=true;
}
function goTo(n){
 document.querySelectorAll('.reg-step').forEach((el,i)=>el.classList.toggle('active',i+1===n));
 for(let i=1;i<=4;i++){
  const rp=document.getElementById('rp-'+i);
  rp.classList.remove('active','done');
  if(i<n) rp.classList.add('done');
  if(i===n) rp.classList.add('active');
  if(i<4) rp.querySelector('.rp-num').innerHTML=i<n?'<i class="fas fa-check" style="font-size:11px"></i>':i;
 }
 document.getElementById('btn-prev').style.display=n>1?'':'none';
 document.getElementById('btn-next').style.display=n<4?'':'none';
 document.getElementById('btn-submit').style.display=n===4?'':'none';
 if(n===4)fillResumen();
 step=n;
 window.scrollTo({top:0,behavior:'smooth'});
}
function nextStep(){if(validate(step))goTo(step+1);}
function prevStep(){goTo(step-1);}
function validate(s){
 if(s===1)return true;
 if(s===2){
  const rfc=document.getElementById('rfc-reg')?.value.trim();
  if(!/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/i.test(rfc)){showErr(document.getElementById('rfc-reg'),'RFC inválido');return false;}
  if(tipo==='fisica'){
   const curp=document.getElementById('curp-reg')?.value.trim();
   if(curp.length!==18){showErr(document.getElementById('curp-reg'),'CURP inválido (18 caracteres)');return false;}
   const nom=document.querySelector('[name="nombres"]')?.value.trim();
   if(!nom){showErr(document.querySelector('[name="nombres"]'),'Campo requerido');return false;}
  }
  return true;
 }
 if(s===3){
  const em=document.getElementById('email-reg')?.value.trim();
  const ec=document.getElementById('email-c-reg')?.value.trim();
  const pw=document.getElementById('pwd-reg')?.value;
  const pc=document.getElementById('pwd-c')?.value;
  if(!em||!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(em)){showErr(document.getElementById('email-reg'),'Correo inválido');return false;}
  if(em!==ec){showErr(document.getElementById('email-c-reg'),'Los correos no coinciden');return false;}
  if(!pw||pw.length<8){showErr(document.getElementById('pwd-reg'),'Mínimo 8 caracteres');return false;}
  if(pw!==pc){showErr(document.getElementById('pwd-c'),'Las contraseñas no coinciden');return false;}
  if(!document.getElementById('check-terms')?.checked){alert('Debes aceptar los Términos y Condiciones.');return false;}
  return true;
 }
 return true;
}
function showErr(el,msg){
 if(!el)return;
 const g=el.closest('.sat-form-group');
 g?.classList.add('has-error');
 const e=g?.querySelector('.sat-input-error');
 if(e){e.textContent=msg;e.style.display='block';}
 el.focus();
}
function fillResumen(){
 const g=n=>document.querySelector('[name="'+n+'"]')?.value||'—';
 const nom=tipo==='fisica'?[g('primer_apellido'),g('segundo_apellido'),g('nombres')].filter(x=>x!=='—').join(' '):g('razon_social');
 document.getElementById('r-tipo').textContent=tipo==='fisica'?'Persona Física':'Persona Moral';
 document.getElementById('r-rfc').textContent=g('rfc').toUpperCase();
 document.getElementById('r-nombre').textContent=nom;
 document.getElementById('r-email').textContent=g('email');
 document.getElementById('r-tel').textContent=g('telefono');
 const cb=document.getElementById('r-curp-box');
 if(tipo==='fisica'){document.getElementById('r-curp').textContent=g('curp').toUpperCase();cb.style.display='';}
 else cb.style.display='none';
}
document.getElementById('rfc-reg')?.addEventListener('input',function(){
 this.value=this.value.toUpperCase().replace(/[^A-ZÑ&0-9]/g,'');
 const ok=/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/.test(this.value);
 const box=document.getElementById('rfc-preview');
 if(ok){box.classList.add('show');document.getElementById('rfc-val-text').textContent=this.value;document.getElementById('rfc-tipo-lbl').textContent=this.value.length===12?'Persona Moral':'Persona Física';}
 else box.classList.remove('show');
 this.closest('.sat-form-group')?.classList.toggle('has-error',this.value.length>3&&!ok);
});
document.getElementById('curp-reg')?.addEventListener('input',function(){this.value=this.value.toUpperCase().replace(/[^A-Z0-9]/g,'');});
document.getElementById('pwd-reg')?.addEventListener('input',function(){
 const v=this.value;let s=0;
 if(v.length>=8)s++;if(/[A-Z]/.test(v))s++;if(/[0-9]/.test(v))s++;if(/[^A-Za-z0-9]/.test(v))s++;
 const f=document.getElementById('pwd-fill'),l=document.getElementById('pwd-lbl');
 const c=['#e74c3c','#e67e22','#f1c40f','#27ae60'],t=['Muy débil','Débil','Regular','Segura'];
 f.style.width=(s*25)+'%';f.style.background=c[s-1]||'#eee';
 l.textContent=s>0?t[s-1]:'Ingresa una contraseña';
});
document.getElementById('pwd-c')?.addEventListener('input',function(){
 const m=document.getElementById('pwd-reg')?.value;
 const g=this.closest('.sat-form-group');
 g?.classList.toggle('has-error',this.value.length>0&&this.value!==m);
 g?.classList.toggle('has-success',this.value.length>0&&this.value===m);
});
document.getElementById('email-c-reg')?.addEventListener('blur',function(){
 const m=document.getElementById('email-reg')?.value;
 this.closest('.sat-form-group')?.classList.toggle('has-error',this.value.length>0&&this.value!==m);
});
document.querySelectorAll('.eye-btn').forEach(b=>b.addEventListener('click',function(){
 const i=document.getElementById(this.dataset.target),ic=this.querySelector('i');
 if(!i)return;i.type=i.type==='password'?'text':'password';
 ic.classList.toggle('fa-eye',i.type==='password');ic.classList.toggle('fa-eye-slash',i.type==='text');
}));
document.querySelectorAll('.sat-input,.sat-select').forEach(el=>el.addEventListener('input',function(){this.closest('.sat-form-group')?.classList.remove('has-error');}));
</script>
@endpush