<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CitizenController;
use App\Http\Controllers\mainmenuController;
use App\Http\Controllers\RoutingController;
use Illuminate\Routing\Events\Routing;

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
Route::post('/add-incident-report', [CitizenController::class, 'add_incident_report']);
/** -------------------------- ../Citizen Controller ----------------------- */

/** --------------------------Home Controller ----------------------- */
Route::get('/home', [HomeController::class, 'index'])->name('home');
/** -------------------------- ../ Home Controller ----------------------- */

/** --------------------------Admin Controller ----------------------- */
Route::get('/incident_types', [mainmenuController::class, 'incident_types_view']);
Route::post('/add-incident', [mainmenuController::class, 'add_incident_type']);
Route::get('/fetch-incident-tbl', [mainmenuController::class, 'fetchIncidentTbl']);
Route::delete('/delete-incident/{id}', [mainmenuController::class, 'deleteIncidentType']);
Route::get('/get-incident-type/{id}', [mainmenuController::class, 'getIncidentType']);
Route::post('/update-incident-type/{id}', [mainmenuController::class, 'updateIncidentType']);


/** --------------------------Reports Tab----------------------- */
Route::get('/reports', [mainmenuController::class, 'reports_view']);
Route::get('/fetch-incidents-map', [mainmenuController::class, 'fetchIncidentsforMap']);
Route::post('/kanban-report-deploy', [mainmenuController::class, 'updateReportKanban']);
Route::post('/kanban-report-resolve', [mainmenuController::class, 'ongoingToResolved']);
Route::get('/get-preport-details/{id}', [mainmenuController::class, 'getPReportDetails']);
Route::post('/update-preport-details/{id}', [mainmenuController::class, 'updatePReportDetails']);
Route::get('/reports-teamtbl', [mainmenuController::class, 'teamstblReports']);
Route::get('/get-available-teams', [mainmenuController::class, 'getAvailableTeams']);
Route::get('/get-deployment-details', [mainmenuController::class, 'getDeploymentDetails']);
Route::post('/dismiss-report', [mainmenuController::class, 'dismissReportkanban']);
Route::get('/get-latest-reports', [mainmenuController::class, 'fetchNewIncidents']);
/** --------------------------../Reports Tab----------------------- */

Route::get('/responseTeams', [mainmenuController::class, 'teams_view']);
Route::post('/add-rteams', [mainmenuController::class, 'add_rteams']);
Route::post('/add-member', [mainmenuController::class, 'add_teammember']);
Route::get('/fetch-teamsmembers-tbl', [mainmenuController::class, 'fetchTeamsMembersTbl']);
Route::get('/fetch-teams-tbl', [mainmenuController::class, 'fetchTeamsTbl']);
Route::delete('/delete-teammem/{id}', [mainmenuController::class, 'deleteRTmember']);
Route::delete('/delete-teams/{id}', [mainmenuController::class, 'deleteTeams']);
Route::get('/get-team/{id}', [mainmenuController::class, 'getTeamID']);
Route::post('/update-team/{id}', [mainmenuController::class, 'updateTeam']);
Route::get('/fetch-teams-options', [mainmenuController::class, 'fetchTeamsOptions']);

Route::get('/get-rtmember/{id}', [mainmenuController::class, 'getRTmemberID']);


Route::get('/routing', [RoutingController::class, 'routingView']);
Route::get('/incidents-assigned', [RoutingController::class, 'getAssignedIncidents']);
Route::post('/report-resolve/{id}', [RoutingController::class, 'resolveReport']);

Route::get('/manage-users', [mainmenuController::class, 'manageUsers']);
Route::get('/fetch-pendingusers-tbl', [mainmenuController::class, 'citizenstbl']);
Route::post('/users/approve/{user}', [mainmenuController::class, 'approveUser']);
Route::post('/users/reject/{user}', [mainmenuController::class, 'rejectUser']);
Route::get('/users/details/{user}', [mainmenuController::class, 'getUserDetails']);
/** --------------------------../Admin Controller ----------------------- */
Route::resources([
    '/roles' => RoleController::class,
    '/users' => UserController::class,
]);