<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
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
// });

