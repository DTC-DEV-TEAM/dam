<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutAssets extends Model
{
    protected $table = 'out_assets';

    protected $fillable = [
        'arf_number', 
        'mo_ref_number', 
        'requestor_id', 
        'requestor_name', 
        'transfer_to',
        'transaction_type',
        'request_type',
        'asset_code',
        'digits_code',
        'item_description',
        'serial_no',
        'quantity',
        'amount',
        'date_received'
    ];
}