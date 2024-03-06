<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CitizenController;
use App\Http\Controllers\mainmenuController;

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
    return view('auth.login');
});



Auth::routes();

/** --------------------------Citizen Controller ----------------------- */
Route::get('/userHome', [CitizenController::class, 'index']);
/** -------------------------- ../Citizen Controller ----------------------- */

/** --------------------------Home Controller ----------------------- */
Route::get('/home', [HomeController::class, 'index'])->name('home');
/** -------------------------- ../ Home Controller ----------------------- */

/** --------------------------Admin Controller ----------------------- */
Route::get('/incident_types', [mainmenuController::class, 'incident_types_view']);
Route::post('/add-incident', [mainmenuController::class, 'add_incident_type']);
/** --------------------------../Admin Controller ----------------------- */
Route::resources([
    '/roles' => RoleController::class,
    '/users' => UserController::class,
]);