<?php

namespace App\Imports;

use App\HeaderRequest;
use App\BodyRequest;
use App\Models\FulfillmentHistories;
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
class PoUpload implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return Users|null
     */
    public function collection(Collection $rows){

        foreach ($rows->toArray() as $key => $row){
            $header   = DB::table('header_request')->where(['reference_number' => $row['arf_number']])->first();
            BodyRequest::where(['header_request_id'=>$header->id,'digits_code'=>$row['digits_code']])
            ->update(
                        [
                        'po_qty' => $row['po_qty'],
                        'po_no'  => $row['po_number']         
                        ]
                    );

            FulfillmentHistories::Create(
                [
                    'arf_number'  => $row['arf_number'], 
                    'digits_code' => $row['digits_code'], 
                    'po_qty'      => $row['po_qty'],
                    'po_no'       => $row['po_number'] ,
                    'updated_by' => CRUDBooster::myId(),
                    'updated_at' => date('Y-m-d H:i:s')
                ]
            );  
           
        }

    }
}