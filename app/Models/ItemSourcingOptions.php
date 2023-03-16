<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemSourcingOptions extends Model
{
    protected $table = 'item_sourcing_options';

    protected $fillable = [
       'header_id',	
       'options',
       'vendor_name',
       'price',
       'created_by',
       'created_at',
       'updated_by',
       'updated_at',
    ];

}
