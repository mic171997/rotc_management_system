<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\TblNavCount;
use App\Models\TblAppNfitem;
use App\Exports\ItemsNotFound;
use App\Exports\CountByBackend;
use App\Models\TblAppCountdata;
use App\Models\TblNavCountdata;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use App\Exports\PcountAppCountData;
use App\Exports\PcountDamageExport;
use App\Models\TblAdvanceCountNotFound;
use App\Models\TblAltaCittaCountdata;
use App\Models\TblAlturasCountdata;
use App\Models\TblAppCountdataSnackbar;
use App\Models\TblPharmaDcCountdata;
use App\Models\TblTalibonCountdata;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;
use App\Models\TblPlazaMarcelaCountdata;
use Illuminate\Support\Facades\File;
use PhpParser\Node\Stmt\TryCatch;

class PhysicalCountController extends Controller
{
    public function getResults()
    {
        $company = auth()->user()->company;
        $user = auth()->user()->id;
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $vendors = base64_decode(request()->vendors);
        $category = request()->category;
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        $dateAsOf = Carbon::parse(base64_decode(request()->date))->endOfDay()->toDateTimeString();
        $date2 = Carbon::parse(base64_decode(request()->date2))->endOfDay()->toDateTimeString();
        $countType = request()->countType;
        $printDate = Carbon::parse(base64_decode(request()->date))->toFormattedDateString();
        $runDate = Carbon::parse(now())->toFormattedDateString();
        $runTime =  Carbon::parse(now())->format('h:i A');

        $masterfile = "tbl_item_masterfile";
        $key = implode('-', [$bu, $dept, $section, $printDate, 'CountData', $user]);

        if ($bu == 'ASC: MAIN') {
            $table = $section != "SNACKBAR" ? (new TblAlturasCountdata())->getTable() : (new TblAppCountdataSnackbar())->getTable();
            $masterfile = $section == "SNACKBAR" ? "tbl_item_masterfile_snackbar" : "tbl_item_masterfile";
        } else if ($bu == 'PLAZA MARCELA') {
            $table = (new TblPlazaMarcelaCountdata())->getTable();
            $masterfile = "tbl_item_masterfile";
        } else if ($bu == 'ALTURAS TALIBON') {
            $table = (new TblTalibonCountdata)->getTable();
            $masterfile = "tbl_item_masterfile";
        } else if ($bu == 'ALTA CITTA') {
            $table = (new TblAltaCittaCountdata)->getTable();
            $masterfile = "tbl_item_masterfile_alta_citta";
        } else if ($bu == 'DISTRIBUTION') {
            $table = (new TblPharmaDcCountdata)->getTable();
            $masterfile = "tbl_item_masterfile_pdc";
        } else {
            $table = (new TblAppCountdata)->getTable();
            $masterfile = "tbl_item_masterfile";
        }
        // dd($table);
        if ($bu == 'DISTRIBUTION') {
            $selectRawValue = "
                $table.id,
                $table.itemcode, 
                $table.barcode, 
                $table.description, 
                $masterfile.extended_desc,
                $table.uom, 
                $table.lot_number, 
                $table.expiry, 
                (SELECT $masterfile.uom 
                FROM $masterfile 
                WHERE $masterfile.conversion_qty = 1 AND $masterfile.item_code = $table.itemcode
                LIMIT 1) as nav_uom ,
                SUM($table.qty) as qty,
                SUM($table.conversion_qty) as total_conv_qty,
                $table.rack_desc,
                $table.empno,
                datetime_scanned,
                datetime_exported,
                vendor_name, 
                $masterfile.group,
                $masterfile.variant_code,
                tbl_app_user.name AS app_user,
                tbl_app_user.position AS app_user_position,
                tbl_app_audit.name AS audit_user,
                tbl_app_audit.position AS audit_position,
                $table.location_id 
            ";
        } else {
            $selectRawValue = "
                $table.id,
                $table.itemcode, 
                $table.barcode, 
                $table.description, 
                $masterfile.extended_desc,
                $table.uom,
                (SELECT $masterfile.uom 
                FROM $masterfile 
                WHERE $masterfile.conversion_qty = 1  AND $masterfile.item_code = $table.itemcode
                LIMIT 1) as nav_uom ,
                SUM($table.qty) as qty,
                SUM($table.conversion_qty) as total_conv_qty,
                $table.rack_desc,
                $table.empno,
                datetime_scanned,
                datetime_exported,
                vendor_name, 
                $masterfile.group,
                $masterfile.variant_code,
                tbl_app_user.name AS app_user,
                tbl_app_user.position AS app_user_position,
                tbl_app_audit.name AS audit_user,
                tbl_app_audit.position AS audit_position,
                $table.location_id 
            ";
        }

        $query = DB::table($table)->selectRaw($selectRawValue)
            ->JOIN($masterfile, function ($join) use ($table, $masterfile) {
                $join->on("$table.uom", '=', "$masterfile.uom");
                $join->on("$table.itemcode", '=', "$masterfile.item_code");
                $join->on("$table.barcode", '=', "$masterfile.barcode");
            })
            ->JOIN('tbl_app_user', 'tbl_app_user.location_id', "$table.location_id")
            ->JOIN('tbl_app_audit', 'tbl_app_audit.location_id', "$table.location_id")
            ->leftJoin('tbl_nav_countdata', function ($use) use ($table) {
                $use->on('tbl_nav_countdata.itemcode', '=', "$table.itemcode")
                    ->on('tbl_nav_countdata.id', '=', DB::raw("(SELECT MAX(id) from tbl_nav_countdata where tbl_nav_countdata.itemcode = $table.itemcode)"));
            });

        $bu != 'null' ? $query->WHERE("$table.business_unit",  'LIKE', "$bu%") : null;
        $dept != 'null' ? $query->WHERE("$table.department", 'LIKE', "$dept%") : null;
        $section != 'null' ? $query->WHERE("$table.section", 'LIKE', "$section%") :  $query->WHERE("$table.section", 'LIKE', "%null%");

        // dd($date, $dateAsOf);

        if ($vendors) {
            $vendors = explode(' , ', $vendors);
            $vendors = str_replace("'", "", $vendors);
            $query = $query->whereIn('vendor_name', $vendors);
        }
        if ($category) {
            $category = explode(" , ", $category);
            $category = str_replace("'", "", $category);
            $query = $query->whereIn('group', $category);
        }

        if ($bu == 'DISTRIBUTION') {
            $query = $query
                ->groupBy('barcode')
                ->groupBy('location_id')
                ->groupBy('rack_desc')
                ->groupBy('lot_number')
                ->groupBy('expiry')
                ->whereBetween('datetime_exported', [$date, $dateAsOf])
                ->orderBy('itemcode');
        } else {
            $query = $query
                ->groupBy('barcode')
                ->groupBy('location_id')
                ->groupBy('rack_desc')
                ->whereBetween('datetime_exported', [$date, $dateAsOf])
                ->orderBy('itemcode');
        }

        // dd($query->get());

        if (request()->has('forExport')) {
            return Cache::remember($key, now()->addMinutes(180), function () use (
                $date,
                $dateAsOf,
                $bu,
                $dept,
                $section,
                $vendors,
                $category,
                $company,
                $printDate,
                $countType,
                $runDate,
                $runTime,
                $query
            ) {

                $data = $query->get()
                    // ->groupBy(['app_user', 'audit_user', 'vendor_name', 'group'])
                    ->groupBy(['rack_desc', 'app_user', 'audit_user', 'vendor_name', 'group'])
                    ->toArray();
                // dd($data);

                $query = array(
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
                    'data' => $data
                );
                return $query;
            });
        }
        // dd($query->get());
        return $query->paginate(10);
    }

