document.addEventListener('DOMContentLoaded', function () {

    // ─── TABS ────────────────────────────────────────────────────────────────
    const tabs        = document.querySelectorAll('.sat-tab');
    const tabContents = document.querySelectorAll('.sat-tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            const target = this.dataset.tab;

            tabs.forEach(t => t.classList.remove('active'));
            tabContents.forEach(tc => tc.classList.remove('active'));

            this.classList.add('active');
            document.querySelector(`.sat-tab-content[data-tab="${target}"]`)
                    ?.classList.add('active');
        });
    });

    // ─── WIZARD STEPS ────────────────────────────────────────────────────────
    const stepContents = document.querySelectorAll('.sat-step-content');
    const stepBullets  = document.querySelectorAll('.sat-step');
    let   currentStep  = 1;

    function goToStep(n) {
        // Ocultar todos los contenidos
        stepContents.forEach(sc => sc.classList.remove('active'));
        // Mostrar el paso destino
        const target = document.querySelector(`.sat-step-content[data-step="${n}"]`);
        if (target) target.classList.add('active');

        // Actualizar bullets del stepper
        stepBullets.forEach((sb, i) => {
            sb.classList.remove('active', 'completed');
            if (i + 1 < n)  sb.classList.add('completed');
            if (i + 1 === n) sb.classList.add('active');
        });

        currentStep = n;

        // Si llegamos al paso 4, construir el resumen
        if (n === 4) buildResumen();

        // Scroll suave al inicio del wizard
        document.querySelector('.sat-tabs-container')
                ?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    // Validación básica de campos requeridos dentro de un step
    function validateStep(stepNum) {
        const container = document.querySelector(`.sat-step-content[data-step="${stepNum}"]`);
        if (!container) return true;

        let valid = true;
        const required = container.querySelectorAll('[required]');

        required.forEach(field => {
            const group = field.closest('.sat-form-group');
            if (!field.value.trim()) {
                valid = false;
                group?.classList.add('has-error');
                field.focus();
            } else {
                group?.classList.remove('has-error');
            }
        });

        // Validación CURP (18 chars alfanumérico)
        const curpInput = container.querySelector('[data-validate="curp"]');
        if (curpInput && curpInput.value) {
            const curpReg = /^[A-Z]{4}\d{6}[HM][A-Z]{5}[A-Z0-9]\d$/i;
            if (!curpReg.test(curpInput.value.trim())) {
                curpInput.closest('.sat-form-group')?.classList.add('has-error');
                valid = false;
            }
        }

        // Validación RFC
        const rfcInput = container.querySelector('[data-validate="rfc"]');
        if (rfcInput && rfcInput.value) {
            const rfcReg = /^[A-Z&Ñ]{3,4}\d{6}[A-Z0-9]{3}$/i;
            if (!rfcReg.test(rfcInput.value.trim())) {
                rfcInput.closest('.sat-form-group')?.classList.add('has-error');
                valid = false;
            }
        }

        if (!valid) {
            // Mostrar mensaje general si falla
            showToast('Por favor completa todos los campos requeridos.', 'error');
        }

        return valid;
    }

    // Limpiar error al escribir
    document.querySelectorAll('.sat-input, .sat-select, .sat-textarea').forEach(field => {
        field.addEventListener('input', function () {
            this.closest('.sat-form-group')?.classList.remove('has-error');
        });
        field.addEventListener('change', function () {
            this.closest('.sat-form-group')?.classList.remove('has-error');
        });
    });

    // Botones "Siguiente"
    document.querySelectorAll('.btn-next-step').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            if (validateStep(currentStep)) {
                goToStep(currentStep + 1);
            }
        });
    });

    // Botones "Anterior"
    document.querySelectorAll('.btn-prev-step').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            goToStep(currentStep - 1);
        });
    });

    // ─── RESUMEN EN PASO 4 ───────────────────────────────────────────────────
    function val(name) {
        const el = document.querySelector(`[name="${name}"]`);
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
                        <i class="fas fa-user" style="margin-right:6px"></i>Datos Personales
                    </p>
                    <table class="sat-table" style="font-size:14px">
                        <tbody>
                            <tr><th style="width:200px">Nombre completo</th>
                                <td>${val('nombres')} ${val('primer_apellido')} ${val('segundo_apellido')}</td></tr>
                            <tr><th>Fecha de nacimiento</th><td>${val('fecha_nacimiento')}</td></tr>
                            <tr><th>Sexo</th><td>${val('sexo') === 'H' ? 'Hombre' : val('sexo') === 'M' ? 'Mujer' : val('sexo')}</td></tr>
                            <tr><th>Estado de nacimiento</th><td>${val('estado_nacimiento')}</td></tr>
                            <tr><th>CURP</th><td>${val('curp')}</td></tr>
                            <tr><th>Correo electrónico</th><td>${val('email')}</td></tr>
                            <tr><th>Teléfono</th><td>${val('telefono')}</td></tr>
                            <tr><th>Identificación oficial</th><td>${val('tipo_identificacion')}</td></tr>
                        </tbody>
                    </table>
                </div>
                <div>
                    <p style="font-size:13px;font-weight:700;color:var(--sat-green);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">
                        <i class="fas fa-map-marker-alt" style="margin-right:6px"></i>Domicilio Fiscal
                    </p>
                    <table class="sat-table" style="font-size:14px">
                        <tbody>
                            <tr><th style="width:200px">Código Postal</th><td>${val('codigo_postal')}</td></tr>
                            <tr><th>Estado</th><td>${val('estado')}</td></tr>
                            <tr><th>Municipio / Alcaldía</th><td>${val('municipio')}</td></tr>
                            <tr><th>Colonia</th><td>${val('colonia')}</td></tr>
                            <tr><th>Calle</th><td>${val('calle')} ${val('no_exterior')} ${val('no_interior') !== '—' ? 'Int. ' + val('no_interior') : ''}</td></tr>
                        </tbody>
                    </table>
                </div>
                <div>
                    <p style="font-size:13px;font-weight:700;color:var(--sat-green);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">
                        <i class="fas fa-briefcase" style="margin-right:6px"></i>Actividad Económica
                    </p>
                    <table class="sat-table" style="font-size:14px">
                        <tbody>
                            <tr><th style="width:200px">Régimen fiscal</th><td>${val('regimen_fiscal')}</td></tr>
                            <tr><th>Actividad principal</th><td>${val('actividad_principal')}</td></tr>
                            <tr><th>Inicio de actividades</th><td>${val('fecha_inicio_actividades')}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>`;
    }

    // ─── EVITAR SUBMIT ACCIDENTAL (solo paso 4 lo permite) ──────────────────
    const rfcForm = document.getElementById('rfcForm');
    if (rfcForm) {
        rfcForm.addEventListener('submit', function (e) {
            // Si el submit viene de un botón que NO es el de envío final, cancelar
            if (currentStep !== 4) {
                e.preventDefault();
            }
        });
    }

    // ─── TOAST HELPER ────────────────────────────────────────────────────────
    function showToast(msg, type = 'info') {
        const existing = document.getElementById('sat-toast');
        if (existing) existing.remove();

        const toast = document.createElement('div');
        toast.id = 'sat-toast';
        toast.style.cssText = `
            position:fixed;bottom:24px;right:24px;z-index:9999;
            padding:14px 20px;border-radius:8px;font-size:14px;font-weight:500;
            display:flex;align-items:center;gap:10px;
            box-shadow:0 4px 20px rgba(0,0,0,.18);
            background:${type === 'error' ? '#c0392b' : '#1a7a42'};
            color:#fff;animation:fadeInUp .3s ease;
        `;
        toast.innerHTML = `<i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'check-circle'}"></i> ${msg}`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 4000);
    }

    // Agregar animación CSS si no existe
    if (!document.getElementById('sat-toast-style')) {
        const style = document.createElement('style');
        style.id = 'sat-toast-style';
        style.textContent = `
            @keyframes fadeInUp {
                from { opacity:0; transform:translateY(16px); }
                to   { opacity:1; transform:translateY(0); }
            }
            .sat-form-group.has-error .sat-input,
            .sat-form-group.has-error .sat-select,
            .sat-form-group.has-error .sat-textarea {
                border-color: #c0392b !important;
                box-shadow: 0 0 0 3px rgba(192,57,43,.15) !important;
            }
            .sat-form-group.has-error .sat-input-error {
                display: block !important;
                color: #c0392b;
                font-size: 12px;
                margin-top: 4px;
            }
            .sat-step.completed .sat-step-num {
                background: var(--sat-green) !important;
                color: #fff !important;
            }
            .sat-step.completed .sat-step-num::after {
                content: '✓';
            }
        `;
        document.head.appendChild(style);
    }

});