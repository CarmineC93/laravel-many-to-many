<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\ProfileController;
use App\Models\Project;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//rotta che rimanda all'url admin/ e alla funzione index nel DashboardController, 
Route::get('admin/', [DashboardController::class, 'index'])
    //con un passaggio di verifica 
    ->middleware(['auth', 'verified'])
    //e nuovo nome della rotta
    ->name('admin.dashboard');


//rotta che rimanda a tutte le pagine admin e CRUD gestite da ProjectsController
//middelware per la verifica di autenticazione
Route::middleware(['auth', 'verified'])
    // prefix per admin/.. nell'url del browser
    ->prefix('admin')
    //name per il nome della rotta
    ->name('admin.')
    //group per raggruppare rotte che stesse caratteristiche ed evitare di scriverle singolarmente
    ->group(function () {
        //nome della rotta: admin.dashboard
        Route::get('/admin', [DashboardController::class, 'index'])->name('dashboard');
        //questa rotta mappa tutte le rotte in ProjectsController
        Route::resource('projects', ProjectController::class)->parameters(['projects' => 'project:slug']);;
    });


require __DIR__ . '/auth.php';
