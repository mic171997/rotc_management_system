<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\company;
use App\Models\section;
use App\Models\TblUser;
use App\Models\Employee;
use App\Models\department;
use App\Models\TblAppUser;
use App\Models\TblAppAudit;
use App\Models\TblLocation;
use App\Models\TblNavCount;
use App\Models\TblUsertype;
use App\Models\BusinessUnit;
use Illuminate\Http\Request;
use App\Exports\LocationData;
use App\Models\TblAppCountdata;
use App\Models\TblLocationRack;
use App\Models\TblJustification;
use App\Models\TblItemMasterfile;
use Illuminate\Support\Facades\DB;
use App\Models\TblQuestionableItem;
use App\Models\TblVendorMasterfile;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\CreateUserRequest;
use App\Models\TblItemCategoryMasterfile;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\CreateLocationRequest;
use App\Models\TblItemMasterfileAltaCitta;

class SetupController extends Controller
{
    public function getResultsLocation()
    {
        $company = request()->company;
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateString();
        $type = request()->type;
        $countType = request()->countType;
        $Type = request()->Type;



        if ($type == 'LocationSetup') {


            $results = TblLocation::with(['app_users', 'app_audit', 'nav_count'])
                ->join('tbl_nav_count', 'tbl_nav_count.location_id', '=', 'tbl_location.location_id')
                ->where([
                    ['company', 'LIKE', "%$company%"],
                    ['business_unit', 'LIKE', "$bu"],
                    ['department', 'LIKE', "$dept"],
                    ['section', 'LIKE', "$section"],
                    ['done', 'LIKE', "false"],
                    ['type', $countType],
                    ['countType', $Type]
                ])
                ->whereDate('batchDate', '=', $date)
                ->OrderBy('created_at', 'ASC');

            // $results->numberList = 1;
            // dd($results->toArray());
            // $test = $results->get();
            // $x = 0;
            // $results->numberList = 1;
            // foreach ($results as $yawa) {
            //     $results = $results->get();
            //     $results->numberList = $x;
            //     $x++;
            // }
            // dd($results->paginate(10));

            return $results->paginate(100);
        }
        if ($type == 'LocationMonitoring') {
            $racks = [];

            $query = TblLocation::SelectRaw('company, business_unit, department , tbl_location.location_id')->
                // join('tbl_nav_count', 'tbl_nav_count.location_id', '=', 'tbl_location.location_id')
                join('tbl_nav_count', function ($join) use ($date) {
                    $join->on('tbl_location.location_id', '=', 'tbl_nav_count.location_id')->whereDate('batchDate', '=', $date);
                })->groupBy(['company', 'business_unit', 'department'])
                // wa man gud na group by apil tong section, wa baw
                ->get();
            // dump($query->toArray());
            // dd(1);
            // dd($query);
            $query->map(function ($row) use (&$countType, &$date, &$racks, &$query) {

                // foreach ($query as $test) {
                //     // dd($test);
                // $comp = $test['company'];
                $comp =
                    $row->company;
                // $bu = $test['business_unit'];
                $bu =   $row->business_unit;
                // $dept = $test['department'];
                $dept =  $row->department;
                // $id = $test['location_id'];
                $id =  $row->location_id;


                // dd($comp, $bu, $dept, $id);

                $sectionList = $row->sectionList = TblLocation::selectRaw('section')
                    ->join('tbl_nav_count', 'tbl_nav_count.location_id', '=', 'tbl_location.location_id')
                    ->where([
                        ['company', $comp],
                        ['business_unit', $bu],
                        ['department', $dept],
                        // ['tbl_location.location_id', $id],
                        // ['section', '!=', 'null']
                    ])
                    ->whereDate('batchDate', $date)
                    ->groupBy('section')
                    ->get();
                // dump($sectionList);

                $sectionList->map(function ($row2) use (&$sectionList, &$date, &$comp, &$bu, &$dept, &$section, &$rackGroup, &$rack, &$id) {
                    // dd($row2->section);


                    $racksList = $row2->racks = TblLocation::selectRaw('rack_desc')
                        ->join('tbl_nav_count', 'tbl_nav_count.location_id', '=', 'tbl_location.location_id')
                        ->where([
                            ['company', $comp],
                            ['business_unit', $bu],
                            ['department', $dept],
                            ['section', $row2->section],
                            // ['tbl_location.location_id', $id]
                        ])
                        ->whereDate('batchDate', $date)
                        ->groupBy('rack_desc')
                        ->get();
                    // $section = $row2;

                    // dd($racksList->toArray());
                    $racksList->map(function ($row3) use (&$sectionList, &$date, &$comp, &$bu, &$dept, &$section, &$rackGroup, &$row2) {
                        $section = $row2->section;
                        // dd($row3);
                        $rack_desc = $row3->rack_desc;
                        // dd($comp, $bu, $dept, $section, $rack_desc, $date);

                        $row3->rackGroup = TblLocation::selectRaw('tbl_location.done, rack_desc, tbl_app_user.name as app_user, tbl_app_audit.name as audit_user, tbl_location.status, countType, tbl_location.location_id')->join('tbl_nav_count', 'tbl_nav_count.location_id', '=', 'tbl_location.location_id')
                            ->join('tbl_app_user', 'tbl_app_user.location_id', '=', 'tbl_location.location_id')
                            ->join('tbl_app_audit', 'tbl_app_audit.location_id', '=', 'tbl_location.location_id')
                            ->where([
                                ['company', $comp],
                                ['business_unit', $bu],
                                ['department', $dept],
                                ['section', $section],
                                ['rack_desc', $rack_desc]
                            ])
                            ->where('batchDate', $date)
                            ->get();
                        // dd($row3->toArray());
                        return $row3;
                    });
                    // dd($row2);
                    return $row2;
                });
                // }
                return $row;
            });
            // dd($query);
            $data['data'] = $query;
            return $data;
        }
    }

