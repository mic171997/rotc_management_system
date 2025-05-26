<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use App\Exports\ConsolidateAdvActualCount;
use App\Models\TblConsolidateAdvActual;
use App\Models\TblConsolidateAdvActualLine;
use App\Models\TblNavCountdata;
use App\Models\TblQuestionableItem;

class ConsolidateReportController extends Controller
{
    public function getResults()
    {
        $user = auth()->user()->id;
        $company = request()->comp;
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $vendors = base64_decode(request()->vendors);
        $category = request()->category;
        $countType = request()->countType;
        $runDate = Carbon::parse(now())->toFormattedDateString();
        $runTime =  Carbon::parse(now())->format('h:i A');
        $date = base64_decode(request()->date);
        $date = explode(',', $date,);
        if ($date[0] != "null") {
            $actualStart = $date[0];
            $actualEnd = Carbon::parse($date[1])->endOfDay()->toDateTimeString();
        } else {
            $actualStart = null;
            $actualEnd = null;
        }

        $date2 = base64_decode(request()->date2);
        $date2 = explode(',', $date2,);

        // dd($advStart);
        if ($date2[0] != "null") {
            $advStart = $date2[0];
            $advEnd = Carbon::parse($date2[1])->endOfDay()->toDateTimeString();
        } else {
            $advStart = $actualStart;
            $advEnd = $actualEnd;
        }

        $printDate = $actualStart . '-' . $actualEnd;
        $date3 = base64_decode(request()->date3);
        // dd($printDate);
        $date3 = explode(',', $date3,);
        if ($date3[0] != "null") {
            $questStart = $date3[0];
            $questEnd = Carbon::parse($date3[1])->endOfDay()->toDateTimeString();
        } else {
            $questStart = $actualStart;
            $questEnd = $actualEnd;
        }

        // $key = implode('-', [$bu, $dept, $section, $printDate, 'ConsolidateAdvActualCount']);
        // dd($advStart, $advEnd, $actualStart, $actualEnd, $questStart, $questEnd);
        // dd($section);
        $section = $section === 'null' ? null : $section;
        // dd($section);
        $forCheck = TblConsolidateAdvActual::where([
            ['company', $company],
            ['business_unit', $bu],
            ['department', $dept],
            ['section', $section],
            ['advance_count_date', $date2[0] != "null" ? $advStart : null],
            ['advance_count_end', $date2[0] != "null" ? Carbon::parse($advEnd)->toDateString() : null],
            ['actual_count_date', $date[0] != "null" ? $actualStart : null],
            ['actual_count_end', $date[0] != "null" ? Carbon::parse($actualEnd)->toDateString() : null],
        ]);
        // dd($advStart, Carbon::parse($advEnd)->toDateString(), $actualStart, Carbon::parse($actualEnd)->toDateString());
        // dd($company, $bu, $dept, $section, $exists);

        $exists = $forCheck->exists();
        // dd($exists);
        if ($exists) {
            // $forCheck = $forCheck->paginate(10);
            $forPrint = array(
                'company' => $company,
                'business_unit' => $bu,
                'department' => $dept,
                'section' => $section,
                'vendors' => $vendors,
                'category' => $category,
                'date' => $printDate,
                'countType' => $countType,
                'user' => auth()->user()->name,
                'user_position' => auth()->user()->position,
                'runDate'   => $runDate,
                'runTime'    => $runTime,
                'hasData' => true
            );

            $merge = collect(['data' => []]);
            $merge = $merge->merge($forPrint);
            return $merge;
        } else {
            // vvv FOR TESTING vvv//
            $table = 'tbl_app_countdata';
            $advtable = 'tbl_advance_count';
            $masterfile = 'tbl_item_masterfile';

            if ($bu == 'ASC: MAIN') {
                $var = $section == "SNACKBAR" ? "_snackbar" :  '_alturas';
                $masterfile = $section == "SNACKBAR" ? "tbl_item_masterfile_snackbar" :  'tbl_item_masterfile';
            } else if ($bu == 'PLAZA MARCELA') {
                $var = '_pm';
            } else if ($bu == 'ALTURAS TALIBON') {
                $var = '_talibon';
            } else if ($bu == 'ALTA CITTA') {
                $var = '_alta';
                $masterfile = "tbl_item_masterfile_alta_citta";
            } else if ($bu == 'DISTRIBUTION') {
                $var = '_pdc';
                $masterfile = "tbl_item_masterfile_pdc";
            } else {
                $var = '';
            }

            $tbl = $table . $var;


            /////////////////////////comment ******************************* shit

            // if ($bu == 'DISTRIBUTION') {
            //     $selectRawValue = "$tbl.itemcode, variant_code, $tbl.lot_number, $tbl.expiry";
            // } else {
            //     $selectRawValue = "$tbl.itemcode, variant_code";
            // }


            $selectRawValue = "$tbl.itemcode, variant_code";


            /////////////////////////comment ******************************* shit

            // dd("$tbl");
            $queryB = DB::table($tbl)->SelectRaw($selectRawValue)
                ->WHERE([
                    ['business_unit',  $bu],
                    ['department', $dept],
                    // ["$tbl.itemcode", '132554']
                ])
                // ->whereIn("$tbl.itemcode", [
                //     '621968', '1902', '8345', '840107', '146776', '101488', '102184', '102327', '105843', '813214', '630590', '189563', '120816',
                //     '8345', '100617', '103006', '103019', '103529', '103549', '103556', '106341', '107650', '107708', '108920', '109620', '112929', '112934', '112948', '112949', '112951', '112981', '112986', '113005', '113011', '113132', '113145', '123985', '130039', '135635', '144365'
                // ])
                ->whereBetween('datetime_exported', [$actualStart, $actualEnd])
                ->join("$masterfile", "$masterfile.barcode", '=', "$tbl.barcode")

                // ->groupBy(["$tbl.itemcode", 'variant_code', "$tbl.lot_number", "$tbl.expiry"]);

                ->groupBy(["$tbl.itemcode", 'variant_code']);
            // dd($query);
            if ($bu != "PLAZA MARCELA" && $bu != "ALTA CITTA" && $bu != "ALTURAS TALIBON") {
                $queryB = $queryB->where("$tbl.section", $section);
                // dd($queryB->get(), 1);
            }

            ///////////

            elseif ($bu == "ALTURAS TALIBON" && $section == "CENTRALIZE") {
                $queryB = $queryB->where("$tbl.section", '<>', "WAREHOUSE");
            } elseif ($bu == "ALTURAS TALIBON" && $section == "WAREHOUSE") {
                $queryB = $queryB->where("$tbl.section", $section);
            }

            ///////


            // dd($queryB->get());

            /////////////////////////comment ******************************* shit

            // if ($bu == 'DISTRIBUTION') {
            //     $selectRawValue = 'itemcode, "" as variant_code, lot_number, expiry';
            // } else {
            //     $selectRawValue = 'itemcode, "" as variant_code';
            // }

            $selectRawValue = 'itemcode, "" as variant_code';

            /////////////////////////comment ******************************* shit
            $queryC = TblQuestionableItem::SelectRaw($selectRawValue)
                ->WHERE([
                    ['business_unit',  $bu],
                    ['department', $dept],
                    // ["itemcode", '132554']
                ])
                // ->whereIn('itemcode', [
                //     '621968', '1902', '8345', '840107', '146776', '101488', '102184', '102327', '105843', '813214', '630590', '189563', '120816',
                //     '8345', '100617', '103006', '103019', '103529', '103549', '103556', '106341', '107650', '107708', '108920', '109620', '112929', '112934', '112948', '112949', '112951', '112981', '112986', '113005', '113011', '113132', '113145', '123985', '130039', '135635', '144365'
                // ])
                ->whereBetween('count_date', [$questStart, $questEnd])

                // ->groupBy(['itemcode', 'variant_code', 'lot_number', 'expiry']);

                ->groupBy(['itemcode', 'variant_code']);

            if ($bu != "PLAZA MARCELA" && $bu != "ALTA CITTA" && $bu != "ALTURAS TALIBON") {
                $queryC = $queryC->where('section', $section);
            }
            ///////

            elseif ($bu == "ALTURAS TALIBON" && $section == "CENTRALIZE") {
                $queryC = $queryC->where('section', '<>', "WAREHOUSE");
            } elseif ($bu == "ALTURAS TALIBON" && $section == "WAREHOUSE") {
                $queryC = $queryC->where('section', $section);
            }
            // dd($queryC->get());
            //////////
            $advtbl = $advtable . $var;
            // dd($advtbl);

            /////////////////////////comment ******************************* shit

            // if ($bu == 'DISTRIBUTION') {
            //     $selectRawValue = "$advtbl.itemcode, variant_code, $advtbl.lot_number, $advtbl.expiry";
            // } else {
            //     $selectRawValue = "$advtbl.itemcode, variant_code";
            // }


            $selectRawValue = "$advtbl.itemcode, variant_code";

            /////////////////////////comment ******************************* shit

            $query = DB::table($advtbl)->SelectRaw($selectRawValue)
                ->WHERE([
                    ['business_unit', $bu],
                    ['department', $dept],
                    // ["$advtbl.itemcode", '132554']
                ])
                // ->whereIn("$advtbl.itemcode", [
                //     '621968', '1902', '8345', '840107', '146776', '101488', '102184', '102327', '105843', '813214', '630590', '189563', '120816',
                //     '8345', '100617', '103006', '103019', '103529', '103549', '103556', '106341', '107650', '107708', '108920', '109620', '112929', '112934', '112948', '112949', '112951', '112981', '112986', '113005', '113011', '113132', '113145', '123985', '130039', '135635', '144365'
                // ])
                ->whereBetween('datetime_exported', [$advStart, $advEnd])
                ->join("$masterfile", "$masterfile.barcode", '=', "$advtbl.barcode")

                // ->groupBy(["$advtbl.itemcode", 'variant_code', "$advtbl.lot_number", "$advtbl.expiry"]);

                ->groupBy(["$advtbl.itemcode", 'variant_code', "$advtbl.lot_number", "$advtbl.expiry"]);

            if ($bu != "PLAZA MARCELA" && $bu != "ALTA CITTA" && $bu != "ALTURAS TALIBON") {
                $query = $query->where("$advtbl.section", $section);
            }

            ////////

            elseif ($bu == "ALTURAS TALIBON" && $section == "CENTRALIZE") {
                $query = $query->where("$advtbl.section", '<>', "WAREHOUSE");
            } elseif ($bu == "ALTURAS TALIBON" && $section == "WAREHOUSE") {
                $query = $query->where("$advtbl.section", $section);
            }


            /////////

            // ->union($queryC);
            // ->limit(1000)
            // dd($query->dd(), $queryB->get(), $queryC->get());


            $query = $query->union($queryC)->union($queryB);
            // ->limit(500);
            // dd($query->get());
            $query2 = $query->cursor()->map(function ($test) use ($actualStart, $actualEnd, $advStart, $advEnd, $table, $advtable, $var, $questStart, $questEnd, $bu, $dept, $section, $tbl, $advtbl, $masterfile) {
                // dd($test);
                $actualQuery = DB::table($tbl)->selectRaw("SUM($tbl.conversion_qty) as qty, SUM($tbl.qty) as count_qty, $tbl.description, itemcode ")
                    // ->WHERE([
                    //     ['itemcode', $test->itemcode],
                    //     ['variant_code', $test->variant_code != "" ? $test->variant_code : ""],
                    //     ['lot_number', $test->lot_number],
                    //     ['expiry', $test->expiry],
                    //     // ['department', $dept]
                    // ])

                    ->WHERE([
                        ['itemcode', $test->itemcode],
                        ['variant_code', $test->variant_code != "" ? $test->variant_code : ""],

                    ])
                    ->join("$masterfile", "$masterfile.barcode", '=', "$tbl.barcode")
                    ///estong
                    // ->groupBy(["$tbl.lot_number", "$tbl.expiry"])
                    ///estong
                    ->whereBetween('datetime_exported', [$actualStart, $actualEnd]);
                // dd($actualQuery->get());
                // dd($test->itemcode == "8345" ? $test : $test);

                $advQuery = DB::table($advtbl)->selectRaw("SUM($advtbl.conversion_qty) as qty, SUM($advtbl.qty) as count_qty, $advtbl.description")
                    ->WHERE([
                        ['itemcode', $test->itemcode],
                        ['variant_code', $test->variant_code != "" ? $test->variant_code : ""],
                        // ['lot_number', $test->lot_number],
                        // ['expiry', $test->expiry],
                        // ["$advtbl.uom", $test->uom],
                        // ['barcode', $test->barcode],
                        // ['business_unit', $bu],
                        // ['department', $dept]
                    ])
                    ->join("$masterfile", "$masterfile.barcode", '=', "$advtbl.barcode")
                    ///estong
                    //  ->groupBy([ "$advtbl.lot_number", "$advtbl.expiry"])
                    ///estong
                    ->whereBetween('datetime_exported', [$advStart, $advEnd]);
                // dd($advQuery->dd());

                $quesQuery = TblQuestionableItem::selectRaw('SUM(qty) as qty, description')->WHERE([
                    ['itemcode', $test->itemcode],
                    ['variant_code', $test->variant_code != "" ? $test->variant_code : null],
                    // ['lot_number', $test->lot_number],
                    // ['expiry', $test->expiry],
                    // ['barcode', $test->barcode],
                    // ['uom', $test->uom],
                    // ['business_unit', $bu],
                    // ['department', $dept]
                ])
                    ->whereBetween('count_date', [$questStart, $questEnd]);

                // dd($quesQuery->get());
                if ($bu != "PLAZA MARCELA" && $bu != "ALTA CITTA" && $bu != "ALTURAS TALIBON") {
                    $actualQuery = $actualQuery->where("$tbl.section", $section);
                    $advQuery = $advQuery->where("$advtbl.section", $section);
                    $quesQuery = $quesQuery->where('section', $section);
                } elseif ($bu == "ALTURAS TALIBON" && $section == "CENTRALIZE") {
                    // $query = $queryB->where("$advtbl.section", '<>', "WAREHOUSE");
                    $actualQuery = $actualQuery->where("$tbl.section", '<>', "WAREHOUSE");
                    $advQuery = $advQuery->where("$advtbl.section", '<>', "WAREHOUSE");
                    $quesQuery = $quesQuery->where('section', '<>', "WAREHOUSE");
                } elseif ($bu == "ALTURAS TALIBON" && $section == "WAREHOUSE") {
                    $actualQuery = $actualQuery->where("$tbl.section", $section);
                    $advQuery = $advQuery->where("$advtbl.section", $section);
                    $quesQuery = $quesQuery->where('section', $section);
                }
                // dd($actualQuery->first()->description != null ? $actualQuery->first()->description : $advQuery->first()->description);
                // $test->barcode == "2200006271611" ? dd($test) : null;
                $test->description =  $actualQuery->first()->description != null
                    ? $actualQuery->first()->description
                    : ($advQuery->first()->description != null
                        ? $advQuery->first()->description
                        : $quesQuery->first()->description);

                $ActualCount = $test->ActualCountQty = $actualQuery->first()->qty != 0 ? (int) $actualQuery->first()->qty : (int) $actualQuery->first()->count_qty;
                // dd($ActualCount);
                $AdvanceCount = $test->AdvCountQty = $advQuery->first()->qty != 0 ? (int) $advQuery->first()->qty : (int) $advQuery->first()->count_qty;
                $QuestionableCount  = $test->QuestCountQty = (int) $quesQuery->first()->qty;
                // dd($ActualCount, $AdvanceCount, $QuestionableCount);
                // $Questionable->exists() ? $QuestionableCount =  $test->QuestCountQty = (int) $Questionable->first()->qty :   $QuestionableCount =  $test->QuestCountQty = 0;

                // $NotfoundItems  = $test->NotFoundQty = (int) TblAppNfitem::selectRaw('SUM(qty) as qty')->where([
                //     ['barcode', $test->barcode],
                //     ['uom', $test->uom]
                // ])
                //     ->WHERE([
                //         ['business_unit', $bu],
                //         ['department', $dept],
                //         ['section',  $section]
                //     ])
                //     ->whereBetween('datetime_exported', [$advStart, $actualEnd])
                //     ->first()->qty;
                // dd($test);
                $nav_uom = TblNavCountdata::selectRaw('uom')->where([
                    ['itemcode', $test->itemcode],
                    ['variant_code', $test->variant_code == '' ? null : $test->variant_code]
                ])
                    ->JOIN("tbl_nav_upload", function ($join) use ($bu, $dept, $section) {
                        $join->on("tbl_nav_upload.id", '=', "tbl_nav_countdata.batch_id")
                            ->where([
                                ['business_unit', $bu],
                                ['department', $dept],
                                ['section', $section]
                            ]);
                    })
                    ->whereNotNull('uom');
                // dd($nav_uom->get());

                // $nav_uom->exists() ? $test->nav_uom = $nav_uom->first()->uom : $test->nav_uom = 'PCS';
                // $nav_uom->exists() ? $test->uom = $nav_uom->first()->uom : $test->nav_uom = 'PCS';

                $masterfile_uom = DB::table($masterfile)->where(['item_code' => $test->itemcode, 'conversion_qty' => 1])->select('uom')->first();

                $test->nav_uom = $masterfile_uom ? $masterfile_uom->uom : 'PCS';
                $test->uom = $masterfile_uom ? $masterfile_uom->uom : 'PCS';

                // $nav_uom->exists() ? $test->nav_uom = $nav_uom->first()->uom : $test->nav_uom = $masterfile_uom ? $masterfile_uom->uom : 'PCS';
                // $nav_uom->exists() ? $test->uom = $nav_uom->first()->uom : $test->uom = $masterfile_uom ? $masterfile_uom->uom : 'PCS';

                // $nav_uom->exists() ? $test->nav_uom = $nav_uom->first()->uom : $test->nav_uom = $masterfile_uom->uom ? $masterfile_uom->uom : 'PCS';
                // $nav_uom->exists() ? $test->uom = $nav_uom->first()->uom : $test->nav_uom = $masterfile_uom->uom ? $masterfile_uom->uom : 'PCS';
                $test->total = $ActualCount + $AdvanceCount + $QuestionableCount;
                // $test->total = $ActualCount + $AdvanceCount + $QuestionableCount + $NotfoundItems;

                if ($test->total != 0) {

                    return (array) $test;
                } else {
                    return null;
                }
            })->filter()->values();

            $forPrint = array(
                'company' => $company,
                'business_unit' => $bu,
                'department' => $dept,
                'section' => $section,
                'vendors' => $vendors,
                'category' => $category,
                'date' => $printDate,
                'countType' => $countType,
                'user' => auth()->user()->name,
                'user_position' => auth()->user()->position,
                'runDate'   => $runDate,
                'runTime'    => $runTime,
                'hasData' => false,
                'data' => $query2
            );

            // $merge = collect(['items' => $forPrint]);
            // $merge = $merge->merge($forPrint);
            return $forPrint;
            // ^^^FOR TESTING ^^^//

            // $queryB = TblAppCountdata::SelectRaw('itemcode, barcode, uom, description')
            //     ->WHERE([
            //         ['business_unit',  'LIKE', "%$bu%"],
            //         ['department', 'LIKE', "%$dept%"],
            //         ['section', 'LIKE', "%$section%"]
            //     ])
            //     ->whereBetween('datetime_saved', [$date, $dateAsOf])
            //     ->groupBy(['itemcode', 'barcode', 'uom']);

            // $query = TblAdvanceCount::SelectRaw('itemcode, barcode, uom, description')
            //     ->WHERE([
            //         ['business_unit',  'LIKE', "%$bu%"],
            //         ['department', 'LIKE', "%$dept%"],
            //         ['section', 'LIKE', "%$section%"]
            //     ])
            //     ->union($queryB)
            //     ->whereBetween('datetime_saved', [$date2, $date2AsOf])
            //     ->groupBy(['itemcode', 'barcode', 'uom']);

            // $query2 = $query->get()->map(function ($test) use ($date, $dateAsOf, $date2, $date2AsOf) {
            //     // dd($test->itemcode);
            //     $ActualCount = $test->ActualCountQty = TblAppCountdata::selectRaw('SUM(qty) as qty')->where([
            //         ['itemcode', $test->itemcode]
            //     ])
            //         ->whereBetween('datetime_saved', [$date, $dateAsOf])
            //         ->first()->qty;

            //     $AdvanceCount = $test->AdvCountQty = TblAdvanceCount::selectRaw('SUM(qty) as qty')->where([
            //         ['itemcode', $test->itemcode]
            //     ])
            //         ->whereBetween('datetime_saved', [$date2, $date2AsOf])
            //         ->first()->qty;

            //     $test->total = $ActualCount + $AdvanceCount;

            //     return $test;
            // });
            // // dd($query2);

            // $query = $query->paginate(10)->through(function ($test) use ($date, $dateAsOf, $date2, $date2AsOf) {
            //     $ActualCount = $test->ActualCountQty = TblAppCountdata::selectRaw('SUM(qty) as qty')->where([
            //         ['itemcode', $test['itemcode']]
            //     ])
            //         ->whereBetween('datetime_saved', [$date, $dateAsOf])
            //         ->first()->qty;

            //     $AdvanceCount = $test->AdvCountQty = TblAdvanceCount::selectRaw('SUM(qty) as qty')->where([
            //         ['itemcode', $test['itemcode']]
            //     ])
            //         ->whereBetween('datetime_saved', [$date2, $date2AsOf])
            //         ->first()->qty;

            //     $test->total = $ActualCount + $AdvanceCount;
            //     return $test;
            // });

            // $forPrint = array(
            //     'company' => $company,
            //     'business_unit' => $bu,
            //     'department' => $dept,
            //     'section' => $section,
            //     'vendors' => $vendors,
            //     'category' => $category,
            //     'date' => $printDate,
            //     'countType' => $countType,
            //     'user' => auth()->user()->name,
            //     'user_position' => auth()->user()->position,
            //     'runDate'   => $runDate,
            //     'runTime'    => $runTime,
            //     'hasData' => false,
            //     'data' => $query2
            // );

            // $merge = collect(['items' => $forPrint]);
            // $merge = $merge->merge($query);
            // return $merge;
        }
    }

