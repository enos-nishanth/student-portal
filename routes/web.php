<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\AdminRegisterController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\ProductController as StudentProductController;
use App\Http\Controllers\Student\CartController;
use App\Http\Controllers\Student\DeliveryAddressController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [RegisterController::class, 'store'])
    ->name('register.store');

Route::get('/login', [LoginController::class, 'create'])
    ->name('login');

Route::post('/login', [LoginController::class, 'store'])
    ->name('login.store');

Route::post('/logout', [LoginController::class, 'destroy'])
    ->name('logout');
    
//student
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'student'])
    ->name('dashboard');

Route::prefix('student')
    ->name('student.')
    ->middleware(['auth', 'student'])
    ->group(function () {
        //product
        Route::get('/products', [StudentProductController::class, 'index'])
            ->name('products.index');

        Route::get('/products/{product}', [StudentProductController::class, 'show'])
            ->name('products.show');

        // Cart
        Route::get('/cart', [CartController::class, 'index'])
            ->name('cart.index');

        Route::post('/cart/{product}', [CartController::class, 'add'])
            ->name('cart.add');

        Route::put('/cart/{cartItem}', [CartController::class, 'update'])
            ->name('cart.update');

        Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])
            ->name('cart.remove');

        Route::post('/buy-now/{product}', [CartController::class, 'buyNow'])
            ->name('buy-now');

        Route::get('/addresses', [DeliveryAddressController::class, 'index'])
            ->name('addresses.index');

        Route::get('/addresses/create', [DeliveryAddressController::class, 'create'])
            ->name('addresses.create');

        Route::post('/addresses', [DeliveryAddressController::class, 'store'])
            ->name('addresses.store');

        Route::get('/addresses/{deliveryAddress}/edit', [DeliveryAddressController::class, 'edit'])
            ->name('addresses.edit');

        Route::put('/addresses/{deliveryAddress}', [DeliveryAddressController::class, 'update'])
            ->name('addresses.update');

        Route::delete('/addresses/{deliveryAddress}', [DeliveryAddressController::class, 'destroy'])
            ->name('addresses.destroy');

    });

//admin
Route::middleware(['auth','admin'])->group(function () {

    Route::get('/admin/register', function () {
        return view('auth.admin-register');
    })->name('admin.register');

    Route::post('/admin/register', [AdminRegisterController::class, 'store'])
        ->name('admin.register.store');

    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::get('/admin/students', [StudentController::class, 'index'])
        ->name('admin.students.index');

    Route::get('/admin/students/{student}', [StudentController::class, 'show'])
        ->name('admin.students.show');

    Route::get('/admin/students/{student}/edit', [StudentController::class, 'edit'])
        ->name('admin.students.edit');

    Route::put('/admin/students/{student}', [StudentController::class, 'update'])
        ->name('admin.students.update');

    Route::delete('/admin/students/{student}', [StudentController::class, 'destroy'])
        ->name('admin.students.destroy');

  
    // adding admin/ in url and admin. in file navigation
    Route::prefix('admin')->name('admin.')->group(function () {

        Route::get('/products', [ProductController::class, 'index'])
            ->name('products.index');

        Route::get('/products/create', [ProductController::class, 'create'])
            ->name('products.create');

        Route::post('/products', [ProductController::class, 'store'])
            ->name('products.store');

        Route::get('/products/{product}', [ProductController::class, 'show'])
            ->name('products.show');

        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
            ->name('products.edit');

        Route::put('/products/{product}', [ProductController::class, 'update'])
            ->name('products.update');

        Route::delete('/products/{product}', [ProductController::class, 'destroy'])
            ->name('products.destroy');

    });
});