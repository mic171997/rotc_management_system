<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CadetController extends Controller
{


    public function get_cadet_counts() {

      $cadet =   DB::table('users')
        ->where('type', 'Cadet')
        ->count();

       $cadetno = 'CADET-0000' . ($cadet + 1);

       return $cadetno;

    }
    public function add_cadets(Request $request) {

       $firstname = request()->firstname;
       $lastname = request()->lastname;
       $company = request()->company;
       $cadetno = request()->cadetno;
       $id = request()->id;



if ($id == null) {
DB::table('users')
       ->insert([
        'name' => $firstname,
        'lastname' => $lastname,
        'company' => $company,
        'cadetno' => $cadetno ,
        'username' => $firstname,
        'password' => Hash::make($cadetno),
        'type' => 'Cadet',
        'created_at' => now(),
        'updated_at' => now()
       ]);

       return response()->json(['message' => 'Success'], 200);
}else {
DB::table('users')
->where('id' ,$id)
       ->update([
        'name' => $firstname,
        'lastname' => $lastname,
        'company' => $company,
        'cadetno' => $cadetno ,
        'username' => $firstname,
        'password' => Hash::make($cadetno),
        'type' => 'Cadet',
        'created_at' => now(),
        'updated_at' => now()
       ]);

       return response()->json(['message' => 'Success'], 200);
}

       
    }

    public function get_cadets() {

        $search = request()->search;
        $result = DB::table('users')
            ->selectRaw('users.*')
            ->where('type', 'Cadet')
            ->when($search !== "null", function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->orWhere('users.name', 'LIKE', "%$search%")
                        ->orWhere('users.lastname', 'LIKE', "%$search%")
                        ->orWhere('users.cadetno', 'LIKE', "%$search%");
                });
            })
            ->paginate(10);

        return $result;

    }

    public function  delete_cadets() {

        $id = request()->id;

        DB::table('users')
        ->where('id' , $id )
        ->delete();

        return response()->json(['message' => 'Success'], 200);
    }

    public function add_schedules() {

       $id = request()->id;
       $date = request()->date;
       $time_from = request()->time_from;
       $time_to = request()->time_to;
       $events = request()->events;

       if ($id == null) {
        DB::table('schedules')
        ->insert([
         'events' => $events,
         'date' => $date,
         'time_from' => $time_from . ":00",
         'time_to' => $time_to . ":00",
         'created_at' => now(),
         'updated_at' => now()
        ]);
 
        return response()->json(['message' => 'Success'], 200);
       } 
       else {
        DB::table('schedules')
        ->where('id' , $id)
        ->update([
         'events' => $events,
         'date' => $date,
         'time_from' => $time_from . ":00",
         'time_to' => $time_to . ":00",
         'created_at' => now(),
         'updated_at' => now()
        ]);
 
        return response()->json(['message' => 'Success'], 200);
       }
    }

    public function get_events() {

        $search = request()->search;
        $result = DB::table('schedules')
            ->selectRaw('schedules.*')
            ->when($search !== "null", function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->orWhere('schedules.events', 'LIKE', "%$search%");
                });
            })
            ->paginate(10);

        return $result;
    }

    
    public function  delete_schedules() {

        $id = request()->id;

        DB::table('schedules')
        ->where('id' , $id )
        ->delete();

        return response()->json(['message' => 'Success'], 200);
    }

    public function add_absent_request() {

        $cadetno = request()->cadetno;
        $name = request()->name;
        $company = request()->company;
        $events = request()->events;
        $date = request()->date;
        $time = request()->time;
        $reason = request()->reason;
        $id = request()->id;

        $check = DB::table('file_absents')
        ->where('id',$id)
        ->where('cadetno',$cadetno)
        ->exists();

        if ($check == false) {
  DB::table('file_absents')
        ->insert([
         'cadetno' => $cadetno,
         'name' =>  $name,
         'company' =>  $company,
         'events' =>  $events,
         'date' =>  $date,
         'time' =>  $time,
         'reason' =>  $reason,
         'created_at' => now(),
         'updated_at' => now(),
         'status' => 0,
         'training_id' => $id
        ]);
 
        return response()->json(['message' => 'Success'], 200);
        }
        else {
            return response()->json(['message' => 'Exists'], 200);
        }
      

      
    }

    public function get_request() {

        $type = auth()->user()->type;
        $cadetno = auth()->user()->cadetno;
        $search = request()->search;
        $status = request()->status;

         $search = request()->search;
        $result = DB::table('file_absents')
            ->selectRaw('file_absents.*')
            ->when($search !== null && $search !== "null" , function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->orWhere('file_absents.events', 'LIKE', "%$search%")
                    ->orWhere('file_absents.name', 'LIKE', "%$search%")
                    ->orWhere('file_absents.cadetno', 'LIKE', "%$search%");
                });
            })
             ->when($status !== null && $status !== "null", function ($q) use ($status) {
                $q->where(function ($q) use ($status) {
                    $q->Where('file_absents.status', $status == 'Pending' ? 0 : 1 );
                   
                });
            })
             ->when($type !== 'Admin', function ($q) use ($cadetno) {
                $q->where(function ($q) use ($cadetno) {
                    $q->Where('file_absents.cadetno', $cadetno);
                   
                });
            })
            ->paginate(10);

        return $result;


    }

    public function approved_request() {

        $id = request()->id;

        DB::table('file_absents')
        ->where('id' , $id)
        ->update([
            'status' => 1,
            'date_approved' => now(),
            'approved_by' => auth()->user()->name . ' ' . auth()->user()->lastname
        ]);

         return response()->json(['message' => 'Success'], 200);

    }

    public function add_attendance() {

       $attendance = request()->attendance;
       $id = request()->id;
       $cadetno = auth()->user()->cadetno;

       $check = DB::table('attendances')
       ->where('training_id' , $id)
       ->where('cadetno' , $cadetno)
       ->exists();


       if ($check == false) {

        if ($attendance === 'Time In') {

         DB::table('attendances')
         ->insert([
         'cadetno' => $cadetno,
         'training_id' =>  $id,
         'time_in' =>  now(),
         'created_at' =>  now(),
         'updated_at' =>  now(),
        ]);

        }
        else {

        DB::table('attendances')
         ->insert([
         'cadetno' => $cadetno,
         'training_id' =>  $id,
         'time_out' =>  now(),
          'created_at' =>  now(),
         'updated_at' =>  now(),
        ]);

        }

        return response()->json(['message' => 'Success'], 200);

       }

       else {

        if ($attendance === 'Time In') {

          $checklog =  DB::table('attendances')
                 ->where('training_id' , $id)
                 ->where('cadetno' , $cadetno)
                 ->whereNull('attendances.time_in')
                 ->exists();

                 if($checklog == true) {

                $checklog =  DB::table('attendances')
                 ->where('training_id' , $id)
                 ->where('cadetno' , $cadetno)
                 ->whereNull('attendances.time_in')
                 ->update([
                    'time_in' => now(),
                    'updated_at' =>  now(),
                 ]);
                  return response()->json(['message' => 'Success'], 200);
                 }
                 else {
                    return response()->json(['message' => $attendance], 200);
                 }
        }
        else {

        $checklog =  DB::table('attendances')
                 ->where('training_id' , $id)
                 ->where('cadetno' , $cadetno)
                 ->whereNull('attendances.time_out')
                 ->exists();

                  if($checklog == true) {

                $checklog =  DB::table('attendances')
                 ->where('training_id' , $id)
                 ->where('cadetno' , $cadetno)
                 ->whereNull('attendances.time_out')
                 ->update([
                    'time_out' => now(),
                    'updated_at' =>  now(),
                 ]);
                  return response()->json(['message' => 'Success'], 200);
                 }
                 else {
                    return response()->json(['message' => $attendance], 200);
                 }
        }   
       }
    }

    public function get_events_attendance() {

        $search = request()->search;
        $result = DB::table('schedules')
        ->leftjoin('attendances' , 'attendances.training_id' , '=' , 'schedules.id')
            ->selectRaw('schedules.* , attendances.time_in , attendances.time_out')
            ->when($search !== "null", function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->orWhere('schedules.events', 'LIKE', "%$search%");
                });
            })
            ->paginate(10);

        return $result;
    }

}