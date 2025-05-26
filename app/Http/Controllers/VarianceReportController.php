<?php

namespace App\Http\Controllers;

use App\Exports\VarianceReport;
use App\Exports\VarianceSummary;
use Carbon\Carbon;
use App\Models\TblUnposted;
use App\Models\TblNavCountdata;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;
use App\Models\TblConsolidateAdvActual;
use App\Models\TblConsolidateAdvActualLine;
use App\Models\TblVarianceReport;
use App\Models\TblVarianceReportLine;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class VarianceReportController extends Controller
{
    public function getResults()
    {
        $company = request()->company;
        $section = request()->section;
        $date = base64_decode(request()->date);
        $date = explode(',', $date);
        $date2 = Carbon::parse(base64_decode(request()->date2))->toDateString();
        $bu = request()->bu;
        $dept = request()->dept;
        $app_id = request()->batch_id;
        $old_batch_id = request()->old_batch_id;
        $new_batch_id = request()->new_batch_id;
        $nav_id = request()->nav_id;
        $varExist = null;
        // dd($app_id, $nav_id);
        $forCheck = TblVarianceReport::where([
            ['company', $company],
            ['business_unit', $bu],
            ['department', $dept],
            ['adv_start_date', $date[0]],
            ['adv_end_date', $date[1]],
            ['actual_start_date', $date[2]],
            ['actual_end_date', $date[3]],
            ['nav_date', $date2],
        ]);

        if ($bu != "PLAZA MARCELA" && $bu != "ALTA CITTA" && $bu != "DISTRIBUTION") {

            $forCheck = $forCheck->where('section', $section);
        }

        if ($old_batch_id && $new_batch_id) {

            if ($forCheck->exists()) {

                $exist = $forCheck->first();
                $query = TblVarianceReportLine::SelectRaw('id, itemcode, variant_code, description as extended_desc, uom, lot_number, expiry, app_qty as conversion_qty, nav_qty, variance, cost_vat, cost_no_vat, amt')->where('batch_id', $exist->id)
                    ->get();
                $varExist = true;
            } else {

                $result = TblConsolidateAdvActualLine::selectRaw('itemcode, variant_code, lot_number, expiry')
                    ->where([
                        ['batch_id', $old_batch_id],
                    ]);

                $result2 = TblConsolidateAdvActualLine::selectRaw('itemcode, variant_code, lot_number, expiry')
                    ->where([
                        ['batch_id', $new_batch_id],
                    ]);

                $x = $result->union($result2)
                    ->orderBy('itemcode', 'DESC')
                    ->GroupBy('itemcode', 'variant_code', 'lot_number', 'expiry')
                    ->cursor();
                $variance = 0;
                $value = 0;

                $query = $x->map(function ($c) use ($bu, $dept, $section, $date2, $old_batch_id, $new_batch_id, $variance, $value) {
                    $app = TblConsolidateAdvActualLine::selectRaw('SUM(totalQty) as totalQty, description, uom')
                        ->where([
                            ['itemcode', $c->itemcode],
                            ['variant_code', $c->variant_code != null ? $c->variant_code : null],
                            ['batch_id', $old_batch_id]
                        ]);
                    $count_desc = $app->exists() ? $app->first()->description : null;
                    $count_uom = $app->exists() ? $app->first()->uom : null;

                    $app->exists() ? $appQty = $c->conversion_qty = (float) $app->first()->totalQty : $appQty = $c->conversion_qty = 0;

                    $x = TblConsolidateAdvActualLine::selectRaw('SUM(totalQty) as totalQty, description, uom')
                        ->where([
                            ['itemcode', $c->itemcode],
                            ['variant_code', $c->variant_code != null ? $c->variant_code : null],
                            ['batch_id', $new_batch_id]
                        ]);

                    $nav_desc = $x->exists() ? $x->first()->description : null;
                    $nav_uom = $x->exists() ? $x->first()->uom : null;

                    $x->exists() ? $c->cost_no_vat = (float)$x->first()->cost_no_vat : $c->cost_no_vat = (float)0;
                    $x->exists() ? $c->amt = (float) $x->first()->amt : $c->amt = (float) 0;
                    $c->extended_desc = $nav_desc != '' ? $nav_desc : $count_desc;
                    $c->uom = $nav_uom != '' || $nav_uom != null ? $nav_uom : $count_uom;

                    $nav = $x->exists() ? $nav = $x->conversion_qty = (float) $x->first()->totalQty :  $nav = $x->conversion_qty = 0;
                    $c->nav_qty = $nav > 0 ? $nav = (float) $nav :  $nav = (float) $nav;
                    $unposted = 0;
                    $c->unposted = (float) $unposted;

                    $variance = $nav - $appQty;
                    $c->variance = $variance;

                    if ($nav == 0 && $appQty == 0 && $variance == 0) {
                        return null;
                    } else {
                        return $c;
                    }
                })->filter()->values();

                $varExist = false;
            }
        } else {
            if ($forCheck->exists()) {
                // $exist = $data['justify'] = $forCheck->first();
                $exist = $forCheck->first();
                $query = TblVarianceReportLine::SelectRaw('id, itemcode, variant_code, description as extended_desc, uom, app_qty as conversion_qty, nav_qty, variance, cost_vat, cost_no_vat, amt')->where('batch_id', $exist->id)
                    // ->limit(300)
                    ->get();
                // $query = $result->map(function ($c) use ($bu, $dept, $section, $date2) {
                //     return $c;
                // })->all();
                $varExist = true;
            } else {
                $result2 = TblNavCountdata::selectRaw('itemcode, variant_code')
                    ->where([
                        ['batch_id', $nav_id],
                        // ['qty', '>', 0],
                    ]);
                // ->whereIn('itemcode', ['100648', '112765', '655431', '655431', '664179']);
                $result = TblConsolidateAdvActualLine::selectRaw('itemcode, variant_code')
                    ->where([
                        ['batch_id', request()->batch_id],
                    ]);
                // ->whereIn('itemcode', ['100648', '112765', '655431', '655431', '664179']);
                // dd($result2->get(), $result->get());

                // $subquery = TblConsolidateAdvActualLine::selectRaw('itemcode')
                //     ->where([
                //         ['batch_id', request()->batch_id],
                //         // ['itemcode', '840107'],
                //     ])
                //     ->whereIn('itemcode', ['621968', '1902', '8345', '840107', '146776', '101488']);

                // $result2 = TblNavCountdata::selectRaw('tbl_nav_countdata.itemcode, tbl_nav_countdata.variant_code,
                //     tbl_nav_countdata.uom, description as extended_desc')
                //     ->where([
                //         ['batch_id', $nav_id],
                //     ])
                //     ->whereIn('itemcode', function ($query) use ($result) {
                //         // $query->select('itemcode')
                //         //     ->from('tbl_nav_countdata')
                //         //     ->whereIn('itemcode', $subquery);
                //         $query->select('itemcode')
                //             ->fromSub($result->getQuery(), 'subquery');
                //     });
                // dd($result3);

                $x = $result->union($result2)
                    ->orderBy('variant_code', 'DESC')
                    ->GroupBy('itemcode', 'variant_code')
                    // ->limit(1500)
                    ->cursor();
                // dd($x);
                $variance = 0;
                $value = 0;

                $query = $x->map(function ($c) use ($bu, $dept, $section, $date2, $app_id, $nav_id, $variance, $value) {
                    // dd($c);
                    $app = TblConsolidateAdvActualLine::selectRaw('SUM(totalQty) as totalQty, description, uom')
                        ->where([
                            ['itemcode', $c->itemcode],
                            ['variant_code', $c->variant_code != null ? $c->variant_code : null],
                            // ['uom', $c->uom],
                            ['batch_id', $app_id]
                        ]);
                    $count_desc = $app->exists() ? $app->first()->description : null;
                    $count_uom = $app->exists() ? $app->first()->uom : null;

                    $app->exists() ? $appQty = $c->conversion_qty = (float) $app->first()->totalQty : $appQty = $c->conversion_qty = 0;

                    $x = TblNavCountdata::selectRaw("SUM(qty) as nav_qty, cost_vat, cost_no_vat, amt, description, uom")->where([
                        ['itemcode', $c->itemcode],
                        ['variant_code', $c->variant_code != null ? $c->variant_code : null],
                        // ['uom', $c->uom != null ? $c->uom : ''],
                        ['batch_id', $nav_id]
                    ]);

                    // dd($x->dd());
                    $nav_desc = $x->exists() ? $x->first()->description : null;
                    $nav_uom = $x->exists() ? $x->first()->uom : null;

                    $x->exists() ? $c->cost_no_vat = (float)$x->first()->cost_no_vat : $c->cost_no_vat = (float)0;
                    $x->exists() ? $c->amt = (float) $x->first()->amt : $c->amt = (float) 0;
                    $c->extended_desc = $nav_desc != '' ? $nav_desc : $count_desc;
                    $c->uom = $nav_uom != '' || $nav_uom != null ? $nav_uom : $count_uom;
                    // $c->extended_desc = $app->exists() ? $app->first()->description : $x->first()->description;
                    // $barcode = TblItemMasterfile::selectRaw('barcode')->where([['item_code', $c->itemcode], ['uom', $c->uom]])->groupBy('barcode');
                    // $barcode->exists() ?  $c->barcode = $barcode->first()->barcode : null;

                    // $y = TblUnposted::selectRaw("SUM(qty) as unposted")->where([
                    //     ['itemcode', $c->itemcode],
                    //     ['business_unit', $bu],
                    //     ['department', $dept],
                    //     ['section', $section],
                    //     ['date', $date2]
                    // ])->groupBy('itemcode');
                    // dd($y->dd(), $date2);
                    $nav = $x->exists() ? $x->first()->nav_qty :  '-';
                    // dd((float) $nav);

                    // $c->nav_qty = (float) $nav;
                    $c->nav_qty = $nav > 0 ? $nav = (float) $nav :  $nav = (float) $nav;
                    // $y->exists() ? $unposted =  $y->first()->unposted : $unposted =  '-';
                    $unposted = 0;
                    $c->unposted = (float) $unposted;


                    if ($unposted != "-" && $nav != "-") {
                        $value = $nav + $unposted;
                    } else if ($nav == '-' && $unposted != '-') {
                        $value = $unposted;
                        $variance = $unposted + $appQty;
                    } else if ($unposted == '-' && $nav != '-') {
                        $value = $nav;
                        $variance = $nav + $appQty;
                    }
                    if ($nav == '-' && $unposted == '-') {
                        $value = '-';
                        $variance = $appQty;
                    }

                    // if ($nav < 0) {
                    //     $value == is_numeric($value) ? $variance = $appQty + $value :
                    //         $variance = $appQty;
                    // } else {
                    //     if ($nav != '-') $value == is_numeric($value) ?
                    //         $variance = $appQty - $value : $variance = $appQty; //
                    //     // if ($item['itemcode'] == '1902') dd($item, $variance);
                    // }
                    $variance = $appQty - $nav;
                    $c->variance = $variance;
                    // $c->itemcode == '100011' ? dd($c) : null;

                    if ($nav == 0 && $appQty == 0 && $variance == 0) {
                        return null;
                    } else {
                        return $c;
                    }
                })->filter()->values();

                $varExist = false;
            }
        }

        $data['data'] = $query;
        $data['exist'] = $varExist;
        return $data;
    }

    public function generateVariance()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $data = $this->varianceReport();
        // $pdf = TabzPDF::loadView('reports.variance_report', ['data' => $data]);
        // return $pdf->setPaper('legal', 'landscape')->download('Variance Report.pdf');
        session(['data' => $data]);
        return Excel::download(new VarianceReport, 'Variance Report.xlsx');
    }

    public function varianceReport()
    {
        $company = request()->company;
        $section = request()->section;
        $vendors = base64_decode(request()->vendors);
        $category = request()->category;
        $date = base64_decode(request()->date);
        // $date = Carbon::parse(base64_decode(request()->date))->toDateString();
        $date2 = Carbon::parse(base64_decode(request()->date2))->toDateString();
        $date =  explode(',', $date);
        $bu = request()->bu;
        $dept = request()->dept;
        $runDate = Carbon::parse(now())->toFormattedDateString();
        $runTime =  Carbon::parse(now())->format('h:i A');
        // dd(request()->all());

        $forCheck = TblVarianceReport::where([
            ['company', $company],
            ['business_unit', $bu],
            ['department', $dept],
            ['adv_start_date', $date[0]],
            ['adv_end_date', $date[1]],
            ['actual_start_date', $date[2]],
            ['actual_end_date', $date[3]],
            ['nav_date', $date2],
        ]);

        if ($bu != "PLAZA MARCELA" && $bu != "ALTA CITTA" && $bu != "DISTRIBUTION") {
            $forCheck = $forCheck->where('section', $section);
        }

        // dd($forCheck->exists());
        if ($forCheck->exists() == false) {
            DB::transaction(function () use ($company, $bu, $dept, $section, $date, $date2) {
                // dd($section != "null" ? $section : null);
                $head = [
                    'user_id' => auth()->user()->id,
                    'company' => $company,
                    'business_unit' => $bu,
                    'department' => $dept,
                    'section' => $section != "null" ? $section : null,
                    'adv_start_date' => $date[0],
                    'adv_end_date' => $date[1],
                    'actual_start_date' => $date[2],
                    'actual_end_date' => $date[3],
                    'nav_date' => $date2
                ];
                // dd($head);
                $log = TblVarianceReport::create($head);
                collect(request()->data)->map(function ($trans) use ($log) {
                    // dd($trans);

                    $values = [
                        "itemcode" => $trans['itemcode'],
                        "variant_code" => $trans['variant_code'],
                        "description" => $trans['extended_desc'],
                        // "barcode" => $trans['barcode'],
                        "uom" => $trans['uom'],
                        // "lot_number" => $trans['lot_number'],
                        // "expiry" => $trans['expiry'],
                        "cost_vat" => null,
                        "cost_no_vat" => $trans['cost_no_vat'],
                        "amt" => $trans['amt'],
                        "app_qty" => (float) $trans['conversion_qty'],
                        "nav_qty" => (float) $trans['nav_qty'],
                        "variance" => (float) $trans['variance'],
                        "batch_id" => $log->id
                    ];

                    // dd($trans);
                    TblVarianceReportLine::create($values);
                });
            });
        }
        // dd(request()->data);
        $header = array(
            'company'       => $company,
            'business_unit' => $bu,
            'department'    => $dept,
            'section'       => $section,
            'vendors'       => $vendors,
            'category'      => $category,
            'date'          => $date,
            'navDate'       => $date2,
            'user'          => auth()->user()->name,
            'userBu'        => auth()->user()->business_unit,
            'userDept'      => auth()->user()->department,
            'userSection'   => auth()->user()->section,
            'user_position' => auth()->user()->position,
            'runDate'       => $runDate,
            'runTime'       => $runTime,
            'report'        => request()->type,
            'data'          => request()->data
        );

        // dd($header);

        return $header;
    }

    public function varianceReportData()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $company = auth()->user()->company;
        $section = request()->section;
        $vendors = base64_decode(request()->vendors);
        $category = request()->category;
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        $dateAsOf = Carbon::parse(base64_decode(request()->date))->endOfDay()->toDateTimeString();
        $printDate = Carbon::parse(base64_decode(request()->date))->toFormattedDateString();
        $bu = request()->bu;
        $dept = request()->dept;
        $runDate = Carbon::parse(now())->toFormattedDateString();
        $runTime =  Carbon::parse(now())->format('h:i A');

        $result = TblConsolidateAdvActual::selectRaw('
        itemcode, 
        barcode,
        description as extended_desc,
        uom, 
        totalQty as conversion_qty
        ')
            ->join('tbl_consolidate_adv_actual_line', 'tbl_consolidate_adv_actual_line.batch_id', '=', 'tbl_consolidate_adv_actual.id');

        if ($bu != 'null') {
            $result->WHERE('business_unit',  'LIKE', "%$bu%");
        }
        if ($dept != 'null') {
            $result->WHERE('department', 'LIKE', "%$dept%");
        }
        if ($section != 'null') {
            $result->WHERE('section', 'LIKE', "%$section%");
        }
        if ($vendors) {
            $vendors = explode(' , ', $vendors);
            $vendors = str_replace("'", "", $vendors);
            $result = $result->whereIn('vendor_name', $vendors);
            $vendors = implode(", ", $vendors);
        }

        if ($category) {
            $category = explode(" , ", $category);
            $category = str_replace("'", "", $category);
            $result = $result->whereIn('group', $category);
            $category = implode(", ", $category);
        }

        if (!request()->has('type')) {
            $report = 'Variance';
            $result = $result->groupBy('barcode')->orderBy('itemcode')->cursor()->groupBy(['vendor_name', 'group']);
            $arr = [];

            foreach ($result as $vendor_name => $categories) {
                foreach ($categories as $category => $items) {
                    $arr[$vendor_name][$category] = $items->map(function ($c) use ($bu, $dept, $section, $dateAsOf) {
                        $query = TblNavCountdata::selectRaw("SUM(qty) as nav_qty")->where([
                            ['itemcode', $c->itemcode],
                            ['business_unit', $bu],
                            ['department', $dept],
                            ['section', $section],
                            ['date', $dateAsOf]
                        ])->groupBy('itemcode');
                        // dd($query->get());

                        if ($query->exists()) {
                            $c->nav_qty = $query->first()->nav_qty;
                        } else {
                            $c->nav_qty = '-';
                        }

                        $y = TblUnposted::selectRaw("SUM(qty) as unposted")->where([
                            ['itemcode', $c->itemcode],
                            ['business_unit', $bu],
                            ['department', $dept],
                            ['section', $section],
                            ['date', $dateAsOf]
                        ])->groupBy('itemcode');

                        if ($y->exists()) {
                            $c->unposted = $y->first()->unposted;
                        } else {
                            $c->unposted = '-';
                        }

                        return $c;
                    });
                }
            }

            $header = array(
                'company'       => $company,
                'business_unit' => $bu,
                'department'    => $dept,
                'section'       => $section,
                'vendors'       => $vendors,
                'category'      => $category,
                'date'          => $printDate,
                'user'          => auth()->user()->name,
                'userBu'        =>  auth()->user()->business_unit,
                'userDept'      => auth()->user()->department,
                'userSection'   => auth()->user()->section,
                'user_position' => auth()->user()->position,
                'runDate'       => $runDate,
                'runTime'       => $runTime,
                'report'        => $report,
                'data'          => $arr
            );
        } else {
            $report = 'Summary';
            $result = $result->groupBy('barcode')->orderBy('itemcode')->get();
            $arr = [];

            // foreach ($result as $items => $c) {

            $result = $result->map(function ($c) use ($bu, $dept, $section) {
                $query = TblNavCountdata::selectRaw("SUM(qty) as nav_qty")->where([
                    ['itemcode', $c->itemcode],
                    ['business_unit', $bu],
                    ['department', $dept],
                    ['section', $section]
                ])->groupBy('itemcode');

                // if ($query->exists()) {
                //     $c->nav_qty = $query->first()->nav_qty;
                // } else {
                //     $c->nav_qty = '-';
                // }
                $query->exists() ? $c->nav_qty = $query->first()->nav_qty : $c->nav_qty = '-';

                $y = TblUnposted::selectRaw("SUM(qty) as unposted")->where([
                    ['itemcode', $c->itemcode],
                    ['business_unit', $bu],
                    ['department', $dept],
                    ['section', $section]
                ])->groupBy('itemcode');

                // if ($y->exists()) {
                //     $c->unposted = $y->first()->unposted;
                // } else {
                //     $c->unposted = '-';
                // }

                $y->exists() ? $c->unposted = $query->first()->unposted : $c->unposted = '-';

                return $c;
            });

            // });
            // }

            $header = array(
                'company'       => $company,
                'business_unit' => $bu,
                'department'    => $dept,
                'section'       => $section,
                'vendors'       => $vendors,
                'category'      => $category,
                'date'          => $printDate,
                'user'          => auth()->user()->name,
                'userBu'        =>  auth()->user()->business_unit,
                'userDept'      => auth()->user()->department,
                'userSection'   => auth()->user()->section,
                'user_position' => auth()->user()->position,
                'runDate'       => $runDate,
                'runTime'       => $runTime,
                'report'        => $report,
                'data'          => $result
            );
        }

        // dd($header);
        return $header;
    }

    public function getDate()
    {
        $title = request()->title;
        $bu = request()->bu;

        $query = DB::table($title)
            ->selectRaw("$title.*, users.name")
            ->where([
                ["$title.business_unit", request()->bu],
                ["$title.department", request()->dept],
                // ["user_id", auth()->user()->id] //IF IT IS RESTRICTED TO PER USER
            ]);

        if (request()->date && $title != "tbl_consolidate_adv_actual") {
            $query = $query->where("$title.date", Date::parse(request()->date));
        }

        $query = $query->join('users', 'users.id', '=', "$title.user_id");

        if ($bu != "PLAZA MARCELA" && $bu != "ALTA CITTA") {
            $query = $query->where("$title.section", request()->section);
        }

        return $query->get();
    }

    public function summary()
    {
        // dd(request()->all());
        $company = request()->company;
        $type = request()->type;
        $section = request()->section;
        $date = base64_decode(request()->date);
        $date =  explode(',', $date);
        $date2 = Carbon::parse(base64_decode(request()->date2))->toDateString();
        $bu = request()->bu;
        $dept = request()->dept;
        $runDate = Carbon::parse(now())->toFormattedDateString();
        $runTime =  Carbon::parse(now())->format('h:i A');

        $forCheck = TblVarianceReport::where([
            ['company', $company],
            ['business_unit', $bu],
            ['department', $dept],
            ['adv_start_date', $date[0]],
            ['adv_end_date', $date[1]],
            ['actual_start_date', $date[2]],
            ['actual_end_date', $date[3]],
            ['nav_date', $date2],
        ]);
        if ($bu != "PLAZA MARCELA" && $bu != "ALTA CITTA" && $bu != "DISTRIBUTION") {
            $forCheck = $forCheck->where('section', $section);
        }

        // dd($forCheck->get()); 
        if ($type == 'Variance Report Summary Unique Itemcode') {

            if ($forCheck->exists()) {
                $exist = $forCheck->first();
                $query = TblVarianceReportLine::SelectRaw('batch_id,itemcode,(CASE WHEN COUNT(CASE WHEN variance != 0 THEN 1 END) = 0 THEN SUM(variance) ELSE 1 END) as grandtotal')
                    ->where('batch_id', $exist->id)
                    ->groupby('itemcode');
                // ->limit(100);
                $getresult =   $query->get();
            }

            $getresult->map(function ($row) {
                // dd($row);
                $customerList = $row->customerList = TblVarianceReportLine::selectRaw("itemcode,variant_code,description,uom,  app_qty, nav_qty, variance")
                    ->where([['itemcode', $row->itemcode], ['batch_id', $row->batch_id]])
                    // ->having('variance', '!=', 0)
                    ->orderby('variance')
                    ->get();

                return $row;
            });
        } else {

            if ($forCheck->exists()) {
                $exist = $forCheck->first();
                $query = TblVarianceReportLine::SelectRaw("itemcode,variant_code,description,uom, cost_no_vat, app_qty * cost_no_vat as appamount , nav_qty * cost_no_vat as navamount , app_qty, nav_qty, variance")
                    ->where('batch_id', $exist->id);
                //  ->orderby('')
                // ->limit(100);
                $getresult =   $query->get();
            }
        }


        $query2['data'] = $getresult->toArray();
        // dd($query2);
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $data = array(

            'company'       => $company,
            'business_unit' => $bu,
            'department'    => $dept,
            'section'       => $section,
            'date'          => $date,
            'date2'         => $date2,
            'type'          => $type,
            'user'          => auth()->user()->name,
            'user_position' => auth()->user()->position,
            'runDate'       => $runDate,
            'runTime'       => $runTime,
            'data'          => $query2
        );

        // dd($data);
        // session(['data' => $data]);
        return Excel::download(new VarianceSummary($data), 'Variance Report Summary.xlsx');
    }
}
