<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CourierController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ReportController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

Route::group(['middleware' => 'auth'], function(){
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    Route::post('product-info', [OrderController::class, 'ProductInfo'])->name('product-info');
    Route::resource('order', OrderController::class);
    Route::post('status-wise-product', [OrderController::class, 'StatusWiseProduct'])->name('status-wise-product');
    Route::get('order-status-report', [ReportController::class, 'orderStatuReport'])->name('order-status-report');
    Route::get('order-product', [OrderController::class, 'showProductSales'])->name('showProductSales');
    Route::get('/order_report_by_date', [OrderController::class, 'getOrderProductReport'])->name('order.report_by_date');

    Route::post('order-wise-product', [OrderController::class, 'OrderWiseProduct'])->name('order-wise-product');

    Route::get('current-month-staff-order', [OrderController::class, 'currentMonthstaffReport'])->name('currentMonthstaffReport');

    Route::get('redx-product-list', [ReportController::class, 'RedxProductList'])->name('redx-product-list');
    Route::get('pathao-product-list', [ReportController::class, 'PathaoProductList'])->name('pathao-product-list');
    Route::get('steadfast-product-list', [ReportController::class, 'steadfastProductList'])->name('steadfast.product.list');
    Route::get('others-product-list', [ReportController::class, 'OthersProductList'])->name('others-product-list');
    Route::get('order-print/{id}', [OrderController::class, 'OrderPrint'])->name('order-print');


    Route::post('courier-booked',[CourierController::class,'orderBooking']);
    Route::get('courier-booked-list',[CourierController::class,'CourierBooked'])->name('Courier.Booked.List');
    Route::get('courier-consignment-check/{id}',[CourierController::class,'consignmentIdByCheck'])->name('consignment.Check');
    Route::get('courier-invoice-check/{invoice}',[CourierController::class,'invoiceIdByStatusCheck'])->name('invoiceIdByStatus.Check');
    Route::get('courier-tracking-check/{trackingCode}',[CourierController::class,'trackingCodeCheck'])->name('trackingCode.Check');
    Route::get('courier-check',[CourierController::class,'productCheck'])->name('courier.check');
    Route::post('courier-check',[CourierController::class,'handleCourierCheck'])->name('courier.checked');

});
require __DIR__.'/auth.php';

