<?php

namespace App\Models;
use CRUDBooster;
use Illuminate\Database\Eloquent\Model;

class ErfHeaderRequest extends Model{
    protected $table = 'erf_header_request';

    protected $fillable = [
        'status_id'    
    ];

    public function scopeDetails($query){
        return $query->leftjoin('companies', 'erf_header_request.company', '=', 'companies.id')
        ->leftjoin('departments', 'erf_header_request.department', '=', 'departments.id')
        ->leftjoin('cms_users as approver', 'erf_header_request.approved_immediate_head_by', '=', 'approver.id')
        ->leftjoin('cms_users as verifier', 'erf_header_request.approved_hr_by', '=', 'verifier.id')
        ->select(
                'erf_header_request.*',
                'approver.name as approved_head_by',
                'verifier.name as verified_by',
                'departments.department_name as department'
                )
        ->get();
    }
}

