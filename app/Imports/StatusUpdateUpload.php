<?php

namespace App\Imports;

use App\HeaderRequest;
use Illuminate\Support\Facades\Hash;
//use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use DB;
use CRUDBooster;
class StatusUpdateUpload implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return Users|null
     */
    public function collection(Collection $rows)
    {
        foreach ($rows->toArray() as $row){
            HeaderRequest::where(['reference_number'=>$row['reference_number']])
            ->update(
                        [
                        'status_id'          => $row['status'],
                        'approved_by'        => NULL,
                        'approved_at'        => NULL, 
                        'approver_comments'  => NULL, 
                        'purchased1_by'      => NULL, 
                        'purchased1_at'      => NULL,  
                        'purchased2_by'      => NULL, 
                        'purchased2_at'      => NULL         
                        ]
                    );
        }
    }
}