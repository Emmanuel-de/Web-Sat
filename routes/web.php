<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Personas\RfcController;
use App\Http\Controllers\Personas\PersonasController;
use App\Http\Controllers\Personas\MiRfcController;
use App\Http\Controllers\Personas\EFirmaController;
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

    // ── RFC ───────────────────────────────────────────────────────
    Route::get('/rfc', [RfcController::class, 'index'])->name('rfc');
    Route::post('/rfc', [RfcController::class, 'store'])->name('rfc.store');
    Route::post('/rfc/consulta', [RfcController::class, 'consulta'])->name('rfc.consulta');
    Route::post('/rfc/reimpresion', [RfcController::class, 'reimpresion'])->name('rfc.reimpresion');

    // ── e.firma  →  EFirmaController ─────────────────────────────
    Route::get('/e-firma',          [EFirmaController::class, 'index'])    ->name('e_firma');
    Route::post('/e-firma',         [EFirmaController::class, 'store'])    ->name('e_firma.store');
    Route::post('/e-firma/verificar',[EFirmaController::class, 'verificar'])->name('e_firma.verificar');
    Route::get('/e-firma/{id}/descargar-cer', [EFirmaController::class, 'descargarCer'])
    ->name('e_firma.descargar_cer');

    // ── Constancia de Situación Fiscal ────────────────────────────
    Route::get('/cif',  [PersonasController::class, 'cif'])     ->name('cif');
    Route::post('/cif', [PersonasController::class, 'cifStore'])->name('cif.store');

    // ── Declaraciones personas físicas ────────────────────────────
    Route::get('/declaracion-anual',       [PersonasController::class, 'declaracionAnual'])      ->name('declaracion_anual');
    Route::get('/declaracion-provisional', [PersonasController::class, 'declaracionProvisional'])->name('declaracion_provisional');
    Route::get('/isr',                     [PersonasController::class, 'isr'])                   ->name('isr');

    // ── Facturación ───────────────────────────────────────────────
    Route::get('/facturacion',    [PersonasController::class, 'facturacion'])   ->name('facturacion');
    Route::get('/verificar-cfdi', [PersonasController::class, 'verificarCfdi'])->name('verificar_cfdi');
    Route::get('/buzon-tributario',[PersonasController::class, 'buzonTributario'])->name('buzontributario');
});

// ── RFC helpers ───────────────────────────────────────────────────
Route::get('/personas/rfc/constancia/{rfc}', [RfcController::class, 'constancia'])
     ->name('personas.rfc.constancia');
Route::get('/personas/rfc/buscar/{rfc}', [RfcController::class, 'buscar'])
     ->name('personas.rfc.buscar');

Route::get('/mi-rfc', [MiRfcController::class, 'index'])
    ->name('personas.mi_rfc')
    ->middleware('auth');

// ==========================================
//  PERSONAS MORALES / EMPRESAS
// ==========================================
Route::prefix('empresas')->name('empresas.')->group(function () {
    Route::get('/', [EmpresasController::class, 'index'])->name('index');

    Route::get('/inscripcion',  [EmpresasController::class, 'inscripcion'])     ->name('inscripcion');
    Route::post('/inscripcion', [EmpresasController::class, 'inscripcionStore'])->name('inscripcion.store');
    Route::get('/actualizacion',[EmpresasController::class, 'actualizacion'])   ->name('actualizacion');
    Route::get('/cancelacion',  [EmpresasController::class, 'cancelacion'])     ->name('cancelacion');

    Route::get('/iva',  [EmpresasController::class, 'iva']) ->name('iva');
    Route::get('/isr',  [EmpresasController::class, 'isr']) ->name('isr');
    Route::get('/ieps', [EmpresasController::class, 'ieps'])->name('ieps');

    Route::get('/cfdi',         [EmpresasController::class, 'cfdi'])        ->name('cfdi');
    Route::get('/complementos', [EmpresasController::class, 'complementos'])->name('complementos');
    Route::get('/nomina',       [EmpresasController::class, 'nomina'])      ->name('nomina');
    Route::post('/nomina',      [EmpresasController::class, 'nominaStore']) ->name('nomina.store');
});

