<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Protection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class ConsolidateAdvActualCount implements FromView, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    use Exportable;
    public function view(): View
    {
        // dd(request()->all());
        $dateNow = Carbon::now()->toFormattedDateString();
        $timeNow = Carbon::now()->format('h:i:s A');
        $type = 'Consolidate Advance & Actual Count';
        // $date = Carbon::parse(base64_decode(request()->date))->toDateString();
        // $date2 = Carbon::parse(base64_decode(request()->date2))->toDateString();
        $date = base64_decode(request()->date);
        $date = explode(',', $date,);
        $actualStart =  $date[0] != 'null' ? str_replace("-", '/', $date[0]) : null;
        $actualEnd = $date[0] != 'null' ? str_replace("-", '/', $date[1]) : null;

        $date2 = base64_decode(request()->date2);
        $date2 = explode(',', $date2,);
        // dd($date2);
        // dd($date2[0]);
        $advStart = $date2[0] != 'null' ? str_replace("-", '/', $date2[0]) : null;
        $advEnd =   $date2[0] != 'null' ? str_replace("-", '/', $date2[1]) : null;

        // dd($actualStart, $actualEnd, $advStart, $advEnd);
        return view('reports.consolidated_report_advance_actual_count_excel', [
            'data' => session()->get('data'),
            'date' => $dateNow . ' ' . $timeNow,
            'user' => request()->user,
            'advStart' => $advStart,
            'advEnd' => $advEnd,
            'actualStart' => $actualStart,
            'actualEnd' => $actualEnd,
            'title' => $type
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $protection = $sheet->getProtection();
        $protection->setAlgorithm(Protection::ALGORITHM_SHA_512);
        $protection->setSpinCount(20000);
        $protection->setPassword('Pcount2021');
        $protection->setSheet(true);
        $protection->setSort(true);
        $protection->setInsertRows(true);
        $protection->setFormatCells(true);
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER
        ];
    }
}
