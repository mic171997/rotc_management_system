<?php

namespace App\Http\Controllers;

use App\Models\company;
use App\Models\section;
use App\Models\department;
use Illuminate\Support\Str;
use App\Models\BusinessUnit;
use App\Models\TblNavUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\TblNavCountdata;
use App\Imports\ImportNavPcount;
use App\Models\TblItemMasterfile;
use Illuminate\Support\Facades\DB;
use App\Imports\AltaItemMasterfile;
use App\Imports\ImportItemUnposted;
use App\Models\TblVendorMasterfile;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportItemMasterfile;
use App\Imports\ImportVendorMasterfile;
use App\Models\TblItemMasterfileMedPlus;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use App\Models\TblItemCategoryMasterfile;
use App\Models\TblItemMasterfileSnackbar;
use App\Models\TblItemMasterfileAltaCitta;
use App\Imports\ImportItemCategoryMasterfile;
use App\Models\TblItemMasterfileDistribution;

class FileUploadController extends Controller
{
    public function navPcount()
    {
        // request()->validate(['file' => 'required|file|mimes:xls,xlsx,csv,txt']);
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        // dd(request()->all());
        $extension = request()->file('file')->getClientOriginalExtension();
        $path = request()->file->storeAs('temp', time() . '.' . $extension);
        $path = storage_path('app') . '/' . $path;
        // Excel::import(new ImportNavPcount, $path);

        $reader = new Csv();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($path);
        $worksheet = $spreadsheet->getActiveSheet();

        $dataArray = $worksheet->toArray();

        $array = explode(' ', $dataArray[2][0]);

        $date = array_splice($array, 2);
        $tempDate = implode(' ', $date);

        $finalDate = Carbon::parse($tempDate)->format('Y-m-d');
        // dd($finalDate);
        $vendor = request()->vendor;
        $category = request()->category;
        $countType = request()->countType;
        $batchDate = request()->date;
        $import = new ImportNavPcount($finalDate, $vendor, $category, $countType, $batchDate);
        $import->import($path);

        // Excel::import(new ImportNavPcount, $finalDate, $vendor, $category);
        // try {
        //     Excel::import($import, $path);
        // } catch (ValidationException $e) {
        //     return response()->json(['success' => 'errorList', 'message' => $e->errors()]);
        // }
        return response()->json(['message' => 'uploaded successfully'], 200);
    }

    public function getCompany()
    {
        $user_bu = auth()->user()->business_unit;
        $user_id = auth()->user()->usertype_id;

        if ($user_id == 1) {
            $comp = array('ASC', 'MFI');
            // $comp = company::get();
        } else {
            if ($user_bu == 'PLAZA MARCELA') {
                $comp = array('MFI');
            } else {
                $comp = array('ASC');
            }
        }

        $get = company::whereIn(
            'acroname',
            $comp
        )->get();
        return $get;

        // return $comp;
    }

    public function getBU()
    {
        $user_bu = auth()->user()->business_unit;
        auth()->user()->usertype_id == 1 ? $bu = array('ASCMAIN', 'ASC TAL', 'ALTA CITTA', 'ICM', 'PM', 'DISTRIBUTION') : $bu = array("$user_bu");
        // dd($bu);
        $get = BusinessUnit::where([
            ['company_code', request()->code],
            ['status', '=', 'active']
        ])->wherein(
            'acroname',
            $bu
        )->orwhere([
            ['company_code', request()->code],
            ['status', '=', 'active']
        ])->wherein(
            'business_unit',
            $bu
        )
            ->get();

        return $get;
    }

    public function getDept()
    {
        return department::where([
            ['company_code', request()->code],
            ['bunit_code', request()->bu],
            ['status', '=', 'active']
        ])->whereIn('dept_name', ['SUPERMARKET', 'PHARMACY DISTRIBUTION CENTER'])
            ->get();
    }

    public function getSection()
    {
        return section::where(
            [
                ['company_code', request()->code],
                ['bunit_code', request()->bu],
                ['dept_code', request()->dept]
            ]
        )->whereIn('section_name', ['SELLING AREA', 'STOCK ROOM', 'STOCKROOM', 'SNACKBAR', 'MIAS', 'WAREHOUSE', 'OLD WAREHOUSE', 'NEW WAREHOUSE'])->get();
    }

