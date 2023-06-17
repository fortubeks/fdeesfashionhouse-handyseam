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
        'items' => App\Http\Controllers\ItemsController::class,['except' => ['create','edit']],
        'customers' => App\Http\Controllers\APIControllers\CustomersController::class,['except' => ['create','edit']],
        'orders' => App\Http\Controllers\OrdersController::class,
        'measurements' => App\Http\Controllers\MeasurementsController::class,
        'invoices' => App\Http\Controllers\InvoicesController::class,
        'payments' => App\Http\Controllers\PaymentsController::class,
        'staffs' => App\Http\Controllers\StaffsController::class,
        'settings' => App\Http\Controllers\SettingsController::class,
        'expense-categories' => App\Http\Controllers\ExpenseCategoriesController::class,
        'expenses' => App\Http\Controllers\ExpensesController::class,
        'item-categories' => App\Http\Controllers\ItemCategoriesController::class,
    ]);
});
Route::post('/login', [App\Http\Controllers\APIControllers\Auth\LoginController::class, 'login']);