    public function generateConsolidateAdvActualCount()
    {
        // dd(request()->data);
        $dateNow = Carbon::now()->toFormattedDateString();
        $timeNow = Carbon::now()->format('h:i:s A');
        // $date = Carbon::parse(base64_decode(request()->date))->toDateString();
        // $date2 = Carbon::parse(base64_decode(request()->date2))->toDateString();
        $date = base64_decode(request()->date);
        $date = explode(',', $date,);
        $actualStart = $date[0];
        $actualEnd = $date[1];

        $date2 = base64_decode(request()->date2);
        $date2 = explode(',', $date2,);
        $advStart = $date2[0];
        $advEnd = $date2[1];

        $pdf = PDF::loadView('reports.consolidated_report_advance_actual_count', ['data' => request()->data, 'date' => $dateNow . ' ' . $timeNow, 'user' => request()->user, 'dateFrom' => $actualStart, 'dateTo' => $actualEnd]);
        return $pdf->setPaper('legal', 'landscape')->download('Consolidated Report Advance & Actual Countu from APP.pdf');
    }

    public function generateExcel()
    {
        $company = request()->company;
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $type = 'Consolidate Advance & Actual Count';
        $exists = request()->data;
        // $date = Carbon::parse(base64_decode(request()->date))->toDateString();
        // $date2 = Carbon::parse(base64_decode(request()->date2))->toDateString();
        $date = base64_decode(request()->date);
        $date = explode(',', $date,);
        if ($date[0] != "null") {
            $actualStart = $date[0];
            $actualEnd = $date[1];
        } else {
            $actualStart = null;
            $actualEnd = null;
        }

        $date2 = base64_decode(request()->date2);
        $date2 = explode(',', $date2,);

        if ($date2[0] != "null") {
            $advStart =  $date2[0];
            $advEnd = $date2[1];
        } else {
            $advStart = null;
            $advEnd = null;
        }

        // dd($date[0], $date[1], $advStart, $advEnd);
        if ($exists['hasData'] != true) {
            DB::transaction(function () use ($company, $bu, $dept, $section, $actualStart, $actualEnd, $advStart, $advEnd, $date2) {

                $values = [
                    'user_id' => auth()->user()->id,
                    'company' => $company,
                    'business_unit' => $bu,
                    'department' => $dept,
                    'section' => $section,
                    'actual_count_date' => $actualStart,
                    'actual_count_end' => $actualEnd,
                    'advance_count_date' => $advStart,
                    'advance_count_end' => $advEnd
                ];

                $log = TblConsolidateAdvActual::create($values);
                $data = request()->data['data'];

                foreach ($data as $line) {
                    // dd((int)$line['total']);
                    $x = [
                        'itemcode' =>  $line['itemcode'],
                        'variant_code' => $line['variant_code'],
                        'uom' => $line['nav_uom'],
                        // 'lot_number' => $line['lot_number'],
                        // 'expiry' => $line['expiry'],
                        'description' => $line['description'],
                        'actualCountQty' => (int)$line['ActualCountQty'],
                        'advanceCountQty' => (int)$line['AdvCountQty'],
                        'questCountQty' => (int)$line['QuestCountQty'],
                        'totalQty' => (int)$line['total'],
                        'batch_id' => $log->id
                    ];
                    TblConsolidateAdvActualLine::create($x);
                }
            });

            session(['data' => request()->data, 'type' => $type]);
            return Excel::download(new ConsolidateAdvActualCount, $type . '.xlsx');
        } else {
            $forCheck = TblConsolidateAdvActual::where([
                ['company', $company],
                ['business_unit', $bu],
                ['department', $dept],
                ['section', $section],
                ['actual_count_date', $actualStart],
                ['actual_count_end', $actualEnd],
                ['advance_count_date', $advStart],
                ['advance_count_end', $advEnd]
            ])
                ->first()->toArray();

            $line = TblConsolidateAdvActualLine::where([
                ['batch_id', $forCheck['id']]
            ])->get();

            $data = [];
            foreach ($line as $a) {
                $x = [
                    'itemcode' =>  $a['itemcode'],
                    'variant_code' => $a['variant_code'],
                    'uom' => $a['uom'],
                    'lot_number' => $a['lot_number'],
                    'expiry' => $a['expiry'],
                    'description' => $a['description'],
                    'ActualCountQty' => $a['actualCountQty'],
                    'AdvCountQty' => $a['advanceCountQty'],
                    'QuestCountQty' => $a['questCountQty'],
                    'total' => $a['totalQty']
                ];
                $data[] = $x;
            }


            $exists = array_merge($exists, ['data' => $data]);
            session(['data' => $exists, 'type' => $type]);
            return Excel::download(new ConsolidateAdvActualCount, $type . '.xlsx');
        }
    }

