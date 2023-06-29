<?php

namespace App\Imports;

use App\Assets;
use App\AssetsInventoryBody;
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
class InventoryUploadUpdate implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return Users|null
     */
    public function collection(Collection $rows)
    {
        foreach ($rows->toArray() as $row){
            dd($row);
            AssetsInventoryBody::where('asset_code', $row['asset_code'])
                ->update([
                    'digits_code'           => $row['digits_code'], 
                    'item_description'      => $row['item_description']
                ]);
        }
    }
}