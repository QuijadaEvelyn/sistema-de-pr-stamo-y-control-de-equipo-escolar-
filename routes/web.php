
<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\IncidentController;

/*
|--------------------------------------------------------------------------
| STOCK TECNOLÓGICO
| RUTAS WEB
|--------------------------------------------------------------------------
*/


// ==========================================
// RUTAS PÚBLICAS
// ==========================================

// Landing page
Route::get('/', function () {
    return view('landing');
})->name('home');


// ==========================================
// AUTENTICACIÓN
// ==========================================

// Solo usuarios no autenticados
Route::middleware('guest')->group(function () {

    // Mostrar formulario de inicio de sesión
    Route::get('/login', [
        AuthController::class,
        'showLogin'
    ])->name('login');

    // Procesar inicio de sesión
    Route::post('/login', [
        AuthController::class,
        'login'
    ])->name('login.submit');

});


// ==========================================
// RUTAS AUTENTICADAS
// ==========================================

Route::middleware('auth')->group(function () {

    // Cerrar sesión
    Route::post('/logout', [
        AuthController::class,
        'logout'
    ])->name('logout');


    // ======================================
    // DASHBOARD
    // ======================================

    Route::get('/dashboard', [
        DashboardController::class,
        'index'
    ])->name('dashboard');


    // ======================================
    // INVENTARIO - CONSULTA
    // ======================================

    // Ambos roles pueden consultar equipos
    Route::get('/equipment', [
        EquipmentController::class,
        'index'
    ])->name('equipment.index');


    // ======================================
    // PRÉSTAMOS - CONSULTA
    // ======================================

    // Ambos roles pueden consultar préstamos
    Route::get('/loans', [
        LoanController::class,
        'index'
    ])->name('loans.index');

    // Detalle de préstamo
    Route::get('/loans/{loan}', [
        LoanController::class,
        'show'
    ])->name('loans.show');


    // ======================================
    // INCIDENCIAS - CONSULTA
    // ======================================

    Route::get('/incidents', [
        IncidentController::class,
        'index'
    ])->name('incidents.index');


    // ======================================
    // ROL SOLICITANTE
    // ======================================

    Route::middleware('role:solicitante')
        ->group(function () {

            // Crear solicitud de préstamo
            Route::post('/loans', [
                LoanController::class,
                'store'
            ])->name('loans.store');

            // Reportar incidencia
            Route::post('/incidents', [
                IncidentController::class,
                'store'
            ])->name('incidents.store');

        });


    // ======================================
    // ROL ENCARGADO
    // ======================================

    Route::middleware('role:encargado')
        ->group(function () {

            // ==================================
            // CATEGORÍAS
            // ==================================

            Route::get('/categories', [
                CategoryController::class,
                'index'
            ])->name('categories.index');

            Route::post('/categories', [
                CategoryController::class,
                'store'
            ])->name('categories.store');

            Route::put('/categories/{category}', [
                CategoryController::class,
                'update'
            ])->name('categories.update');

            Route::delete('/categories/{category}', [
                CategoryController::class,
                'destroy'
            ])->name('categories.destroy');


            // ==================================
            // EQUIPOS
            // ==================================

            Route::post('/equipment', [
                EquipmentController::class,
                'store'
            ])->name('equipment.store');

            Route::put('/equipment/{equipment}', [
                EquipmentController::class,
                'update'
            ])->name('equipment.update');

            Route::delete('/equipment/{equipment}', [
                EquipmentController::class,
                'destroy'
            ])->name('equipment.destroy');


            // ==================================
            // PRÉSTAMOS
            // ==================================

            // Aprobar solicitud
            Route::post('/loans/{loan}/approve', [
                LoanController::class,
                'approve'
            ])->name('loans.approve');

            // Rechazar solicitud
            Route::post('/loans/{loan}/reject', [
                LoanController::class,
                'reject'
            ])->name('loans.reject');

            // Registrar entrega
            Route::post('/loans/{loan}/deliver', [
                LoanController::class,
                'deliver'
            ])->name('loans.deliver');

            // Registrar devolución
            Route::post('/loans/{loan}/return', [
                LoanController::class,
                'returnLoan'
            ])->name('loans.return');


            // ==================================
            // INCIDENCIAS
            // ==================================

            Route::put('/incidents/{incident}', [
                IncidentController::class,
                'update'
            ])->name('incidents.update');

        });

});
