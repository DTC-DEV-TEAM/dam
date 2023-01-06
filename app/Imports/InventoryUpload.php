<?php

namespace App\Imports;
use DB;
use CRUDBooster;
use App\AssetsInventoryBody;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Storage;
use App\MoveOrder;
class InventoryUpload implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows){
        $it_cat = DB::table('category')->where('id', 5)->value('category_description');
        $fa_cat = DB::table('category')->where('id', 1)->value('category_description');
        $DatabaseCounterIt = DB::table('assets_inventory_body')->where('item_category', $it_cat)->count();
        $DatabaseCounterFixAsset = DB::table('assets_inventory_body')->where('item_category',$fa_cat)->count();
        foreach ($rows->toArray() as $row) {
            $name_id           = DB::table('cms_users')->where('id','!=',1)->where(DB::raw('LOWER(name)'),strtolower($row['employee_name']))->value('id');
            $item_id 	        = DB::table('assets')->where(['digits_code' => $row['digits_code']])->first();
        
            if(strtolower($row['status']) == "working" && empty($row['deployed_to'])){
				$statuses = 6;
                $item_condition = "Good";
                $deployed = NULL;
                $quantity = $row['qty'];
			}else if(strtolower($row['status']) == "defective" && empty($row['deployed_to'])) {
                $statuses = 23;
                $item_condition = "Defective";
                $deployed = NULL;
                $quantity = $row['qty'];
            }else{
                $statuses = 3;
                $item_condition = "Good";
                $deployed = $row['deployed_to'];
                $quantity = 0;
            }
            
            if($item_id->category_id == 5){
                $asset_code = "A1".str_pad ($DatabaseCounterIt + 1, 6, '0', STR_PAD_LEFT);
                $DatabaseCounterIt++; // or any rule you want.	
                $location = 3;
                $request_type_id_inventory = 1;
                $item_category = "IT ASSETS";
            }else{
                $asset_code = "A2".str_pad ($DatabaseCounterFixAsset + 1, 6, '0', STR_PAD_LEFT);
				$DatabaseCounterFixAsset++; // or any rule you want.	
                $location = 2;
                $request_type_id_inventory = 5;
                $item_category = "FIXED ASSETS";
            }
         
           
            $latestRequest = DB::table('assets_inventory_body')->select('id')->orderBy('id','DESC')->first();
			$latestRequestId = $latestRequest->id != NULL ? $latestRequest->id : 0;
            AssetsInventoryBody::create([
                'header_id'                      => NULL,
                'item_id'                        => $item_id->id,
                'statuses_id'                    => $statuses,
                'deployed_to'                    => $deployed,
                'location'                       => $location,
                'asset_code'                     => $asset_code,
                'digits_code'                    => $row['digits_code'],
                'item_description'               => $item_id->item_description,
                'value'                          => $row['value'],
                'quantity'                       => $quantity,
                'serial_no'                      => $row['serial_number'],
                'warranty_coverage'              => $row['warranty_coverage'],
                'item_condition'                 => $item_condition,
                'created_by'		             => CRUDBooster::myId(),
                'deployed_to_id'                 => $name_id,
                'request_type_id_inventory'      => $request_type_id_inventory,
                'item_category'                  => $item_category,
            ]); 

            $deployed_id = DB::table('assets_inventory_body')->where('id','>', $latestRequestId)->where('statuses_id',3)->get();
          
            $finalinventory_id =  [];
            $finalcreatedBy =  [];
            $request_type_id =  [];
            $digits_code = [];
            $asset_code = [];
            $item_description = [];
            $category = [];
            $serial_no = [];
            $item_id = [];
            $unit_cost = [];
			foreach($deployed_id as $invData){
				array_push($finalinventory_id, $invData->id);
                array_push($finalcreatedBy, $invData->deployed_to_id);
                array_push($request_type_id, $invData->request_type_id_inventory);
                array_push($digits_code, $invData->digits_code);
                array_push($asset_code, $invData->asset_code);
                array_push($item_description, $invData->item_description);
                array_push($category, $invData->item_category);
                array_push($serial_no, $invData->serial_no);
                array_push($item_id, $invData->item_id);
                array_push($unit_cost, $invData->value);
			}
            for($x=0; $x < count($finalinventory_id); $x++) {
                MoveOrder::create([
                    'status_id'           => 13,
                    'inventory_id'        => $finalinventory_id[$x],
                    'item_id'             => $item_id[$x],
                    'request_created_by'  => $finalcreatedBy[$x],
                    'request_type_id_mo'  => $request_type_id[$x],
                    'digits_code'         => $digits_code[$x],
                    'asset_code'          => $asset_code[$x],
                    'item_description'    => $item_description[$x],
                    'category_id'         => $category[$x],
                    'serial_no'           => $serial_no[$x],
                    'quantity'            => 1,
                    'unit_cost'           => $unit_cost[$x],
                ]);
            }
  
        }
    }

    // public function prepareForValidation($data, $index)
    // {
    //     $data['check_reward']['check'] = false;
    //     $currentDate = date('Y-m-d');
    //     $getPoints = LoyaltyCenter::where('type', '=' ,'creation of account')
    //     ->whereDate('start','<=',$currentDate)
    //     ->whereDate('duration','>=',$currentDate)
    //     ->get()->count();
    //     if($getPoints === 1){
    //         $data['check_reward']['check'] = true;
    //     }
        
    //     $data['check_unique'] = ['email' => $data['email']];
        

    //     return $data;
    // }

    // public function rules(): array
    // {
    //     return [
    //         '*.email' => 'required|distinct',
    //         '*.check_unique' => function($attribute, $value, $onFailure) {
    //             $exist = Customer::get()
    //                 ->where('email', '=', $value['email'])
    //                 ->first();
    //             if (isset($exist)) {
    //                 $onFailure('Email: '.$value['email'].' already exist.');
    //             }
    //         },
    //         '*.check_reward' => function($attribute, $value, $onFailure) {
    //             if ($value['check'] === false) {
    //                 $onFailure('No Active Rewards for Account Creation!');
    //             }
    //         },
    //         '*.first_name' => 'required',
    //         '*.last_name' => 'required',
    //         '*.city' => 'required',
    //         '*.birthday' => 'required',
    //         '*.contact' => 'regex:/^[0-9]{10}+$/',
    //     ];
    // }

    // public function customValidationMessages()
    // {
    //     return [
    //         '*.email.required' => 'Email is required!',
    //         '*.first_name.required' => 'First Name is required!',
    //         '*.last_name.required' => 'Last Name is required!',
    //         '*.city.required' => 'City is required!',
    //         '*.birthday.required' => 'Birthday is required!',
    //         '*.contact.regex' => 'Contact Number Invalid Format!',
    //     ];
    // }
}
