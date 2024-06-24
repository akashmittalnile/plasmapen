<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SupportController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('otp-verfication', [AuthController::class, 'otpVerification']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('logout', [AuthController::class, "logout"]);

    Route::get('profile', [AuthController::class, 'profile']);
    Route::post('update-profile', [AuthController::class, 'updateProfile']);
    Route::post('change-password', [AuthController::class, 'changePassword']);

    Route::get('home', [UserController::class, 'home']);

    Route::get('courses', [CourseController::class, 'courses']);
    Route::get('course-category', [CourseController::class, 'courseCategory']);
    Route::get('course-details/{id}', [CourseController::class, 'courseDetails']);

    Route::get('products', [ProductController::class, 'products']);
    Route::get('product-details/{id}', [ProductController::class, 'productDetails']);

    Route::get('blogs', [BlogController::class, 'blogs']);
    Route::get('blog-details/{id}', [BlogController::class, 'blogDetails']);

    Route::post('create-query', [SupportController::class, 'createQuery']);
    Route::get('query-list', [SupportController::class, 'queryList']);
});

Route::get('token-expire', [AuthController::class, 'tokenExpire'])->name('login');