    public function toggleStatusLocation()
    {
        $result = TblLocation::where('location_id', request()->id)->update(['status' => request()->status]);
        if ($result)  return response()->json(['message' => 'Status updated successfully'], 200);
    }

    public function getResultsUser()
    {
        return TblUser::paginate(10);
    }

    public function toggleStatusUser()
    {
        $result = TblUser::where('id', request()->id)->update(['status' => request()->status]);
        if ($result)  return response()->json(['message' => 'Status updated successfully'], 200);
    }

    public function createUser(CreateUserRequest $request)
    {
        $validated = $request->validated();
        // dd($validated, $validated['name']['emp_id']);

        if (!request()->id) {
            $comp_code = $validated['name']['company_code'];
            $bu_code = $validated['name']['bunit_code'];
            $dept_code = $validated['name']['dept_code'];
            $section_code = $validated['name']['section_code'];
            $position = $validated['name']['position'];

            $getCompany = company::where([['company_code', $comp_code], ['status', 'active']])->get()->toarray();

            $getBU = BusinessUnit::where([
                ['company_code', $comp_code],
                ['bunit_code', $bu_code],
                ['status', 'active']
            ]);
            $getBU->exists() ? $BU = $getBU->get()->toArray() : null;

            $getDept = department::where([
                ['company_code', $comp_code],
                ['bunit_code', $bu_code],
                ['dept_code', $dept_code],
                ['status', 'active']
            ]);
            $getDept->exists() ? $Dept = $getDept->get()->toArray() : null;

            $getSection = section::where([
                ['company_code', $comp_code],
                ['bunit_code', $bu_code],
                ['dept_code', $dept_code],
                ['section_code', $section_code],
                ['status', 'active']
            ]);
            $getSection->exists() ? $getSect = $getSection->get()->toArray() : null;
            $company = $getCompany[0]['acroname'];
            $business_unit = $getBU->exists() ? $BU[0]['business_unit'] : null;
            $department = $getDept->exists() ? $Dept[0]['dept_name'] : null;
            $section = $getSection->exists() ? $getSect[0]['section_name'] : null;
            // dd($company, $business_unit, $department, $section, $position);
            // dd($section);
            $checkUsername = User::where('username', $validated['username'])->exists();
            if (!$checkUsername) {
                User::create([
                    'name' => $validated['name']['name'],
                    'username' => $validated['username'],
                    'password' => bcrypt($validated['password']),
                    'company' => $company,
                    'business_unit' => $business_unit,
                    'department' => $department,
                    'section' => $section,
                    'usertype_id' => $validated['usertype_id'],
                    'position' => $position
                ]);
                return response()->json(['message' => 'User created successfully!'], 200);
            }
            return response()->json(['message' => 'Already exists!'], 406);
        }
        // dd($validated);
        $result = User::where('id', request()->id)->update([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'usertype_id' => $validated['usertype_id']
        ]);
        if ($result) return response()->json(['message' => 'Update successful!'], 201);
    }