// ==========================================
//  DECLARACIONES
// ==========================================
Route::prefix('declaraciones')->name('declaraciones.')->group(function () {
    Route::get('/', [DeclaracionesController::class, 'index'])->name('index');

    Route::middleware('auth')->group(function () {
        Route::post('/store',          [DeclaracionesController::class, 'store'])               ->name('store');
        Route::get('/{declaracion}',   [DeclaracionesController::class, 'show'])                ->name('show');
        Route::get('/{declaracion}/acuse', [DeclaracionesController::class, 'acuse'])           ->name('acuse');
        Route::post('/anual',          [DeclaracionesController::class, 'anualStore'])          ->name('anual.store');
        Route::post('/provisional',    [DeclaracionesController::class, 'provisionalStore'])    ->name('provisional.store');
        Route::post('/complementaria', [DeclaracionesController::class, 'complementariaStore'])->name('complementaria.store');
    });
});

Route::get('/mis-declaraciones', [DeclaracionesController::class, 'misDeclaraciones'])
    ->name('declaraciones.usuario')
    ->middleware('auth');

// ==========================================
//  FACTURACIÓN ELECTRÓNICA
// ==========================================
Route::prefix('facturacion')->name('facturacion.')->group(function () {
    Route::get('/',          [FacturacionController::class, 'index'])         ->name('index');
    Route::get('/emitir',    [FacturacionController::class, 'emitir'])        ->name('emitir');
    Route::post('/emitir',   [FacturacionController::class, 'store'])         ->name('store');
    Route::get('/verificar', [FacturacionController::class, 'verificar'])     ->name('verificar');
    Route::post('/verificar',[FacturacionController::class, 'verificarStore'])->name('verificar.store');
    Route::get('/cancelar/{id}',  [FacturacionController::class, 'cancelar'])     ->name('cancelar');
    Route::post('/cancelar/{id}', [FacturacionController::class, 'cancelarStore'])->name('cancelar.store');
    Route::get('/{id}/pdf',  [FacturacionController::class, 'pdf'])->name('pdf');
    Route::get('/{id}/xml',  [FacturacionController::class, 'xml'])->name('xml');
});

Route::get('/mis-facturas', [FacturacionController::class, 'misFacturas'])
    ->name('facturacion.mis_facturas')
    ->middleware('auth');

// ==========================================
//  TRÁMITES Y SERVICIOS
// ==========================================
Route::prefix('tramites')->name('tramites.')->group(function () {
    Route::get('/', [TramitesController::class, 'index'])->name('index');

    Route::get('/citas',                  [TramitesController::class, 'citas'])            ->name('citas');
    Route::post('/citas',                 [TramitesController::class, 'citasStore'])       ->name('citas.store');
    Route::get('/citas/{folio}/cancelar', [TramitesController::class, 'citasCancelar'])   ->name('citas.cancelar');

    Route::get('/consultas',  [TramitesController::class, 'consultas'])     ->name('consultas');
    Route::post('/consultas', [TramitesController::class, 'consultasStore'])->name('consultas.store');

    Route::get('/opinion-cumplimiento',  [TramitesController::class, 'opiniones'])     ->name('opiniones');
    Route::post('/opinion-cumplimiento', [TramitesController::class, 'opinionesStore'])->name('opiniones.store');

    Route::get('/devoluciones',  [TramitesController::class, 'devoluciones'])     ->name('devoluciones');
    Route::post('/devoluciones', [TramitesController::class, 'devolucionesStore'])->name('devoluciones.store');

    Route::get('/compensaciones', [TramitesController::class, 'compensaciones'])->name('compensaciones');
    Route::get('/saldo-favor',    [TramitesController::class, 'saldoFavor'])     ->name('saldo_favor');
});

