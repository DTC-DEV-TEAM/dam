<?php

namespace App\Imports;

use App\BodyRequest;
use App\Models\CmsPrivileges;
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
class FulfillmentUpload implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return Users|null
     */
    public function collection(Collection $rows)
    {
        foreach ($rows->toArray() as $row){
            $header = DB::table('header_request')->where(['reference_number' => $row['arf_number']])->first();
            BodyRequest::where(['header_request_id'=>$header->id,'digits_code'=>$row['digits_code']])
            ->update(
                        [
                        'mo_so_num'    => DB::raw('CONCAT_WS(",",mo_so_num, "'. $row['mo_so_num'].'")'),
                        'serve_qty'    => DB::raw("IF(serve_qty IS NULL, '".$row['fulfill_qty']."', serve_qty + '".$row['fulfill_qty']."')"), 
                        'unserved_qty' => DB::raw("unserved_qty - '".$row['fulfill_qty']."'"),    
                        'reorder_qty'  => DB::raw("reorder_qty - '".$row['fulfill_qty']."'")            
                        ]
                    );
        }
    }
}