<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

/* Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home'); 
Auth::routes();

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home')->middleware('auth');
*/
Route::group(['middleware' => 'auth'], function () {
	Route::get('table-list', function () {
		return view('pages.table_list');
	})->name('table');

	Route::get('typography', function () {
		return view('pages.typography');
	})->name('typography');

	Route::get('icons', function () {
		return view('pages.icons');
	})->name('icons');

	Route::get('map', function () {
		return view('pages.map');
	})->name('map');

	Route::get('notifications', function () {
		return view('pages.notifications');
	})->name('notifications');

	Route::get('rtl-support', function () {
		return view('pages.language');
	})->name('language');

	Route::get('upgrade', function () {
		return view('pages.upgrade');
	})->name('upgrade');
});

Route::group(['middleware' => ['auth', 'log.activity']], function () {
	//Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);

	Route::post('/subscription/activate', [App\Http\Controllers\SubscriptionController::class, 'activate']);
	Route::get('/verify-subscription-payment/{ref}', [App\Http\Controllers\SubscriptionController::class, 'verifySubscriptionPayment']);

	Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware(["verified"]);
	Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

	Route::get('/staff-login', [App\Http\Controllers\PagesController::class, 'staffLogin'])->name('staffLogin');

	Route::get('/create-order/tailoring/step1', [App\Http\Controllers\PagesController::class, 'createtailoringorderstep1']);
	Route::get('/create-order/tailoring/step2/{customer_id}', [App\Http\Controllers\PagesController::class, 'createtailoringorderstep2']);
	Route::get('/create-order/tailoring/step3', [App\Http\Controllers\PagesController::class, 'createtailoringorderstep3']);
	Route::post('/create-order/tailoring/addstyle', [App\Http\Controllers\OrdersController::class, 'addStyleToOrder']);

	Route::get('/items-search', [App\Http\Controllers\ItemsController::class, 'search'])->name('adminsearchitem');
	Route::get('/customers-search', [App\Http\Controllers\CustomersController::class, 'search']);

	Route::get('/create-order/sales/step1', [App\Http\Controllers\PagesController::class, 'createsalesorderstep1']);
	Route::get('/create-order/sales/step2/{customer_id}', [App\Http\Controllers\PagesController::class, 'createsalesorderstep2']);
	Route::get('/create-order/sales/step3', [App\Http\Controllers\PagesController::class, 'createsalesorderstep3']);
	Route::get('/orders/addToCart/{item_id}', [App\Http\Controllers\OrdersController::class, 'addItemToCart']);
	Route::get('/orders/removeFromCart/{item_id}', [App\Http\Controllers\OrdersController::class, 'removeItemFromCart']);

	Route::get('/payments/create-by-invoice', [App\Http\Controllers\PagesController::class, 'createPaymentForInvoice']);
	Route::get('/payments/printInvoice/{invoice_id}', [App\Http\Controllers\InvoicesController::class, 'printInvoice1']);
	Route::get('/payments/printPDFInvoice/{invoice_id}', [App\Http\Controllers\InvoicesController::class, 'printInvoice']);
	Route::get('/payments/printThermalInvoice/{order_id}', [App\Http\Controllers\PrintController::class, 'printThermalInvoice']);
	Route::get('/payments/printPDFReceipt/{invoice_id}', [App\Http\Controllers\InvoicesController::class, 'printReceipt']);
	Route::get('/payments-search/', [App\Http\Controllers\PaymentsController::class, 'search']);

	Route::get('/measurement/print/{measurement_id}', [App\Http\Controllers\MeasurementsController::class, 'printMeasurement']);
	Route::get('/printMeasurementInst/{outfit_id}', [App\Http\Controllers\PrintController::class, 'printMeasurementAndInstruction']);

	Route::get('/changePassword', [App\Http\Controllers\HomeController::class, 'showChangePasswordForm']);
	Route::post('/changePassword', [App\Http\Controllers\HomeController::class, 'ChangePassword'])->name('changePassword');
	Route::post('/setup/measurements', [App\Http\Controllers\SettingsController::class, 'saveMeasurement']);
	Route::post('/save-customer-measurements/{customer_id}', [App\Http\Controllers\CustomersController::class, 'updateMeasurement']);
	Route::get('/update-measurement-settings', [App\Http\Controllers\SettingsController::class, 'showupdateMeasurementSettingsForm']);
	Route::post('/update-measurement-settings', [App\Http\Controllers\SettingsController::class, 'updateMeasurement']);
	Route::get('/admin-staffs-search/', [App\Http\Controllers\StaffsController::class, 'search']);
	Route::get('/weekly-outfit-payments/', [App\Http\Controllers\ExpensesController::class, 'weeklyOutfitPaymentsIndex']);
	Route::get('/tailor-payment-date-update/', [App\Http\Controllers\ExpensesController::class, 'updateTailorPaymentDate']);
	Route::get('/outfit-payments-search/', [App\Http\Controllers\ExpensesController::class, 'getWeeklyOutfitsPayments']);
	Route::get('/resend-verification-email', [App\Http\Controllers\CustomersController::class, 'resendVerificationEmail']);
	Route::get('/filter-expenses/', [App\Http\Controllers\ExpensesController::class, 'filter']);
	Route::get('/expenses-search/', [App\Http\Controllers\ExpensesController::class, 'search']);

	Route::get('/filter-orders/', [App\Http\Controllers\OrdersController::class, 'filter']);
	Route::post('/add-items-used/', [App\Http\Controllers\OrdersController::class, 'addItemsUsed']);
	
});

Route::group(['middleware' => ['auth', 'verified', 'log.activity'] ], function () {
Route::resources([
    'items' => App\Http\Controllers\ItemsController::class,
    'customers' => App\Http\Controllers\CustomersController::class,
    'orders' => App\Http\Controllers\OrdersController::class,
    'measurements' => App\Http\Controllers\MeasurementsController::class,
    'invoices' => App\Http\Controllers\InvoicesController::class,
    'payments' => App\Http\Controllers\PaymentsController::class,
    'staffs' => App\Http\Controllers\StaffsController::class,
    'settings' => App\Http\Controllers\SettingsController::class,
    'expense-categories' => App\Http\Controllers\ExpenseCategoriesController::class,
    'expenses' => App\Http\Controllers\ExpensesController::class,
    'item-categories' => App\Http\Controllers\ItemCategoriesController::class,
	'purchases' => App\Http\Controllers\PurchaseController::class,
	]);
});
Route::group(['middleware' => ['verified', 'log.activity', 'subscribed'] ], function () {
	Route::resources([
		'sales-report' => App\Http\Controllers\SalesReportController::class,
		]);
	Route::get('/sales-report/', [App\Http\Controllers\SalesReportController::class, 'index'])->name('salesreport');
	Route::post('/sales-report/view', [App\Http\Controllers\SalesReportController::class, 'showReport'])->name('salesreportshow');
	Route::get('/export-customers/', [App\Http\Controllers\CustomersController::class, 'export'])->name('export.customers');
	});
Auth::routes(['verify' => true]);