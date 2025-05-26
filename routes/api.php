<?php

use App\Http\Controllers\AppController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('mapi')->group(function() {
    Route::get('/checkConnection', [AppController::class, 'checkConnection']);
    Route::get('/getFilteredItemMasterfile', [AppController::class, 'getFilteredItemMasterfile']);
    Route::get('/getItemMasterfileCount', [AppController::class, 'getItemMasterfileCount']);
    Route::get('/getItemMasterfileOffset', [AppController::class, 'getItemMasterfileOffset']);
    Route::get('/getUserMasterfile', [AppController::class, 'getUserMasterfile']);
    Route::get('/getAuditMasterifle', [AppController::class, 'getAuditMasterifle']);
    Route::get('/getLocationMasterfile', [AppController::class, 'getLocationMasterfile']);
    
    Route::post('/insertCountData', [AppController::class, 'insertCountData']);
});