    public function getNotFound()
    {
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        $dateAsOf = Carbon::parse(base64_decode(request()->date))->endOfDay()->toDateTimeString();
        $bu = request()->bu;

        // if ($bu == 'ASC: MAIN') {
        //     $table = 'tbl_app_nfitem_alturas';
        // } else {
        $table = 'tbl_app_nfitem';
        // }

        return DB::table($table)->where([
            ['business_unit', 'LIKE', request()->bu],
            ['department', 'LIKE', request()->dept],
            ['section', 'LIKE', request()->section]
        ])
            ->whereBetween('datetime_exported', [$date, $dateAsOf])->get();
    }

    public function generate()
    {
        // dd(request()->all());
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        $user = auth()->user()->id;
        $printDate = Carbon::parse(base64_decode(request()->date))->toFormattedDateString();
        $key = implode('-', [$bu, $dept, $section, $printDate, 'CountData', $user]);
        $data = Cache::get($key);

        // dd($data);

        // $export = json_decode(base64_decode(request()->export), true);
        // dd($export);
        // $export = collect($export)->all();

        // dd(collect($export['data'])->flatten());
        if ($bu == 'ASC: MAIN') {
            $table = 'tbl_app_countdata_alturas';
        } else if ($bu == 'PLAZA MARCELA') {
            $table = 'tbl_app_countdata_pm';
        } else if ($bu == 'ALTURAS TALIBON') {
            $table = 'tbl_app_countdata_talibon';
        } else {
            $table = 'tbl_app_countdata';
        }

        $data['data'] = collect($data['data'])->map(function ($trans)  use ($table) {
            $res = array_map(function ($x) use ($table) {
                return array_map(function ($y) use ($table) {
                    return array_map(function ($z)  use ($table) {
                        return array_map(function ($w) use ($table) {
                            $newArr = [];
                            foreach ($w as $index => $xyz) {
                                if ($index === 0) {
                                    $xyz = (array) $xyz;
                                    $res = DB::table($table)->where('itemcode', $xyz['itemcode'])->first();
                                    $xyz['user_signature'] = $res->user_signature;
                                    $xyz['audit_signature'] = $res->audit_signature;
                                    $newArr[] = $xyz;
                                } else {
                                    $newArr[] = (array) $xyz;
                                }
                            }
                            return $newArr;
                        }, $z);
                    }, $y);
                }, $x);
            }, $trans);

            return $res;
        })->all();

        $pdf = PDF::loadView('reports.pcount_app', ['data' => $data, 'report' => 'Actual Count (APP)']);
        return $pdf->setPaper('legal', 'landscape')->download('PCount From App.pdf');
    }

    public function generateAppDataExcel()
    {
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        $printDate = Carbon::parse(base64_decode(request()->date))->toFormattedDateString();
        $key = implode('-', [$bu, $dept, $section, $printDate, 'CountData', auth()->user()->id]);
        $data = Cache::get($key);
        // dd($data);
        session(['data' => $data, 'type' => 'ActualCount', 'bu' => $bu, 'section' => $section]);
        return Excel::download(new PcountAppCountData, 'invoices.xlsx');
    }

    public function olddata()
    {
        // $trans = request()->data;
        // $array = explode(',', $trans);
        // $data_res = [];

        // foreach ($array as $key => $id) {
        //     $result = TblAppCountdata::where('id', $id)->get();
        //     foreach ($result as $datahead) {
        //         // dd($result);

        //         $data_res[] = array(
        //             "id"  => $datahead->id,
        //             "itemcode"  => $datahead->itemcode,
        //             "barcode"  => $datahead->barcode,
        //             "description"  => $datahead->description,
        //             "uom"  => $datahead->uom,
        //             "qty"  => $datahead->qty,
        //             "business_unit"  => $datahead->business_unit,
        //             "department"  => $datahead->department,
        //             "section"  => $datahead->section,
        //             "rack_desc"  => $datahead->rack_desc,
        //             "empno"  => $datahead->empno,
        //             "datetime_scanned"  => $datahead->datetime_scanned,
        //             "datetime_saved"  => $datahead->datetime_saved,
        //             "datetime_exported"  => $datahead->datetime_exported
        //         );
        //     }
        // }

        // $data = array(
        //     'header' => 'Header',
        //     'data' => $data_res
        // );
        // return $data;
    }

