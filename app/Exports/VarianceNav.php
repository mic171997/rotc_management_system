<?php

namespace App\Exports;

use App\Models\TblItemMasterfile;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Protection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;

class VarianceNav implements WithHeadings, FromCollection, WithStyles, ShouldAutoSize, WithColumnFormatting
{
    use Exportable;

    public function headings(): array
    {
        if (request()->bu === "ALTA CITTA") {
            return [
                'Journal Template Name',
                'Line No.',
                'Item No.',
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
                'Shortcut Dimension 1 Code',
                'Shortcut Dimension 2 Code',
                'Journal Batch Name',
                'Reason Code',
                'Gen. Prod. Posting Group',
                'Document Date',
                'External Document No.',
                'Variant Code',
                'Qty. per Unit of Measure',
                'Unit of Measure Code',
                'Quantity (Base)',
                'Invoiced Qty. (Base)',
                'Value Entry Type',
                'Division',

            ];
        } else {
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
    }

    public function collection()
    {
        // $export = json_decode(utf8_encode(base64_decode(request()->export)));
        $export = json_decode(request()->export);
        $test = null;

        $export = collect($export)->map(function ($trans) use ($test) {
            $postingDate = date_format(date_create($trans->postingDate), "m/d/Y");
            $docDate = date_format(date_create($trans->postingDate), "m/d/Y");
            $trans->invQty == 0 ? $trans->invQty = '0' : null;
            $trans->variance == 0 ? $trans->variance = '0' : null;
            $trans->qtyBase == 0 ? $trans->qtyBase = '0' : null;
            $trans->invQtyBase == 0 ? $trans->invQtyBase = '0' : null;
            // $trans->unitAmt == 0 ? $trans->unitAmt = 0.00 : null;
            $trans->docNo = 'PC-' . $trans->docNo;
            $trans->postingDate = $postingDate;
            $trans->docDate = $docDate;
            $trans->valueEntry = 'Direct Cost';
            $trans->reasonCode = 'ADJ_PCOUNT';
            // $trans->itemDiv = request()->bu == 'ALTA CITTA' ? '2' : '1';
            $masterfile = 'tbl_item_masterfile';
            $bu = request()->bu;
            $section = request()->section;

            if ($bu == 'ASC: MAIN') {
                $var = $section == "SNACKBAR" ? "_snackbar" :  '_alturas';
                $masterfile = $section == "SNACKBAR" ? "tbl_item_masterfile_snackbar" :  'tbl_item_masterfile';
            } else if ($bu == 'ALTA CITTA') {
                $var = '_alta';
                $masterfile = "tbl_item_masterfile_alta_citta";
            } else if ($bu == 'DISTRIBUTION') {
                $var = '_pdc';
                $masterfile = "tbl_item_masterfile_pdc";
            } else {
                $masterfile = 'tbl_item_masterfile';
            }
    
           $itemdiv = DB::table($masterfile)->select('item_division')->where([
                ['item_code', $trans->itemCode],
            ])->groupBy('item_division')
            ->value('item_division');
            
            $trans->itemDiv =  $itemdiv !== null ? $itemdiv : '';

            $item = DB::table($masterfile)->selectRaw('conversion_qty')->where([
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

        if (request()->bu === "ALTA CITTA") {

            return [
                'J' => NumberFormat::FORMAT_NUMBER_0000,
                'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED4,
                'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED4,
                'M' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED4,
                'N' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED4,
                'Z' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED4,
                'AA' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED4,

            ];
        } else {

            return [
                'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED4,
                'M' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED4,
                'N' =>  NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED4,
                'O' =>  NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED4,
                'P' =>  NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED4,
                'Z' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED4,
                'AA' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED4,

            ];
        }
    }
}
