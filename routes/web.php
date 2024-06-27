<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CommunityController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ImageUploadController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\Admin\GoalController;
use App\Http\Controllers\Admin\AnnouncementController;
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
        Route::get('/course/edit/{id}', [CourseController::class, 'createEdit'])->name('course.edit');
        Route::post('/course/update', [CourseController::class, 'courseUpdate'])->name('course.update.store');
        Route::post('/course/delete', [CourseController::class, 'courseDelete'])->name('course.delete');
        Route::get('/course/lesson/{courseId}', [CourseController::class, 'courseLessonEmpty'])->name('course.lesson.empty');
        Route::get('/course/lesson/{courseId}/{lessonId}', [CourseController::class, 'courseLessonAll'])->name('course.lesson.all');

        Route::post('/courses/lesson/save', [CourseController::class, 'saveCourseLesson'])->name('course.lesson.save');
        Route::post('/courses/lesson/delete', [CourseController::class, 'deleteLesson'])->name('course.lesson.delete');

        Route::post('/courses/lesson/section/create', [CourseController::class, 'lessonSectionCreate'])->name('course.lesson.section.create');
        Route::post('/courses/lesson/section/delete', [CourseController::class, 'lessonSectionDelete'])->name('course.lesson.section.delete');
        Route::post('/courses/lesson/section/update', [CourseController::class, 'lessonSectionUpdate'])->name('course.lesson.section.update');


        // product
        Route::get('/products', [ProductController::class, 'list'])->name('product.list');
        Route::post('/product/store', [ProductController::class, 'productCreate'])->name('product.store');
        Route::post('/product/delete', [ProductController::class, 'productDelete'])->name('product.delete');
        Route::get('/product/info/{id}', [ProductController::class, 'getProductInfo'])->name('product.info');
        Route::post('/product/update', [ProductController::class, 'productUpdate'])->name('product.update');

        // communities
        Route::get('/communities', [CommunityController::class, 'list'])->name('community.list');
        Route::post('/community/store', [CommunityController::class, 'communityCreate'])->name('community.store');
        Route::post('/community/delete', [CommunityController::class, 'communityDelete'])->name('community.delete');
        Route::get('/community/info/{id}', [CommunityController::class, 'getCommunityInfo'])->name('community.info');
        Route::post('/community/update', [CommunityController::class, 'communityUpdate'])->name('community.update');

        //blog
        Route::get('/blogs', [BlogController::class, 'list'])->name('blog.list');
        Route::post('/blog/store', [BlogController::class, 'blogCreate'])->name('blog.store');
        Route::post('/blog/delete', [BlogController::class, 'blogDelete'])->name('blog.delete');
        Route::get('/blog/info/{id}', [BlogController::class, 'getBlogInfo'])->name('blog.info');
        Route::post('/blog/update', [BlogController::class, 'blogUpdate'])->name('blog.update');

        Route::post('/image-upload', [ImageUploadController::class, 'uploadImage'])->name('image-upload');
        Route::post('/image-delete', [ImageUploadController::class, 'deleteImage'])->name('image-delete');
        Route::get('/uploaded-image-delete/{id}/{type}', [ImageUploadController::class, 'uploadDeleteImage'])->name('uploaded-image-delete');

        // support & communication
        Route::get('/help-support', [SupportController::class, 'supportCommunication'])->name('support.list');
        Route::post('/send-reply', [SupportController::class, 'sendReply'])->name('support.send.reply');

        // notification
        Route::get('/notifications', [SupportController::class, 'notification'])->name('notification.list');
        Route::post('/create-notifications', [SupportController::class, 'createNotification'])->name('notification.store');

        //goal
        Route::get('/goal', [GoalController::class, 'goal'])->name('goal.list');
        Route::get('/goals/{id}', [GoalController::class, 'showGoal'])->name('goals.detail');

        //announcement
        Route::get('/announcements', [AnnouncementController::class, 'announcements'])->name('announcement.list');
        Route::post('announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::delete('announcements/{id}', [AnnouncementController::class, 'delete'])->name('announcements.delete');
        Route::get('/announcements/edit', [AnnouncementController::class, 'edit'])->name('announcements.edit');
        Route::put('/announcements/update', [AnnouncementController::class, 'update'])->name('announcements.update');

        // Route for deleting an announcement
        // logout
        Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});
