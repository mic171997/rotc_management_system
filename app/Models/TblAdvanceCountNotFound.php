<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblAdvanceCountNotFound extends Model
{
    use HasFactory;
    protected $table = 'tbl_app_advNfitem';

    public function hasbarcode()
    {
        return $this->hasMany(TblItemMasterfile::class, 'barcode', 'barcode');
    }
    public function hasitemcode()
    {
        return $this->hasMany(TblItemMasterfile::class, 'item_code', 'itemcode');
    }
}
