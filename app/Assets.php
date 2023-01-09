<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assets extends Model
{
    //
    protected $primaryKey = 'id';
    protected $table = 'assets';
    protected $fillable = [
        'digits_code',
        'item_description',
        'item_cost',
        'brand_id' ,
        'category_id',
        'class_id',
        'vendor_id', 
        'created_by',
        'created_at',
        'asset_tag',
        'status_id',
        'quantity',
        'add_quantity',
        'total+quantity',
    ] ;
}
