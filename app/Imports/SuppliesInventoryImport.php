<?php

namespace App\Imports;

use App\Models\AssetsSuppliesInventory;
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
class SuppliesInventoryImport implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return Users|null
     */
    public function collection(Collection $rows)
    {
        foreach ($rows->toArray() as $key => $row){

            $save = AssetsSuppliesInventory::updateOrcreate([
                'digits_code'      => $row['digits_code'] 
            ],
            [
                'digits_code'      => $row['digits_code'],
                'description'      => $row['item_description'],
                'quantity'         => DB::raw("IF(quantity IS NULL, '".(int)$row['quantity']."', quantity + '".(int)$row['quantity']."')"), 
   

            ]);

            if ($save->wasRecentlyCreated) {
                $save->created_by = CRUDBooster::myId();
                $save->created_at = date('Y-m-d H:i:s');
                $save->updated_at = NULL;
            }else{
                $save->updated_by = CRUDBooster::myId();
                $save->updated_at = date('Y-m-d H:i:s');
            }
            $save->save();
        }
    }
}