    public function data()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $company = auth()->user()->company;
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $vendors = base64_decode(request()->vendors);
        $category = request()->category;
        $countType = request()->countType;
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        $dateAsOf = Carbon::parse(base64_decode(request()->date))->endOfDay()->toDateTimeString();
        $date2 = Carbon::parse(base64_decode(request()->date2))->endOfDay()->toDateTimeString();
        $printDate = Carbon::parse(base64_decode(request()->date))->toFormattedDateString();
        $runDate = Carbon::parse(now())->toFormattedDateString();
        $runTime =  Carbon::parse(now())->format('h:i A');

        $result = TblAppCountdata::selectRaw('
                tbl_app_countdata.id,
                tbl_app_countdata.itemcode, 
                tbl_app_countdata.barcode, 
                tbl_app_countdata.description,
                tbl_item_masterfile.extended_desc,
                tbl_app_countdata.uom, 
                tbl_nav_countdata.uom as nav_uom,
                SUM(tbl_app_countdata.qty) as qty,
                SUM(tbl_app_countdata.conversion_qty) as total_conv_qty,
                tbl_app_countdata.rack_desc,
                tbl_app_countdata.empno,
                tbl_app_countdata.datetime_scanned,
                tbl_app_countdata.datetime_saved,
                tbl_app_countdata.datetime_exported,
                tbl_app_countdata.date_expiry,
                tbl_app_user.name AS app_user,
                tbl_app_user.position AS app_user_position,
                tbl_app_audit.name AS audit_user,
                tbl_app_audit.position AS audit_position,
                vendor_name, 
                tbl_item_masterfile.group
                ')
            ->join('tbl_app_user', 'tbl_app_user.location_id', 'tbl_app_countdata.location_id')
            ->join('tbl_app_audit', 'tbl_app_audit.location_id', 'tbl_app_countdata.location_id')
            ->join('tbl_item_masterfile', 'tbl_item_masterfile.barcode', 'tbl_app_countdata.barcode')
            ->LEFTJOIN('tbl_nav_countdata', 'tbl_nav_countdata.itemcode', '=', 'tbl_app_countdata.itemcode')
            ->whereBetween('datetime_saved', [$date, $dateAsOf])->orderBy('itemcode');

        // dd($result->groupBy('barcode')->get()->groupBy(['app_user', 'audit_user', 'vendor_name'])->toArray());

        if ($bu != 'null') {
            $result->WHERE('tbl_app_countdata.business_unit',  'LIKE', "%$bu%");
        }
        if ($dept != 'null') {
            $result->WHERE('tbl_app_countdata.department', 'LIKE', "%$dept%");
        }
        if ($section != 'null') {
            $result->WHERE('tbl_app_countdata.section', 'LIKE', "%$section%");
        }
        if ($vendors) {
            // $vendors = explode('|', $vendors);
            $vendors = explode(" , ", $vendors);
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

        // dd($result->groupBy('itemcode')->get()->groupBy(['app_user', 'audit_user']))->toArray();

        $result = $result->groupBy('barcode')->get()->groupBy(['app_user', 'audit_user', 'vendor_name', 'group'])->toArray();

        // $appCount = TblAppCountdata::whereIn('itemcode', $result->pluck('itemcode'));
        // $appCount->get()->groupBy('itemcode')->map(function ($data) use ($appCount) {
        //     foreach ($appCount as $key => $value) {
        //         return array(
        //             "id"  => $data->id,
        //             "itemcode"  => $data->itemcode,
        //             "barcode"  => $data->barcode,
        //             "description"  => $data->description,
        //             "uom"  => $data->uom,
        //             "qty"  => $data->total_qty,
        //             "rack_desc"  => $data->rack_desc,
        //             "empno"  => $data->empno,
        //             "datetime_scanned"  => $data->datetime_scanned,
        //             "datetime_saved"  => $data->datetime_saved,
        //             "datetime_exported"  => $data->datetime_exported
        //         );
        //     }
        //     // dump($data);

        //     // return $data;
        // })->toArray();

        // dd($result);

        $header = array(
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
            'data' => $result
        );

        // dd($header);

        return $header;
    }

    public function generateCountDamages()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $pdf = PDF::loadView('reports.pcount_damages', ['data' => $this->dataCountDamages()]);
        return $pdf->setPaper('legal', 'landscape')->download('PCount From App.pdf');
    }

