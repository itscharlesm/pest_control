<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\POSController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UtilityController;
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

// - Group
// ------------------------------------------------------------------------------------------------------------------------------------ //
// Transactions
Route::get('admin/pos/new-transaction', [POSController::class, 'pos_main']);
Route::post('admin/pos/new-transaction/payment', [POSController::class, 'payment']);
Route::get('admin/pos/transactions', [POSController::class, 'transaction_history']);
Route::get('admin/pos/cash-on-hand', [POSController::class, 'cash_on_hand']);
Route::post('admin/pos/cash-on-hand/starting-cash', [POSController::class, 'starting_cash']);
Route::post('admin/pos/cash-on-hand/ending-cash', [POSController::class, 'ending_cash']);

// Utility
Route::get('admin/utility/manage-categories', [UtilityController::class, 'category_main']);
Route::post('admin/utility/manage-categories/create', [UtilityController::class, 'category_create']);
Route::post('admin/utility/manage-categories/update/{mcat_id}', [UtilityController::class, 'category_update']);

// ? PURCHASE
Route::get('pos/purchase/new-transaction', [POSController::class, 'pos_purchase_main']);
Route::post('pos/purchase/add-items', [POSController::class, 'pos_purchase_add']);

Route::post('pos/purchase/new-transaction/add', [POSController::class, 'pos_purchase_add_transaction']);

// ? DAMAGE
Route::get('pos/damages/create-new', [POSController::class, 'pos_damages_main']);
Route::post('pos/damages/create-new/add', [POSController::class, 'pos_damages_add']);
// ------------------------------------------------------------------------------------------------------------------------------------ //
// HRIS
// - Employees

// - Attendances

// - Leave Applications

// - Violations

// - Payroll

// - Accounting Payroll

// ------------------------------------------------------------------------------------------------------------------------------------ //
// ACCOUNTS
// - Information

// - Setup

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