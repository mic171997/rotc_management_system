<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckDuplicateController extends Controller
{
    public function getResults()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $countType = request()->type;
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
        } else if ($bu == 'DISTRIBUTION') {
            $var = '_pdc';
        } else {
            $var = '';
        }

        $finaltable = $table . $var;

        $query = DB::table($finaltable)
            ->selectRaw("id, itemcode, barcode, description, uom, lot_number, expiry, qty, rack_desc, tbl_app_audit.name as auditor, tbl_app_user.name as storerep, datetime_exported, datetime_scanned, $finaltable.location_id as loc")
            ->join('tbl_app_audit', 'tbl_app_audit.location_id', '=', "$finaltable.location_id")
            ->join('tbl_app_user', 'tbl_app_user.location_id', '=', "$finaltable.location_id")
            ->where([
                ["$finaltable.business_unit", "$bu"],
                ["$finaltable.department", "$dept"],
                ["$finaltable.section", "$section"],
            ])
            ->whereBetween("$finaltable.datetime_exported", [$date, $dateEnd])
            ->groupBy('itemcode', 'barcode', 'uom', 'qty', 'datetime_scanned', 'loc')
            ->havingRaw('COUNT(itemcode) > 1 && COUNT(barcode) > 1 && COUNT(uom) > 1 && COUNT(lot_number) > 1 && COUNT(expiry) > 1 && COUNT(qty) > 1 && COUNT(datetime_scanned) > 1 && COUNT(loc) > 1')
            ->orderBy('datetime_scanned', 'asc')
            ->paginate(10);

        // dd($query);

        return $query;
    }

    public function getDates()
    {
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
        } else if ($bu == 'DISTRIBUTION') {
            $var = '_pdc';
        } else {
            $var = '';
        }

        return DB::table($table . $var)->selectRaw("
            DATE_FORMAT(datetime_exported, '%Y-%m-%d') as batchDate
            ")->where([
            ['business_unit', $bu],
            ['department', $dept],
            ['section', $sect]
        ])
            ->GROUPBYRAW('EXTRACT(DAY FROM datetime_exported)')->cursor();
    }

    public function updateconversionqty()
    {
        // dd(request()->text);

        $results = DB::table('tbl_app_countdata_pdc')

            ->join('tbl_item_masterfile_pdc', function ($join) {
                $join->on('tbl_item_masterfile_pdc.item_code', '=', 'tbl_app_countdata_pdc.itemcode')
                    ->on('tbl_item_masterfile_pdc.uom', '=', 'tbl_app_countdata_pdc.uom')
                    ->on('tbl_item_masterfile_pdc.barcode', '=', 'tbl_app_countdata_pdc.barcode');
            })
            ->selectRaw('tbl_app_countdata_pdc.itemcode , tbl_app_countdata_pdc.uom , tbl_app_countdata_pdc.qty , tbl_app_countdata_pdc.barcode , tbl_item_masterfile_pdc.conversion_qty, tbl_app_countdata_pdc.id ')
            // ->where('tbl_app_countdata_pdc.itemcode', '=', '260310')
            ->where('tbl_app_countdata_pdc.conversion_qty', '=', 0)
            ->get();
        // dd($results);

        foreach ($results as $item) {

            // dd($item);

            DB::table('tbl_app_countdata_pdc')
                // ->join('tbl_item_masterfile_pdc', function ($join) {
                //     $join->on('tbl_item_masterfile_pdc.item_code', '=', 'tbl_app_countdata_pdc.itemcode')
                //         ->on('tbl_item_masterfile_pdc.uom', '=', 'tbl_app_countdata_pdc.uom')
                //         ->on('tbl_item_masterfile_pdc.barcode', '=', 'tbl_app_countdata_pdc.barcode');
                // })
                ->where([
                    ['tbl_app_countdata_pdc.itemcode', $item->itemcode],
                    ['tbl_app_countdata_pdc.uom', $item->uom],
                    ['tbl_app_countdata_pdc.barcode', $item->barcode],
                    ['tbl_app_countdata_pdc.id', $item->id],
                    ['tbl_app_countdata_pdc.conversion_qty', '=', 0]


                ])
                ->update([
                    'tbl_app_countdata_pdc.conversion_qty' =>  $item->qty * $item->conversion_qty
                ]);
        }

        return response()->json(['message' => 'Success']);
    }
}
