<?php

namespace App\Http\Controllers;

use \PDF;
use Carbon\Carbon;
use App\Models\TblUnposted;
use Illuminate\Http\Request;
use App\Models\TblAppCountdata;
use App\Models\TblNavCountdata;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class NavSysController extends Controller
{
    public function NetNavSys()
    {
        // dd(request()->all());
        $reportType = request()->type;
        $company = auth()->user()->company;
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateString();
        $printDate = Carbon::parse(base64_decode(request()->date))->toFormattedDateString();
        $runDate = Carbon::parse(now())->toFormattedDateString();
        $runTime =  Carbon::parse(now())->format('h:i A');
        // dd($reportType, $date);

        if ($reportType != 'NotInCount') {
            // $pdf = PDF::loadView('reports.net_nav_sys_report', ['data' => $this->varianceReportData()]);
            // return $pdf->setPaper('legal', 'landscape')->download('Nav Sys Var Report.pdf');

            $key = implode('-', [$bu, $dept, $section, $date, 'NetNavSys']);
            $data = Cache::get($key);
            // dd($data);


            $data = collect($data['navData']['data'])->map(function ($trans) use ($reportType) {
                // dd($trans->nav_qty);



                $sum = $trans->nav_qty + $trans->unposted;
                if ($reportType == 'NegativeNetNavSys') {
                    if ($sum < 0) return $trans;
                    return [];
                }

                if ($reportType == 'PositiveNetNavSys') {
                    if ($sum > 0) return $trans;
                    return [];
                }

                // dd($sum);
                return $trans;
            })->filter(function ($trans) {
                return !empty($trans);
            });


            // dd(1, $data);
            // dd(1);
            $header = array(
                'company'       => $company,
                'business_unit' => $bu,
                'department'    => $dept,
                'section'       => $section,
                'date'          => $printDate,
                'user'          => auth()->user()->name,
                'userBu'        => auth()->user()->business_unit,
                'userDept'      => auth()->user()->department,
                'userSection'   => auth()->user()->section,
                'user_position' => auth()->user()->position,
                'runDate'       => $runDate,
                'runTime'       => $runTime,
                'report'        => 'All',
                'reportType'    => $reportType,
                'data'          => $data
            );

            $pdf = PDF::loadView('reports.net_nav_sys_report', ['data' => $header]);
            return $pdf->setPaper('legal', 'landscape')->download('Nav Sys Var Report.pdf');
        } else {
            $key = implode('-', [$bu, $dept, $section, $date, 'NotInCount']);
            $data = Cache::get($key);
            // dd($data);

            $pdf = PDF::loadView('reports.net_nav_sys_report', ['data' => $data]);
            return $pdf->setPaper('legal', 'landscape')->download('Not In Count Report.pdf');
        }
    }

    public function varianceReportData()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        // $result = TblAppCountdata::selectRaw('
        //         tbl_app_countdata.itemcode, 
        //         tbl_app_countdata.barcode, 
        //         tbl_item_masterfile.extended_desc,
        //         tbl_nav_countdata.uom, 
        //         vendor_name,
        //         tbl_item_masterfile.group
        // ')
        //     ->join('tbl_item_masterfile', 'tbl_item_masterfile.barcode', 'tbl_app_countdata.barcode')
        //     ->join('tbl_nav_countdata', 'tbl_nav_countdata.itemcode', 'tbl_app_countdata.itemcode')
        //     ->whereBetween('datetime_saved', [$date, $dateAsOf])
        //     ->orderBy('itemcode');

        // dd(request()->all());



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
        $reportType = request()->type;

        $key = implode('-', [$bu, $dept, $section, $date, $reportType]);
        return Cache::remember($key, now()->addMinutes(15), function () use (
            $company,
            $section,
            $vendors,
            $category,
            $date,
            $dateAsOf,
            $printDate,
            $bu,
            $dept,
            $runDate,
            $runTime,
            $reportType
        ) {
            $result = TblNavCountdata::selectRaw('
            tbl_nav_countdata.itemcode, 
            tbl_nav_countdata.description as extended_desc,
            tbl_nav_countdata.uom, 
            vendor_name,
            tbl_item_masterfile.group
            ')
                ->join('tbl_item_masterfile', 'tbl_item_masterfile.item_code', 'tbl_nav_countdata.itemcode')
                ->where('date', $date)
                ->orderBy('itemcode');

            if ($bu != 'null') {
                $result->WHERE('tbl_nav_countdata.business_unit',  'LIKE', "%$bu%");
            }
            if ($dept != 'null') {
                $result->WHERE('tbl_nav_countdata.department', 'LIKE', "%$dept%");
            }
            if ($section != 'null') {
                $result->WHERE('tbl_nav_countdata.section', 'LIKE', "%$section%");
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

            // dd($result->get());

            // $result = $result->groupBy('barcode')->orderBy('itemcode')->get()->groupBy(['vendor_name', 'group']);
            // dd($result->groupBy('itemcode')->orderBy('itemcode')->cursor()->groupBy(['vendor_name', 'group']));
            $result = $vendors != null ? $result->groupBy('itemcode')->orderBy('itemcode')->cursor()->groupBy(['vendor_name', 'group'])
                : $result->groupBy('itemcode')->orderBy('itemcode')->limit(2000)->cursor();

            if ($vendors) {
                $arr = [];

                foreach ($result as $vendor_name => $categories) {
                    foreach ($categories as $category => $items) {
                        $arr[$vendor_name][$category] = $items->map(function ($c) use ($bu, $dept, $section, $reportType) {
                            $query = TblNavCountdata::selectRaw("SUM(qty) as nav_qty")->where([
                                ['itemcode', $c->itemcode],
                                ['business_unit', $bu],
                                ['department', $dept],
                                ['section', $section]
                            ])->groupBy('itemcode');
                            // dd($query->get());

                            $queryResult = $query->exists();
                            $navQty = $queryResult ? $query->first()->nav_qty : '-';
                            $c->nav_qty = $navQty;

                            $y = TblUnposted::selectRaw("SUM(qty) as unposted")->where([
                                ['itemcode', $c->itemcode],
                                ['business_unit', $bu],
                                ['department', $dept],
                                ['section', $section]
                            ])->groupBy('itemcode');

                            $yResult = $y->exists();
                            $unposted = $yResult ? $y->first()->unposted : '-';
                            $c->unposted = $unposted;

                            if ($reportType == 'Variance') {
                                $temp1 = $navQty === '-' ? 0 : $navQty;
                                $temp2 = $unposted === '-' ? 0 : $unposted;

                                $res = $temp1 + $temp2;

                                if ($res < 0) {
                                    return $c;
                                }
                                return [];
                            }
                            return $c;
                        })->filter(function ($ar) {
                            return $ar;
                        });
                    }
                }

                if ($reportType == 'NetNavSys') {
                    $arr = collect($arr)->filter(function ($a) {
                        foreach ($a as $key => $b) {
                            // dd($b);
                            $res[] = !$b->isEmpty();
                        };

                        return in_array(true, $res);
                    })->map(function ($b) {
                        // dump($b);
                        return collect($b)->filter(function ($c) {
                            return !$c->isEmpty();
                        });
                    })->all();
                }
                // dd($arr);
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
                    'report'        => $reportType,
                    'reportType'    => $reportType,
                    'data'          => $arr
                );
            } else {
                $report = 'All';
                // $arr = [];

                // foreach ($result as $items => $c) {

                //     $query = TblNavCountdata::selectRaw("SUM(qty) as nav_qty")->where([
                //         ['itemcode', $c->itemcode],
                //         ['business_unit', $bu],
                //         ['department', $dept],
                //         ['section', $section]
                //     ])->groupBy('itemcode');

                //     if ($query->exists()) {
                //         $c->nav_qty = $query->first()->nav_qty;
                //     } else {
                //         $c->nav_qty = '-';
                //     }

                //     $y = TblUnposted::selectRaw("SUM(qty) as unposted")->where([
                //         ['itemcode', $c->itemcode],
                //         ['business_unit', $bu],
                //         ['department', $dept],
                //         ['section', $section]
                //     ])->groupBy('itemcode');

                //     if ($y->exists()) {
                //         $c->unposted = $y->first()->unposted;
                //     } else {
                //         $c->unposted = '-';
                //     }

                //     // return $c;
                //     // });
                // }
                $result = collect($result)->map(function ($c) use ($bu, $dept, $section, $reportType) {
                    // dd($c);
                    // $c = collect($c)->map(function ($item) use ($bu, $dept, $section) {
                    //     dd($item);
                    $x = TblNavCountdata::selectRaw("SUM(qty) as nav_qty")->where([
                        ['itemcode', $c->itemcode],
                        ['business_unit', $bu],
                        ['department', $dept],
                        ['section', $section]
                    ])->groupBy('itemcode');


                    $y = TblUnposted::selectRaw("SUM(qty) as unposted")->where([
                        ['itemcode', $c->itemcode],
                        ['business_unit', $bu],
                        ['department', $dept],
                        ['section', $section]
                    ])->groupBy('itemcode');

                    // if ($x->exists()) {
                    //     $c->nav_qty = $x->first()->nav_qty;
                    //     $c->cost_vat = $x->first()->cost_vat;
                    //     $c->cost_no_vat = $x->first()->cost_no_vat;
                    //     $c->amt = $x->first()->amt;
                    // } else {
                    //     $c->nav_qty = '-';
                    //     $c->cost_vat = '-';
                    //     $c->cost_no_vat = '-';
                    //     $c->amt = '-';
                    // }

                    // if ($y->exists()) {
                    //     $c->unposted = $y->first()->unposted;
                    // } else {
                    //     $c->unposted = '-';
                    // }
                    $queryResult = $x->exists();
                    $navQty = $queryResult ? $x->first()->nav_qty : '-';
                    $c->nav_qty = $navQty;

                    $yResult = $y->exists();
                    $unposted = $yResult ? $y->first()->unposted : '-';
                    $c->unposted = $unposted;

                    // dd($reportType);

                    if ($reportType == 'NegativeNetNavSys') {
                        $temp1 = $navQty === '-' ? 0 : $navQty;
                        $temp2 = $unposted === '-' ? 0 : $unposted;

                        $res = $temp1 + $temp2;

                        if ($res < 0) {
                            return $c;
                        }
                        return [];
                    }

                    return $c;
                })->filter(function ($ar) {
                    return $ar;
                });

                // $arr = $result->filter(function ($a) {
                //     dd($a);
                //     foreach ($a as $key => $b) {
                //         $res[] = !$b->isEmpty();
                //     };

                //     return in_array(true, $res);
                // })->map(function ($b) {
                //     // dump($b);
                //     return collect($b)->filter(function ($c) {
                //         return !$c->isEmpty();
                //     });
                // })->all();

                // dd($result);

                $header = array(
                    'company'       => $company,
                    'business_unit' => $bu,
                    'department'    => $dept,
                    'section'       => $section,
                    'vendors'       => $vendors,
                    'category'      => $category,
                    'date'          => $printDate,
                    'user'          => auth()->user()->name,
                    'userBu'        => auth()->user()->business_unit,
                    'userDept'      => auth()->user()->department,
                    'userSection'   => auth()->user()->section,
                    'user_position' => auth()->user()->position,
                    'runDate'       => $runDate,
                    'runTime'       => $runTime,
                    'report'        => $report,
                    'reportType'    => $reportType,
                    'data'          => $result
                );
            }

            // dd($header);
            return $header;
        });
    }

    public function NotInCountData()
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
        $reportType = request()->type;

        // $result = TblNavCountdata::selectRaw('
        //         tbl_nav_countdata.itemcode, 
        //         tbl_item_masterfile.extended_desc,
        //         tbl_nav_countdata.uom, 
        //         vendor_name,
        //         tbl_item_masterfile.group
        // ')
        //     ->join('tbl_item_masterfile', 'tbl_item_masterfile.item_code', 'tbl_nav_countdata.itemcode')
        //     ->where('date', $date)
        //     ->orderBy('itemcode');

        // $result = TblNavCountdata::doesnthave('NotInCount')
        //     ->selectRaw('
        //         itemcode, 
        //         description as extended_desc,
        //         uom
        //     ');
        $result = TblNavCountdata::selectRaw('
                itemcode, 
                description as extended_desc,
                uom
            ');

        // dd($result->limit(50)->orderBy('itemcode')->get());

        if ($bu != 'null')
            $result->WHERE('business_unit',  'LIKE', "%$bu%");

        if ($dept != 'null')
            $result->WHERE('department', 'LIKE', "%$dept%");

        if ($section != 'null')
            $result->WHERE('section', 'LIKE', "%$section%");


        $result =  $result->orderBy('itemcode')
            ->where('date', $date)->whereNotIn('itemcode', function ($query) use ($bu, $dept, $section, $date, $dateAsOf) {
                $query->select(DB::raw('itemcode'))
                    ->from('tbl_app_countdata')
                    ->where('tbl_app_countdata.itemcode', '=', 'itemcode');

                if ($bu != 'null')
                    $query->WHERE('business_unit',  'LIKE', "%$bu%");

                if ($dept != 'null')
                    $query->WHERE('department', 'LIKE', "%$dept%");

                if ($section != 'null')
                    $query->WHERE('section', 'LIKE', "%$section%");


                $query->whereBetween('datetime_saved', [$date, $dateAsOf]);
            });

        // dd($result->get());

        // dd($result->limit(5)->get());

        $result = $vendors != null ? $result->get()
            : $result->limit(1500)->get();

        $report = 'All';
        $result = collect($result)->map(function ($c) use ($bu, $dept, $section, $reportType) {
            $x = TblNavCountdata::selectRaw("SUM(qty) as nav_qty")->where([
                ['itemcode', $c->itemcode],
                ['business_unit', $bu],
                ['department', $dept],
                ['section', $section]
            ])->groupBy('itemcode');

            // dd($x->get());


            $y = TblUnposted::selectRaw("SUM(qty) as unposted")->where([
                ['itemcode', $c->itemcode],
                ['business_unit', $bu],
                ['department', $dept],
                ['section', $section]
            ])->groupBy('itemcode');

            $queryResult = $x->exists();
            $navQty = $queryResult ? $x->first()->nav_qty : '-';
            $c->nav_qty = $navQty;

            $yResult = $y->exists();
            $unposted = $yResult ? $y->first()->unposted : '-';
            $c->unposted = $unposted;

            if ($reportType == 'Variance') {
                $temp1 = $navQty === '-' ? 0 : $navQty;
                $temp2 = $unposted === '-' ? 0 : $unposted;

                $res = $temp1 + $temp2;

                if ($res < 0) {
                    return $c;
                }
                return [];
            }

            return $c;
        })->filter(function ($ar) {

            return $ar;
        });

        // $arr = $result->filter(function ($a) {
        //     dd($a);
        //     foreach ($a as $key => $b) {
        //         $res[] = !$b->isEmpty();
        //     };

        //     return in_array(true, $res);
        // })->map(function ($b) {
        //     // dump($b);
        //     return collect($b)->filter(function ($c) {
        //         return !$c->isEmpty();
        //     });
        // })->all();

        // dd($result);

        $header = array(
            'company'       => $company,
            'business_unit' => $bu,
            'department'    => $dept,
            'section'       => $section,
            'vendors'       => $vendors,
            'category'      => $category,
            'date'          => $printDate,
            'user'          => auth()->user()->name,
            'userBu'        => auth()->user()->business_unit,
            'userDept'      => auth()->user()->department,
            'userSection'   => auth()->user()->section,
            'user_position' => auth()->user()->position,
            'runDate'       => $runDate,
            'runTime'       => $runTime,
            'report'        => $report,
            'reportType'    => $reportType,
            'data'          => $result
        );

        // dd($header);
        return $header;
    }

    public function getResults()
    {
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $user = auth()->user()->id;
        $date = Carbon::parse(base64_decode(request()->date))->toDateString();
        $nav_id = request()->nav_id;
        $type = request()->type;
        // dd(request()->all());
        // $result = TblAppCountdata::selectRaw('
        // tbl_app_countdata.itemcode, 
        // tbl_app_countdata.barcode,
        // tbl_nav_countdata.description as extended_desc,
        // tbl_app_countdata.uom
        // ')
        //     ->JOIN('tbl_nav_countdata', 'tbl_nav_countdata.itemcode', 'tbl_app_countdata.itemcode')
        //     ->whereBetween('datetime_saved', [$date, $dateAsOf])->orderBy('itemcode');

        // $key = implode('-', [$section, $bu, $dept, $date, 'r']);
        $key = implode('-', [$bu, $dept, $section, $date, $type, $user]);
        // dd(Cache::get($key), $key);
        // dd($key);

        return Cache::remember($key, now()->addMinutes(120), function () use ($bu, $dept, $section, $nav_id, $date) {
            $result = TblNavCountdata::selectRaw('
            itemcode, 
            variant_code,
            description as extended_desc,
            uom
            ')
                ->where('batch_id', $nav_id)->orderBy('itemcode');

            $x = $result->groupByRaw('itemcode')->limit(250)->cursor();
            // $x = $result->groupByRaw('itemcode')->cursor();
            $query = $x->map(function ($c) use ($date, $nav_id) {

                // dd($c->itemcode);
                $x = TblNavCountdata::selectRaw("cost_vat, cost_no_vat, amt, SUM(qty) as nav_qty")->where([
                    ['itemcode', $c->itemcode],
                    ['batch_id', $nav_id]
                ]);

                // dd($x->get());
                // $y = TblUnposted::selectRaw("SUM(qty) as unposted")->where([
                //     ['itemcode', $c->itemcode],
                //     ['date', $date]
                // ]);

                // if ($x->exists()) {
                //     $c->nav_qty = $x->first()->nav_qty;
                //     $c->cost_vat = $x->first()->cost_vat;
                //     $c->cost_no_vat = $x->first()->cost_no_vat;
                //     $c->amt = $x->first()->amt;
                // } else {
                //     $c->nav_qty = '-';
                //     $c->cost_vat = '-';
                //     $c->cost_no_vat = '-';
                //     $c->amt = '-';
                // }
                // ? 0 :
                $nav =  $x->exists() ? (int) $x->first()->nav_qty : 0;
                $c->nav_qty = $nav;
                // $unposted = 0;
                // $c->unposted = $unposted;

                // $x->exists() ? $nav = $x->first()->nav_qty : $nav = '-';
                // $c->nav_qty = (int) $nav;
                // $unposted = $y->first()->unposted == null ? 0 : $y->first()->unposted;
                // $c->unposted = $unposted;

                // if ($y->exists()) {
                //     $c->unposted = $y->first()->unposted;
                // } else {
                //     $c->unposted = '-';
                // }

                return $c;
            });
            // dd($query);

            // dd($result->whereBetween('date', [$date, $dateAsOf])
            //     ->groupByRaw('tbl_nav_countdata.itemcode')
            //     ->orderBy('itemcode')->limit(5)->get());
            $data['data'] = $query->all();
            // $test = $result->groupByRaw('itemcode')->simplePaginate(25)->through(function ($yes) use ($bu, $dept, $section, $date) {
            //     $x = TblNavCountdata::selectRaw("cost_vat, cost_no_vat, amt, SUM(qty) as nav_qty")->where([
            //         ['itemcode', $yes->itemcode],
            //         ['business_unit', $bu],
            //         ['department', $dept],
            //         ['section', $section],
            //         ['date', $date]
            //     ]);

            //     $y = TblUnposted::selectRaw("SUM(qty) as unposted")->where([
            //         ['itemcode', $yes->itemcode],
            //         ['business_unit', $bu],
            //         ['department', $dept],
            //         ['section', $section],
            //         ['date', $date]
            //     ]);

            //     $nav = $x->first()->nav_qty == null ? 0 : $x->first()->nav_qty;
            //     $yes->nav_qty = $nav;
            //     $unposted = $y->first()->unposted == null ? 0 : $y->first()->unposted;
            //     $yes->unposted = $unposted;

            //     return $yes;
            // });

            // $merge = collect(['navData' => $data]);
            // $merge = $merge->merge($test);
            return $data;
        });




        // $result = TblNavCountdata::selectRaw("
        // tbl_nav_countdata.itemcode, 
        // tbl_item_masterfile.extended_desc,
        // tbl_nav_countdata.uom, 
        // vendor_name,
        // tbl_item_masterfile.group
        // ")
        //     ->JOIN('tbl_item_masterfile', 'tbl_item_masterfile.item_code', 'tbl_nav_countdata.itemcode')
        //     ->where('date', $date)->orderBy('itemcode')
        //     ->limit(1000);

        // dd($result->limit(10)->get());



    }

    public function getNotinCount()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $company = auth()->user()->company;
        $section = request()->section;
        $vendors = base64_decode(request()->vendors);
        $category = request()->category;
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        $date2 = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateString();
        $dateAsOf = Carbon::parse(base64_decode(request()->date))->endOfDay()->toDateTimeString();
        $printDate = Carbon::parse(base64_decode(request()->date))->toFormattedDateString();
        $bu = request()->bu;
        $dept = request()->dept;
        $runDate = Carbon::parse(now())->toFormattedDateString();
        $runTime =  Carbon::parse(now())->format('h:i A');
        $reportType = request()->type;

        // $result = TblNavCountdata::selectRaw('
        //         tbl_nav_countdata.itemcode, 
        //         tbl_item_masterfile.extended_desc,
        //         tbl_nav_countdata.uom, 
        //         vendor_name,
        //         tbl_item_masterfile.group
        // ')
        //     ->join('tbl_item_masterfile', 'tbl_item_masterfile.item_code', 'tbl_nav_countdata.itemcode')
        //     ->where('date', $date)
        //     ->orderBy('itemcode');

        // $result = TblNavCountdata::doesnthave('NotInCount')
        //     ->selectRaw('
        //         itemcode, 
        //         description as extended_desc,
        //         uom
        //     ');
        $key = implode('-', [$bu, $dept, $section, $date2, 'NotInCount']);
        // dd($key);

        $result = TblNavCountdata::selectRaw('
                itemcode, 
                description as extended_desc,
                uom
            ')->where('date', $date2);

        // dd($result->limit(50)->orderBy('itemcode')->get());

        if ($bu != 'null')
            $result->WHERE('business_unit',  'LIKE', "%$bu%");

        if ($dept != 'null')
            $result->WHERE('department', 'LIKE', "%$dept%");

        if ($section != 'null')
            $result->WHERE('section', 'LIKE', "%$section%");


        $result =  $result->orderBy('itemcode')
            ->whereNotIn('itemcode', function ($query) use ($bu, $dept, $section, $date, $dateAsOf) {
                $query->select(DB::raw('itemcode'))
                    ->from('tbl_app_countdata')
                    ->where('tbl_app_countdata.itemcode', '=', 'itemcode')
                    ->whereBetween('datetime_saved', [$date, $dateAsOf]);

                if ($bu != 'null')
                    $query->WHERE('business_unit',  'LIKE', "%$bu%");

                if ($dept != 'null')
                    $query->WHERE('department', 'LIKE', "%$dept%");

                if ($section != 'null')
                    $query->WHERE('section', 'LIKE', "%$section%");
            });

        // dd($result->get());

        // dd($result->limit(5)->get());

        $result = $vendors != null ? $result->get()
            : $result->limit(1500)->get()->all();

        $report = 'All';
        $result = collect($result)->map(function ($c) use ($bu, $dept, $section, $reportType, $date2) {
            // dd($c->itemcode);
            $x = TblNavCountdata::selectRaw("SUM(qty) as nav_qty")->where([
                ['itemcode', $c->itemcode],
                ['business_unit', $bu],
                ['department', $dept],
                ['section', $section],
                ['date', $date2]
            ]);

            // dd($x->get());


            $y = TblUnposted::selectRaw("itemcode, SUM(qty) as unposted")->where([
                ['itemcode', $c->itemcode],
                ['business_unit', $bu],
                ['department', $dept],
                ['section', $section],
                ['date', $date2]
            ]);
            // dd($y);

            $queryResult = $x->exists();
            $navQty = $queryResult ? $x->first()->nav_qty : '-';
            $c->nav_qty = $navQty;

            // $yResult = $y->exists();
            $unposted = $y->first()->unposted == null ? 0 : $y->first()->unposted;
            $c->unposted = $unposted;

            if ($reportType == 'Variance') {
                $temp1 = $navQty === '-' ? 0 : $navQty;
                $temp2 = $unposted === '-' ? 0 : $unposted;

                $res = $temp1 + $temp2;

                if ($res < 0) {
                    return $c;
                }
                return [];
            }

            return $c;
        })->filter(function ($ar) {
            // dump($ar);
            return $ar;
        });
        // dd(1);

        // $arr = $result->filter(function ($a) {
        //     dd($a);
        //     foreach ($a as $key => $b) {
        //         $res[] = !$b->isEmpty();
        //     };

        //     return in_array(true, $res);
        // })->map(function ($b) {
        //     // dump($b);
        //     return collect($b)->filter(function ($c) {
        //         return !$c->isEmpty();
        //     });
        // })->all();

        // dd($result);
        return Cache::remember($key, now()->addMinutes(15), function () use (
            $date,
            $date2,
            $dateAsOf,
            $bu,
            $dept,
            $section,
            $vendors,
            $category,
            $reportType,
            $company,
            $printDate,
            $runDate,
            $runTime,
            $report,
            $result
        ) {
            $header = array(
                'company'       => $company,
                'business_unit' => $bu,
                'department'    => $dept,
                'section'       => $section,
                'vendors'       => $vendors,
                'category'      => $category,
                'date'          => $printDate,
                'user'          => auth()->user()->name,
                'userBu'        => auth()->user()->business_unit,
                'userDept'      => auth()->user()->department,
                'userSection'   => auth()->user()->section,
                'user_position' => auth()->user()->position,
                'runDate'       => $runDate,
                'runTime'       => $runTime,
                'report'        => $report,
                'reportType'    => $reportType,
                'data'          => $result
            );
            return $header;
        });
        $data['data'] = $result;
        return $data;
    }

    public function exportResult()
    {
        $export = json_decode(utf8_encode(base64_decode(request()->export)));
        $test = null;

        // dd(request()->all());
        $export = collect($export)->map(function ($trans) use ($test) {
            dd($trans);
            // $postingDate = date_format(date_create($trans->postingDate), "m/d/Y");
            // $docDate = date_format(date_create($trans->postingDate), "m/d/Y");

            // $trans->docNo = 'PC-10000101';
            // $trans->postingDate = $postingDate;
            // $trans->docDate = $docDate;
            // $trans->valueEntry = 'Direct Cost';
            // $trans->reasonCode = 'ADJ_PCOUNT';
            // $trans->itemDiv = '1';

            // $item = TblItemMasterfile::selectRaw('conversion_qty')->where([
            //     ['item_code', $trans->itemCode],
            //     ['uom', $trans->uom]
            // ]);
            // $item->exists() ? $trans->qtyPerUom = $item->first()->conversion_qty :  $trans->qtyPerUom = 1;
            // return $trans;
        });
    }
}
