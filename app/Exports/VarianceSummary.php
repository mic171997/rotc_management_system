<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Protection;
use Maatwebsite\Excel\Concerns\WithEvents;

class VarianceSummary implements FromView,  ShouldAutoSize, WithStyles, WithColumnFormatting, WithColumnWidths
{
    use Exportable;

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        // $data = session()->get('data');
        // dd(collect($data));
        // $resultArray = $data->map(function ($item) {
        //     // dd($item);
        //     return $item->toArray();
        // })->all();
        // dd($resultArray);
        // $dateNow = Carbon::now()->toFormattedDateString();
        // $timeNow = Carbon::now()->format('h:i:s A');
        return view('reports.variance_report_excel_summary', ['data' => $this->data]);
    }

    public function styles(Worksheet $sheet)
    {
        // $this->excelEnableProtection($sheet);
        $protection = $sheet->getProtection();
        $protection->setAlgorithm(Protection::ALGORITHM_SHA_512);
        $protection->setSpinCount(20000);
        $protection->setPassword('Pcount2021');
        $protection->setSheet(true);
        $protection->setSort(true);
        $protection->setInsertRows(true);
        $protection->setFormatCells(true);


        $lastRow = $sheet->getHighestRow();

        $sheet->getStyle('A9:A' . $lastRow)->getAlignment()
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        return [];
        // $protection->isProtectionEnabled(true);

        // return [
        //     // Style the first row as bold text.
        //     // 'A' => ['font' => ['bold' => true]],

        //     // Styling a specific cell by coordinate.
        //     // 'B' => ['font' => ['bold' => true]]

        //     // Styling an entire column.
        //     // 'C'  => ['font' => ['size' => 16]],
        // ];
    }

    // public function columnFormats(): array
    // {
    //     return [
    //         'C' => NumberFormat::FORMAT_NUMBER
    //     ];
    // }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER_000,
            'E' => NumberFormat::FORMAT_NUMBER_0000,
            'F' => NumberFormat::FORMAT_NUMBER_0000,
            'G' => NumberFormat::FORMAT_NUMBER_0000,
            'H' => NumberFormat::FORMAT_NUMBER_0000,
            'I' => NumberFormat::FORMAT_NUMBER_0000,
            'J' => NumberFormat::FORMAT_NUMBER_0000,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 45,
            'B' => 20,
            'C' => 45,
            'D' => 20,
            'E' => 20,
            'F' => 20,
            'H' => 20,
            'I' => 20,
            'J' => 30,

        ];
    }
    // public function styles(Worksheet $sheet)
    // {


    // }
}