    public function importItemMasterfile(Request $request)
    {
        // dd(request()->all());

        $request->validate([

            'itemmasterfiles' => 'required'

        ], [

            'itemmasterfiles.required' => 'The itemmasterfiles field is required.',

        ]);
        // dd(request()->itemmasterfiles);
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        DB::transaction(function () {
            // TblItemMasterfile::truncate();
            // dd(request()->file('file'));
            // dd(File::lines(request()->file('file'))->first(function ($row) {
            //     return Str::contains($row, '100076');
            // }));

            File::lines(request()->file('file'))->slice(1)->reject(function ($row) {
                return empty($row);
            })
                ->each(function ($xrow, $index) {
                    $x = explode(',', $xrow);

                    $xx = [];

                    foreach ($x as $index => $word) {
                        if (Str::startsWith($word, '"') && Str::endsWith($word, '"')) {
                            $word = Str::replace('"', '', $word);
                            $xx[] = $word;
                        } else {
                            if (Str::contains($word, '"')) {
                                if (Str::endsWith($word, '"')) {
                                    $prevWord = $x[$index - 1];
                                    $newWord = Str::replace('"', '', "$prevWord,$word");

                                    $xx[$index - 1] = $newWord;
                                }
                                // dd($xx);
                            } else {
                                $xx[] = $word;
                            }
                        }
                    }

                    $row = array_values($xx);
                    // dd(trim($row[9]));
                    $values = [
                        'item_code' => trim($row[0]),
                        'uom' => trim($row[1]),
                        'conversion_qty' => trim($row[2]),
                        'barcode' => (string)trim($row[3]),
                        'variant_code' => trim($row[4]),
                        'desc' => trim($row[5]),
                        'extended_desc' => trim($row[6]),
                        'vendor_code' => trim($row[7]),
                        'vendor_name' =>  trim($row[8]),
                        'item_division' => trim($row[9]),
                        'section' => trim($row[10]),
                        'group' => trim($row[12]),
                        'category' => trim($row[14])
                    ];
                    if (request()->itemmasterfiles == 'Centralize Masterfiles') {
                        // dd(1);
                        TblItemMasterfile::updateOrCreate(['barcode' => $values['barcode']], $values);
                    } elseif (request()->itemmasterfiles == 'Alta Cita Masterfiles') {
                        // dd(2);
                        TblItemMasterfileAltaCitta::updateOrCreate(['barcode' => $values['barcode']], $values);
                    } elseif (request()->itemmasterfiles == 'MedPlus Masterfiles') {
                        // dd(3);
                        TblItemMasterfileMedPlus::updateOrCreate(['barcode' => $values['barcode']], $values);
                    } elseif (request()->itemmasterfiles == 'Distribution Masterfiles') {
                        // dd('Distribution');
                        TblItemMasterfileDistribution::updateOrCreate(['barcode' => $values['barcode']], $values);
                    } else {
                        // dd(4);
                        TblItemMasterfileSnackbar::updateOrCreate(['barcode' => $values['barcode']], $values);
                    }

                    // TblItemMasterfile::updateOrCreate(['barcode' => $values['barcode']], $values);
                    // TblItemMasterfileAltaCitta::updateOrCreate(['barcode' => $values['barcode']], $values);
                    // TblItemMasterfileSnackbar::updateOrCreate(['barcode' => $values['barcode']], $values);
                });
        });
        // dd(1);
        return response()->json(['message' => 'uploaded successfully'], 200);
    }

    public function getVendor()
    {
        $vendor = request()->vendor;
        if ($vendor) {

            return TblVendorMasterfile::selectRaw('vendor_name, vendor_code ')
                ->where('vendor_name', 'LIKE', "%$vendor%")
                ->orWhere('vendor_code', $vendor)
                ->get();
        } else {
            return TblVendorMasterfile::take(500)->get()->prepend(collect(['vendor_name' => 'ALL VENDORS']));
        }
    }
    public function getCategory()
    {
        $category = request()->category;

        if ($category) {

            // $x = TblItemCategoryMasterfile::selectRaw('category, dept_code')
            //     ->where('category', 'LIKE', "%$category%")
            //     ->orWhere('dept_code', $category)
            //     ->get();

            // dd($x);

            return TblItemCategoryMasterfile::selectRaw('category, dept_code')
                ->where('category', 'LIKE', "%$category%")
                ->orWhere('dept_code', $category)
                ->get();
        } else {
            return TblItemCategoryMasterfile::take(500)->get()->prepend(collect(['category' => 'ALL CATEGORIES']));
            // return TblItemCategoryMasterfile::take(500)->get();
        }
    }

    public function importVendorMasterfile()
    {
        $extension = request()->file('file')->getClientOriginalExtension();
        $path = request()->file->storeAs('temp', time() . '.' . $extension);
        $path = storage_path('app') . '/' . $path;
        $import = new ImportVendorMasterfile();
        $import->import($path);

        return response()->json(['message' => 'uploaded successfully'], 200);
    }
    public function importItemCategoryMasterfile()
    {
        $extension = request()->file('file')->getClientOriginalExtension();
        $path = request()->file->storeAs('temp', time() . '.' . $extension);
        $path = storage_path('app') . '/' . $path;
        $import = new ImportItemCategoryMasterfile();
        $import->import($path);

        return response()->json(['message' => 'uploaded successfully'], 200);
    }

