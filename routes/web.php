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

Route::middleware(['auth', 'verified'])->group(function () {
Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'dashboard'])->name('dashboard');

Route::get('/valueset/create', [ValueSetController::class, 'create'])->name('valueset.create');
Route::post('/valueset/upload', [ValueSetController::class, 'uploadCsv'])->name('valueset.upload');
Route::get('/valueset/get-list', [ValueSetController::class, 'getValuesetList'])->name('valueset.list');
Route::get('/valueset/list', [ValueSetController::class, 'index'])->name('valueset.index');
Route::get('/valueset/compare-list', [ValueSetController::class, 'getValueSetCompareList'])->name('valueset.compare.list');
Route::get('/valueset/medication-list', [ValueSetController::class, 'getValueSetMedicationList'])->name('valueset.medication.list');
Route::post('/valueset/add-to-compare', [ValueSetController::class, 'ValueSetAddToCompare'])->name('valueset.add.compare');
Route::post('/valueset/remove-from-compare', [ValueSetController::class, 'ValueSetRemoveCompare'])->name('valueset.remove.compare');
Route::get('/valueset/compare', [ValueSetController::class, 'ValueSetCompare'])->name('valueset.compare');

Route::get('/medications/create', [MedicationController::class, 'create'])->name('medications.create');
Route::post('/medications/upload', [MedicationController::class, 'uploadCsv'])->name('medications.upload');
Route::get('/medications/get-list', [MedicationController::class, 'getMedications'])->name('medications.list');
Route::get('/medications/list', [MedicationController::class, 'index'])->name('medications.index');
});

Auth::routes();

Route::get('/home', function() {
    return view('home');
})->name('home')->middleware('auth');
