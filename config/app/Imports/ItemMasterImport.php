<?php

namespace App\Imports;

use App\Assets;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithConditionalSheets;
use DB;
use CRUDBooster;
class ItemMasterImport implements ToCollection, SkipsEmptyRows, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return Users|null
     */
    public function collection(Collection $rows)
    {
        foreach ($rows->toArray() as $row){
            // Assets::updateOrcreate([
            //     'digits_code' => $row['digits_code'] 
            // ],
            // [
            //     'digits_code' => $row['digits_code'],
            //     'item_description' => $row['item_description'],
            //     'item_cost' => $row['item_cost'],
            //     'brand_id' => $row['brand_id'],
            //     'category_id' => $row['category_id'],  
            //     'class_id' => $row['class_id'],
            //     'vendor_id' => $row['vendor_id'],
            //     'created_by' => CRUDBooster::myId(),
            //     'created_at' => date('Y-m-d H:i:s'),
            //     'asset_tag' => "",
            //     'quantity' => 0,
            //     'add_quantity' => 0,
            //     'total_quantity' => 0,
            //     'status_id' => 0,

            // ]);
            $category         = DB::table('new_category')->where(DB::raw('LOWER(TRIM(category_description))'),strtolower(trim($row['category'])))->value('id');
            $sub_category     = DB::table('new_sub_category')->where(DB::raw('LOWER(TRIM(sub_category_description))'),strtolower(trim($row['sub_category'])))->value('id');
        
            if(in_array($category,[2,3,12])){
                $aimfs_category = 2;
            }else if(in_array($category,[13])){
                $aimfs_category = 4;
            }else if(in_array($category,[10,11])){
                $aimfs_category = 1;
            }else if(in_array($category,[1,5,6,7,8])){
                $aimfs_category = 3;
            }else{
                $aimfs_category = $category;
            }

            Assets::where(['digits_code' => $row['digits_code']])
            ->update(
                        [
                        'aimfs_category'         => $aimfs_category,
                        'aimfs_sub_category'     => $sub_category,       
                        ]
                    );
        }
    }
}