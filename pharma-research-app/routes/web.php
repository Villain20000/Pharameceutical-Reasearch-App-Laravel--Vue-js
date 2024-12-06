<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group.
|
*/

// API routes are handled in routes/api.php and are prefixed with /api automatically

// All other routes should return the SPA
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '^(?!api).*$');
