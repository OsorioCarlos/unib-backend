<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CareerDirectorController;
use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\FormulariosController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\GradingCriteriaController;
use App\Http\Controllers\InternshipRepresentativeController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PreProfessionalPracticeController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
//use App\Http\Controllers\UsuarioController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::get('auth/authUser', 'authUser');
    Route::post('auth/login', 'login');
    Route::post('auth/logout', 'logout');
    Route::post('auth/register', 'register');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('usuarios', UserController::class);
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('organizaciones', OrganizationController::class);
    Route::apiResource('directores_carrera', CareerDirectorController::class);
    Route::apiResource('recursos', ResourceController::class);
    Route::apiResource('catalogos', CatalogueController::class);
    Route::apiResource('practicas_preprofesionales', PreProfessionalPracticeController::class);
    Route::apiResource('calificaciones', GradeController::class);
    Route::apiResource('criterios_calificacion', GradingCriteriaController::class);
    Route::apiResource('reportes', ReportController::class);

    Route::controller(FormulariosController::class)->group(function () {
        Route::get('formularios/informacionVSO003/{cedula}', 'obtenerInformacionFormularioVSO003');
        Route::get('formularios/informacionVSO004/{ruc}', 'obtenerInformacionFormularioVSO004');
        Route::get('formularios/informacionVSO005/{cedula}', 'obtenerInformacionFormularioVSO005');
        Route::post('formularios/generar_carta_compromiso', 'generarCartaCompriso');
    });

    Route::controller(StudentController::class)->group(function () {
        Route::post('estudiantes/solicitarPractica', 'requestPractice');
        Route::post('estudiantes/aceptarCompromiso', 'acceptCompromise');
        Route::get('estudiantes/buscarPorCedula/{cedula}', 'buscarEstudiante');
    });

    Route::controller(OrganizationController::class)->group(function () {
        Route::get('organizaciones/buscarPorRUC/{ruc}', 'buscarOrganizacion');
    });

    Route::controller(InternshipRepresentativeController::class)->group(function () {
        Route::get('representantes_practicas/buscarPorCedula/{ruc}', 'buscarRepresentatePracticas');
    });
});

