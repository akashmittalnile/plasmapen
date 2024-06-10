<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\LangController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

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

Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('config:cache');
    $exitCode = Artisan::call('view:clear');
    $exitCode = Artisan::call('optimize:clear');
    $exitCode = Artisan::call('route:clear');
    return '<center>Cache clear</center>';
});

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::get('lang/change', [LangController::class, 'change'])->name('changeLang');


Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    // submit credentials for login
    Route::post('authenticate', [AuthController::class, 'authenticateAdmin'])->name('authenticate');

    Route::middleware(["isAdmin"])->group(function () {
        // dashboard
        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

        // student
        Route::get('/students', [StudentController::class, 'list'])->name('student.list');
        Route::get('/student/detail/course/{id}', [StudentController::class, 'detailCourse'])->name('student.details.course');
        Route::get('/student/detail/product/{id}', [StudentController::class, 'detailProduct'])->name('student.details.product');

        // course
        Route::get('/courses', [CourseController::class, 'list'])->name('course.list');
        Route::get('/course/create', [CourseController::class, 'createCourse'])->name('course.create');
        Route::post('/course/create', [CourseController::class, 'courseCreate'])->name('course.create.store');
        Route::post('/course/delete', [CourseController::class, 'courseDelete'])->name('course.delete');

        // logout
        Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    });

}); 
