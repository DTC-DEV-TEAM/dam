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
class ItemMasterImport implements ToCollection, SkipsEmptyRows, WithHeadingRow, WithValidation
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
                'digits' => $row['digits'] 
            ],
            [
           
            ]);
        }
    }

    public function prepareForValidation($data, $index)
    {

        // $data['privilege_not_exist']['check'] = false;
        // $check = CmsPrivileges::where(DB::raw('LOWER(name)'),'=',strtolower($data['privilege']))->get()->count();
        // if($check === 1){
        //     $data['privilege_not_exist']['check'] = true;
        // }

        // return $data;
    }

    public function rules(): array
    { 
    //     return [
    //         '*.privilege_not_exist' => function($attribute, $value, $onFailure) {
    //             if ($value['check'] === false) {
    //                 $onFailure('Invalid Privilege. Please refer to the ff. (Employee, Store, IT, HR, Approver, Asset Custodian, Accounting, Manager, Executive, Admin, Warehouse Picker)');
    //             }
    //         },
    //         '*.privilege' => 'required',
    //         //'*.approver' => 'required',
    //    ];
    }
}