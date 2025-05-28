<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\CadetController;


Route::get('/', [AppController::class, 'index']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AppController::class, 'dashboard'])->name('dashboard');

    // Route::prefix('setup')->group(function () {

    //     Route::prefix('users')->group(function () {
    //         Route::get('/getResults', [SetupController::class, 'getResultsUser']);
    //         Route::post('/toggleStatus', [SetupController::class, 'toggleStatusUser']);
    //         Route::post('/createUser', [SetupController::class, 'createUser']);
    //         Route::get('/getUsertypes', [SetupController::class, 'getUsertypes']);
    //         Route::post('/updatePassword', [SetupController::class, 'updatePassword']);
    //         Route::post('/resetPassword', [SetupController::class, 'resetPassword']);
    //     });

    // });

    Route::prefix('cadets')->group(function () {
            Route::get('/get_cadet_counts', [CadetController::class, 'get_cadet_counts']);
            Route::post('/add_cadets', [CadetController::class, 'add_cadets']);
            Route::get('/get_cadets', [CadetController::class, 'get_cadets']);
            Route::post('/delete_cadets', [CadetController::class, 'delete_cadets']);
            Route::post('/add_schedules', [CadetController::class, 'add_schedules']);
            Route::get('/get_events', [CadetController::class, 'get_events']);
            Route::post('/delete_schedules', [CadetController::class, 'delete_schedules']);
            Route::post('/add_absent_request', [CadetController::class, 'add_absent_request']);
            Route::get('/get_request', [CadetController::class, 'get_request']);
            Route::post('/approved_request', [CadetController::class, 'approved_request']);
            Route::post('/add_attendance', [CadetController::class, 'add_attendance']);
            Route::get('/get_events_attendance', [CadetController::class, 'get_events_attendance']);
            Route::get('/get_count_cadets', [CadetController::class, 'get_count_cadets']);
            Route::get('/get_count_schedules', [CadetController::class, 'get_count_schedules']);
            Route::get('/get_count_request', [CadetController::class, 'get_count_request']);
            Route::get('/get_count_attendance', [CadetController::class, 'get_count_attendance']);
            Route::get('/get_cadets_attendancelist', [CadetController::class, 'get_cadets_attendancelist']);
    });

});

Route::get('/.well-known/{any}', function () {
    return redirect('/dashboard');
})->where('any', '.*');

require __DIR__ . '/auth.php';

Route::middleware('auth')->get('{any}', [AppController::class, 'allowWhat'])->where('any', '.*');
