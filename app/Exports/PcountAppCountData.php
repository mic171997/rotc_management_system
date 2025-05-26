<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Protection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;

class PcountAppCountData implements FromView, ShouldAutoSize, WithEvents, WithColumnFormatting
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
        $type = session()->get('type');
        // dd($type);
        if ($type == 'AdvanceCount') {
            $title = 'Advance Count (APP)';
            $table = 'tbl_advance_count';
        } else {
            $title = 'Actual Count (APP)';
            $table = 'tbl_app_countdata';
        }
        $bu = session()->get('bu');
        $section = session()->get('section');
        $da = collect(session()->get('data'));

        // $da['data'] = collect($da['data'])->map(function ($trans) {
        //     $res = array_map(function ($x) {
        //         return array_map(function ($y) {
        //             return array_map(function ($z) {
        //                 return array_map(function ($w) {
        //                     $newArr = [];
        //                     foreach ($w as $index => $xyz) {
        //                         if ($index === 0) {
        //                             $newArr[] = (array) $xyz;
        //                         } else {
        //                             $newArr[] = (array) $xyz;
        //                         }
        //                     }
        //                     return $newArr;
        //                 }, $z);
        //             }, $y);
        //         }, $x);
        //     }, $trans);

        //     return $res;
        // })->all();

        // if ($bu == 'ASC: MAIN') {
        //     $table = 'tbl_app_countdata_alturas';
        // } else if ($bu == 'PLAZA MARCELA') {
        //     $table = 'tbl_app_countdata_pm';
        // } else if ($bu == 'ALTURAS TALIBON') {
        //     $table = 'tbl_app_countdata_talibon';
        // } else {
        //     $table = 'tbl_app_countdata';
        // }

        // $countType == 'ACTUAL' ? $table = 'tbl_app_countdata' :  $table = 'tbl_advance_count';

        if ($bu == 'ASC: MAIN') {
            $var = $section == "SNACKBAR" ? "_snackbar" :  '_alturas';
        } else if ($bu == 'PLAZA MARCELA') {
            $var = '_pm';
        } else if ($bu == 'ALTURAS TALIBON') {
            $var = '_talibon';
        } else if ($bu == 'ALTA CITTA') {
            $var = '_alta';
        } else if ($bu == 'DISTRIBUTION') {
            $var = '_pdc';
        } else {
            $var = '';
        }
        // dd($da);
        $i = 0;
        $previous_location_id = null;
        $imageaudit = null;
        $imageuser = null;
        $da['data'] = collect($da['data'])->map(function ($trans)  use ($table, $var, &$i, &$previous_location_id, &$imageaudit, &$imageuser) {

            $res = array_map(function ($x) use ($table, $var, &$i, &$previous_location_id, &$imageaudit, &$imageuser) {
                return array_map(function ($y) use ($table, $var, &$i, &$previous_location_id, &$imageaudit, &$imageuser) {
                    return array_map(function ($z)  use ($table, $var, &$i, &$previous_location_id, &$imageaudit, &$imageuser) {
                        return array_map(function ($w) use ($table, $var, &$i, &$previous_location_id, &$imageaudit, &$imageuser) {
                            $newArr = [];
                            $test = 'what';
                            $test2 = 'audit';

                            foreach ($w as $index => $xyz) {
                                $xyz = (array) $xyz;
                                // dd($xyz['location_id']);
                                if ($index == 0 && $xyz['location_id'] !== $previous_location_id) {
                                    // dump($previous_location_id);
                                    $i++;

                                    // $res = DB::table($table . $var)->where('itemcode', $xyz['itemcode'])->first();
                                    $res = DB::table('tbl_app_audit')
                                        ->join('tbl_app_user', 'tbl_app_user.location_id', '=', 'tbl_app_audit.location_id')
                                        ->selectRaw("tbl_app_user.user_signature , tbl_app_audit.audit_signature")
                                        ->where('tbl_app_audit.location_id', $xyz['location_id'])->first();
                                    $previous_location_id = $xyz['location_id'];
                                    // $user = base64_decode($res->user_signature);
                                    // dd($res->user_signature);
                                    $image1_data = "data:image/png;base64,$res->user_signature";
                                    $image2_data = "data:image/png;base64,$res->audit_signature";
                                    // $i = 5087 ? dd($res) : null;

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
                                    $imageuser = $image_url1;
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
                                    $imageaudit = $image_url2;
                                    $xyz['audit_signature'] = $image_url2;
                                    // dd($xyz['audit_signature']);

                                    $newArr[] = $xyz;
                                } else {
                                    $xyz = (array) $xyz;

                                    $xyz['audit_signature'] = $imageaudit;
                                    $xyz['user_signature'] = $imageuser;
                                    // $xyz['user_signature'] = 'test';
                                    // $xyz['audit_signature'] = 'test';
                                    $newArr[] = $xyz;
                                }
                            }
                            // dump($newArr);
                            // dd(1);
                            return $newArr;
                        }, $z);
                    }, $y);
                }, $x);
            }, $trans);
            // dd($res);
            return $res;
        })->all();

        // dd($da);

        return view('reports.pcount_app_excel', ['data' => $da, 'title' => $title]);
    }

    // public function styles(Worksheet $sheet)
    // {
    //     $protection = $sheet->getProtection();
    //     $protection->setAlgorithm(Protection::ALGORITHM_SHA_512);
    //     $protection->setSpinCount(20000);
    //     $protection->setPassword('Pcount2021');
    //     $protection->setSheet(true);
    //     $protection->setSort(true);
    //     $protection->setInsertRows(true);
    //     $protection->setFormatCells(true);
    // }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER
        ];
    }
}
