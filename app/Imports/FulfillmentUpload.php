<?php

namespace App\Imports;

use App\HeaderRequest;
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
        foreach ($rows->toArray() as $key => $row){
            $header   = DB::table('header_request')->where(['reference_number' => $row['arf_number']])->first();
            $checkQty = DB::table('body_request')->where(['header_request_id'=>$header->id,'digits_code'=>$row['digits_code']])->value('unserved_qty');
            
            if($row['fulfill_qty'] > $checkQty){
                return CRUDBooster::redirect(CRUDBooster::adminpath('for_purchasing'),"Fullfill Qty Exceed! at line: ".($key+2),"danger");
            }
         
            HeaderRequest::where('id',$header->id)
			->update([
					'mo_so_num' => $row['mo_so_num'],
			]);	
            BodyRequest::where(['header_request_id'=>$header->id,'digits_code'=>$row['digits_code']])
            ->update(
                        [
                        'mo_so_num'    => DB::raw('CONCAT_WS(",",mo_so_num, "'. $row['mo_so_num'].'")'),
                        'serve_qty'    => DB::raw("IF(serve_qty IS NULL, '".(int)$row['fulfill_qty']."', serve_qty + '".(int)$row['fulfill_qty']."')"), 
                        'unserved_qty' => DB::raw("unserved_qty - '".(int)$row['fulfill_qty']."'"),    
                        'reorder_qty'  => DB::raw("reorder_qty - '".(int)$row['fulfill_qty']."'")            
                        ]
                    );
        }
    }
}