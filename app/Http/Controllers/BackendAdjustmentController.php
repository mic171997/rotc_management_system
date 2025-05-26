<?php

namespace App\Http\Controllers;

use App\Models\TblBackendAdjustment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BackendAdjustmentController extends Controller
{
    public function getResults()
    {
        // dd(request()->all());
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $company = auth()->user()->company;
        $user = auth()->user()->id;
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $countType = request()->type;
        $search = request()->barcode;
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        $dateEnd = Carbon::parse(base64_decode(request()->date))->endOfDay()->toDateTimeString();

        $countType == 'ACTUAL' ? $table = 'tbl_app_countdata' : $table = 'tbl_advance_count';
        if ($bu == 'ASC: MAIN') {
            $var = $section == "SNACKBAR" ? "_snackbar" :  '_alturas';
        } else if ($bu == 'PLAZA MARCELA') {
            $var = '_pm';
            $sect = null;
        } else if ($bu == 'ALTURAS TALIBON') {
            $var = '_talibon';
        } else if ($bu == 'ALTA CITTA') {
            $var = '_alta';
        } else {
            $var = '';
        }

        // dd($table . $var);

        $finaltable = $table . $var;
        // dd($finaltable, $bu, $dept, $section, $date, $dateEnd, $search);
        // dd($date, $dateEnd);

        $query = DB::table($finaltable)->selectRaw("id,itemcode,barcode,description,uom,qty,rack_desc,user_signature,audit_signature,tbl_app_audit.name as auditor , tbl_app_user.name as storerep ,datetime_exported ")
            ->join('tbl_app_audit', 'tbl_app_audit.location_id', '=', "$finaltable.location_id")
            ->join('tbl_app_user', 'tbl_app_user.location_id', '=', "$finaltable.location_id")
            ->where([
                ["$finaltable.business_unit", "$bu"],
                ["$finaltable.department", "$dept"],
                ["$finaltable.section", "$section"],
                // ["$finaltable.barcode", "$search"]
                // ["$finaltable.barcode", 'LIKE', "$search%"]
            ])
            ->where(function ($result) use ($search, $finaltable) {
                $result
                    ->where("$finaltable.barcode", 'like', "%$search%")
                    ->orWhere("$finaltable.itemcode", 'like', "%$search%")
                    ->orWhere("$finaltable.desc", 'like', "%$search%")
                    ->orWhere("$finaltable.uom", 'like', "%$search%")
                    ->orWhere("$finaltable.description", 'like', "%$search%")
                    ->orWhere("$finaltable.rack_desc", 'like', "%$search%");
            })
            ->whereBetween("$finaltable.datetime_exported", [$date, $dateEnd])
            // ->limit(500)
            ->paginate(10);

        // dd($query);

        return $query;
    }

    public function getforinput()
    {
        // dd(request()->all());
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $bu = request()->bu;
        $section = request()->section;
        $search = request()->barcode;


        if ($bu == 'ASC: MAIN' && $section == 'SNACKBAR') {
            $table = 'tbl_item_masterfile_snackbar';
        } else if ($bu == 'ALTA CITTA') {
            $table = 'tbl_item_masterfile_alta_citta';
        } else {
            $table = 'tbl_item_masterfile';
        }

        // dd($table);


        // dd($finaltable, $bu, $dept, $section, $date, $dateEnd, $search);
        // dd($date, $dateEnd);

        $query = DB::table($table)->selectRaw('id,item_code,uom,barcode,variant_code,`desc`,extended_desc,section')
            // ->where("$table.barcode", 'LIKE', "$search%")
            ->where(function ($result) use ($search, $table) {
                $result
                    ->where("$table.barcode", 'like', "%$search%")
                    ->orWhere("$table.item_code", 'like', "%$search%")
                    ->orWhere("$table.desc", 'like', "%$search%")
                    ->orWhere("$table.uom", 'like', "%$search%")
                    ->orWhere("$table.extended_desc", 'like', "%$search%");
            })
            // ->limit(10)
            ->paginate(10);
        // dd($query);
        return $query;
    }


    public function getinputedit()
    {

        // dd(request()->all());
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $bu = request()->bu;
        $section = request()->section;
        $search = request()->barcode;
        $id = request()->id;


        if ($bu == 'ASC: MAIN' && $section == 'SNACKBAR') {
            $table = 'tbl_item_masterfile_snackbar';
        } else if ($bu == 'ALTA CITTA') {
            $table = 'tbl_item_masterfile_alta_citta';
        } else {
            $table = 'tbl_item_masterfile';
        }

        $query = DB::table($table)->selectRaw('id,item_code,uom,barcode,variant_code,`desc`,extended_desc,section,conversion_qty')
            ->where([
                ["$table.id", "$id"],
            ])
            ->get();

        // dd($query);

        return $query;
    }

    public function getResultshistory()
    {
        // dd(request()->all());
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $company = auth()->user()->company;
        $user = auth()->user()->id;
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $countType = request()->type;
        $search = request()->barcode;
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        $dateEnd = Carbon::parse(base64_decode(request()->date))->endOfDay()->toDateTimeString();


        $query = TblBackendAdjustment::selectRaw("b_id,id,itemcode,barcode,description,uom,qty,rack_desc,user_signature,audit_signature,tbl_app_audit.name as auditor , tbl_app_user.name as storerep ,reason,status ")
            ->join('tbl_app_audit', 'tbl_app_audit.location_id', '=', "tbl_backend_adjustment.location_id")
            ->join('tbl_app_user', 'tbl_app_user.location_id', '=', "tbl_backend_adjustment.location_id")
            ->where([
                ["tbl_backend_adjustment.business_unit", "$bu"],
                ["tbl_backend_adjustment.department", "$dept"],
                ["tbl_backend_adjustment.section", "$section"],
                // ["$finaltable.barcode", "$search"]
                // ["tbl_backend_adjustment.barcode", 'LIKE', "$search%"]
            ])
            ->where(function ($result) use ($search) {
                $result
                    ->where("tbl_backend_adjustment.barcode", 'like', "%$search%")
                    ->orWhere("tbl_backend_adjustment.itemcode", 'like', "%$search%")
                    ->orWhere("tbl_backend_adjustment.desc", 'like', "%$search%")
                    ->orWhere("tbl_backend_adjustment.uom", 'like', "%$search%")
                    ->orWhere("tbl_backend_adjustment.description", 'like', "%$search%")
                    ->orWhere("tbl_backend_adjustment.rack_desc", 'like', "%$search%");
            })

            ->whereBetween("tbl_backend_adjustment.datetime_exported", [$date, $dateEnd])
            // ->limit(500)
            ->paginate(10);

        // dd($query);

        return $query;
    }

    public function getResultload()
    {
        // dd(request()->all());
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $company = auth()->user()->company;
        $user = auth()->user()->id;
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $countType = request()->type;
        $search = request()->barcode;
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        $dateEnd = Carbon::parse(base64_decode(request()->date))->endOfDay()->toDateTimeString();


        $query = TblBackendAdjustment::selectRaw("b_id,id,itemcode,barcode,description,uom,qty,rack_desc,user_signature,audit_signature,tbl_app_audit.name as auditor , tbl_app_user.name as storerep ,reason,status ")
            ->join('tbl_app_audit', 'tbl_app_audit.location_id', '=', "tbl_backend_adjustment.location_id")
            ->join('tbl_app_user', 'tbl_app_user.location_id', '=', "tbl_backend_adjustment.location_id")
            ->where([
                ["tbl_backend_adjustment.business_unit", "$bu"],
                ["tbl_backend_adjustment.department", "$dept"],
                ["tbl_backend_adjustment.section", "$section"],
                // ["$finaltable.barcode", "$search"]
                ["tbl_backend_adjustment.barcode", 'LIKE', "$search%"]
            ])
            ->whereBetween("tbl_backend_adjustment.datetime_exported", [$date, $dateEnd])
            // ->limit(500)
            ->paginate(10);

        // dd($query);

        return $query;
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
        } else if ($bu == 'ALTURAS TALIBON') {
            $var = '_talibon';
        } else if ($bu == 'ALTA CITTA') {
            $var = '_alta';
        } else {
            $var = '';
        }
        // dd($table . $var);

        return DB::table($table . $var)->selectRaw("
            DATE_FORMAT(datetime_exported, '%Y-%m-%d') as batchDate
            ")->where([
            ['business_unit', $bu],
            ['department', $dept],
            ['section', $sect]
        ])
            ->GROUPBYRAW('EXTRACT(DAY FROM datetime_exported)')->cursor();
    }

    public function getracklocation()
    {
        // dd(request()->all());
        $comp = request()->comp;
        $bu = request()->bu;
        $dept = request()->dept;
        $sect = request()->sect;
        $countType = request()->countType;


        $query = DB::table('tbl_location')->selectRaw("
           rack_desc
            ")
            ->join('tbl_nav_count', 'tbl_nav_count.location_id', '=', "tbl_location.location_id")
            ->where([
                ['company', $comp],
                ['business_unit', $bu],
                ['department', $dept],
                ['section', $sect],
                ['countType', $countType]
            ])
            ->groupBy('rack_desc')
            ->get();

        // dd($query->toArray());
        // return $query;
        $resultArray = $query->pluck('rack_desc')->toArray();

        return $resultArray;
    }

    public function getaudit()
    {
        // dd(request()->all());
        $comp = request()->comp;
        $bu = request()->bu;
        $dept = request()->dept;
        $sect = request()->sect;
        $countType = request()->countType;
        $location = request()->location;


        $query = DB::table('tbl_app_audit')->selectRaw("
        id, tbl_app_audit.name as audit, tbl_app_user.name as storerep
            ")
            ->join('tbl_location', 'tbl_location.location_id', '=', "tbl_app_audit.location_id")
            ->join('tbl_nav_count', 'tbl_nav_count.location_id', '=', "tbl_app_audit.location_id")
            ->join('tbl_app_user', 'tbl_app_user.location_id', '=', "tbl_app_audit.location_id")

            ->where([
                ['tbl_location.company', $comp],
                ['tbl_location.business_unit', $bu],
                ['tbl_location.department', $dept],
                ['tbl_location.section', $sect],
                ['tbl_nav_count.countType', $countType],
                ['tbl_location.rack_desc', $location]
            ])

            ->get();

        // $resultArray = $query;
        //  dd($resultArray);
        return  $query;
        // $result = [];
        // foreach ($query as $item) {
        //     $result[] = (array) $item;
        // }
        // // dd($result);
        // return $result;
        // $query->toArray();

        // return $query;
    }
    public function getuser()
    {
        // dd(request()->all());
        $comp = request()->comp;
        $bu = request()->bu;
        $dept = request()->dept;
        $sect = request()->sect;
        $countType = request()->countType;
        $location = request()->location;
        $iad = request()->iad;


        $query = DB::table('tbl_app_user')->selectRaw("
        id,tbl_app_user.name as storerep
            ")
            ->join('tbl_location', 'tbl_location.location_id', '=', "tbl_app_user.location_id")
            ->join('tbl_nav_count', 'tbl_nav_count.location_id', '=', "tbl_app_user.location_id")
            ->join('tbl_app_audit', 'tbl_app_audit.location_id', '=', "tbl_app_user.location_id")

            ->where([
                ['tbl_location.company', $comp],
                ['tbl_location.business_unit', $bu],
                ['tbl_location.department', $dept],
                ['tbl_location.section', $sect],
                ['tbl_nav_count.countType', $countType],
                ['tbl_location.rack_desc', $location],
                ['tbl_app_audit.name', $iad]
            ])

            ->get();


        return  $query;
    }

    public function getinfo()
    {

        // dd(request()->all());

        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $countType = request()->type;
        $id = request()->id;

        $countType == 'ACTUAL' ? $table = 'tbl_app_countdata' : $table = 'tbl_advance_count';
        if ($bu == 'ASC: MAIN') {
            $var = $section == "SNACKBAR" ? "_snackbar" :  '_alturas';
        } else if ($bu == 'PLAZA MARCELA') {
            $var = '_pm';
            $sect = null;
        } else if ($bu == 'ALTURAS TALIBON') {
            $var = '_talibon';
        } else if ($bu == 'ALTA CITTA') {
            $var = '_alta';
        } else {
            $var = '';
        }
        $finaltable = $table . $var;

        //     dd($finaltable);

        $query = DB::table($finaltable)->selectRaw("id,itemcode,barcode,description,uom,qty,conversion_qty, $finaltable.location_id,rack_desc,business_unit,department,section,empno,datetime_scanned,datetime_saved,date_expiry,user_signature,audit_signature,tbl_app_audit.name as auditor , tbl_app_user.name as storerep ,datetime_exported")
            ->join('tbl_app_audit', 'tbl_app_audit.location_id', '=', "$finaltable.location_id")
            ->join('tbl_app_user', 'tbl_app_user.location_id', '=', "$finaltable.location_id")
            ->where([
                ["$finaltable.id", "$id"],
            ])

            ->get();

        // dd($query);

        return $query;
    }

    public function getinfohistory()
    {

        // dd(request()->all());

        $id = request()->id;
        // $table = request()->table;

        $query = TblBackendAdjustment::selectRaw("b_id,id,itemcode,barcode,description,uom,qty,conversion_qty,tbl_backend_adjustment.location_id,rack_desc,business_unit,department,section,empno,datetime_scanned,datetime_saved,date_expiry,user_signature,audit_signature,tbl_app_audit.name as auditor , tbl_app_user.name as storerep ,datetime_exported,reason,status ")
            ->join('tbl_app_audit', 'tbl_app_audit.location_id', '=', "tbl_backend_adjustment.location_id")
            ->join('tbl_app_user', 'tbl_app_user.location_id', '=', "tbl_backend_adjustment.location_id")
            ->where([
                ["tbl_backend_adjustment.b_id", "$id"],
            ])

            ->get();

        // dd($query);

        return $query;
    }
    public function getdeleteinfo()
    {

        // dd(request()->all());
        $button = request()->button;
        $cqty = request()->c_qty;
        $qty = request()->qty;
        $newqty = request()->newqty;
        $newuom = request()->newuom;
        $user = auth()->user()->id;
        $id = request()->data;
        $reason = request()->reason;
        // $status = request()->reason;
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->sect;
        $countType = request()->countType;

        $countType == 'ACTUAL' ? $table = 'tbl_app_countdata' : $table = 'tbl_advance_count';
        if ($bu == 'ASC: MAIN') {
            $var = $section == "SNACKBAR" ? "_snackbar" :  '_alturas';
        } else if ($bu == 'PLAZA MARCELA') {
            $var = '_pm';
            $sect = null;
        } else if ($bu == 'ALTURAS TALIBON') {
            $var = '_talibon';
        } else if ($bu == 'ALTA CITTA') {
            $var = '_alta';
        } else {
            $var = '';
        }
        $finaltable = $table . $var;
        // dd($finaltable);
        if ($button == 'delete') {

            $query = DB::table($finaltable)->find($id);
            // dd($query);
            if ($query) {
                $resultArray = (array) $query;

                $resultArray['reason'] = $reason;
                $resultArray['user_id'] = $user;
                $resultArray['status'] = $finaltable;
                $resultArray['counttype'] = $countType;
                // dd($resultArray);
                TblBackendAdjustment::insert($resultArray);

                DB::table($finaltable)->where('id', $id)->delete();
            }
        } else {

            $newcqty = $cqty /  $qty;
            $finalcqty = $newqty * $newcqty;

            DB::table($finaltable)
                ->where('id', $id)
                ->update([
                    'qty' => $newqty,
                    'conversion_qty' =>  $finalcqty,
                    // Add more columns and values to update as needed
                ]);
        }
    }

    public function getdeleteinfohistory()
    {

        // dd(request()->all());
        $b_id = request()->data;
        $table = request()->table;
        $excludedColumns = ['b_id', 'reason', 'user_id', 'status', 'counttype', 'date_executed'];
        $query = DB::table('tbl_backend_adjustment')
            ->select(array_diff(DB::getSchemaBuilder()->getColumnListing('tbl_backend_adjustment'), $excludedColumns))
            ->where('b_id', $b_id)
            ->get();

        // dd($query);

        foreach ($query as $row) {
            DB::table($table)->insert((array) $row);
        }


        // TblBackendAdjustment::insert($resultArray);

        DB::table('tbl_backend_adjustment')->where('b_id', $b_id)->delete();
    }
    public function save()
    {
        // dd(request()->all());


        $itemcode = request()->itemcode;
        $barcode = request()->barcode;
        $c_qty = request()->c_qty;
        $desc = request()->desc;
        $ex_desc = request()->ex_desc;
        $qty = request()->qty;
        $countType = request()->countType;
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->sect;
        $location = request()->location;
        $iad = request()->iad;
        $storerep = request()->storerep;
        $userid = request()->userid;
        $uom = request()->uom;
        // $date = base64_decode(request()->date);
        // $finaldate = Carbon::parse($date)->endOfDay()->toDateTimeString();


        $countType == 'ACTUAL' ? $table = 'tbl_app_countdata' : $table = 'tbl_advance_count';
        if ($bu == 'ASC: MAIN') {
            $var = $section == "SNACKBAR" ? "_snackbar" :  '_alturas';
        } else if ($bu == 'PLAZA MARCELA') {
            $var = '_pm';
            $sect = null;
        } else if ($bu == 'ALTURAS TALIBON') {
            $var = '_talibon';
        } else if ($bu == 'ALTA CITTA') {
            $var = '_alta';
        } else {
            $var = '';
        }
        $finaltable = $table . $var;
        // dd($finaltable);

        $query = DB::table($finaltable)->selectRaw("
      user_signature,audit_signature,$finaltable.location_id,$finaltable.empno,datetime_scanned,datetime_saved,datetime_exported,date_expiry")
            ->where([
                ["$finaltable.location_id", $userid]
            ])
            ->groupby("$finaltable.location_id")
            ->get();

        // dd($query);
        // return  $query;
        foreach ($query as  $resultArray) {

            $resultArray->itemcode = $itemcode;
            $resultArray->barcode = $barcode;
            $resultArray->desc = $desc;
            $resultArray->description = $ex_desc;
            $resultArray->uom = $uom;
            $resultArray->qty = $qty;
            $resultArray->conversion_qty = $qty * $c_qty;
            $resultArray->business_unit = $bu;
            $resultArray->department = $dept;
            $resultArray->section = $section;
            $resultArray->rack_desc = $location;

            // dd($resultArray);
            DB::table($finaltable)->insert((array)$resultArray);
        }
    }
}
