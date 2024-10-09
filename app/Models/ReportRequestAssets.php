<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportRequestAssets extends Model
{
    protected $table = 'request_assets';

    public function scopeRequest($query, $fields){
        $from = $fields['datefrom'];
        $to = $fields['dateto'];
        $category = $fields['category'];
        $query->select('header_id as header_id',
                       'body_request_id as id',
                       'status as status',
                       'reference_number as reference_number',
                       'body_digits_code as digits_code',
                       'description as description',
                       'request_quantity as request_quantity',
                       'transaction_type as transaction_type',
                       'request_type as request_type',
                       'requested_by as requested_by',
                       'department as department',
                       'coa as coa',
                       'store_branch as store_branch',
                       'replenish_qty as replenish_qty',
                       'reorder_qty as reorder_qty',
                       'fulfill_qty as fulfill_qty',
                       'mo_reference as mo_reference',
                       'mo_item_code as mo_item_code',
                       'mo_item_description as mo_item_description',
                       'mo_qty_serve_qty as mo_qty_serve_qty',
                       'requested_date as requested_date',
                       'transacted_by as transacted_by',
                       'transacted_date as transacted_date',
                       'received_by as received_by',
                       'received_at as received_at')
        ->orderBy('reference_number','ASC');

        if($from != '' && !is_null($from)){
            $query->whereBetween('requested_date',[$from,$to]);
        }
        if($category != '' && !is_null($category)){
            $query->where('request_type_id', $category);
        }
        return $query->get();
    }
}
