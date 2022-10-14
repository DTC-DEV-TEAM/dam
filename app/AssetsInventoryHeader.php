<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetsInventoryHeader extends Model
{
    protected $table = 'assets_inventory_header';
    protected $fillable = [
        'digits_code', 
        'item_id', 
        'po_no',
        'invoice_date',
        'rr_date',
        'expiration_date',
        'si_di',
        'wattage',
        'phase',
        'created_by'
    ];

    public function assetsinventorybody()
    {
        return $this->hasMany('App\AssetsInventoryBody');
    }
}
