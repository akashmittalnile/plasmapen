<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
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
        Route::get('/course/lesson/{courseId}', [CourseController::class, 'courseLessonEmpty'])->name('course.lesson.empty');
        Route::get('/course/lesson/{courseId}/{lessonId}', [CourseController::class, 'courseLessonAll'])->name('course.lesson.all');

        Route::post('/courses/lesson/save', [CourseController::class, 'saveCourseLesson'])->name('course.lesson.save');
        Route::post('/courses/lesson/delete', [CourseController::class, 'deleteLesson'])->name('course.lesson.delete');

        Route::post('/courses/lesson/section/create', [CourseController::class, 'lessonSectionCreate'])->name('course.lesson.section.create');


        // product
        Route::get('/products', [ProductController::class, 'list'])->name('product.list');
        Route::post('/product/store', [ProductController::class, 'productCreate'])->name('product.store');
        Route::post('/product/delete', [ProductController::class, 'productDelete'])->name('product.delete');
        Route::get('/product/detail/{id}', [ProductController::class, 'getProductDetail'])->name('product.detail');
        Route::post('/product/update', [ProductController::class, 'productUpdate'])->name('product.update');


        //blog

        // logout
        Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});