    public function getLogs()
    {
        return TblConsolidateAdvActual::SelectRaw('tbl_consolidate_adv_actual.id as batch_id, user_id, tbl_consolidate_adv_actual.company, tbl_consolidate_adv_actual.business_unit, tbl_consolidate_adv_actual.department, tbl_consolidate_adv_actual.section, actual_count_date, advance_count_date, tbl_consolidate_adv_actual.created_at, name')
            ->join('users', 'users.id', 'tbl_consolidate_adv_actual.user_id')->paginate(10);
    }
    public function getLineData()
    {
        $search = request()->search;
        if (!$search) {
            return TblConsolidateAdvActualLine::where('batch_id', request()->batch_id)->paginate(10);
        } else {
            return TblConsolidateAdvActualLine::where([
                ['batch_id', request()->batch_id],
                ['itemcode', 'LIKE', "%$search%"]
            ])->paginate(10);
        }
    }

    public function getDates()
    {
        // dd(request()->all());
        $comp = request()->comp;
        $bu = request()->bu;
        $dept = request()->dept;
        $sect = request()->sect;
        $countType = request()->countType;

        $countType == 'ACTUAL' ? $table = 'tbl_app_countdata' : $table = 'tbl_advance_count';
        if ($bu == 'ASC: MAIN') {
            $var = $sect == "SNACKBAR" ? "_snackbar" :  '_alturas';
        } else if ($bu == 'PLAZA MARCELA') {
            $var = '_pm';
            $sect = null;
        } else if ($bu == 'ALTURAS TALIBON') {
            $var = '_talibon';
        } else if ($bu == 'ALTA CITTA') {
            $var = '_alta';
        } else if ($bu == 'DISTRIBUTION') {
            $var = '_pdc';
        } else {
            $var = '';
        }
        // dd($table . $var);
        // dd($bu);

        $query = DB::table($table . $var)->selectRaw("
            DATE_FORMAT(datetime_exported, '%Y-%m-%d') as batchDate
            ");

        if ($bu != "PLAZA MARCELA" && $bu != "ALTA CITTA" && $bu != "ALTURAS TALIBON") {
            $query = $query->where([
                ['business_unit', $bu],
                ['department', $dept],
                ['section', $sect]
            ]);
        } elseif ($bu == "ALTURAS TALIBON" && $sect == "CENTRALIZE") {
            $query = $query->where([
                ['business_unit', $bu],
                ['department', $dept]
            ])
                ->where('section', '<>', "WAREHOUSE");
        } elseif ($bu == "ALTURAS TALIBON" && $sect == "WAREHOUSE") {
            $query = $query->where([
                ['business_unit', $bu],
                ['department', $dept],
                ['section', $sect]
            ]);
        } else {
            $query = $query->where([
                ['business_unit', $bu],
                ['department', $dept]
            ]);
        }
        return $query->GROUPBYRAW('EXTRACT(DAY FROM datetime_exported)')->cursor();
    }
}
