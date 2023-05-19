<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locations extends Model
{
    
    protected $primaryKey = 'id';
    protected $table = 'locations';
    protected $fillable = [
        'channel_id',
        'store_name',
        'coa_id',
        'store_status' ,
        'created_by',
        'updated_by'
    ] ;
}
