<?php

namespace App\Exports;

use Illuminate\Support\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Protection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;

class QuestionableItems implements FromView, WithStyles, ShouldAutoSize, WithEvents, WithColumnFormatting, WithColumnWidths
{
    // lass VarianceNav implements WithHeadings, FromCollection, WithStyles, ShouldAutoSize, WithColumnFormatting
    use Exportable, RegistersEventListeners;

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getProtection()->setPassword('Pcount2021');
                $event->sheet->getProtection()->setSheet(true);
                $event->sheet->getProtection()->setSort(true);
                $event->sheet->getProtection()->setInsertRows(true);
                $event->sheet->getProtection()->setInsertHyperlinks(true);
                $event->sheet->getProtection()->setFormatCells(true);
                $event->sheet->getProtection()->setFormatColumns(true);
                $event->sheet->getProtection()->setFormatRows(true);
                $event->sheet->getProtection()->setObjects(true);
                $event->sheet->getProtection()->setScenarios(true);
                $event->sheet->getProtection()->setDeleteColumns(false);
                $event->sheet->getProtection()->setDeleteRows(false);
            }
        ];
    }

    public function view(): View
    {
        $data = request()->data;
        $date = Carbon::parse(base64_decode(request()->date))->toDateString();
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $countType = request()->countType;
        $runDate = Carbon::parse(now())->toFormattedDateString();
        $runTime =  Carbon::parse(now())->format('h:i A');

        $data = collect($data)->map(function ($use) {
            // dump($use);
            // dd($use);
            return $use;
        });
        // dd($data);
        $header = array(
            'bu'            => $bu,
            'dept'          => $dept,
            'section'       => $section,
            'countType'     => $countType,
            'user'          => auth()->user()->name,
            'user_position' => auth()->user()->position,
            'runDate'       => $runDate,
            'runTime'       => $runTime,
            'date'          => $date,
            'data'          => $data
        );

        return view('reports.questionable_items_excel', [
            'data' => $header
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
            'B' => NumberFormat::FORMAT_NUMBER,
            'E' => '0.00',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 20,
            'C' => 100,
            'D' => 20,
            'E' => 15,
            'F' => 20,
            'G' => 25
        ];
    }
}
