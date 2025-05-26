<?php

namespace App\Exports;

use App\Models\TblItemMasterfile;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Protection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class InventoryValuation implements WithHeadings, FromCollection, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    use Exportable;

    public function headings(): array
    {
        return [
            'Journal Template Name',
            'Journal Batch Name',
            'Line No.',
            'Item No.',
            'Variant Code',
            'Posting Date',
            'Entry Type',
            'Document No.',
            'Description',
            'Location Code',
            'Inventory Posting Group',
            'Quantity',
            'Invoiced Quantity',
            'Unit Amount',
            'Unit Cost',
            'Amount',
            'Source Code',
            'Company Code',
            'Department Code',
            'Reason Code',
            'Gen. Prod. Posting Group',
            'Document Date',
            'External Document No.',
            'Qty. per Unit of Measure',
            'Unit of Measure Code',
            'Quantity (Base)',
            'Invoiced Qty. (Base)',
            'Value Entry Type',
            'Item Division'
        ];
    }

    public function collection()
    {
        $export = json_decode(utf8_encode(base64_decode(request()->export)));
        $test = null;
        // dd(request()->all());
        // $export = collect($export)->map(function ($trans) use ($test) {
        //     dd($trans);
        // });
        // $export = collect($export);

        $export = collect($export)->map(function ($trans) use ($test) {
            $postingDate = date_format(date_create($trans->postingDate), "m/d/Y");
            $docDate = date_format(date_create($trans->postingDate), "m/d/Y");
            // dd($postingDate, $docDate);

            $trans->invQty == 0 ? $trans->invQty = '0' : null;
            $trans->qty == 0 ? $trans->qty = '0' : null;
            $trans->qtyBase == 0 ? $trans->qtyBase = '0' : null;
            $trans->invQtyBase == 0 ? $trans->invQtyBase = '0' : null;
            // $trans->unitAmt == 0 ? $trans->unitAmt = 0.00 : null;
            $trans->docNo = 'PC-10000101';
            $trans->postingDate = $postingDate;
            $trans->docDate = $docDate;
            $trans->valueEntry = 'Direct Cost';
            $trans->reasonCode = 'ADJ_PCOUNT';
            $trans->itemDiv = '1';

            $item = TblItemMasterfile::selectRaw('conversion_qty')->where([
                ['item_code', $trans->itemCode],
                ['uom', $trans->uom]
            ]);
            $item->exists() ? $trans->qtyPerUom = $item->first()->conversion_qty :  $trans->qtyPerUom = 1;
            return $trans;
        });

        return $export;
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
        // return [
        //     'E' => NumberFormat::FORMAT_NUMBER
        // ];
        return [
            'L' => NumberFormat::FORMAT_NUMBER_0000,
            'M' => NumberFormat::FORMAT_NUMBER_0000,
            'N' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'O' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'P' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'Z' => NumberFormat::FORMAT_NUMBER_0000,
            'AA' => NumberFormat::FORMAT_NUMBER_0000,

        ];
    }
}
