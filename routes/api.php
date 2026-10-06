<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\AdminDashboardController;
use App\Http\Controllers\Api\Admin\StudentController;
use App\Http\Controllers\Api\Student\StudentDashboardController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);


Route::middleware(['auth:sanctum', 'student'])->group(function () {

    Route::get('/student/dashboard', [StudentDashboardController::class, 'index']);

});


Route::middleware(['auth:sanctum', 'admin'])->group(function () {

    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index']);// dashbord (no of students)

    Route::get('/admin/students', [StudentController::class, 'index']);// list all the student

    Route::get('/admin/students/{student}', [StudentController::class, 'show']);// individual student

    Route::put('/admin/students/{id}', [StudentController::class, 'update']);// update

    Route::delete('/admin/students/{id}', [StudentController::class, 'destroy']);//delete
});