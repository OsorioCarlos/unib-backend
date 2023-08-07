<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CareerDirectorController;
use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\GradingCriteriaController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PreProfessionalPracticeController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
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
    Route::post('login', 'login');
    Route::post('logout', 'logout');
    Route::post('register', 'register');
});

// Route::middleware('auth:sanctum')->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('usuarios', 'getAll');
        Route::get('usuarios/{id}', 'getById');
        Route::post('usuarios', 'create');
        Route::put('usuarios', 'update');
        Route::delete('usuarios/{id}', 'delete');
    });

    Route::controller(StudentController::class)->group(function () {
        Route::post('estudiantes/solicitarPractica', 'requestPractice');
        Route::post('estudiantes/aceptarCompromiso', 'acceptCompromise');
    });

    Route::apiResource('roles', RoleController::class);
    Route::apiResource('organizaciones', OrganizationController::class);
    Route::apiResource('directores_carrera', CareerDirectorController::class);
    Route::apiResource('recursos', ResourceController::class);
    Route::apiResource('catalogos', CatalogueController::class);
    Route::apiResource('practicas_preprofesionales', PreProfessionalPracticeController::class);
    Route::apiResource('calificaciones', GradeController::class);
    Route::apiResource('criterios_calificacion', GradingCriteriaController::class);
// });

