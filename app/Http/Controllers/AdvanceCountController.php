<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Exports\ItemsNotFound;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use App\Exports\PcountAppCountData;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;

class AdvanceCountController extends Controller
{
    public function getAdvanceCount()
    {
        $company = auth()->user()->company;
        $user = auth()->user()->id;
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $vendors = base64_decode(request()->vendors);
        $category = request()->category;
        $date = base64_decode(request()->date);
        $date = explode(',', $date,);
        $dateStart = $date[0];
        $dateEnd = Carbon::parse($date[1])->endOfDay()->toDateTimeString();
        $printDate = Carbon::parse($dateStart)->toFormattedDateString();
        $countType = request()->countType;
        $runDate = Carbon::parse(now())->toFormattedDateString();
        $runTime =  Carbon::parse(now())->format('h:i A');
        $keyDate = $dateStart . '~' . Carbon::parse($date[1])->toDateString();

        // dd($keyDate);
        $key = implode('-', [$bu, $dept, $section, $keyDate, 'AdvanceCount', $user]);
        // dd($key);
        if ($bu == 'ASC: MAIN') {
            $table = 'tbl_advance_count_alturas';
        } else if ($bu == 'PLAZA MARCELA') {
            $table = 'tbl_advance_count_pm';
        } else if ($bu == 'ALTURAS TALIBON') {
            $table = 'tbl_advance_count_talibon';
        } else if ($bu == 'ALTA CITTA') {
            $table = 'tbl_advance_count_alta';
        } else if($bu == 'DISTRIBUTION') {
            $table = 'tbl_advance_count_pdc';
        } else {
            $table = 'tbl_advance_count';
        }
        // dd("$table.itemcode");
        $query = DB::table($table)->selectRaw("
        $table.itemcode, 
        $table.barcode, 
        $table.description, 
        tbl_item_masterfile.extended_desc,
        $table.uom, 
        $table.lot_number, 
        $table.expiry, 
        tbl_nav_countdata.uom as nav_uom, 
        SUM($table.qty) as qty,
        SUM($table.conversion_qty) as total_conv_qty,
        $table.rack_desc,
        $table.empno,
        datetime_scanned,
        datetime_saved,
        datetime_exported,
        vendor_name, 
        tbl_item_masterfile.group,
        tbl_item_masterfile.variant_code,
        tbl_app_user.name AS app_user,
        tbl_app_user.position AS app_user_position,
        tbl_app_audit.name AS audit_user,
        tbl_app_audit.position AS audit_position,
        $table.location_id 
        ")
            ->JOIN('tbl_item_masterfile', function ($join) use ($table) {
                $join->on("$table.itemcode", '=', 'tbl_item_masterfile.item_code');
                $join->on("$table.barcode", '=', 'tbl_item_masterfile.barcode');
            })
            ->JOIN('tbl_app_user', 'tbl_app_user.location_id', "$table.location_id")
            ->JOIN('tbl_app_audit', 'tbl_app_audit.location_id', "$table.location_id")
            ->leftJoin('tbl_nav_countdata', function ($use) use ($table) {
                $use->on('tbl_nav_countdata.itemcode', '=', "$table.itemcode")
                    ->on('tbl_nav_countdata.id', '=', DB::raw("(SELECT MAX(id) from tbl_nav_countdata where tbl_nav_countdata.itemcode = $table.itemcode)"));
            });

        if ($bu != 'null') {
            $query->WHERE("$table.business_unit",  $bu);
        }

        if ($dept != 'null') {
            $query->WHERE("$table.department", $dept);
        }

        if ($section != 'null') {
            $query->WHERE("$table.section", $section);
        } else {
            $query->WHERE("$table.section", null);
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

        $query = $query->whereBetween('datetime_saved', [$dateStart, $dateEnd])
            ->groupBy('barcode')
            ->groupBy('location_id');
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
        } else if ($bu == 'ALTURAS TALIBON') {
            $table = 'tbl_advance_count_talibon';
        } else if ($bu == 'ALTA CITTA') {
            $table = 'tbl_advance_count_alta';
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

        // dd($data);


        $pdf = PDF::loadView('reports.advance_count_report', ['data' => $data, 'title' => 'Advance Count (APP)']);
        return $pdf->setPaper('legal', 'landscape')->download('Advance Count.pdf');
    }

    public function generateAdvanceCountExcel()
    {
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $date = base64_decode(request()->date);
        $date = explode(',', $date,);
        $dateStart = $date[0];
        $dateEnd = Carbon::parse($date[1])->toDateString();
        $printDate = Carbon::parse($dateStart)->toFormattedDateString();
        $keyDate = $dateStart . '~' . $dateEnd;

        $key = implode('-', [$bu, $dept, $section, $keyDate, 'AdvanceCount', auth()->user()->id]);
        // dd($key);
        $data = Cache::get($key);

        session(['data' => $data, 'type' => 'AdvanceCount', 'bu' => $bu]);
        return Excel::download(new PcountAppCountData, 'invoices.xlsx');
    }

    public function getAdvanceCountNotFound()
    {
        // dd(request()->all());
        // $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        // $dateAsOf = Carbon::parse(base64_decode(request()->date))->endOfDay()->toDateTimeString();
        $date = base64_decode(request()->date);
        $date = explode(',', $date,);
        $dateStart = $date[0];

        !array_key_exists(1, $date) ? $dateEnd =  Carbon::parse($date[0])->endOfDay()->toDateTimeString() :  $dateEnd = Carbon::parse($date[1])->endOfDay()->toDateTimeString();
        request()->bu == 'ASC: MAIN' ?  $table = 'tbl_app_advnfitem_alturas' :   $table = 'tbl_app_advnfitem';

        return DB::table($table)->where([
            ['business_unit', request()->bu],
            ['department', request()->dept],
            ['section', request()->section]
        ])
            ->whereBetween('datetime_exported', [$dateStart, $dateEnd])->groupBy('barcode')->get();
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

        $date = base64_decode(request()->date);
        $date = explode(',', $date,);
        $dateStart = $date[0];

        !array_key_exists(1, $date) ? $dateEnd =  Carbon::parse($date[0])->endOfDay()->toDateTimeString() :  $dateEnd = Carbon::parse($date[1])->endOfDay()->toDateTimeString();

        $printDate = Carbon::parse($dateStart)->toFormattedDateString();
        $runDate = Carbon::parse(now())->toFormattedDateString();
        $runTime =  Carbon::parse(now())->format('h:i A');

        if ($bu == 'ASC: MAIN') {
            $table = 'tbl_app_advnfitem_alturas';
        } else {
            //MAIN sa ICM
            $table = 'tbl_app_advNfitem';
        }

        if ($bu == 'ASC: MAIN') {
            $table = $section != "SNACKBAR" ? "tbl_item_masterfile" : "tbl_item_masterfile_snackbar";
        } else if ($bu == 'ALTA CITTA') {
            $table = "tbl_item_masterfile_alta_citta";
        } else {
            $tablemasterfiles = "tbl_item_masterfile";
        }

        $result = DB::table($table)->selectRaw("
        $table.barcode,
        $table.uom, 
        $table.location_id, 
        SUM($table.qty) as total_qty,
        business_unit,
        department,
        $table.section,
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
        $tablemasterfiles.vendor_name, 
        $tablemasterfiles.group")
            ->join('tbl_app_user', 'tbl_app_user.location_id', "$table.location_id")
            ->join('tbl_app_audit', 'tbl_app_audit.location_id', "$table.location_id")
            ->leftjoin("$tablemasterfiles", "$tablemasterfiles.barcode", "$table.barcode")
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

        $result = $result->groupBy('barcode')->groupBy('location_id')->get()->groupBy(['app_user', 'audit_user', 'vendor_name', 'group'])->toArray();
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
}