    public function searchEmployee()
    {
        $last_name = request()->lastname;
        return Employee::selectRaw('emp_id, emp_no, emp_pins as emp_pin, name, position, company_code, bunit_code, dept_code, section_code')
            ->where([
                ['name', 'LIKE', "%$last_name%"],
                ['current_status', 'Active']
            ])->cursor();
    }

    public function createLocation(CreateLocationRequest $request)
    {
        $batchDate = Carbon::parse(base64_decode(request()->countDate))->startOfDay()->toDateString();
        $validated = $request->validated();
        $countType = request()->countType;
        $Type = request()->type;

        if (!request()->forPrintVendor) {
            $vendor = 'null';
        } else {
            $vendor = request()->forPrintVendor;
        }
        if (!request()->forPrintCategory) {
            $category = 'null';
        } else {
            $category = request()->forPrintCategory;
        }

        if (!request()->section) {
            $section = 'null';
        } else {
            $section = request()->section;
        }
        if (!request()->department) {
            $dept = 'null';
        } else {
            $dept = request()->department;
        }

        if ($validated['selectedEmp']['emp_id'] == $validated['selectedAudit']['emp_id'])   return response()->json(['message' => 'Same names are now allowed'], 406);

        if (!request()->location_id) {
            // dd($validated['selectedEmp']['emp_id']);
            $comp = $validated['company'];
            $bu = $validated['business_unit'];
            $app_user = $validated['selectedEmp']['emp_id'];
            $app_audit = $validated['selectedAudit']['emp_id'];

            $ifAppUserExists = TblNavCount::join('tbl_app_user', 'tbl_app_user.location_id', '=', 'tbl_nav_count.location_id')
                ->join('tbl_app_audit', 'tbl_app_audit.location_id', '=', 'tbl_nav_count.location_id')
                ->join('tbl_location', 'tbl_location.location_id', '=', 'tbl_nav_count.location_id')
                ->where([
                    ['company', 'LIKE', "%$comp%"],
                    ['business_unit', 'LIKE', "%$bu%"],
                    ['department', 'LIKE', "%$dept%"],
                    ['section', 'LIKE', "$section"],
                    ['tbl_app_user.emp_id', $app_user],
                    ['tbl_app_user.done', 'false'],
                    ['tbl_nav_count.type', $countType]
                ])->whereDate('batchDate', $batchDate)
                ->orwhere([
                    ['company', 'LIKE', "%$comp%"],
                    ['business_unit', 'LIKE', "%$bu%"],
                    ['department', 'LIKE', "%$dept%"],
                    ['section', 'LIKE', "$section"],
                    ['tbl_app_audit.emp_id', $app_user],
                    ['tbl_app_audit.done', 'false'],
                    ['tbl_nav_count.type', $countType]
                ])->whereDate('batchDate', $batchDate)
                ->exists();

            $ifAppAuditExists = TblNavCount::join('tbl_app_audit', 'tbl_app_audit.location_id', '=', 'tbl_nav_count.location_id')
                ->join('tbl_app_user', 'tbl_app_user.location_id', '=', 'tbl_nav_count.location_id')
                ->join('tbl_location', 'tbl_location.location_id', '=', 'tbl_nav_count.location_id')
                ->where([
                    ['company', 'LIKE', "%$comp%"],
                    ['business_unit', 'LIKE', "%$bu%"],
                    ['department', 'LIKE', "%$dept%"],
                    ['section', 'LIKE', "$section"],
                    ['tbl_app_audit.emp_id', $app_audit],
                    ['tbl_app_audit.done', 'false'],
                    ['tbl_nav_count.type', $countType]
                ])->whereDate('batchDate', $batchDate)
                ->orwhere([
                    ['company', 'LIKE', "%$comp%"],
                    ['business_unit', 'LIKE', "%$bu%"],
                    ['department', 'LIKE', "%$dept%"],
                    ['section', 'LIKE', "$section"],
                    ['tbl_app_user.emp_id', $app_audit],
                    ['tbl_app_user.done', 'false'],
                    ['tbl_nav_count.type', $countType]
                ])->whereDate('batchDate', $batchDate)
                ->exists();
            // dd($ifAppUserExists, $ifAppAuditExists);

            if (!$ifAppUserExists && !$ifAppAuditExists) {
                DB::transaction(function () use ($validated, $section, $dept, $vendor, $category, $batchDate, $countType, $Type) {
                    $location = TblLocation::create([
                        'company' => $validated['company'],
                        'business_unit' => $validated['business_unit'],
                        // 'department' => $validated['department'],
                        'department' => $dept,
                        'section' => $section,
                        'rack_desc' => $validated['rack_desc'],
                        'date_added' => now(),
                        'status' => 'true'
                    ]);

                    TblAppUser::create([
                        'emp_id' => $validated['selectedEmp']['emp_id'],
                        'emp_no' => $validated['selectedEmp']['emp_no'],
                        'emp_pin' => $validated['selectedEmp']['emp_pin'],
                        'name' => $validated['selectedEmp']['name'],
                        'position' => $validated['selectedEmp']['position'],
                        'location_id' => $location->id,
                        'date_register' => now(),
                        'done' => 'false',
                        'locked' => 'false'
                    ]);

                    TblAppAudit::create([
                        'emp_id' => $validated['selectedAudit']['emp_id'],
                        'emp_no' => $validated['selectedAudit']['emp_no'],
                        'emp_pin' => $validated['selectedAudit']['emp_pin'],
                        'name' => $validated['selectedAudit']['name'],
                        'position' => $validated['selectedAudit']['position'],
                        'location_id' => $location->id,
                        'date_register' => now()
                    ]);

                    TblNavCount::create([
                        'byCategory' =>  $category === 'null' ? 'False' : 'True',
                        'categoryName' => $category,
                        'byVendor' => $vendor === 'null' ? 'False' : 'True',
                        'vendorName' => $vendor,
                        'location_id' => $location->id,
                        'type' => $countType,
                        'countType' => $Type,
                        'batchDate' => $batchDate
                    ]);
                });

                return response()->json(['message' => 'User created successfully!'], 200);
            } else if ($ifAppUserExists) {
                return response()->json(['message' => 'Inventory Clerk already exists!'], 406);
            } else if ($ifAppAuditExists) {
                return response()->json(['message' => 'Auditor already exists!'], 406);
            }
        }

        // dd(request()->all());
        DB::transaction(function () use ($validated, $section, $dept, $category, $vendor) {
            TblLocation::where('location_id', request()->location_id)->update([
                'company' => $validated['company'],
                'business_unit' => $validated['business_unit'],
                // 'department' => $validated['department'],
                'department' => $dept,
                'section' => $section,
                'rack_desc' => $validated['rack_desc'],
            ]);

            TblAppUser::where('location_id', request()->location_id)->update([
                'emp_id' => $validated['selectedEmp']['emp_id'],
                'emp_no' => $validated['selectedEmp']['emp_no'],
                'emp_pin' => $validated['selectedEmp']['emp_pin'],
                'name' => $validated['selectedEmp']['name'],
                'position' => $validated['selectedEmp']['position']
            ]);

            TblAppAudit::where('location_id', request()->location_id)->update([
                'emp_id' => $validated['selectedAudit']['emp_id'],
                'emp_no' => $validated['selectedAudit']['emp_no'],
                'emp_pin' => $validated['selectedAudit']['emp_pin'],
                'name' => $validated['selectedAudit']['name'],
                'position' => $validated['selectedAudit']['position']
            ]);

            TblNavCount::where('location_id', request()->location_id)->update([
                'byCategory' =>  $category === 'null' ? 'False' : 'True',
                'categoryName' => $category,
                'byVendor' => $vendor === 'null' ? 'False' : 'True',
                'vendorName' => $vendor
            ]);
        });

        return response()->json(['message' => 'Update successful!'], 201);
    }

