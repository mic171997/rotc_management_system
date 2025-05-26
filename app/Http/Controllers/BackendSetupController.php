<?php

namespace App\Http\Controllers;

use App\Models\TblLocation;
use App\Models\TblNavCount;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\TblQuestionableItem;

class BackendSetupController extends Controller
{
    public function getResults()
    {
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $date = Carbon::parse(base64_decode(request()->date))->toDateString();
        // dd(request()->all(), $date);
        return TblQuestionableItem::selectRaw('tbl_questionable_items.*, users.name')
            ->where([
                ['tbl_questionable_items.business_unit', $bu],
                ['tbl_questionable_items.department', $dept],
                ['tbl_questionable_items.section', $section],
                ['count_date', $date]
            ])
            ->join('users', 'users.id', 'tbl_questionable_items.user_id')
            ->get()->toArray();
    }

    public function getCountDate()
    {
        // dd(request()->all());
        $bu = request()->bu;
        $dept = request()->dept;
        $sect = request()->sect;
        $countType = request()->type;

        $countType == 'ACTUAL' ? $table = 'tbl_app_countdata' :  $table = 'tbl_advance_count';

        if ($bu == 'ASC: MAIN') {
            $var = '_alturas';
        } else if ($bu == 'PLAZA MARCELA') {
            $var = '_pm';
        } else if ($bu == 'ALTURAS TALIBON') {
            $var = '_talibon';
        } else if ($bu == 'ALTA CITTA') {
            $var = '_alta';
        } else {
            $var = '';
        }

        if (request()->type != 'MONITORING') {
            return DB::table($table . $var)->selectRaw("
                DATE_FORMAT(datetime_exported, '%Y-%m-%d') as batchDate
                ")->where([
                ['business_unit', $bu],
                ['department', $dept],
                ['section', $sect]
            ])
                ->GROUPBYRAW('EXTRACT(DAY FROM datetime_exported)')->cursor();
        } else {
            return TblNavCount::groupByRaw('batchDate')
                ->get();
        }

        // CONCAT(DATE_FORMAT(MIN(date_field), '%Y-%m-%d'), ' - ', DATE_FORMAT(MAX(date_field), '%Y-%m-%d')) AS date_range

        // SELECT DATE(date_field) AS date, COUNT(*) AS count
        // FROM my_table
        // WHERE date_field BETWEEN '2022-01-01' AND '2022-12-31'
        // GROUP BY DATE(date_field);
    }

    public function questionableItemsDateSetup()
    {
        $bu = request()->bu;
        $dept = request()->dept;
        $sect = request()->sect;
        $report = request()->report;

        // dd($report);
        if (!$report) {
            return TblQuestionableItem::selectRaw("
                DATE_FORMAT(count_date, '%Y-%m-%d') as batchDate
                ")->where([
                ['business_unit', $bu],
                ['department', $dept],
                ['section', $sect]
            ])
                ->GROUPBYRAW('EXTRACT(DAY FROM count_date)')->cursor();
        } else {
            // dd('consolidated');
            $query = TblQuestionableItem::selectRaw("
                DATE_FORMAT(count_date, '%Y-%m-%d') as batchDate
                ");

            if ($bu != "PLAZA MARCELA" && $bu != "ALTA CITTA" && $bu != "ALTURAS TALIBON") {
                $query = $query->where([
                    ['business_unit', $bu],
                    ['department', $dept],
                    ['section', $sect]
                ]);
            } elseif ($bu == "ALTURAS TALIBON" && $sect == "Centralize") {
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
            return $query->GROUPBYRAW('EXTRACT(DAY FROM count_date)')->cursor();
        }
    }

    public function notFoundCountDate()
    {
        // dd(request()->all());
        $bu = request()->bu;
        $dept = request()->dept;
        $sect = request()->sect;
        $countType = request()->type;

        $countType == 'ACTUAL' ? $table = 'tbl_app_nfitem' :  $table = 'tbl_app_advnfitem';

        // if ($bu == 'ASC: MAIN') {
        //     $var = '_alturas';
        // }
        // else if ($bu == 'PLAZA MARCELA') {
        //     $var = '_pm';
        // } else if ($bu == 'ALTURAS TALIBON') {
        //     $var = '_talibon';
        // } else if ($bu == 'ALTA CITTA') {
        //     $var = '_alta';
        // }
        // else {
        //     $var = '';
        // }

        return DB::table($table)->selectRaw("
                DATE_FORMAT(datetime_exported, '%Y-%m-%d') as batchDate
                ")->where([
            ['business_unit', $bu],
            ['department', $dept],
            ['section', $sect]
        ])
            ->GROUPBYRAW('EXTRACT(DAY FROM datetime_exported)')->cursor();
    }
}
