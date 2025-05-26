<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblAppNfitem extends Model
{
    use HasFactory;
    protected $table = 'tbl_app_nfitem';

    public function hasbarcode()
    {
        if (request()->bu != "ALTA CITTA") {
            return $this->hasMany(TblItemMasterfile::class, 'barcode', 'barcode');
        } else {
            return $this->hasMany(TblItemMasterfileAltaCitta::class, 'barcode', 'barcode');
        }
    }
    public function hasitemcode()
    {
        if (request()->bu != "ALTA CITTA") {
            return $this->hasMany(TblItemMasterfile::class, 'item_code', 'itemcode');
        } else {
            return $this->hasMany(TblItemMasterfileAltaCitta::class, 'item_code', 'itemcode');
        }
    }
}
