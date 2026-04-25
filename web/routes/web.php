<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MainController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfilingController;
use App\Http\Controllers\LocationController;

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

// ------------------------------------------------------------------------------------------------------------------------------------ //
// LOGIN
Route::get('/', [MainController::class, 'main']);
Route::post('/validate', [LoginController::class, 'validate_user']);
Route::get('logout', [LoginController::class, 'logout']);
// ------------------------------------------------------------------------------------------------------------------------------------ //

// ------------------------------------------------------------------------------------------------------------------------------------ //
// HOME
Route::get('home', [AdminController::class, 'home']);
Route::get('setup', [AdminController::class, 'setup']);
// ------------------------------------------------------------------------------------------------------------------------------------ //

// ------------------------------------------------------------------------------------------------------------------------------------ //
// ANNOUNCEMENTS
Route::post('announcement/save', [AnnouncementController::class, 'save']);
Route::post('announcement/delete/{ann_uuid}', [AnnouncementController::class, 'delete']);
// ------------------------------------------------------------------------------------------------------------------------------------ //

// ------------------------------------------------------------------------------------------------------------------------------------ //
// ACCOUNT
Route::get('account', [UserController::class, 'account']);
Route::post('account/update', [UserController::class, 'account_update']);
Route::post('account/address/add',  [UserController::class, 'address_add']);
Route::post('account/address/edit',  [UserController::class, 'address_edit']);
Route::post('user/update-password', [UserController::class, 'update_password']);
// ------------------------------------------------------------------------------------------------------------------------------------ //

// ------------------------------------------------------------------------------------------------------------------------------------ //
// MESSAGES
Route::get('messages', [MessageController::class, 'main']);
Route::get('messages/chat/{mesg_group_id}', [MessageController::class, 'personal']);
Route::post('messages/send', [MessageController::class, 'send']);
Route::post('messages/compose', [MessageController::class, 'compose']);
// ------------------------------------------------------------------------------------------------------------------------------------ //

// ------------------------------------------------------------------------------------------------------------------------------------ //
// PROFILING
// - Users
Route::get('profiling/users/active', [ProfilingController::class, 'users_active']);
Route::get('profiling/users/deleted', [ProfilingController::class, 'users_deleted']);
Route::post('profiling/users/add', [ProfilingController::class, 'users_add']);
Route::post('profiling/users/update/role/{usr_id}', [ProfilingController::class, 'users_update_role']);
Route::post('profiling/users/reset/password/{usr_id}', [ProfilingController::class, 'users_reset_password']);
Route::post('profiling/users/delete/{usr_id}', [ProfilingController::class, 'users_delete']);
Route::post('profiling/users/restore/{usr_id}', [ProfilingController::class, 'users_restore']);
// - Technicians
Route::get('profiling/technicians/active', [ProfilingController::class, 'technicians_active']);
Route::get('profiling/technicians/deleted', [ProfilingController::class, 'technicians_deleted']);
Route::post('profiling/technicians/add', [ProfilingController::class, 'technicians_add']);
Route::post('profiling/technicians/update/availability/{usr_id}', [ProfilingController::class, 'technicians_update_availability']);
Route::post('profiling/technicians/reset/password/{usr_id}', [ProfilingController::class, 'technicians_reset_password']);
Route::post('profiling/technicians/delete/{usr_id}', [ProfilingController::class, 'technicians_delete']);
Route::post('profiling/technicians/restore/{usr_id}', [ProfilingController::class, 'technicians_restore']);
// ------------------------------------------------------------------------------------------------------------------------------------ //

// ------------------------------------------------------------------------------------------------------------------------------------ //
// LOCATION
Route::get('/locations/{region}/provinces', [LocationController::class, 'provinces']);
Route::get('/locations/{province}/municipalities', [LocationController::class, 'municipalities']);
Route::get('/locations/{muni}/barangays', [LocationController::class, 'barangays']);
// ------------------------------------------------------------------------------------------------------------------------------------ //

// ------------------------------------------------------------------------------------------------------------------------------------ //
// LARAVEL COMMANDS //
Route::get('/laravel/clear-all', function () {
    $commands = [
        'cache:clear',
        'view:clear',
        'route:clear',
        'config:clear',
        'config:cache',
    ];

    foreach ($commands as $command) {
        Artisan::call($command);
    }

    return response()->json(['message' => 'All caches and configurations cleared successfully!']);
});
// ------------------------------------------------------------------------------------------------------------------------------------ //