    public function getUsertypes()
    {
        $usertype = auth()->user()->usertype_id;

        $usertype == 1 ?  $query = TblUsertype::all() : $query = TblUsertype::where('id', '!=', 1)->get();
        return $query;
    }

    public function getVendorMasterfile()
    {
        $search = request()->vendor;

        if (!$search) {
            return TblVendorMasterfile::paginate(10);
        } else {

            return TblVendorMasterfile::where('vendor_name', 'LIKE', "%$search%")->paginate(10);
        }
    }
    public function getItemCategoryMasterfile()
    {
        $search = request()->category;

        if (!$search) {
            return TblItemCategoryMasterfile::paginate(10);
        } else {

            return TblItemCategoryMasterfile::where('category', 'LIKE', "%$search%")
                ->orWhere('dept_code', $search)
                ->paginate(10);
        }
    }

    public function generateLocation()
    {
        session(['data' => $this->LocationData()]);
        return Excel::download(new LocationData, 'LocationData.xlsx');
    }

    public function LocationData()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $comp = request()->company;
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $date = Carbon::parse(base64_decode(request()->countdate))->startOfDay()->toDateString();

        $query =  TblLocation::with(['app_users', 'app_audit', 'nav_count'])
            ->join('tbl_nav_count', 'tbl_nav_count.location_id', '=', 'tbl_location.location_id')
            ->where([
                ['company', 'LIKE', "%$comp%"],
                ['business_unit', 'LIKE', "$bu"],
                ['department', 'LIKE', "$dept"],
                ['section', 'LIKE', "$section"],
                ['done', 'LIKE', "false"]

            ])
            ->whereDate('batchDate', '=', $date)
            ->get()
            ->toArray();

