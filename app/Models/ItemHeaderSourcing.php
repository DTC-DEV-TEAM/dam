<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemHeaderSourcing extends Model
{
    protected $table = 'item_sourcing_header';

    protected $fillable = [
       'status_id',	
       'reference_number',
       'employee_name',
       'company_name',
       'position',
       'department',
       'store_branch',
       'purpose',
       'conditions',
       'quantity_total',
       'cost_total',
       'total',
       'requestor_comments',
       'created_by',
       'created_at',
       'approved_by',
       'approved_at',
       'request_type_id',
       'privilege_id',
       'application',
       'application_others',
       'to_reco',
       'if_from_erf'
    ];

    public static function boot(){
        parent::boot();
        static::creating(function($model){
            $model->created_by = CRUDBooster::myId();
        });
        
    }
}