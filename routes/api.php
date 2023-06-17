<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => ['auth:sanctum'] ], function () {
    Route::resources([
        'items' => App\Http\Controllers\APIControllers\ItemsController::class,
        'customers' => App\Http\Controllers\APIControllers\CustomersController::class,
        'orders' => App\Http\Controllers\APIControllers\OrdersController::class,
        'payments' => App\Http\Controllers\APIControllers\PaymentsController::class,
        'staffs' => App\Http\Controllers\APIControllers\StaffsController::class,
        'expense-categories' => App\Http\Controllers\APIControllers\ExpenseCategoriesController::class,
        'expenses' => App\Http\Controllers\APIControllers\ExpensesController::class,
        'item-categories' => App\Http\Controllers\APIControllers\ItemCategoriesController::class,
    ],['except' => ['create','edit']]);
    Route::resource('settings', App\Http\Controllers\APIControllers\SettingsController::class)->only(['show', 'update']);
});
Route::post('/login', [App\Http\Controllers\APIControllers\Auth\LoginController::class, 'login']);