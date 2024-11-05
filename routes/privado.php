<?php

use App\Http\Controllers\PrivadoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PRIVADO Routes
|--------------------------------------------------------------------------
|
*/

Route::post('uploadcsv', [PrivadoController::class, 'uploadcsv'])->name('gfc.privado.uploadcsv');
