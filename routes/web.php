<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\NavSysController;
use App\Http\Controllers\ExportsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\UtilitiesController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\AdvanceCountController;
use App\Http\Controllers\BackendSetupController;
use App\Http\Controllers\JustificationController;
use App\Http\Controllers\PhysicalCountController;
use App\Http\Controllers\CheckDuplicateController;
use App\Http\Controllers\VarianceReportController;
use App\Http\Controllers\BackendAdjustmentController;
use App\Http\Controllers\ConsolidateReportController;
use App\Http\Controllers\QuestionableItemsController;
use App\Http\Controllers\InventoryValuationController;

Route::get('/', [AppController::class, 'index']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AppController::class, 'dashboard'])->name('dashboard');

    Route::prefix('setup')->group(function () {
        Route::prefix('location')->group(function () {
            Route::get('/getResults', [SetupController::class, 'getResultsLocation']);
            Route::post('/toggleStatus', [SetupController::class, 'toggleStatusLocation']);
            Route::post('/createLocation', [SetupController::class, 'createLocation']);
            Route::get('generateLocation', [SetupController::class, 'generateLocation']);
            Route::get('/getCompany', [LocationController::class, 'getCompany']);
            Route::get('/getBU', [LocationController::class, 'getBU']);
            Route::get('/getDept', [LocationController::class, 'getDept']);
            Route::get('/getSection', [LocationController::class, 'getSection']);
            Route::get('/getRacks', [SetupController::class, 'getRacks']);
            Route::post('/createRack', [SetupController::class, 'createRack']);
            Route::get('/getLocationCount', [SetupController::class, 'getLocationCount']);
        });

        Route::prefix('users')->group(function () {
            Route::get('/getResults', [SetupController::class, 'getResultsUser']);
            Route::post('/toggleStatus', [SetupController::class, 'toggleStatusUser']);
            Route::post('/createUser', [SetupController::class, 'createUser']);
            Route::get('/getUsertypes', [SetupController::class, 'getUsertypes']);
            Route::post('/updatePassword', [SetupController::class, 'updatePassword']);
            Route::post('/resetPassword', [SetupController::class, 'resetPassword']);
        });

        Route::prefix('masterfiles')->group(function () {
            Route::get('/getVendorMasterfile', [SetupController::class, 'getVendorMasterfile']);
            Route::get('/getItemCategoryMasterfile', [SetupController::class, 'getItemCategoryMasterfile']);
            Route::get('/getItems', [SetupController::class, 'getItems']);
        });

        Route::prefix('countBackendSetup')->group(function () {
            Route::get('/postCount', [SetupController::class, 'postCount']);
            Route::post('/removeItem', [SetupController::class, 'removeItem']);
        });

        Route::prefix('justification')->group(function () {
            Route::post('/createJustification', [SetupController::class, 'createJustification']);
        });
    });

    Route::prefix('employee')->group(function () {
        Route::get('/search', [SetupController::class, 'searchEmployee']);
    });

    Route::prefix('uploading')->group(function () {
        Route::prefix('nav_upload')->group(function () {
            Route::post('/navPcount', [FileUploadController::class, 'newItemValuation']);
            Route::get('/getCompany', [FileUploadController::class, 'getCompany']);
            Route::get('/getBU', [FileUploadController::class, 'getBU']);
            Route::get('/getDept', [FileUploadController::class, 'getDept']);
            Route::get('/getSection', [FileUploadController::class, 'getSection']);
            Route::get('/getVendor', [FileUploadController::class, 'getVendor']);
            Route::get('/getCategory', [FileUploadController::class, 'getCategory']);
        });

        Route::prefix('masterfiles')->group(function () {
            Route::post('/importItemMasterfile', [FileUploadController::class, 'importItemMasterfile']);
            Route::post('/importVendorMasterfile', [FileUploadController::class, 'importVendorMasterfile']);
            Route::post('/importItemCategoryMasterfile', [FileUploadController::class, 'importItemCategoryMasterfile']);
            Route::post('/importAltaCitta', [FileUploadController::class, 'importAltaCitta']);
        });

        Route::prefix('unposted')->group(function () {
            Route::post('/importUnposted', [FileUploadController::class, 'importUnposted']);
        });
    });

    Route::prefix('reports')->group(function () {
        Route::prefix('backend')->group(function () {
            Route::get('/backendCount', [PhysicalCountController::class, 'backendCount']);
            Route::get('/questionable_items', [BackendSetupController::class, 'getResults']);
            Route::post('/generateBackendCount', [PhysicalCountController::class, 'generateBackendCount']);
            Route::post('/generateQuestionableItems', [QuestionableItemsController::class, 'generateReport']);
            Route::get('/getCountDate', [BackendSetupController::class, 'getCountDate']);
            Route::get('/notFoundCountDate', [BackendSetupController::class, 'notFoundCountDate']);
            Route::get('/questionableItemsDateSetup', [BackendSetupController::class, 'questionableItemsDateSetup']);
        });
        Route::prefix('appdata')->group(function () {
            Route::get('/getResults', [PhysicalCountController::class, 'getResults']);
            Route::post('/generate', [PhysicalCountController::class, 'generate']);
            Route::get('/generateAppDataExcel', [PhysicalCountController::class, 'generateAppDataExcel']);
            Route::get('/getNotFound', [PhysicalCountController::class, 'getNotFound']);
            Route::get('/generateNotFound', [PhysicalCountController::class, 'generateNotFound']);
            Route::get('/getDates', [PhysicalCountController::class, 'getDates']);
            Route::delete('/deleteimage', [PhysicalCountController::class, 'deleteimage']);
            // Route::put('/updatesignature', [PhysicalCountController::class, 'updatesignature']);
        });
        Route::prefix('damageCount')->group(function () {
            // Route::get('/getResults', [PhysicalCountController::class, 'getResults']);
            Route::get('/generateCountDamages', [PhysicalCountController::class, 'generateCountDamages']);
            Route::get('/generateCountDamagesEXCEL', [PhysicalCountController::class, 'generateCountDamagesEXCEL']);
        });
        Route::prefix('pcount_cost')->group(function () {
            Route::get('/getResultPcountCost', [ReportsController::class, 'getResultPcountCost']);
            Route::get('/generatePcountCost', [ReportsController::class, 'generatePcountCost']);
            Route::get('/generatePcountCostExcel', [ReportsController::class, 'generatePcountCostExcel']);
        });
        Route::prefix('nav_sys')->group(function () {
            Route::get('/getResults', [NavSysController::class, 'getResults']);
            Route::get('/NetNavSys', [NavSysController::class, 'NetNavSys']);
            Route::post('/exportResult', [NavSysController::class, 'exportResult']);
        });
        Route::prefix('not_in_count')->group(function () {
            Route::get('/generate', [NavSysController::class, 'NetNavSys']);
            Route::get('/getNotinCount', [NavSysController::class, 'getNotinCount']);
        });
        Route::prefix('variance_report')->group(function () {
            Route::get('/getResults', [VarianceReportController::class, 'getResults']);
            Route::get('/getResults2', [ReportsController::class, 'getResultsVariance']);
            Route::post('/generate', [VarianceReportController::class, 'generateVariance']);
            Route::post('/export', [ExportsController::class, 'exportVariance']);
            Route::get('/getDate', [VarianceReportController::class, 'getDate']);
            Route::post('/summary', [VarianceReportController::class, 'summary']);
        });
        Route::prefix('variance_report_cost')->group(function () {
            Route::get('/getResultVarianceCost', [ReportsController::class, 'getResultVarianceCost']);
            Route::get('/generateVarianceReportCost', [ReportsController::class, 'generateVarianceReportCost']);
        });
        Route::prefix('consolidate_report')->group(function () {
            Route::get('/getResults', [ReportsController::class, 'getResultsConsolidateReport']);
            Route::get('/generate', [ReportsController::class, 'generateConsolidateReport']);
        });
        Route::prefix('consolidation_nav')->group(function () {
            Route::get('/getResults', [ReportsController::class, 'getResults']);
            Route::get('/generate', [ReportsController::class, 'generate']);
        });
        Route::prefix('inventoryValuation')->group(function () {
            Route::get('/getResults', [InventoryValuationController::class, 'getResults']);
            Route::post('/exportResult', [InventoryValuationController::class, 'exportResult']);
        });
        Route::prefix('advance_count')->group(function () {
            Route::get('/getResults', [AdvanceCountController::class, 'getAdvanceCount']);
            Route::post('/generate', [AdvanceCountController::class, 'generateAdvanceCount']);
            Route::get('/generateAppDataExcel', [AdvanceCountController::class, 'generateAdvanceCountExcel']);
            Route::get('/getNotFound', [AdvanceCountController::class, 'getAdvanceCountNotFound']);
            Route::get('/generateNotFound', [AdvanceCountController::class, 'generateAdvanceCountNotFound']);
        });
        Route::prefix('consolidate_adv_actual')->group(function () {
            Route::get('/getResults', [ConsolidateReportController::class, 'getResults']);
            Route::post('/generate', [ConsolidateReportController::class, 'generateConsolidateAdvActualCount']);
            Route::post('/generateExcel', [ConsolidateReportController::class, 'generateExcel']);
            Route::get('/getDates', [ConsolidateReportController::class, 'getDates']);

            Route::prefix('variance')->group(function () {
                Route::get('/getLogs', [ConsolidateReportController::class, 'getLogs']);
                Route::get('/getLineData', [ConsolidateReportController::class, 'getLineData']);
            });
        });
        Route::prefix('justification')->group(function () {
            Route::get('getResults', [JustificationController::class, 'getResults']);
            Route::get('getLines', [JustificationController::class, 'getLines']);
            Route::get('getReasons', [JustificationController::class, 'getReasons']);
            Route::post('postReason', [JustificationController::class, 'postReason']);
        });

        Route::prefix('backendadjustment')->group(function () {
            Route::get('getResults', [BackendAdjustmentController::class, 'getResults']);
            Route::get('getforinput', [BackendAdjustmentController::class, 'getforinput']);
            Route::get('getinputedit', [BackendAdjustmentController::class, 'getinputedit']);
            Route::get('getracklocation', [BackendAdjustmentController::class, 'getracklocation']);
            Route::get('getaudit', [BackendAdjustmentController::class, 'getaudit']);
            Route::get('getuser', [BackendAdjustmentController::class, 'getuser']);
            Route::get('getResultshistory', [BackendAdjustmentController::class, 'getResultshistory']);
            Route::get('getResultload', [BackendAdjustmentController::class, 'getResultload']);
            Route::get('getDates', [BackendAdjustmentController::class, 'getDates']);
            Route::get('getinfo', [BackendAdjustmentController::class, 'getinfo']);
            Route::get('getinfohistory', [BackendAdjustmentController::class, 'getinfohistory']);
            Route::post('getdeleteinfo', [BackendAdjustmentController::class, 'getdeleteinfo']);
            Route::post('getdeleteinfohistory', [BackendAdjustmentController::class, 'getdeleteinfohistory']);
            Route::post('save', [BackendAdjustmentController::class, 'save']);
        });

        Route::prefix('checkduplicateditems')->group(function () {
            Route::get('getResults', [CheckDuplicateController::class, 'getResults']);
            Route::get('getDates', [CheckDuplicateController::class, 'getDates']);
            Route::post('updateconversionqty', [CheckDuplicateController::class, 'updateconversionqty']);
        });

        Route::prefix('utils')->group(function () {
            Route::get('updateconversionqty', [UtilitiesController::class, 'updateconversionqty']);
        });
    });
});

