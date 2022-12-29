<?php

namespace App\Imports;

use App\Assets;
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
class ItemMasterImport implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return Users|null
     */
    public function collection(Collection $rows)
    {
        foreach ($rows->toArray() as $row){
        
            Assets::updateOrcreate([
                'digits_code' => $row['digits_code'] 
            ],
            [
                'digits_code' => $row['digits_code'],
                'item_description' => $row['item_description'],
                'brand_id' => $row['brand_id'],
                'category_id' => $row['category_id'],  
                'class_id' => $row['class_id'],
                'vendor_id' => $row['vendor_id'],
                'created_by' => CRUDBooster::myId(),
                'created_at' => date('Y-m-d H:i:s'),
                'asset_tag' => "",
                'quantity' => 0,
                'add_quantity' => 0,
                'total_quantity' => 0,
                'status_id' => 0,

            ]);
        }
    }
}