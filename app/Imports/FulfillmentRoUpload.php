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
class FulfillmentRoUpload implements ToCollection, WithHeadingRow
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
            $checkQty = DB::table('body_request')->where(['header_request_id'=>$header->id,'digits_code'=>$row['digits_code']])->value('unserved_ro_qty');
            
            if($row['dr_qty'] > $checkQty){
                return CRUDBooster::redirect(CRUDBooster::adminpath('for_purchasing'),"ReOrder Fullfill Qty Exceed! at line: ".($key+2),"danger");
            }
         
            HeaderRequest::where('id',$header->id)
			->update([
					'mo_so_num' => $row['dr_number'],
			]);	
            BodyRequest::where(['header_request_id'=>$header->id,'digits_code'=>$row['digits_code']])
            ->update(
                        [
                        'mo_so_num'       => DB::raw('CONCAT_WS(",",mo_so_num, "'. $row['dr_number'].'")'),
                        'serve_qty'       => DB::raw("IF(serve_qty IS NULL, '".(int)$row['dr_qty']."', serve_qty + '".(int)$row['dr_qty']."')"), 
                        'unserved_ro_qty' => DB::raw("unserved_ro_qty - '".(int)$row['dr_qty']."'"), 
                        'unserved_qty'    => DB::raw("unserved_qty - '".(int)$row['dr_qty']."'"),
                        'dr_qty'          => DB::raw("IF(dr_qty IS NULL, '".(int)$row['dr_qty']."', dr_qty + '".(int)$row['dr_qty']."')")            
                        ]
                    );

            FulfillmentHistories::Create(
                [
                    'arf_number'  => $row['arf_number'], 
                    'digits_code' => $row['digits_code'], 
                    'dr_qty'      => $row['dr_qty'],
                    'dr_no'       => $row['dr_number'],
                    'dr_type'     => $row['dr_type'],
                    'updated_by'  => CRUDBooster::myId(),
                    'updated_at'  => date('Y-m-d H:i:s')
                ]
            );  

            //Close if all unserved quantity is fulfill
            $checkBodyQty = DB::table('body_request')->where(['header_request_id'=>$header->id])->whereNull('deleted_at')->get();
            $resData = [];
            foreach($checkBodyQty as $item){
                if($item->quantity != $item->serve_qty){                
                    $t = $item;
                    $resData[] = $t;                
                }
            }

            if(empty($resData)){
                HeaderRequest::where('id',$header->id)
				->update([
						'closing_plug'=> 1,
						'status_id'=> 13,
						'closed_by'=> CRUDBooster::myId(),
						'closed_at'=> date('Y-m-d H:i:s'),
	
				]);	
            }
       
        }
    }
}