    public function generateCountDamagesEXCEL()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        session(['dataCountDamages' => $this->dataCountDamages()]);
        return Excel::download(new PcountDamageExport, 'invoices.xlsx');
    }

    public function dataCountDamages()
    {
        $company = auth()->user()->company;
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $vendors = base64_decode(request()->vendors);
        $category = request()->category;
        $countType = request()->countType;
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        $dateAsOf = Carbon::parse(base64_decode(request()->date))->endOfDay()->toDateTimeString();
        $date2 = Carbon::parse(base64_decode(request()->date2))->endOfDay()->toDateTimeString();
        $printDate = Carbon::parse(base64_decode(request()->date))->toFormattedDateString();
        $runDate = Carbon::parse(now())->toFormattedDateString();
        $runTime =  Carbon::parse(now())->format('h:i A');

        $result = TblAppCountdata::selectRaw('
                tbl_app_countdata.id,
                tbl_app_countdata.itemcode, 
                tbl_app_countdata.barcode, 
                tbl_app_countdata.description,
                tbl_app_countdata.uom, 
                SUM(tbl_app_countdata.qty) as total_qty,
                SUM(tbl_app_countdata.conversion_qty) as total_conv_qty,
                tbl_app_countdata.rack_desc,
                tbl_app_countdata.empno,
                tbl_app_countdata.datetime_scanned,
                tbl_app_countdata.datetime_saved,
                tbl_app_countdata.datetime_exported,
                tbl_app_countdata.date_expiry,
                tbl_app_user.name AS app_user,
                tbl_app_user.position AS app_user_position,
                tbl_app_countdata.user_signature as app_user_sign,
                tbl_app_audit.name AS audit_user,
                tbl_app_audit.position AS audit_position,
                tbl_app_countdata.audit_signature AS audit_user_sign
                ')
            // tbl_nav_countdata.uom as nav_uom,
            // vendor_name
            // tbl_item_masterfile.extended_desc,
            // tbl_item_masterfile.group
            ->join('tbl_app_user', 'tbl_app_user.location_id', 'tbl_app_countdata.location_id')
            ->join('tbl_app_audit', 'tbl_app_audit.location_id', 'tbl_app_countdata.location_id')
            // ->join('tbl_item_masterfile', 'tbl_item_masterfile.barcode', 'tbl_app_countdata.barcode')
            // ->join('tbl_item_masterfile', function ($join) {
            //     $join->on('tbl_item_masterfile.barcode', 'tbl_app_countdata.barcode');
            //     $join->orOn('tbl_item_masterfile.extended_desc', 'tbl_app_countdata.description');
            //     // $join->orOn('tbl_item_masterfile.uom', 'tbl_app_countdata.uom');
            // })
            // ->LEFTJOIN('tbl_nav_countdata', 'tbl_nav_countdata.itemcode', '=', 'tbl_app_countdata.itemcode')
            ->whereBetween('datetime_saved', [$date, $dateAsOf])->orderBy('itemcode');

        // dd($result->groupBy('barcode')->get()->groupBy(['app_user', 'audit_user', 'rack_desc'])->toArray());

        if ($bu != 'null') {
            $result->WHERE('tbl_app_countdata.business_unit',  'LIKE', "%$bu%");
        }
        if ($dept != 'null') {
            $result->WHERE('tbl_app_countdata.department', 'LIKE', "%$dept%");
        }
        if ($section != 'null') {
            $result->WHERE('tbl_app_countdata.section', 'LIKE', "%$section%");
        }
        if ($vendors) {
            $vendors = explode('|', $vendors);
            $result = $result->whereIn('vendor_name', $vendors);
            $vendors = implode(", ", $vendors);
        }

        if ($category) {
            $category = explode('|', $category);
            $result = $result->whereIn('group', $category);
            $category = implode(", ", $category);
        }

        $result = $result->groupBy('barcode')->get()->groupBy(['app_user', 'audit_user', 'rack_desc'])->toArray();
        $header = array(
            'company' => $company,
            'business_unit' => $bu,
            'department' => $dept,
            'section' => $section,
            // 'vendors' => $vendors,
            'category' => $category,
            'date' => $printDate,
            'countType' => $countType,
            'user' => auth()->user()->name,
            'user_position' => auth()->user()->position,
            'runDate'   => $runDate,
            'runTime'    => $runTime,
            'data' => $result
        );
        // dd($header['data']['Apa-ap, Bendion Paul Pocot']);

        return $header;
    }

    public function generateNotFound()
    {
        $type = request()->report;
        $countType = request()->type;
        session(['data' => $this->itemsNotFoundData($type)]);
        $data = session()->get('data');
        // dd(!$countType ? 'Actual Count (APP)' : $countType . ' Count (APP)');
        // $pdf = PDF::loadView('reports.pcount_app_notfound', ['data' => $data]);
        $pdf = PDF::loadView('reports.pcount_app_notfound', ['data' => $data, 'name' => 'Items Not Found from Actual Count (APP)', 'title' => !$countType ? 'Actual Count (APP)' : $countType . ' COUNT (APP)']);

        // return $type == "ExcelReport" ? Excel::download(new ItemsNotFound, 'invoices.xlsx') : (new ItemsNotFound)->download('invoices.pdf',);
        return $type == "Excel" ? Excel::download(new ItemsNotFound, 'notfound.xlsx') : $pdf->setPaper('legal', 'landscape')->download('notfound.pdf',);
        // return Excel::download(new ItemsNotFound, 'invoices.xlsx');
    }

    public function itemsNotFoundData($type)
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $company = auth()->user()->company;
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $vendors = base64_decode(request()->vendors);
        $category = request()->category;
        $countType = request()->countType;
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        $dateAsOf = Carbon::parse(base64_decode(request()->date))->endOfDay()->toDateTimeString();
        $printDate = Carbon::parse(base64_decode(request()->date))->toFormattedDateString();
        $runDate = Carbon::parse(now())->toFormattedDateString();
        $runTime =  Carbon::parse(now())->format('h:i A');

        if ($bu == 'ASC: MAIN') {
            $table = $section != "SNACKBAR" ? "tbl_item_masterfile" : "tbl_item_masterfile_snackbar";
        } else if ($bu == 'ALTA CITTA') {
            $table = "tbl_item_masterfile_alta_citta";
        } else {
            $table = "tbl_item_masterfile";
        }
        // dd($table);

        if ($bu == 'DISTRIBUTION') {
            $selectRawValue = "
            tbl_app_nfitem.barcode,
            tbl_app_nfitem.location_id,
            tbl_app_nfitem.uom,
            tbl_app_nfitem.lot_number,
            tbl_app_nfitem.expiry, 
            SUM(tbl_app_nfitem.qty) as total_qty,
            business_unit,
            department,
            tbl_app_nfitem.section,
            datetime_scanned,
            datetime_exported,
            rack_desc,
            tbl_app_user.name AS app_user,
            tbl_app_user.position AS app_user_position,
            tbl_app_user.user_signature as app_user_sign,
            tbl_app_audit.name AS audit_user,
            tbl_app_audit.position AS audit_position,
            tbl_app_audit.audit_signature AS audit_user_sign,
            description,
            $table.vendor_name, 
            $table.group";
        } else {
            $selectRawValue = "
            tbl_app_nfitem.barcode,
            tbl_app_nfitem.location_id,
            tbl_app_nfitem.uom, 
            SUM(tbl_app_nfitem.qty) as total_qty,
            business_unit,
            department,
            tbl_app_nfitem.section,
            datetime_scanned,
            datetime_exported,
            rack_desc,
            tbl_app_user.name AS app_user,
            tbl_app_user.position AS app_user_position,
            tbl_app_user.user_signature as app_user_sign,
            tbl_app_audit.name AS audit_user,
            tbl_app_audit.position AS audit_position,
            tbl_app_audit.audit_signature AS audit_user_sign,
            description,
            $table.vendor_name, 
            $table.group";
        }

        $result = TblAppNfitem::selectRaw($selectRawValue)
            ->join('tbl_app_user', 'tbl_app_user.location_id', 'tbl_app_nfitem.location_id')
            ->join('tbl_app_audit', 'tbl_app_audit.location_id', 'tbl_app_nfitem.location_id')
            ->leftjoin("$table", "$table.barcode", 'tbl_app_nfitem.barcode')
            ->whereBetween('datetime_scanned', [$date, $dateAsOf])->orderBy('datetime_scanned');

        // dd($result->groupBy('barcode')->get()->groupBy(['app_user', 'audit_user', 'vendor_name', 'group'])->toArray());

        if ($bu != 'null') {
            $result->WHERE('tbl_app_nfitem.business_unit',  'LIKE', "%$bu%");
        }
        if ($dept != 'null') {
            $result->WHERE('tbl_app_nfitem.department', 'LIKE', "%$dept%");
        }
        if ($section != 'null') {
            $result->WHERE('tbl_app_nfitem.section', 'LIKE', "%$section%");
        }
        // if ($vendors) {
        //     $vendors = explode('|', $vendors);
        //     $result = $result->whereIn('vendor_name', $vendors);
        //     $vendors = implode(", ", $vendors);
        // }

        // if ($category) {
        //     $category = explode('|', $category);
        //     $result = $result->whereIn('group', $category);
        //     $category = implode(", ", $category);
        // }

        // dd($result->groupBy('barcode')->get()->groupBy(['app_user', 'audit_user', 'vendor_name', 'group']))->toArray();

        if ($bu == 'DISTRIBUTION') {
            $result = $result->groupBy('barcode')->groupBy('location_id')->groupBy('lot_number')->groupBy('expiry')->get()->groupBy(['app_user', 'audit_user', 'vendor_name', 'group'])->toArray();
        } else {
            $result = $result->groupBy('barcode')->groupBy('location_id')->get()->groupBy(['app_user', 'audit_user', 'vendor_name', 'group'])->toArray();
        }


        // $appCount = TblAppCountdata::whereIn('itemcode', $result->pluck('itemcode'));
        // $appCount->get()->groupBy('itemcode')->map(function ($data) use ($appCount) {
        //     foreach ($appCount as $key => $value) {
        //         return array(
        //             "id"  => $data->id,
        //             "itemcode"  => $data->itemcode,
        //             "barcode"  => $data->barcode,
        //             "description"  => $data->description,
        //             "uom"  => $data->uom,
        //             "qty"  => $data->total_qty,
        //             "rack_desc"  => $data->rack_desc,
        //             "empno"  => $data->empno,
        //             "datetime_scanned"  => $data->datetime_scanned,
        //             "datetime_saved"  => $data->datetime_saved,
        //             "datetime_exported"  => $data->datetime_exported
        //         );
        //     }
        //     // dump($data);

        //     // return $data;
        // })->toArray();

        // dd($result);

        $header = array(
            'company'       => $company,
            'business_unit' => $bu,
            'department'    => $dept,
            'section'       => $section,
            'vendors'       => $vendors,
            'category'      => $category,
            'date'          => $printDate,
            'countType'     => $countType,
            'user'          => auth()->user()->name,
            'user_position' => auth()->user()->position,
            'runDate'       => $runDate,
            'runTime'       => $runTime,
            'type'          => $type,
            'data'          => $result
        );

        return $header;
    }

    public function backendCount()
    {
        $company = auth()->user()->company;
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $dateAsOf = Carbon::parse(base64_decode(request()->date))->endOfDay()->toDateTimeString();
        $type = request()->type;
        $date = Carbon::parse(base64_decode(request()->date))->toDateString();
        // dd(request()->all());
        if ($type == 'ACTUAL') {
            $query = TblAppNfitem::selectRaw('
            tbl_app_nfitem.id,
            itemcode,
            barcode, 
            description,
            uom, 
            qty,
            nav_qty,
            location_id,
            name as updated_by,
            tbl_app_nfitem.updated_at
        ')->with(['hasbarcode', 'hasitemcode'])
                ->leftjoin('users', 'users.id', 'tbl_app_nfitem.updated_by')
                ->WHERE([
                    ['tbl_app_nfitem.business_unit',  'LIKE', "%$bu%"],
                    ['tbl_app_nfitem.department', 'LIKE', "%$dept%"],
                    ['tbl_app_nfitem.section', 'LIKE', "%$section%"]
                ]);
        } else {
            $query = TblAdvanceCountNotFound::selectRaw('
            tbl_app_advNfitem.id,
            itemcode,
            barcode, 
            description,
            uom, 
            qty,
            nav_qty,
            location_id,
            name as updated_by,
            tbl_app_advNfitem.updated_at
        ')->with(['hasbarcode', 'hasitemcode'])
                ->leftjoin('users', 'users.id', 'tbl_app_advNfitem.updated_by')
                ->WHERE([
                    ['tbl_app_advNfitem.business_unit',  'LIKE', "%$bu%"],
                    ['tbl_app_advNfitem.department', 'LIKE', "%$dept%"],
                    ['tbl_app_advNfitem.section', 'LIKE', "%$section%"]
                ]);
        }
        // dd($query->get());

        // if ($bu != 'null')
        //     $query->WHERE('business_unit',  'LIKE', "%$bu%");


        // if ($dept != 'null')
        //     $query->WHERE('department', 'LIKE', "%$dept%");


        // if ($section != 'null') {
        //     $query->WHERE('section', 'LIKE', "%$section%");
        // } else {
        //     $query->WHERE('section', 'LIKE', "%null%");
        // }

        // $query = $query
        //     ->where([['location_id', '=', 0], ['rack_desc', 'LIKE', "%SETUP BY BACKEND%"]])
        //     ->whereBetween('datetime_saved', [$date, $dateAsOf])
        //     ->groupBy('barcode')
        //     ->orderBy('itemcode');
        $query = $query->whereBetween('datetime_exported', [$date, $dateAsOf]);
        $query = $query->get()
            ->toArray();


        // dd($query);

        return $query;
    }

    public function generateBackendCount()
    {
        // dd(request()->all());
        // $company = auth()->user()->company;
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $vendors = base64_decode(request()->vendors);
        $category = request()->category;
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        $dateAsOf = Carbon::parse(base64_decode(request()->date))->endOfDay()->toDateTimeString();
        $date2 = Carbon::parse(base64_decode(request()->date2))->endOfDay()->toDateTimeString();
        $countType = request()->countType;
        $printDate = Carbon::parse(base64_decode(request()->date))->toFormattedDateString();
        $runDate = Carbon::parse(now())->toFormattedDateString();
        $runTime =  Carbon::parse(now())->format('h:i A');
        $type = request()->report;

        $export = json_decode(base64_decode(request()->export), true);
        $headers = array(
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
            'report' => $type,
            'data' => $export
        );
        session(['data' => $headers]);
        $data = session()->get('data');
        $pdf = PDF::loadView('reports.countdata_backend', ['data' => $data]);

        // return $type == "ExcelReport" ? Excel::download(new ItemsNotFound, 'invoices.xlsx') : (new ItemsNotFound)->download('invoices.pdf',);
        return $type == "Excel" ? Excel::download(new CountByBackend, 'Count by Backend.xlsx') : $pdf->setPaper('legal', 'landscape')->download('Count by Backend.pdf',);
    }

    public function getAdvanceCount()
    {
        $company = auth()->user()->company;
        $user = auth()->user()->id;
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $vendors = base64_decode(request()->vendors);
        $category = request()->category;
        // $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        // $dateAsOf = Carbon::parse(base64_decode(request()->date))->endOfDay()->toDateTimeString();
        // $date2 = Carbon::parse(base64_decode(request()->date2))->endOfDay()->toDateTimeString();
        $date = base64_decode(request()->date);
        $date = explode(',', $date,);
        $dateStart = $date[0];
        $dateEnd = Carbon::parse($date[1])->endOfDay()->toDateTimeString();
        $printDate = Carbon::parse($dateStart)->toFormattedDateString();
        // dd($printDate);
        $countType = request()->countType;
        // $printDate = Carbon::parse(base64_decode(request()->date))->toFormattedDateString();
        $runDate = Carbon::parse(now())->toFormattedDateString();
        $runTime =  Carbon::parse(now())->format('h:i A');

        $key = implode('-', [$bu, $dept, $section, $printDate, 'AdvanceCount', auth()->user()->id]);
        // dd($key);
        if ($bu == 'ASC: MAIN') {
            $table = 'tbl_advance_count_alturas';
        } else if ($bu == 'PLAZA MARCELA') {
            $table = 'tbl_advance_count_pm';
        } else if ($bu == 'ALTURAS TALIBON') {
            $table = 'tbl_advance_count_talibon';
        } else if ($bu == 'DISTRIBUTION') {
            $table = 'tbl_advance_count_pdc';
        } else {
            $table = 'tbl_advance_count';
        }
        // dd("$table.itemcode");

        if ($bu == 'DISTRIBUTION') {
            $selectRawValue = "
            $table.itemcode, 
            $table.barcode, 
            $table.description, 
            tbl_item_masterfile.extended_desc,
            $table.uom, 
            $table.lot_number, 
            $table.expiry, 
            SUM($table.qty) as qty,
            SUM($table.conversion_qty) as total_conv_qty,
            $table.rack_desc,
            $table.empno,
            datetime_scanned,
            datetime_saved,
            datetime_exported,
            date_expiry,
            vendor_name, 
            tbl_item_masterfile.group,
            tbl_app_user.name AS app_user,
            tbl_app_user.position AS app_user_position,
            tbl_app_audit.name AS audit_user,
            tbl_app_audit.position AS audit_position
            ";
        } else {
            $selectRawValue = "
            $table.itemcode, 
            $table.barcode, 
            $table.description, 
            tbl_item_masterfile.extended_desc,
            $table.uom, 
            SUM($table.qty) as qty,
            SUM($table.conversion_qty) as total_conv_qty,
            $table.rack_desc,
            $table.empno,
            datetime_scanned,
            datetime_saved,
            datetime_exported,
            date_expiry,
            vendor_name, 
            tbl_item_masterfile.group,
            tbl_app_user.name AS app_user,
            tbl_app_user.position AS app_user_position,
            tbl_app_audit.name AS audit_user,
            tbl_app_audit.position AS audit_position
            ";
        }

        $query = DB::table($table)->selectRaw($selectRawValue)
            ->JOIN('tbl_item_masterfile', function ($join) use ($table) {
                // dd(`$table.itemcode`);
                $join->on("$table.itemcode", '=', 'tbl_item_masterfile.item_code');
                $join->on("$table.barcode", '=', 'tbl_item_masterfile.barcode');
            })
            ->JOIN('tbl_app_user', 'tbl_app_user.location_id', "$table.location_id")
            ->JOIN('tbl_app_audit', 'tbl_app_audit.location_id', "$table.location_id");
        // ->LEFTJOIN('tbl_nav_countdata', function ($join) {
        //     $join->on('tbl_advance_count.itemcode', '=', 'tbl_nav_countdata.itemcode');
        // });

        // dd($query->get());

        if ($bu != 'null') {
            $query->WHERE("$table.business_unit",  'LIKE', "%$bu%");
        }

        if ($dept != 'null') {
            $query->WHERE("$table.department", 'LIKE', "%$dept%");
        }

        if ($section != 'null') {
            $query->WHERE("$table.section", 'LIKE', "%$section%");
        } else {
            $query->WHERE("$table.section", 'LIKE', "%null%");
        }

        if ($vendors) {
            $vendors = explode(' , ', $vendors);
            $vendors = str_replace("'", "", $vendors);
            $query = $query->whereIn('vendor_name', $vendors);
        }
        if ($category) {
            $category = explode(" , ", $category);
            $category = str_replace("'", "", $category);
            $query = $query->whereIn('group', $category);
        }

        if ($bu == 'DISTRIBUTION') {
            $query = $query->whereBetween('datetime_saved', [$dateStart, $dateEnd])
                ->groupBy('barcode')->groupBy('lot_number')->groupBy('expiry');
        } else {
            $query = $query->whereBetween('datetime_saved', [$dateStart, $dateEnd])
                ->groupBy('barcode');
        }
        // ->orderBy('itemcode');


        // dd($query->get());


        if (request()->has('forExport')) {
            return Cache::remember($key, now()->addMinutes(180), function () use (
                $date,
                $bu,
                $dept,
                $section,
                $vendors,
                $category,
                $company,
                $printDate,
                $countType,
                $runDate,
                $runTime,
                $query
            ) {

                $data = $query->get()
                    ->groupBy(['app_user', 'audit_user', 'vendor_name', 'group'])
                    ->toArray();
                // dd($data);

                $query = array(
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
                    'data' => $data
                );
                return $query;
            });
        }


        // return  $query->groupBy(['app_user', 'audit_user', 'vendor_name', 'group'])
        //     ->paginate(10);
        // dd($query->get());
        $query2 = $query->get();
        $countData = [];
        foreach ($query2 as $data) {
            $countData[] = $data;
        }
        $query = $query->paginate(10);
        $merge = collect(['countData' => $countData]);
        $finalResult = $merge->merge($query);

        return $finalResult;
    }

    public function generateAdvanceCount()
    {
        // dd(request()->all());
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        // $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        // $printDate = Carbon::parse(base64_decode(request()->date))->toFormattedDateString();
        $date = base64_decode(request()->date);
        $date = explode(',', $date,);
        $dateStart = $date[0];
        $dateEnd = Carbon::parse($date[1])->endOfDay()->toDateTimeString();
        $printDate = Carbon::parse($dateStart)->toFormattedDateString();
        $key = implode('-', [$bu, $dept, $section, $printDate, 'AdvanceCount', auth()->user()->id]);

        if ($bu == 'ASC: MAIN') {
            $table = 'tbl_advance_count_alturas';
        } else if ($bu == 'PLAZA MARCELA') {
            $table = 'tbl_advance_count_pm';
        } else if ($bu == 'DISTRIBUTION') {
            $table = 'tbl_advance_count_pdc';
        } else {
            $table = 'tbl_advance_count';
        }

        // dd($key);
        // $data = request()->data;
        $data = Cache::get($key);
        // asa ani boss
        $data['data'] = collect($data['data'])->map(function ($trans)  use ($table) {
            // dd($trans->all());
            $res = array_map(function ($x) use ($table) {
                return array_map(function ($y) use ($table) {
                    return array_map(function ($z) use ($table) {
                        $newArr = [];
                        foreach ($z as $index => $xyz) {
                            if ($index === 0) {
                                $xyz = (array) $xyz;
                                $res = DB::table($table)->where('itemcode', $xyz['itemcode'])->first();

                                $xyz['user_signature'] = $res->user_signature;
                                $xyz['audit_signature'] = $res->audit_signature;
                                $newArr[] = $xyz;
                            } else {
                                $newArr[] = (array) $xyz;
                            }
                        }
                        return $newArr;
                    }, $y);
                }, $x);
            }, $trans);

            return $res;
        })->all();

        // dd($data);


        $pdf = PDF::loadView('reports.advance_count_report', ['data' => $data]);
        return $pdf->setPaper('legal', 'landscape')->download('Advance Count.pdf');
    }

    public function generateAdvanceCountExcel()
    {
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        // $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        // $printDate = Carbon::parse(base64_decode(request()->date))->toFormattedDateString();
        $date = base64_decode(request()->date);
        $date = explode(',', $date,);
        $dateStart = $date[0];
        $dateEnd = Carbon::parse($date[1])->endOfDay()->toDateTimeString();
        $printDate = Carbon::parse($dateStart)->toFormattedDateString();

        $key = implode('-', [$bu, $dept, $section, $printDate, 'AdvanceCount', auth()->user()->id]);
        // dd($key);
        $data = Cache::get($key);

        session(['data' => $data, 'type' => 'AdvanceCount', 'bu' => $bu]);
        return Excel::download(new PcountAppCountData, 'invoices.xlsx');
    }

    public function getAdvanceCountNotFound()
    {
        // $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        // $dateAsOf = Carbon::parse(base64_decode(request()->date))->endOfDay()->toDateTimeString();
        $date = base64_decode(request()->date);
        $date = explode(',', $date,);
        $dateStart = $date[0];
        $dateEnd = Carbon::parse($date[1])->endOfDay()->toDateTimeString();
        $printDate = Carbon::parse($dateStart)->toFormattedDateString();

        if (request()->bu == 'ASC: MAIN') {
            $table = 'tbl_app_advnfitem_alturas';
        } else {
            //MAIN sa ICM
            $table = 'tbl_app_advNfitem';
        }

        return DB::table($table)->where([
            ['business_unit', 'LIKE', request()->bu],
            ['department', 'LIKE', request()->dept],
            ['section', 'LIKE', request()->section]
        ])
            ->whereBetween('datetime_scanned', [$dateStart, $dateEnd])->groupBy('barcode')->get();
    }

    public function generateAdvanceCountNotFound()
    {
        $type = request()->report;
        // dd($type);
        session(['data' => $this->AdvanceCountNotFoundData($type)]);
        $data = session()->get('data');
        // dd($data);
        $pdf = PDF::loadView('reports.pcount_app_notfound', ['data' => $data, 'name' => 'Items Not Found from Advance Count (APP)', 'title' => 'Advance Count (APP)']);

        return $type == "Excel" ? Excel::download(new ItemsNotFound, 'notfound.xlsx') : $pdf->setPaper('legal', 'landscape')->download('notfound.pdf',);
    }

    public function AdvanceCountNotFoundData($type)
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $company = auth()->user()->company;
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $vendors = base64_decode(request()->vendors);
        $category = request()->category;
        $countType = request()->countType;
        // $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        // $dateAsOf = Carbon::parse(base64_decode(request()->date))->endOfDay()->toDateTimeString();
        // $printDate = Carbon::parse(base64_decode(request()->date))->toFormattedDateString();
        $date = base64_decode(request()->date);
        $date = explode(',', $date,);
        $dateStart = $date[0];
        $dateEnd = Carbon::parse($date[1])->endOfDay()->toDateTimeString();
        $printDate = Carbon::parse($dateStart)->toFormattedDateString();
        $runDate = Carbon::parse(now())->toFormattedDateString();
        $runTime =  Carbon::parse(now())->format('h:i A');

        if ($bu == 'ASC: MAIN') {
            $table = 'tbl_app_advnfitem_alturas';
        } else if ($bu == 'PLAZA MARCELA') {
            $table = 'tbl_app_advnfitem_pm';
        } else {
            //MAIN sa ICM
            $table = 'tbl_app_advNfitem';
        }

        $result = DB::table($table)->selectRaw("
        $table.barcode,
        $table.uom, 
        SUM($table.qty) as total_qty,
        business_unit,
        department,
        $table.section,
        datetime_scanned,
        datetime_exported,
        rack_desc,
        tbl_app_user.name AS app_user,
        tbl_app_user.position AS app_user_position,
        $table.user_signature as app_user_sign,
        tbl_app_audit.name AS audit_user,
        tbl_app_audit.position AS audit_position,
        $table.audit_signature AS audit_user_sign,
        description,
        vendor_name, 
        tbl_item_masterfile.group")
            ->join('tbl_app_user', 'tbl_app_user.location_id', "$table.location_id")
            ->join('tbl_app_audit', 'tbl_app_audit.location_id', "$table.location_id")
            ->leftjoin('tbl_item_masterfile', 'tbl_item_masterfile.barcode', "$table.barcode")
            ->whereBetween('datetime_scanned', [$dateStart, $dateEnd])->orderBy('datetime_scanned');

        if ($bu != 'null') {
            $result->WHERE("$table.business_unit",  'LIKE', "%$bu%");
        }
        if ($dept != 'null') {
            $result->WHERE("$table.department", 'LIKE', "%$dept%");
        }
        if ($section != 'null') {
            $result->WHERE("$table.section", 'LIKE', "%$section%");
        }

        $result = $result->groupBy('barcode')->get()->groupBy(['app_user', 'audit_user', 'vendor_name', 'group'])->toArray();
        $result = json_decode(json_encode($result), true);
        // dd($result);
        $header = array(
            'company'       => $company,
            'business_unit' => $bu,
            'department'    => $dept,
            'section'       => $section,
            'vendors'       => $vendors,
            'category'      => $category,
            'date'          => $printDate,
            'countType'     => $countType,
            'user'          => auth()->user()->name,
            'user_position' => auth()->user()->position,
            'runDate'       => $runDate,
            'runTime'       => $runTime,
            'type'          => $type,
            'data'          => $result
        );

        return $header;
    }

    public function getDates()
    {
        // dd(request()->all());
        $comp = request()->comp;
        $bu = request()->bu;
        $dept = request()->dept;
        $sect = request()->sect;
        $countType = request()->countType;
        $type = request()->type;
        $loc = [];
        $loc =  DB::table('tbl_nav_count')->select('location_id')->get()->toArray();

        $countType == 'ACTUAL' ? $table = 'tbl_app_countdata' : $table = 'tbl_advance_count';
        if ($bu == 'ASC: MAIN') {
            $var = $sect == "SNACKBAR" ? "_snackbar" :  '_alturas';
        } else if ($bu == 'PLAZA MARCELA') {
            $var = '_pm';
        } else if ($bu == 'ALTURAS TALIBON') {
            $var = '_talibon';
        } else if ($bu == 'ALTA CITTA') {
            $var = '_alta';
        } else if ($bu == 'DISTRIBUTION') {
            $var = '_pdc';
        } else {
            $var = '';
        }

        if ($type != 'null') {
            $loc = DB::table('tbl_nav_count')->where('type', $type)->select('location_id')->get()->pluck('location_id')->toArray();
            return DB::table($table . $var)->selectRaw("
            DATE_FORMAT(datetime_exported, '%Y-%m-%d') as batchDate
            ")->where([
                ['business_unit', $bu],
                ['department', $dept],
                ['section', $sect]
            ])->whereIn('location_id', $loc)
                ->GROUPBYRAW('EXTRACT(DAY FROM datetime_exported)')->cursor();
        } else {
            return DB::table($table . $var)->selectRaw("
            DATE_FORMAT(datetime_exported, '%Y-%m-%d') as batchDate
            ")->where([
                ['business_unit', $bu],
                ['department', $dept],
                ['section', $sect]
            ])
                ->GROUPBYRAW('EXTRACT(DAY FROM datetime_exported)')->cursor();
        }
    }

    public function deleteimage()
    {

        $directory = public_path('temp');

        // Get all files in the directory
        $files = File::allFiles($directory);

        foreach ($files as $file) {
            // Check if the file has a .png extension
            if ($file->getExtension() === 'png') {
                // Delete the file
                File::delete($file->getPathname());
            }
        }
    }
}
