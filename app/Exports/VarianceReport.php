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

class VarianceReport implements FromView, ShouldAutoSize, WithStyles, WithColumnFormatting, WithColumnWidths
{
    use Exportable;
    public function view(): View
    {
        $data = session()->get('data');
        $dateNow = Carbon::now()->toFormattedDateString();
        $timeNow = Carbon::now()->format('h:i:s A');
        return view('reports.variance_report_excel', ['data' => $data]);
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
        // $protection->isProtectionEnabled(true);

        return [
            // Style the first row as bold text.
            // 'A' => ['font' => ['bold' => true]],

            // Styling a specific cell by coordinate.
            // 'B' => ['font' => ['bold' => true]]

            // Styling an entire column.
            // 'C'  => ['font' => ['size' => 16]],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_NUMBER_0000,
            'F' => NumberFormat::FORMAT_NUMBER_0000,
            'G' => NumberFormat::FORMAT_NUMBER_0000,
            'H' => NumberFormat::FORMAT_NUMBER_0000,
            'I' => NumberFormat::FORMAT_NUMBER_0000,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 20,
            'C' => 30,
            'D' => 30,
            'E' => 20,
            'F' => 20,
            'G' => 30,
            'H' => 30,
            'I' => 20
        ];
    }
}
