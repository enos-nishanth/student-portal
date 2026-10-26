<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\AdminDashboardController;
use App\Http\Controllers\Api\Admin\StudentController;
use App\Http\Controllers\Api\Student\StudentDashboardController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\OrderController;
use App\Http\Controllers\Api\Student\ProductController as StudentProductController;
use App\Http\Controllers\Api\Student\DeliveryAddressController;
use App\Http\Controllers\Api\Student\CartController;
use App\Http\Controllers\Api\Student\PaymentController;
use App\Http\Controllers\Api\Student\OrderController as StudentOrderController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware(['auth:sanctum', 'student'])->group(function () {

    Route::get('/student/dashboard', [StudentDashboardController::class, 'index']);

    // Student Product APIs
    Route::get('/student/products', [StudentProductController::class,'index']);
    Route::get('/student/products/{product}', [StudentProductController::class,'show']);

    // Delivery Address APIs
    Route::get('/student/addresses', [DeliveryAddressController::class, 'index']);
    Route::get('/student/addresses/{deliveryAddress}', [DeliveryAddressController::class, 'show']);
    Route::post('/student/addresses', [DeliveryAddressController::class, 'store']);
    Route::put('/student/addresses/{deliveryAddress}', [DeliveryAddressController::class, 'update']);
    Route::patch('/student/addresses/{deliveryAddress}', [DeliveryAddressController::class, 'update']);
    Route::delete('/student/addresses/{deliveryAddress}', [DeliveryAddressController::class, 'destroy']);

    // Student Cart APIs
    Route::get('/student/cart', [CartController::class, 'index']);
    Route::post('/student/cart/items', [CartController::class, 'add']);
    Route::patch('/student/cart/items/{cartItem}', [CartController::class, 'update']);
    Route::delete('/student/cart/items/{cartItem}', [CartController::class, 'remove']);
    Route::post('/student/cart/buy-now/{product}', [CartController::class, 'buyNow']);
    Route::post('/student/checkout', [CartController::class, 'checkout']);
    Route::post('/student/orders', [CartController::class, 'placeOrder']);

    // Student Payment APIs
    Route::post('/student/payment/create-order', [PaymentController::class,'createOrder',]);
    Route::get('/student/payment/{order}', [PaymentController::class,'show',]);
    Route::post('/student/payment/verify', [PaymentController::class,'verify',]);

    // Student Order APIs
    Route::get('/student/orders', [StudentOrderController::class,'index',]);
    Route::get('/student/orders/{order}', [StudentOrderController::class,'show',]);
    Route::get('/student/orders/{order}/track', [StudentOrderController::class,'track',]);
    Route::post('/student/orders/{order}/cancel', [StudentOrderController::class,'cancel',]);
    Route::post('/student/orders/{order}/refund', [StudentOrderController::class,'requestRefund',]);
});


Route::middleware(['auth:sanctum', 'admin'])->group(function () {

    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index']);// dashbord (no of students)

    //student APIs
    Route::get('/admin/students', [StudentController::class, 'index']);// list all the student
    Route::get('/admin/students/{student}', [StudentController::class, 'show']);// individual student
    Route::patch('/admin/students/{id}', [StudentController::class, 'update']);// update
    Route::delete('/admin/students/{id}', [StudentController::class, 'destroy']);//delete

    // Product APIs
    Route::get('/admin/products', [ProductController::class, 'index']);
    Route::post('/admin/products', [ProductController::class, 'store']);
    Route::get('/admin/products/{product}', [ProductController::class, 'show']);
    Route::put('/admin/products/{product}', [ProductController::class, 'update']);
    Route::delete('/admin/products/{product}', [ProductController::class, 'destroy']);

    // Order APIs
    Route::get('/admin/orders', [OrderController::class, 'index']);
    Route::get('/admin/orders/{order}', [OrderController::class, 'show']);
    Route::patch('/admin/orders/{order}/status',[OrderController::class, 'updateStatus']);
    Route::patch('/admin/orders/{order}/mark-paid',[OrderController::class, 'markAsPaid']);

});   
