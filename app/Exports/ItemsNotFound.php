<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;

class ItemsNotFound implements FromView, ShouldAutoSize, WithEvents, WithColumnFormatting
{
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
        $data = session()->get('data');
        $table = null;
        $i = 0;
        $data['data'] = collect($data['data'])->map(function ($trans)  use (&$i) {
            // dd($trans->all());
            $res = array_map(function ($x) use (&$i) {
                return array_map(function ($y) use (&$i) {
                    return array_map(function ($z) use (&$i) {
                        $newArr = [];
                        $test = 'what';
                        $test2 = 'audit';
                        foreach ($z as $index => $xyz) {
                            if ($index === 0) {
                                $i++;
                                $xyz = (array) $xyz;
                                // dd($xyz['app_user_sign']);
                                $app_user = $xyz['app_user_sign'];
                                $audit_user = $xyz['audit_user_sign'];

                                $image1_data = "data:image/png;base64,$app_user";
                                $image2_data = "data:image/png;base64,$audit_user";

                                $image1_parts = explode(";base64,", $image1_data);
                                $image1_base64 = base64_decode($image1_parts[1]);

                                // Create a transparent image from the decoded data
                                $image1 = imagecreatefromstring($image1_base64);
                                imagesavealpha($image1, true);
                                $transparent = imagecolorallocatealpha($image1, 0, 0, 0, 127);
                                imagecolortransparent($image1, $transparent);
                                imagefill($image1, 0, 0, $transparent);

                                // Create the directory if it doesn't exist
                                $directory = 'temp';
                                if (!file_exists($directory)) {
                                    mkdir($directory, 0777, true);
                                }
                                $filename = "$test-$i.png";
                                imagepng($image1, $directory . '/' . $filename);

                                $image_url1 = $directory . '/' . $filename;

                                imagedestroy($image1);
                                $xyz['user_signature'] = $image_url1;
                                // $xyz['audit_signature'] = base64_decode($res->audit_signature);

                                $image2_parts = explode(";base64,", $image2_data);
                                $image2_base64 = base64_decode($image2_parts[1]);
                                $image2 = imagecreatefromstring($image2_base64);
                                imagesavealpha($image2, true);
                                $transparent2 = imagecolorallocatealpha($image2, 0, 0, 0, 127);
                                imagefill($image2, 0, 0, $transparent2);
                                $filename2 = "$test2-$i.png";
                                imagepng($image2, $directory . '/' . $filename2);

                                imagedestroy($image2);
                                $image_url2 = $directory . '/' . $filename2;
                                $xyz['audit_signature'] = $image_url2;

                                // Add an extra flag to prevent the image from being deleted
                                $xyz['prevent_image_deletion'] = true;

                                $newArr[] = $xyz;
                            } else {
                                $newArr[] = (array) $xyz;
                            }
                        }
                        return $newArr;
                        // return array_map(function ($w) use ($table) {

                        // }, $z);
                    }, $y);
                }, $x);
            }, $trans);

            return $res;
        })->all();
        // dd($data);
        $countType = request()->type;

        return $data['type'] == 'Excel' ?  view('reports.pcount_app_notfound_excel', ['data' => $data, 'title' => $countType . ' COUNT (APP)']) : view('reports.pcount_app_notfound', ['data' => $data]);
        // return view('reports.pcount_app_notfound_excel', ['data' => session()->get('data')]);
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER
        ];
    }
    // public function columnWidths(): array
    // {
    //     return [
    //         'A' => 20,
    //         'B' => 30,
    //         'C' => 20,
    //         'D' => 30,
    //         'E' => 20
    //     ];
    // }
}
