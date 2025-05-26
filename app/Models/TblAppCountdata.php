<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class TblAppCountdata extends Model
{
    use HasFactory;
    protected $table = 'tbl_app_countdata';

    // protected $fillable = ['title', 'content'];

    // public function validate(array $data)
    // {
    //     // Trim whitespace from input data
    //     $data = array_map('trim', $data);

    //     $validator = Validator::make($data, [
    //         'title' => 'required|max:255',
    //         'content' => 'required',
    //     ]);

    //     return $validator;
    // }

    public function NavCountData()
    {
        # code...
    }
}
