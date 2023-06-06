<?php

namespace App\Imports;

use App\Models\PositionsModel;
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
class PositionsImport implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return Users|null
     */
    public function collection(Collection $rows){
        foreach ($rows->toArray() as $key => $row){

            $save = PositionsModel::updateOrcreate([
                'position_description'      => $row['position_description'] 
            ],
            [
                'position_description'      => $row['position_description'],
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