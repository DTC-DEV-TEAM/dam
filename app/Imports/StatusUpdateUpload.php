<?php

namespace App\Imports;

use App\HeaderRequest;
use App\BodyRequest;
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
            $header = HeaderRequest::where(['reference_number' => $row['reference_number']])->first();

            HeaderRequest::where(['reference_number'=>$row['reference_number']])
            ->update(
                        [
                            'status_id'          => $row['status'],   
                        ]
                    );

            BodyRequest::where(['header_request_id'=>$header->id])
            ->update(
                        [
                            'deleted_by'=> CRUDBooster::myId(),
                            'deleted_at'=> date('Y-m-d H:i:s')   
                        ]
                    );
        }
    }
}