    public function importUnposted()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $extension = request()->file('file')->getClientOriginalExtension();
        $path = request()->file->storeAs('temp', time() . '.' . $extension);
        $path = storage_path('app') . '/' . $path;

        $reader = new Csv();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($path);
        $worksheet = $spreadsheet->getActiveSheet();

        $dataArray = $worksheet->toArray();

        $array = explode(' ', $dataArray[2][0]);

        $date = array_splice($array, 2);
        $tempDate = implode(' ', $date);

        $finalDate = Carbon::parse($tempDate)->format('Y-m-d');
        // dd($finalDate);
        $batchDate = request()->date;
        $import = new ImportItemUnposted($finalDate, $batchDate);
        $import->import($path);

        return response()->json(['message' => 'uploaded successfully'], 200);
    }

    public function newItemValuation(Request $request)
    {
        // dd($request->date);
        if (request()->business_unit === 'PLAZA MARCELA' || request()->business_unit === 'ALTA CITTA') {

            $request->validate([
                // 'company' => 'required',
                'business_unit' => 'required',
                'department' => 'required',
                'date' => 'required',

            ], [
                // 'company.required' => 'The company field is required.',
                'business_unit.required' => 'The business_unit field is required.',
                'department.required' => 'The department field is required.',
                'date.required' => 'The date field is required.',

            ]);
        } else {

            $request->validate([
                // 'company' => 'required',
                'business_unit' => 'required',
                'department' => 'required',
                'section' => 'required',
                'date' => 'required',

            ], [
                // 'company.required' => 'The company field is required.',
                'business_unit.required' => 'The business_unit field is required.',
                'department.required' => 'The department field is required.',
                'section.required' => 'The section field is required.',
                'date' => 'required',

            ]);
        }
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $extension = request()->file('file')->getClientOriginalExtension();
        $path = request()->file->storeAs('temp', time() . '.' . $extension);
        $path = storage_path('app') . '/' . $path;
        // Excel::import(new ImportNavPcount, $path);

        $reader = new Csv();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($path);
        $worksheet = $spreadsheet->getActiveSheet();

        $dataArray = $worksheet->toArray();

        $array = explode(' ', $dataArray[2][0]);

        // $date = array_splice($array, 2);
        // $tempDate = implode(' ', $date);

        // $finalDate = Carbon::parse($tempDate)->format('Y-m-d');
        $finalDate = request()->date;

        DB::transaction(function () use ($finalDate) {
            // dd(File::lines(request()->file('file'))->first(function ($row) {
            //     return Str::contains($row, '113038');
            // }));
            $user = auth()->user()->id;
            $company = auth()->user()->company;
            $business_unit = request()->business_unit;
            $department = request()->department;
            $section = request()->section;

            $values = [
                'company' => $company,
                'business_unit' => $business_unit,
                'department' => $department,
                'section' => $section,
                'user_id' => $user,
                'date' => $finalDate,
                'type' => 'Item Valuation'
            ];
            $log = TblNavUpload::create($values);

            File::lines(request()->file('file'))->slice(1)->reject(function ($row) {
                // return empty($row);
                $x = explode(',', $row);
                $x = array_values($x);
                if (!$x[0]) {
                    // dd('yawa', (float) str_replace(',', '', $row[6]));
                    return empty($row);
                }
            })
                ->each(function ($xrow, $index) use ($log) {
                    // dd($finalDate);
                    $x = explode(',', $xrow);
                    $row = array_values($x);

                    $qty = (float)$row[4];
                    $cost_no_vat = (float) str_replace(',', '', $row[5]);
                    $amt = (float) str_replace(',', '', $row[6]);

                    $values = [
                        'itemcode' => trim($row[0]),
                        'variant_code' => trim($row[1]) == "" ? null : trim($row[1]),
                        'description' => trim($row[2]),
                        'uom' => trim($row[3]),
                        'qty' => $qty,
                        'cost_no_vat' => $cost_no_vat,
                        'amt' => $amt,
                        'batch_id' => $log->id
                    ];
                    // dd($values);
                    TblNavCountdata::create($values);
                });
        });
        // dd(1);
        return response()->json(['message' => 'uploaded successfully'], 200);
    }

    public function importAltaCitta(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // dd($file);
            // Import the Excel file using Laravel Excel
            // Excel::import(new AltaItemMasterfile, $file);

            $data = Excel::toArray([], $file);

            foreach ($data[2] as $row) {
                // Do something with each row
                // For example, you can print the row data to the console
                dd($row);
            }

            return response()->json([
                'success' => true,
                'message' => 'File imported successfully.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No file uploaded.'
            ]);
        }
    }
}
