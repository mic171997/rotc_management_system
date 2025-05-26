<?php

namespace App\Imports;

use App\Models\TblItemMasterfile;
use App\Models\TblItemMasterfileMedPlus;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class ImportItemMasterfile implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    use Importable;


    public function model(array $row)
    {
        // TblItemMasterfile::truncate();

        // foreach ($rows as $row) {
        dd($row);
        // $row = $row->values();


        $values = [
            'item_code' => trim($row[0]),
            'uom' => trim($row[1]),
            'conversion_qty' => trim($row[2]),
            'barcode' => trim($row[3]),
            'variant_code' => trim($row[4]),
            'desc' => trim($row[5]),
            'extended_desc' => trim($row[6]),
            'vendor_code' => trim($row[7]),
            'vendor_name' => trim($row[8]),
            'section' => trim($row[10]),
            'group' => trim($row[12]),
            'category' => trim($row[14])
        ];

        //FOR SUPERMARKET
        // TblItemMasterfile::firstOrCreate($values, $values);
        // TblItemMasterfile::create($values);

        //FOR MEDPLUS
        // TblItemMasterfileMedPlus::create([
        //     'item_code' => trim($row[0]),
        //     'uom' => trim($row[1]),
        //     'conversion_qty' => trim($row[2]),
        //     'barcode' => trim($row[3]),
        //     'variant_code' => trim($row[4]),
        //     'desc' => trim($row[5]),
        //     'extended_desc' => trim($row[6]),
        //     'vendor_code' => trim($row[7]),
        //     'vendor_name' => trim($row[8]),
        //     'section' => trim($row[10]),
        //     'group' => trim($row[12]),
        //     'category' => trim($row[14])
        // ]);
        // }
    }

    public function batchSize(): int
    {
        return 1000;
    }
    public function chunkSize(): int
    {
        return 1000;
    }
}
