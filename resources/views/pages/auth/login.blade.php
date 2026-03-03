@extends('layouts.app')
@section('title', 'Iniciar Sesión - Portal SAT')

@push('styles')
<style>
    /* Reutilizamos tu misma base de diseño del registro */
    .login-page {
        min-height: calc(100vh - 120px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        background: linear-gradient(135deg, #f0f9f4 0%, #f8fafc 50%, #fdf0f2 100%);
        position: relative;
        overflow: hidden;
    }
    .login-page::before {
        content: '';
        position: absolute;
        top: -120px;
        right: -120px;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(0, 104, 71, 0.07) 0%, transparent 70%);
        pointer-events: none;
    }
    .login-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 40px rgba(0,0,0,.10), 0 1px 4px rgba(0,0,0,.06);
        width: 100%;
        max-width: 450px; /* Más angosto que el registro */
        overflow: hidden;
        position: relative;
        z-index: 1;
    }
    .login-header {
        background: linear-gradient(135deg, #006847 0%, #004d35 100%);
        padding: 32px 40px;
        text-align: center;
    }
    .reg-logo-box {
        width: 42px;
        height: 42px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: white;
        margin: 0 auto 15px;
    }
    .login-header h1 {
        font-size: 24px;
        font-weight: 700;
        color: white;
        margin-bottom: 5px;
    }
    .login-header p {
        font-size: 14px;
        color: rgba(255,255,255,0.8);
        margin-bottom: 0;
    }
    .login-body { padding: 40px; }
    
    /* Estilos para los inputs con icono (fi-wrap) que ya usas */
    .fi-wrap { position: relative; display: flex; align-items: center; }
    .fi-wrap .fi { position: absolute; left: 14px; color: var(--sat-gray); font-size: 13px; pointer-events: none; z-index: 1; }
    .fi-wrap .sat-input { padding-left: 40px !important; width: 100%; }
    
    .login-footer {
        padding: 20px 40px;
        background: #f8fafc;
        border-top: 1px solid var(--sat-gray-border);
        text-align: center;
        font-size: 14px;
    }
    .login-footer a { color: var(--sat-green); font-weight: 600; text-decoration: none; }
</style>
@endpush

@section('content')
<div class="login-page">
    <div class="login-card">
        {{-- Cabecera idéntica al Registro --}}
        <div class="login-header">
            <div class="reg-logo-box"><i class="fas fa-lock"></i></div>
            <h1>Acceso al Portal</h1>
            <p>Introduce tus credenciales fiscales</p>
        </div>

        <div class="login-body">
            {{-- Errores de validación --}}
            @if($errors->any())
                <div class="sat-info-box" style="background: #fdf0f2; border-color: #f5c6cb; color: #721c24; margin-bottom: 20px;">
                    <ul class="mb-0" style="list-style: none; padding: 0; font-size: 13px;">
                        @foreach($errors->all() as $error)
                            <li><i class="fas fa-exclamation-circle me-2"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                
                <div class="sat-form-group">
                    <label class="fw-bold mb-1" style="font-size: 13px; color: var(--sat-dark);">RFC </label>
                    <div class="fi-wrap">
                        <i class="fas fa-user fi"></i>
                        <input type="text" name="rfc" class="sat-input" placeholder="Ej: GOML850101ABC" required autofocus>
                    </div>
                </div>

                <div class="sat-form-group mt-3">
                    <div class="d-flex justify-content-between">
                        <label class="fw-bold mb-1" style="font-size: 13px; color: var(--sat-dark);">Contraseña</label>
                        <a href="#" class="small text-decoration-none" style="color: var(--sat-green);">¿Olvidaste tu contraseña?</a>
                    </div>
                    <div class="fi-wrap">
                        <i class="fas fa-key fi"></i>
                        <input type="password" name="password" id="login-pwd" class="sat-input" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="mt-3">
                    <label class="sat-checkbox">
                        <input type="checkbox" name="remember">
                        <span class="sat-checkbox-label" style="font-size: 13px;">Recordar mi sesión en este equipo</span>
                    </label>
                </div>

                <button type="submit" class="btn-sat-green w-100 mt-4 py-2">
                    <i class="fas fa-sign-in-alt me-2"></i> Ingresar al Sistema
                </button>
            </form>
        </div>

        <div class="login-footer">
            <span class="text-muted">¿No tienes una cuenta aún?</span><br>
            <a href="{{ route('registro') }}">Regístrate como nuevo contribuyente</a>
        </div>
    </div>
</div>
@endsection