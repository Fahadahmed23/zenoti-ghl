<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ZenotiContactController;
use App\Http\Controllers\Api\ZenotiAppointmentGroupController;
use App\Http\Controllers\Api\ZenotiInvoiceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::post('login', [LoginController::class, 'login']);
//Route::post('register', [LoginController::class, 'register']);


// Route::middleware('auth:api')->group(function () {
//     Route::resource('posts', PostController::class);
// });


Route::post('zenoti-contacts', [ZenotiContactController::class, 'store']);

Route::post('zenoti-appointment-groups/store-or-update', [ZenotiAppointmentGroupController::class, 'storeOrUpdate']);

Route::delete('zenoti-appointment-groups/delete', [ZenotiAppointmentGroupController::class, 'destroy']);

Route::post('zenoti-revenue', [ZenotiInvoiceController::class, 'storeOrUpdateInvoiceData']);