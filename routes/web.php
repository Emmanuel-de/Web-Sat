<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Personas\RfcController;
use App\Http\Controllers\Personas\PersonasController;
use App\Http\Controllers\Empresas\EmpresasController;
use App\Http\Controllers\Declaraciones\DeclaracionesController;
use App\Http\Controllers\Facturacion\FacturacionController;
use App\Http\Controllers\Tramites\TramitesController;
use App\Http\Controllers\Contacto\ContactoController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PerfilController;
// ==========================================
//  HOME
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');

// ==========================================
//  AUTENTICACIÓN
// ==========================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/registro', [AuthController::class, 'showRegistro'])->name('registro');
Route::post('/registro', [AuthController::class, 'registro'])->name('registro.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
//  PERSONAS FÍSICAS
// ==========================================
Route::prefix('personas')->name('personas.')->group(function () {
    Route::get('/', [PersonasController::class, 'index'])->name('index');

    // RFC
    Route::get('/rfc', [RfcController::class, 'index'])->name('rfc');
    Route::post('/rfc', [RfcController::class, 'store'])->name('rfc.store');
    Route::post('/rfc/consulta', [RfcController::class, 'consulta'])->name('rfc.consulta');
    Route::post('/rfc/reimpresion', [RfcController::class, 'reimpresion'])->name('rfc.reimpresion');

    // e.firma
    Route::get('/e-firma', [PersonasController::class, 'eFirma'])->name('e_firma');
    Route::post('/e-firma', [PersonasController::class, 'eFirmaStore'])->name('e_firma.store');
    Route::post('/e-firma/verificar', [PersonasController::class, 'eFirmaVerificar'])->name('e_firma.verificar');
    // Constancia de Situación Fiscal
    Route::get('/cif', [PersonasController::class, 'cif'])->name('cif');
    Route::post('/cif', [PersonasController::class, 'cifStore'])->name('cif.store');

    // Declaraciones personas físicas
    Route::get('/declaracion-anual', [PersonasController::class, 'declaracionAnual'])->name('declaracion_anual');
    Route::get('/declaracion-provisional', [PersonasController::class, 'declaracionProvisional'])->name('declaracion_provisional');
    Route::get('/isr', [PersonasController::class, 'isr'])->name('isr');

    // Facturación
    Route::get('/facturacion', [PersonasController::class, 'facturacion'])->name('facturacion');
    Route::get('/verificar-cfdi', [PersonasController::class, 'verificarCfdi'])->name('verificar_cfdi');
    Route::get('/buzon-tributario', [PersonasController::class, 'buzonTributario'])->name('buzontributario');
});

// ==========================================
//  PERSONAS MORALES / EMPRESAS
// ==========================================
Route::prefix('empresas')->name('empresas.')->group(function () {
    Route::get('/', [EmpresasController::class, 'index'])->name('index');

    // Inscripción
    Route::get('/inscripcion', [EmpresasController::class, 'inscripcion'])->name('inscripcion');
    Route::post('/inscripcion', [EmpresasController::class, 'inscripcionStore'])->name('inscripcion.store');
    Route::get('/actualizacion', [EmpresasController::class, 'actualizacion'])->name('actualizacion');
    Route::get('/cancelacion', [EmpresasController::class, 'cancelacion'])->name('cancelacion');

    // Impuestos
    Route::get('/iva', [EmpresasController::class, 'iva'])->name('iva');
    Route::get('/isr', [EmpresasController::class, 'isr'])->name('isr');
    Route::get('/ieps', [EmpresasController::class, 'ieps'])->name('ieps');

    // CFDI
    Route::get('/cfdi', [EmpresasController::class, 'cfdi'])->name('cfdi');
    Route::get('/complementos', [EmpresasController::class, 'complementos'])->name('complementos');
    Route::get('/nomina', [EmpresasController::class, 'nomina'])->name('nomina');
    Route::post('/nomina', [EmpresasController::class, 'nominaStore'])->name('nomina.store');
});

// ==========================================
//  DECLARACIONES
// ==========================================
Route::prefix('declaraciones')->name('declaraciones.')->group(function () {
    Route::get('/', [DeclaracionesController::class, 'index'])->name('index');

    // Anual
    Route::post('/anual', [DeclaracionesController::class, 'anualStore'])->name('anual.store');
    Route::get('/anual/{id}/acuse', [DeclaracionesController::class, 'acuse'])->name('acuse');

    // Provisional
    Route::post('/provisional', [DeclaracionesController::class, 'provisionalStore'])->name('provisional.store');

    // Complementaria
    Route::post('/complementaria', [DeclaracionesController::class, 'complementariaStore'])->name('complementaria.store');
});

// ==========================================
//  FACTURACIÓN ELECTRÓNICA
// ==========================================
Route::prefix('facturacion')->name('facturacion.')->group(function () {
    Route::get('/', [FacturacionController::class, 'index'])->name('index');
    Route::get('/emitir', [FacturacionController::class, 'emitir'])->name('emitir');
    Route::post('/emitir', [FacturacionController::class, 'store'])->name('store');
    Route::get('/verificar', [FacturacionController::class, 'verificar'])->name('verificar');
    Route::post('/verificar', [FacturacionController::class, 'verificarStore'])->name('verificar.store');
    Route::get('/cancelar/{id}', [FacturacionController::class, 'cancelar'])->name('cancelar');
    Route::post('/cancelar/{id}', [FacturacionController::class, 'cancelarStore'])->name('cancelar.store');
    Route::get('/{id}/pdf', [FacturacionController::class, 'pdf'])->name('pdf');
    Route::get('/{id}/xml', [FacturacionController::class, 'xml'])->name('xml');
});

// ==========================================
//  TRÁMITES Y SERVICIOS
// ==========================================
Route::prefix('tramites')->name('tramites.')->group(function () {
    Route::get('/', [TramitesController::class, 'index'])->name('index');

    // Citas
    Route::get('/citas', [TramitesController::class, 'citas'])->name('citas');
    Route::post('/citas', [TramitesController::class, 'citasStore'])->name('citas.store');
    Route::get('/citas/{folio}/cancelar', [TramitesController::class, 'citasCancelar'])->name('citas.cancelar');

    // Consultas
    Route::get('/consultas', [TramitesController::class, 'consultas'])->name('consultas');
    Route::post('/consultas', [TramitesController::class, 'consultasStore'])->name('consultas.store');

    // Opinión de cumplimiento
    Route::get('/opinion-cumplimiento', [TramitesController::class, 'opiniones'])->name('opiniones');
    Route::post('/opinion-cumplimiento', [TramitesController::class, 'opinionesStore'])->name('opiniones.store');

    // Devoluciones
    Route::get('/devoluciones', [TramitesController::class, 'devoluciones'])->name('devoluciones');
    Route::post('/devoluciones', [TramitesController::class, 'devolucionesStore'])->name('devoluciones.store');

    // Compensaciones
    Route::get('/compensaciones', [TramitesController::class, 'compensaciones'])->name('compensaciones');
    Route::get('/saldo-favor', [TramitesController::class, 'saldoFavor'])->name('saldo_favor');
});

// ==========================================
//  CONTACTO
// ==========================================
Route::prefix('contacto')->name('contacto.')->group(function () {
    Route::get('/', [ContactoController::class, 'index'])->name('index');
    Route::post('/mensaje', [ContactoController::class, 'mensajeStore'])->name('mensaje.store');
    Route::post('/queja', [ContactoController::class, 'quejaStore'])->name('queja.store');
});

// ==========================================
//  NOTICIAS
// ==========================================
Route::get('/noticias', function () {
    return view('pages.noticias.index');
})->name('noticias.index');
Route::get('/noticias/{id}', function ($id) {
    return view('pages.noticias.show');
})->name('noticias.show');

// ==========================================
//  API INTERNA (Ajax)
// ==========================================
Route::prefix('api')->name('api.')->group(function () {
    // Consulta de Código Postal
    Route::get('/codigos-postales/{cp}', function ($cp) {
        // Aquí conectarías con la API SEPOMEX o tu base de datos de CPs
        // Retorno de ejemplo:
        return response()->json([
            'estado' => 'Tamaulipas',
            'municipio' => 'Ciudad Victoria',
            'colonias' => ['Centro', 'Jardín', 'Unidad Nacional', 'Las Flores']
        ]);
    })->name('codigos_postales');

    // Módulos por estado (Ajax para el formulario de citas)
    Route::get('/modulos/{estado}', function ($estado) {
        $modulos = \App\Models\ModuloSat::where('estado', $estado)->get();
        return response()->json($modulos);
    })->name('modulos');

    // Validar RFC
    Route::post('/validar-rfc', function (\Illuminate\Http\Request $request) {
        $rfc = strtoupper($request->rfc);
        $valido = preg_match('/^[A-Z&Ñ]{3,4}\d{6}[A-Z0-9]{3}$/', $rfc);
        return response()->json(['valido' => (bool) $valido, 'rfc' => $rfc]);
    })->name('validar_rfc');

    // Buscar declaración por no. de operación
    Route::get('/declaracion/{no_operacion}', [DeclaracionesController::class, 'buscarDeclaracion'])->name('buscar_declaracion');
});

// ==========================================
//  BÚSQUEDA
// ==========================================
Route::get('/buscar', function (\Illuminate\Http\Request $request) {
    $query = $request->q;
    // Implementa tu lógica de búsqueda aquí
    return view('pages.buscar', compact('query'));
})->name('buscar');

Route::post('/registro', [AuthController::class, 'registro'])->name('registro.post');
Route::get('/dashboard', function () {
    return view('pages.dashboard'); // El punto indica que entre a la carpeta 'pages'
})->name('dashboard')->middleware('auth');

// La URL será /perfil, el nombre interno es perfil.index
Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil.index');
Route::put('/perfil/actualizar', [PerfilController::class, 'actualizar'])->name('perfil.actualizar');
Route::put('/perfil/password', [PerfilController::class, 'cambiarPassword'])->name('perfil.password');
Route::post('/perfil/2fa', [PerfilController::class, 'toggle2fa'])->name('perfil.2fa');
Route::post('/perfil/sesiones/cerrar', [PerfilController::class, 'cerrarSesiones'])->name('perfil.sesiones.cerrar');
Route::post('/perfil/sesiones/revocar', [PerfilController::class, 'revocarSesion'])->name('perfil.sesiones.revocar');
Route::get('/perfil/exportar', [PerfilController::class, 'exportar'])->name('perfil.exportar');
Route::delete('/perfil/eliminar', [PerfilController::class, 'eliminar'])->name('perfil.eliminar');

Route::middleware('auth')->group(function () {
    // Esta apunta a resources/views/pages/declaraciones.blade.php
    // Le cambiamos el nombre a 'mis_declaraciones' para que NO choque
    Route::get('/mis-declaraciones', function () {return view('pages.declaraciones'); })->name('declaraciones.usuario');
});
Route::get('/mis-declaraciones', [DeclaracionesController::class, 'misDeclaraciones'])->name('declaraciones.usuario')->middleware('auth');
Route::post('/declaraciones', [DeclaracionesController::class, 'store'])->name('declaraciones.store');
Route::get('/declaraciones/{id}', [DeclaracionesController::class, 'show'])->name('declaraciones.show');
Route::get('/declaraciones/{id}/acuse', [DeclaracionesController::class, 'acuse'])->name('declaraciones.acuse');