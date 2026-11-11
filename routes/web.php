<?php

use Illuminate\Support\Facades\Route;
use Spatie\Analytics\Period;
use Carbon\Carbon;
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
Route::get('testing-wa', '\App\Http\Controllers\TestingController@testingWa');
Route::get('testing', '\App\Http\Controllers\TestingController@index');
Route::get('json-testing', '\App\Http\Controllers\TestingController@jsonRelation');
Route::get('local-storage', '\App\Http\Controllers\TestingController@localStorage');
Route::get('on-progress', '\App\Http\Controllers\TestingController@onprogress');

Route::get('/', function () {
    return redirect('/id/index.php');
})->name('home');

Route::post('upload', '\App\Http\Controllers\Admin\DashboardController@upload')->name('upload');
Route::post('upload/announcement', '\App\Http\Controllers\Admin\DashboardController@uploadAnnouncement')->name('upload');

// admin site
Route::group(['middleware' => ['auth', 'admin', 'accountIsActive']], function() {
    Route::group(['prefix' => 'admin', 'namespace' => '\App\Http\Controllers\Admin', 'as' => 'admin.'], function() {
        // dashboard
        Route::get('/dashboard', 'DashboardController@index')->name('admin.dashboard');
        // Route::post('upload', 'DashboardController@upload')->name('admin.upload');

        Route::group(['namespace' => 'MasterData'], function() {
            // categories
            Route::resource('/categories', 'CategoryController')->except(['show']);
            Route::get('categories/{id}/question-titles', 'CategoryController@getQuestionTitle')->name('categories.question-titles');
            Route::get('categories/{id}/value-categories', 'CategoryController@getValueCategory')->name('categories.value-categories');

            // students
            Route::resource('students', 'StudentController');
            // announcements
            Route::resource('announcements', 'AnnouncementController');
            // faqs
            Route::resource('faqs', 'FaqController');
        });

        Route::group(['namespace' => 'Lesson'], function() {
            // value categories
            Route::resource('value-categories', 'ValueCategoryController')->except(['show']);
            Route::resource('value-categories.detail-value-categories', 'DetailValueCategoryController')->except(['show']);
            // question itles
            Route::resource('question-titles', 'QuestionTitleController')->except(['show']);
            // questions
            Route::resource('question-titles.questions', 'QuestionController');
        });

        Route::group(['namespace' => 'User'], function() {
            // users
            Route::resource('users', 'UserController');
            Route::get('users/{user}/activation-reminder', 'UserController@activationReminder')->name('users.activation_reminder');
            Route::get('users/{user}/member-reminder', 'UserController@memberReminder')->name('users.member_reminder');
        });

        Route::group(['namespace' => 'Transaction'], function() {
            // banks
            Route::resource('banks', 'BankController')->except(['show']);
            // transactions
            Route::resource('transactions', 'TransactionController');
            Route::get('transactions/{transaction}/invoice', 'TransactionController@invoice')->name('transactions.invoice');
        });

        Route::group(['namespace' => 'Exam'], function() {
            // vouchers
            Route::resource('exams', 'ExamController');
            Route::get('exams/grades/{id}/unblocked', 'ExamController@unblocked');
        });
    });
});

// user site
Route::group(['middleware' => ['auth', 'accountIsActive']], function() {
    Route::group(['prefix' => 'user', 'namespace' => '\App\Http\Controllers\User', 'as' => 'user.'], function() {
        // dashboard
        Route::get('/dashboard', 'DashboardController')->name('user.dashboard');

        // announcements
        Route::resource('/announcements', 'AnnouncementController')->only(['index', 'show']);

        // users
        Route::resource('/users', 'UserController');

        // faqs
        Route::resource('/faqs', 'FaqController')->only(['index']);

        Route::group(['namespace' => 'TryOut'], function() {
            // categories
            Route::resource('/categories', 'CategoryController')->only(['index']);
            //exam
            Route::resource('categories.exams', 'ExamController')->only(['index', 'show']);
            Route::get('exams/{id}/exam-start', 'ExamController@examStart')->name('exams.exam-start');
            Route::get('exams/{id}/exam-reset', 'ExamController@examReset')->name('exams.exam-reset');
            Route::get('exams/{id}/sections/{section}', 'ExamController@examShowQuestion')->name('exams.exam-show-questions');
            Route::post('exams/{id}/exam-answer', 'ExamController@answerQuestion')->name('exams.exam-answer');
            Route::post('exams/{id}/audio-played', 'ExamController@audioPlayed')->name('exams.audio-played');
            Route::post('exams/{id}/exam-end', 'ExamController@endExam')->name('exams.exam-end');
            Route::post('exams/{id}/decrement-tolerance', 'ExamController@decrementTolerance')->name('exams.decrement-tolerance');
            
            // grade
            Route::resource('grades', 'GradeController')->only(['index', 'show']);
            Route::get('grades/{id}/certificate', 'GradeController@certificate');
        });

        Route::group(['namespace' => 'Transaction'], function() {
            // transaction
            Route::resource('/transactions', 'TransactionController')->only(['index', 'show']);
            Route::get('/transactions/exams/{id}/buy', 'TransactionController@buy')->name('transactions.buy');

            // voucher activations
            Route::resource('/voucher-activations', 'VoucherActivationController')->only(['index', 'store']);
        });
    });
});

Route::group(['namespace' => '\App\Http\Controllers\Admin\Region'], function() {
    // regional
    route::get('region/province', 'RegionController@province');
    route::get('region/city/{provinsi_id}', 'RegionController@city');
    route::get('region/district/{city_id}', 'RegionController@district');
    route::get('region/village/{district_id}', 'RegionController@village');
});

Route::get('user/{id}/activation', '\App\Http\Controllers\ActivationController@activation');
Route::get('user/{id}/activation/actived', '\App\Http\Controllers\ActivationController@actived');
Route::get('user/forgot-password', '\App\Http\Controllers\ActivationController@forgotPassword');
Route::post('user/forgot-password', '\App\Http\Controllers\ActivationController@storeForgotPassword');
Route::get('/symlink', function() {
    Artisan::call('storage:link');
    return "Symlink berhasil dibuat";
});