Route::get('yw', function () {
    $snappy = App::make('snappy.pdf');
    //To file
    $html = '<h1>Bill</h1><p>You owe me money, dude.</p>';
    $snappy->generateFromHtml($html, "/tmp/" . time() . ".pdf");
    $snappy->generate('http://www.github.com', '/tmp/github.pdf');
    //Or output:
    return new Response(
        $snappy->getOutputFromHtml($html),
        200,
        array(
            'Content-Type'          => 'application/pdf',
            'Content-Disposition'   => 'attachment; filename="file.pdf"'
        )
    );
});

Route::get('delete-duplicates', function () {
    set_time_limit(0);

    ini_set('memory_limit', '-1');
    // $result = DB::statement("SELECT COUNT( item_code ) AS item_count, item_code FROM tbl_item_masterfile GROUP BY item_code HAVING item_count > 1");
    $result = DB::table('tbl_item_masterfile')->selectRaw("COUNT( item_code ) AS item_count, item_code")->groupBy('item_code')->having('item_count', '>', 1)->get();
    // DB::beginTransaction();
    foreach ($result as $key => $item_masterfile) {
        $result2 = DB::table('tbl_item_masterfile')->where('item_code', $item_masterfile->item_code);

        $result2->where('id', '!=', $result2->max('id'))->delete();

        // dd($query->get(), $result2->get());
    }

    // DB::rollBack();
    return response()->json(['message' => 'Database operation success']);
});

// Route::prefix('mapi')->group(function() {
//     Route::get('/checkConnection', [AppController::class, 'checkConnection']);
//     Route::get('/getFilteredItemMasterfile', [AppController::class, 'getFilteredItemMasterfile']);
//     Route::get('/getItemMasterfileCount', [AppController::class, 'getItemMasterfileCount']);
//     Route::get('/getItemMasterfileOffset', [AppController::class, 'getItemMasterfileOffset']);
//     Route::get('/getUserMasterfile', [AppController::class, 'getUserMasterfile']);
//     Route::get('/getAuditMasterifle', [AppController::class, 'getAuditMasterifle']);
//     Route::get('/getLocationMasterfile', [AppController::class, 'getLocationMasterfile']);

//     Route::post('/insertCountData', [AppController::class, 'insertCountData']);
// });




require __DIR__ . '/auth.php';

Route::middleware('auth')->get('{any}', [AppController::class, 'allowWhat'])->where('any', '.*');
