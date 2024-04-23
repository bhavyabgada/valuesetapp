<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ValueSetController;
use App\Http\Controllers\MedicationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/valueset/create', [ValueSetController::class, 'create'])->name('valueset.create');
Route::post('/valueset/upload', [ValueSetController::class, 'uploadCsv'])->name('valueset.upload');

Route::get('/medications/create', [MedicationController::class, 'create'])->name('medications.create');
Route::post('/medications/upload', [MedicationController::class, 'uploadCsv'])->name('medications.upload');

