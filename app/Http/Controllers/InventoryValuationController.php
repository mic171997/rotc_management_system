<?php

namespace App\Http\Controllers;

use App\Exports\InventoryValuation;
use Carbon\Carbon;
use App\Models\TblNavCountdata;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;


class InventoryValuationController extends Controller
{
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
        $key = implode('-', [$bu, $dept, $section, $date, $user]);
        // dd(Cache::get($key), $key);
        // dd($key);

        return Cache::remember($key, now()->addMinutes(120), function () use ($bu, $dept, $section, $nav_id, $date) {
            $result = TblNavCountdata::selectRaw('
            itemcode, 
            variant_code,
            description as extended_desc,
            uom,
            qty as nav_qty,
            cost_vat, cost_no_vat, amt
            ')
                ->where([
                    ['batch_id', $nav_id],
                    ['qty', '<', 0]
                    // ['variant_code', '!=', '']
                ]);
            // ->orderBy('variant_code', 'desc');

            // $x = $result->groupByRaw('itemcode')->limit(250)->cursor();
            $x = $result
                // ->groupByRaw('itemcode')
                ->cursor();
            // $query = $x->map(function ($c) use ($date, $nav_id) {

            //     // dd($c->itemcode);
            //     $x = TblNavCountdata::selectRaw("cost_vat, cost_no_vat, amt, SUM(qty) as nav_qty")->where([
            //         ['itemcode', $c->itemcode],
            //         ['batch_id', $nav_id]
            //     ]);
            //     $nav =  $x->exists() ? (int) $x->first()->nav_qty : 0;
            //     $c->nav_qty = $nav;
            //     // $unposted = 0;
            //     // $c->unposted = $unposted;

            //     // $x->exists() ? $nav = $x->first()->nav_qty : $nav = '-';
            //     // $c->nav_qty = (int) $nav;
            //     // $unposted = $y->first()->unposted == null ? 0 : $y->first()->unposted;
            //     // $c->unposted = $unposted;

            //     // if ($y->exists()) {
            //     //     $c->unposted = $y->first()->unposted;
            //     // } else {
            //     //     $c->unposted = '-';
            //     // }

            //     return $c;
            // });
            $data['data'] = $x->all();

            return $data;
        });
    }

    public function exportResult()
    {
        return Excel::download(new InventoryValuation, 'export.xlsx');
    }
}
