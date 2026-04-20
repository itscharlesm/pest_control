<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MainController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\MessageController;

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
// MAIN
// - Login
Route::get('/', [MainController::class, 'main']);
Route::post('/validate', [LoginController::class, 'validateUser']);
Route::get('logout', [LoginController::class, 'logout']);
// - Password
Route::post('user/update-password', [UserController::class, 'update_password']);
// - Home
Route::get('home', [AdminController::class, 'home']);
Route::get('setup', [AdminController::class, 'setup']);
// - Announcements
Route::post('announcement/save', [AnnouncementController::class, 'save']);
Route::post('announcement/delete/{ann_uuid}', [AnnouncementController::class, 'delete']);
// ------------------------------------------------------------------------------------------------------------------------------------ //
// ------------------------------------------------------------------------------------------------------------------------------------ //
// MESSAGES
// - Main
Route::get('messages', [MessageController::class, 'main']);
 
// Individual chat view
Route::get('messages/chat/{mesg_group_id}', [MessageController::class, 'personal']);
 
// Send message to existing group (POST)
Route::post('messages/send', [MessageController::class, 'send']);
 
// Compose new message — find or create group then send (POST)
Route::post('messages/compose', [MessageController::class, 'compose']);
// - Personal Message
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