<?php

namespace App\Http\Controllers;

use App\Models\BusinessUnit;
use App\Models\department;
use App\Models\section;
use App\Models\TblLocation;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getResults()
    {
        return TblLocation::paginate(10);
    }

    public function toggleStatus()
    {
        dd(request()->all());
    }

    public function getBU()
    {
        // dd(request()->all());
        return BusinessUnit::where([
            ['company_code', '02'],
            ['acroname', 'ASCMAIN']
        ])->orWhere([
            ['company_code', '02'],
            ['acroname', 'ASC TAL']
        ])->orWhere([
            ['company_code', '02'],
            ['acroname', 'ICM']
        ])->orWhere([
            ['company_code', '02'],
            ['acroname', 'ASC TUBIGON']
        ])->orWhere([
            ['company_code', '02'],
            ['acroname', 'ALTA CITTA']
        ])->orWhere([
            ['company_code', '03'],
            ['acroname', 'PM']
        ])->orWhere([
            ['company_code', '03'],
            ['acroname', 'PM']
        ])->get();
        // $get = BusinessUnit::all();
        // return $get;
    }

    public function getDept()
    {
        if (!request()->code) {
            return department::where([
                ['bunit_code', request()->bu],
                ['status', 'active']
            ])->get();
        } else {
            return department::where([
                ['company_code', request()->code],
                ['bunit_code', request()->bu],
                ['status', '=', 'active']
            ])->whereIn('dept_name', ['SUPERMARKET', 'PHARMACY DISTRIBUTION CENTER'])
                ->get();
        }
    }

    public function getSection()
    {
        if (!request()->code) {
            return section::where(
                [
                    ['bunit_code', request()->bu],
                    ['dept_code', request()->dept],
                ]
            )
                ->whereIn('section_name', ['SELLING AREA', 'STOCK ROOM', 'SNACKBAR', 'WAREHOUSE', 'OLD WAREHOUSE', 'NEW WAREHOUSE'])
                ->get();
        } else {
            return section::where(
                [
                    ['company_code', request()->code],
                    ['bunit_code', request()->bu],
                    ['dept_code', request()->dept],
                ]
            )
                ->whereIn('section_name', ['SELLING AREA', 'STOCK ROOM', 'SNACKBAR', 'WAREHOUSE', 'OLD WAREHOUSE', 'NEW WAREHOUSE'])
                ->get();
        }
    }
}