// ==========================================
//  CONTACTO
// ==========================================
Route::prefix('contacto')->name('contacto.')->group(function () {
    Route::get('/', [ContactoController::class, 'index'])->name('index');
    Route::post('/mensaje', [ContactoController::class, 'mensajeStore'])->name('mensaje.store');
    Route::post('/queja',   [ContactoController::class, 'quejaStore'])  ->name('queja.store');

    Route::get('/citas',              [ContactoController::class, 'citasIndex'])  ->name('citas.index');
    Route::post('/cita',              [ContactoController::class, 'citaStore'])   ->name('cita.store');
    Route::get('/cita/{folio}/cancelar',[ContactoController::class, 'citaCancelar'])->name('cita.cancelar');
});

// ==========================================
//  NOTICIAS
// ==========================================
Route::get('/noticias',      fn() => view('pages.noticias.index'))->name('noticias.index');
Route::get('/noticias/{id}', fn() => view('pages.noticias.show')) ->name('noticias.show');

// ==========================================
//  API INTERNA (Ajax)
// ==========================================
Route::prefix('api')->name('api.')->group(function () {

    // Código Postal
    Route::get('/codigos-postales/{cp}', function ($cp) {
        return response()->json([
            'estado'    => 'Tamaulipas',
            'municipio' => 'Ciudad Victoria',
            'colonias'  => ['Centro', 'Jardín', 'Unidad Nacional', 'Las Flores'],
        ]);
    })->name('codigos_postales');

    // Módulos por estado  →  /api/modulos/Tamaulipas
    Route::get('/modulos/{estado}', [EFirmaController::class, 'modulosPorEstado'])
        ->name('modulos');

    // Autocompletado RFC  →  /api/rfc-autocomplete?q=GOML
    Route::get('/rfc-autocomplete', [EFirmaController::class, 'rfcAutocomplete'])
        ->name('rfc_autocomplete');

    // Validar RFC
    Route::post('/validar-rfc', function (\Illuminate\Http\Request $request) {
        $rfc   = strtoupper($request->rfc);
        $valido = preg_match('/^[A-Z&Ñ]{3,4}\d{6}[A-Z0-9]{3}$/', $rfc);
        return response()->json(['valido' => (bool) $valido, 'rfc' => $rfc]);
    })->name('validar_rfc');

    // Buscar declaración
    Route::get('/declaracion/{no_operacion}', [DeclaracionesController::class, 'buscarDeclaracion'])
        ->name('buscar_declaracion');
});

// ==========================================
//  OTRAS PÁGINAS
// ==========================================
Route::get('/buscar', function (\Illuminate\Http\Request $request) {
    $query = $request->q;
    return view('pages.buscar', compact('query'));
})->name('buscar');

Route::get('/dashboard', fn() => view('pages.dashboard'))
    ->name('dashboard')
    ->middleware('auth');

Route::get('/ayuda', fn() => view('pages.ayuda'))
    ->name('ayuda')
    ->middleware('auth');

// ==========================================
//  PERFIL
// ==========================================
Route::get('/perfil',                   [PerfilController::class, 'index'])          ->name('perfil.index');
Route::put('/perfil/actualizar',        [PerfilController::class, 'actualizar'])     ->name('perfil.actualizar');
Route::put('/perfil/password',          [PerfilController::class, 'cambiarPassword'])->name('perfil.password');
Route::post('/perfil/2fa',              [PerfilController::class, 'toggle2fa'])      ->name('perfil.2fa');
Route::post('/perfil/sesiones/cerrar',  [PerfilController::class, 'cerrarSesiones'])->name('perfil.sesiones.cerrar');
Route::post('/perfil/sesiones/revocar', [PerfilController::class, 'revocarSesion']) ->name('perfil.sesiones.revocar');
Route::get('/perfil/exportar',          [PerfilController::class, 'exportar'])       ->name('perfil.exportar');
Route::delete('/perfil/eliminar',       [PerfilController::class, 'eliminar'])       ->name('perfil.eliminar');


Route::get('/personas/mie-firma', [EFirmaController::class, 'miEfirma'])->name('personas.mie_firma');
Route::get('/personas/e-firma/{id}/descargar', [EFirmaController::class, 'descargarCer'])->name('personas.e_firma.descargar');
Route::get('/personas/mi-constancia', [RfcController::class, 'miConstancia'])
     ->name('personas.mi_constancia');