        $header = array(
            'business_unit' => $bu,
            'department' => $dept,
            'section' => $section,
            'countDate' => $date,
            'data' => $query
        );

        return $header;
    }

    public function getRacks()
    {
        return TblLocationRack::where([
            'company' => request()->company,
            'business_unit' => request()->business_unit,
            'department' => request()->department,
            'section' => request()->section !== "null" ? request()->section : null
        ])->get();
    }

    public function createRack()
    {
        // dd(request()->all());
        $validate =  TblLocationRack::where([
            'company' => request()->company,
            'business_unit' => request()->business_unit,
            'department' => request()->department,
            'section' => request()->section,
            'rack_name' => request()->name
        ])->exists();

        if (!request()->id) {
            if (!$validate) {
                TblLocationRack::create([
                    'company' => request()->company,
                    'business_unit' => request()->business_unit,
                    'department' => request()->department,
                    'section' => request()->section,
                    'rack_name' => request()->name
                ]);
                return response()->json(['message' => 'User created successfully!'], 200);
            }
            return response()->json(['message' => 'Already exists!'], 406);
        } else {
            if (!$validate) {
                TblLocationRack::where('id', request()->id)->update(['rack_name' => request()->name]);
                return response()->json(['message' => 'Update successful!'], 200);
            }
            return response()->json(['message' => 'Already exists!'], 406);
        }
    }

    public function getItems()
    {
        // dd(request()->all());
        $type = request()->type;
        if ($type) {
            // dd('naay type');
            $item = json_decode(base64_decode(request()->item), true);
            // dd($item['barcode']);

            if ($item['itemcode'] == "00000") {

                request()->bu == "ALTA CITTA" ?
                    $query = TblItemMasterfileAltaCitta::where('barcode', 'LIKE', $item['barcode'])->get()
                    :  $query = TblItemMasterfile::where('barcode', 'LIKE', $item['barcode'])->get();
                return $query;
            } else {

                return  TblItemMasterfile::where('item_code', 'LIKE', $item['itemcode'])->get();
            }
        } else {
            $search = request()->item;
            return  TblItemMasterfile::where('item_code', 'LIKE', "$search%")->orWhere('barcode', 'LIKE', "$search%")->get();
        }
    }

    public function postCount()
    {
        // dd(request()->all());
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateString();
        $items = json_decode(base64_decode(request()->data), true);
        $rack_desc = 'SETUP BY BACKEND';
        $name = auth()->user()->name;

        $emp = Employee::where('name', 'LIKE', "%$name%")->first();

        // dd(request()->type);
        // dd($items);
        foreach ($items as $item) {
            DB::beginTransaction();
            if (request()->type == 'QuestionableItems') {
                TblQuestionableItem::insert([
                    'itemcode' => $item['item_code'],
                    'barcode' => $item['barcode'],
                    'variant_code' => $item['variant_code'],
                    'description' => $item['extended_desc'],
                    'uom' => $item['uom'],
                    'qty' => $item['qty'],
                    'business_unit' => $bu,
                    'department' => $dept,
                    'section' => $section,
                    'rack_desc' => $rack_desc,
                    'empno' => $emp['emp_no'],
                    'user_id' => auth()->user()->id,
                    'count_date' => $date,
                    'created_at' => now()
                ]);
            }
            if (request()->type == 'ItemSelection') {
                // dd('ItemSelection ywa');
                request()->countType == "ACTUAL" ? $table = 'tbl_app_nfitem' : $table = 'tbl_app_advnfitem';
                // dd($item['id']);

                DB::table($table)->where('id', $item['id'])->update([
                    'itemcode' => $item['itemcode'],
                    'barcode' => $item['barcode'],
                    'description' => $item['description'],
                    'updated_by' => auth()->user()->id,
                    'updated_at' => now()
                ]);
            } else {

                // TblAppCountdata::insert([
                //     'itemcode' => $item['item_code'],
                //     'barcode' => $item['barcode'],
                //     'description' => $item['desc'],
                //     'uom' => $item['uom'],
                //     'qty' => $item['qty'],
                //     'conversion_qty' => $item['qty'],
                //     'business_unit' => $bu,
                //     'department' => $dept,
                //     'section' => $section,
                //     'rack_desc' => $rack_desc,
                //     'empno' => $emp['emp_no'],
                //     'datetime_scanned' => now(),
                //     'datetime_saved' => $date,
                //     'datetime_exported' => now(),
                //     'date_expiry' => '0000-00-00 00:00:00',
                //     'user_signature' => 'null',
                //     'audit_signature' => 'null'
                // ]);
            }

            DB::commit();
            // DB::rollBack();
        }
        return response()->json(['message' => 'Save successfull'], 200);
    }

    public function getLocationCount()
    {
        $loc_id = request()->location_id;
        $bu = request()->BU;
        $countType = request()->countType;
        if ($countType == "ACTUAL") {
            $nftable = 'tbl_app_nfitem';

            if ($bu == 'ASC: MAIN') {
                $table = 'tbl_app_countdata_alturas';
                $nftable = 'tbl_app_nfitem_alturas';
            } else if ($bu == 'PLAZA MARCELA') {
                $table = 'tbl_app_countdata_pm';
            } else if ($bu == 'ALTURAS TALIBON') {
                $table = 'tbl_app_countdata_talibon';
            } else if ($bu == 'ALTA CITTA') {
                $table = 'tbl_app_countdata_alta';
            } else if ($bu == 'DISTRIBUTION') {
                $table = 'tbl_app_countdata_pdc';
            } else {
                $table = 'tbl_app_countdata';
            }
        } else {
            $nftable = 'tbl_app_advnfitem';

            if ($bu == 'ASC: MAIN') {
                $table = 'tbl_advance_count_alturas';
                $nftable = 'tbl_app_advnfitem_alturas';
            } else if ($bu == 'PLAZA MARCELA') {
                $table = 'tbl_advance_count_pm';
            } else if ($bu == 'ALTURAS TALIBON') {
                $table = 'tbl_advance_count_talibon';
            } else if ($bu == 'ALTA CITTA') {
                $table = 'tbl_advance_count_alta';
            } else if ($bu == 'DISTRIBUTION') {
                $table = 'tbl_app_countdata_pdc';
            } else {
                $table = 'tbl_advance_count';
            }
        }


        if ($loc_id == "407" || $loc_id == "408") {
            return DB::table('tbl_app_countdata_snackbar')->selectRaw("count(itemcode) as items, datetime_exported")->where('location_id', request()->location_id)->groupByRaw('day(datetime_exported),hour(datetime_exported)')->get();
        } else {
            // $query = DB::table($table)->selectRaw("count(itemcode) as items, datetime_exported")->where('location_id', request()->location_id)->groupByRaw('day(datetime_exported),hour(datetime_exported)')->get();

            $results = DB::table($table)
                ->select(
                    DB::raw("$table.datetime_exported"),
                )
                ->where("$table.location_id", request()->location_id)->groupByRaw("day($table.datetime_exported),hour($table.datetime_exported)")
                ->get();

            $query = $results->map(function ($test) use ($table, $nftable) {
                $a = DB::table($table)->selectRaw("count(itemcode) as items, datetime_exported")
                    ->where('location_id', request()->location_id)
                    ->whereRaw("DATE_FORMAT(datetime_exported, '%Y-%m-%d %H') = DATE_FORMAT('$test->datetime_exported', '%Y-%m-%d %H')")
                    ->groupByRaw('day(datetime_exported),hour(datetime_exported)')
                    ->first();
                // dd($a->datetime_exported);
                $test->items = $a->items;
                // dd(date('Y-m-d H:i', strtotime($test->datetime_exported)));

                $b = DB::table($nftable)->selectRaw("count(itemcode) as tes, datetime_exported")
                    ->where('location_id', request()->location_id)
                    ->whereRaw("DATE_FORMAT(datetime_exported, '%Y-%m-%d %H') = DATE_FORMAT('$test->datetime_exported', '%Y-%m-%d %H')")
                    ->groupByRaw('day(datetime_exported),hour(datetime_exported)');
                // dd($b->datetime_exported);
                // dd($b->items);
                $b->exists() ? $test->nf_items = $b->first()->tes : $test->nf_items = 0;
                return $test;
            });
            // dd($query);

            return $query;
        }
    }

    public function createJustification()
    {
        // dd(request()->all());
        if (request()->reason) {
            $validate =  TblJustification::where([
                'reason' => request()->reason
            ])->exists();
            if (!request()->id) {
                if (!$validate) {
                    TblJustification::create([
                        'reason' => request()->reason,
                        'added_by' =>  auth()->user()->id
                    ]);
                    return response()->json(['message' => 'Created successfully!'], 200);
                }
                return response()->json(['message' => 'Already exists!'], 406);
            } else {
                if (!$validate) {
                    TblJustification::where('id', request()->id)->update(['reason' => request()->reason, 'added_by' => auth()->user()->id]);
                    return response()->json(['message' => 'Update successful!'], 200);
                }
                return response()->json(['message' => 'Already exists!'], 406);
            }
        }
        return response()->json(['message' => 'Empty!'], 406);
    }

    public function updatePassword(ChangePasswordRequest $request)
    {
        $validated = $request->validated();
        // $user = User::findOrFail(auth()->user()->id);

        // if (Hash::check($request->password, $user->password)) {
        //     $result = User::where('id', auth()->user()->id)->update([
        //         'password' =>  bcrypt($validated['password'])
        //     ]);

        //     if ($result) return response()->json(['message' => 'Update successful!'], 200);
        // } else {
        //     return redirect()->back()->withInput()->withErrors($validated);
        // }
    }

    public function resetPassword()
    {
        $id = request()->id;
        $result = TblUser::where('id', $id)->update([
            'password' => bcrypt('Pcount2021')
        ]);
        if ($result) return response()->json(['message' => 'Reset password successful!'], 200);
    }

    public function removeItem()
    {
        // dd(request()->all());
        $item = json_decode(base64_decode(request()->item), true);
        // dd($item['id']);

        $result = TblQuestionableItem::find($item['id'])->delete();
        if ($result) return response()->json(['message' => 'Delete successful!'], 200);
    }
}
