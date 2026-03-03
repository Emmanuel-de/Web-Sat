// SAT - JavaScript Principal

document.addEventListener('DOMContentLoaded', function () {

    // === NAVEGACIÓN MÓVIL ===
    const navToggle = document.getElementById('navToggle');
    const navMenu = document.getElementById('navMenu');
    if (navToggle && navMenu) {
        navToggle.addEventListener('click', () => {
            navMenu.classList.toggle('open');
            const icon = navToggle.querySelector('i');
            icon.classList.toggle('fa-bars');
            icon.classList.toggle('fa-times');
        });
    }

    // === TABS ===
    document.querySelectorAll('.sat-tab').forEach(tab => {
        tab.addEventListener('click', function () {
            const target = this.dataset.tab;
            const parent = this.closest('.sat-tabs-container');
            if (!parent) return;
            parent.querySelectorAll('.sat-tab').forEach(t => t.classList.remove('active'));
            parent.querySelectorAll('.sat-tab-content').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            const content = parent.querySelector(`.sat-tab-content[data-tab="${target}"]`);
            if (content) content.classList.add('active');
        });
    });

    // === STEPS / WIZARD ===
    let currentStep = 1;
    const steps = document.querySelectorAll('.sat-step');
    const stepContents = document.querySelectorAll('.sat-step-content');

    function goToStep(step) {
        if (step < 1 || step > steps.length) return;
        steps.forEach((s, i) => {
            s.classList.remove('active', 'completed');
            if (i + 1 < step) s.classList.add('completed');
            if (i + 1 === step) s.classList.add('active');
        });
        stepContents.forEach((c, i) => {
            c.classList.remove('active');
            if (i + 1 === step) c.classList.add('active');
        });
        currentStep = step;
        updateStepLines();
    }

    function updateStepLines() {
        document.querySelectorAll('.sat-step-line').forEach((line, i) => {
            line.style.background = i + 1 < currentStep ? 'var(--sat-green)' : 'var(--sat-gray-border)';
        });
    }

    document.querySelectorAll('.btn-next-step').forEach(btn => {
        btn.addEventListener('click', function () {
            if (validateCurrentStep()) goToStep(currentStep + 1);
        });
    });

    document.querySelectorAll('.btn-prev-step').forEach(btn => {
        btn.addEventListener('click', () => goToStep(currentStep - 1));
    });

    if (steps.length > 0) goToStep(1);

    // === VALIDACIÓN DE FORMULARIOS ===
    function validateCurrentStep() {
        const content = document.querySelector(`.sat-step-content[data-step="${currentStep}"]`);
        if (!content) return true;
        let valid = true;
        content.querySelectorAll('[required]').forEach(field => {
            const group = field.closest('.sat-form-group');
            if (!field.value.trim()) {
                valid = false;
                if (group) group.classList.add('has-error');
            } else {
                if (group) group.classList.remove('has-error');
            }
        });
        return valid;
    }

    // Validación en tiempo real
    document.querySelectorAll('.sat-input, .sat-select, .sat-textarea').forEach(input => {
        input.addEventListener('blur', function () {
            const group = this.closest('.sat-form-group');
            if (!group) return;
            if (this.required && !this.value.trim()) {
                group.classList.add('has-error');
            } else {
                group.classList.remove('has-error');
            }
            // Validar RFC
            if (this.dataset.validate === 'rfc') validateRFC(this);
            // Validar CURP
            if (this.dataset.validate === 'curp') validateCURP(this);
        });
    });

    function validateRFC(field) {
        const rfc = field.value.toUpperCase().trim();
        const rfcRegex = /^[A-Z&Ñ]{3,4}\d{6}[A-Z0-9]{3}$/;
        const group = field.closest('.sat-form-group');
        if (rfc && !rfcRegex.test(rfc)) {
            if (group) group.classList.add('has-error');
            showFieldError(group, 'RFC inválido. Formato: AAAA######AAA');
        } else {
            if (group) group.classList.remove('has-error');
        }
        field.value = rfc;
    }

    function validateCURP(field) {
        const curp = field.value.toUpperCase().trim();
        const curpRegex = /^[A-Z]{4}\d{6}[HM][A-Z]{5}[A-Z0-9]\d$/;
        const group = field.closest('.sat-form-group');
        if (curp && !curpRegex.test(curp)) {
            if (group) group.classList.add('has-error');
            showFieldError(group, 'CURP inválido');
        } else {
            if (group) group.classList.remove('has-error');
        }
        field.value = curp;
    }

    function showFieldError(group, msg) {
        if (!group) return;
        let err = group.querySelector('.sat-input-error');
        if (!err) {
            err = document.createElement('span');
            err.className = 'sat-input-error';
            group.appendChild(err);
        }
        err.textContent = msg;
        err.style.display = 'block';
    }

    // === ENVÍO DE FORMULARIOS CON AJAX ===
    document.querySelectorAll('.sat-form-ajax').forEach(form => {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const btn = form.querySelector('[type="submit"]');
            const originalText = btn ? btn.innerHTML : '';
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="sat-spinner"></span>Procesando...';
            }
            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: form.method || 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json',
                    },
                    body: formData,
                });
                const data = await response.json();
                if (response.ok) {
                    showNotification(data.message || 'Operación exitosa', 'success');
                    if (data.redirect) setTimeout(() => window.location.href = data.redirect, 1500);
                    if (data.next_step) goToStep(data.next_step);
                } else {
                    if (data.errors) {
                        Object.entries(data.errors).forEach(([field, msgs]) => {
                            const input = form.querySelector(`[name="${field}"]`);
                            if (input) {
                                const group = input.closest('.sat-form-group');
                                if (group) {
                                    group.classList.add('has-error');
                                    showFieldError(group, msgs[0]);
                                }
                            }
                        });
                    }
                    showNotification(data.message || 'Error al procesar la solicitud', 'error');
                }
            } catch (err) {
                showNotification('Error de conexión. Intente más tarde.', 'error');
            } finally {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            }
        });
    });

    // === NOTIFICACIONES ===
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `sat-alert sat-alert-${type}`;
        notification.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;max-width:400px;animation:slideIn 0.3s ease';
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            ${message}
            <button class="sat-alert-close" onclick="this.parentElement.remove()">×</button>
        `;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 5000);
    }

    // === BÚSQUEDA ===
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            if (this.value.length < 3) return;
            searchTimeout = setTimeout(() => {
                // Implementar búsqueda real en backend
                console.log('Buscar:', this.value);
            }, 500);
        });
        searchInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                window.location.href = `/buscar?q=${encodeURIComponent(this.value)}`;
            }
        });
    }

    // === CONTADOR ANIMADO ===
    const counters = document.querySelectorAll('[data-count]');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                const target = parseFloat(el.dataset.count);
                const duration = 2000;
                const step = target / (duration / 16);
                let current = 0;
                const timer = setInterval(() => {
                    current += step;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    el.textContent = el.dataset.suffix
                        ? current.toFixed(1) + el.dataset.suffix
                        : Math.floor(current).toLocaleString('es-MX');
                }, 16);
                observer.unobserve(el);
            }
        });
    });
    counters.forEach(c => observer.observe(c));

    // === RFC FORMATTER ===
    document.querySelectorAll('[data-validate="rfc"]').forEach(input => {
        input.addEventListener('input', function () {
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9&Ñ]/g, '').slice(0, 13);
        });
    });

    // === SMOOTH SCROLL ===
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
});
