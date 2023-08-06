<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\CidadesController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\MedicoPacienteController;

Route::controller(AuthController::class)->group(function () {
    Route::get('/user', 'me')->middleware('auth');
    Route::post('/login', 'login');
});

Route::get('/cidades', [CidadesController::class, 'index']);

Route::controller(MedicoController::class)->group(function () {
    Route::get('/medicos', 'index');
    Route::get('/cidades/{id_cidade}/medicos', 'medicosPorCidade');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/medicos', [MedicoController::class, 'store']);

    Route::controller(MedicoPacienteController::class)->group(function () {
        Route::get('/medicos/{id_medico}/pacientes', 'listarPacientesDoMedico');
        Route::post('/medicos/{id_medico}/pacientes', 'relacionarPaciente');
    });

    Route::controller(PacienteController::class)->group(function () {
        Route::post('/pacientes', 'store');
        Route::put('/pacientes/{id}', 'update');
    });
});
