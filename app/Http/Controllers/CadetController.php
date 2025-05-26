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

}