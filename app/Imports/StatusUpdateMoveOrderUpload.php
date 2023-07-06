<?php

namespace App\Imports;

use App\HeaderRequest;
use App\BodyRequest;
use App\Models\AssetsInventoryReserved;
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
class StatusUpdateMoveOrderUpload implements ToCollection, WithHeadingRow
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
            $body   = BodyRequest::where(['header_request_id' => $header->id])->get();

            HeaderRequest::where(['reference_number'=>$row['reference_number']])
            ->update(
                        [
                            'status_id'          => $row['status'],   
                        ]
                    );

            BodyRequest::where(['header_request_id'=>$header->id])
            ->update(
                        [
                            'reorder_qty'        =>  1,
                            'unserved_qty'       =>  1,
                            'unserved_ro_qty'    =>  1 
                        ]
                    );

            foreach($body as $key => $fBodyItFaVal){
                AssetsInventoryReserved::Create(
                    [
                        'reference_number'    => $header->reference_number, 
                        'body_id'             => $fBodyItFaVal->id,
                        'digits_code'         => $fBodyItFaVal->digits_code, 
                        'approved_qty'        => $fBodyItFaVal->quantity,
                        'reserved'            => NULL,
                        'for_po'              => 1,
                        'created_by'          => CRUDBooster::myId(),
                        'created_at'          => date('Y-m-d H:i:s'),
                        'updated_by'          => CRUDBooster::myId(),
                        'updated_at'          => date('Y-m-d H:i:s')
                    ]
                );  
            }
            
        }
    }
}