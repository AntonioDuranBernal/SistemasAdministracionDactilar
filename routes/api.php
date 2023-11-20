<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\expedientes\ClientesController;
use App\Http\Controllers\guardavalores\GuardavaloresController;



// Rutas para Clientes expedientes
Route::post('/clienteExpedientesPost', [ClientesController::class, 'storeAPI_clientes_expedientes']);

// Ruta para Clientes guardavalores
Route::post('/clienteGuardavaloresPost', [ClientesController::class, 'storeAPI_clientes_guardavalores']);

// Rutas para Guardavalores
Route::post('/guardavaloresPost', [GuardavaloresController::class, 'storeAPI']);


