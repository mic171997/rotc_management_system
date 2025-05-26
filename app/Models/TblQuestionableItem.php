<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblQuestionableItem extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'tbl_questionable_items';
}
