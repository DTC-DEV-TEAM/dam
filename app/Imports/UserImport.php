<?php

namespace App\Imports;

use App\Users;
use Illuminate\Support\Facades\Hash;
//use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use DB;
class UserImport implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return Users|null
     */
    public function collection(Collection $rows)
    {
        foreach ($rows->toArray() as $row){
            $priviledgeId = DB::table('cms_privileges')->where(DB::raw('LOWER(name)'),strtolower($row['privilege']))->value('id');
            $approval_array = array();
            $approver = trim(strtolower($row['approver']));
            $explodeApprover = preg_replace('/\s+/', '', $approver);
            $final = array_map('intval',$approver);
            //$approverFromCms = DB::table('cms_privileges')->whereIn(DB::raw('TRIM(LOWER(name))'),$explodeApprover)->value('id');
            //dd($final);
            Users::updateOrcreate([
                'email' => $row['email'] 
            ],
            [
            'name'                 => $row['first_name'] . " " . $row['last_name'],
            'first_name'           => $row['first_name'],
            'last_name'            => $row['last_name'],
            'user_name'            => $row['last_name'].''.substr($row['first_name'], 0, 1),
            'photo'                => 'uploads/1/2019-05/businessman.png',
            'email'                => $row['email'], 
            'id_cms_privileges'    => $priviledgeId,
            'password'             => bcrypt('qwerty'),
            'store_id'             => $row['store'] ? $row['store'] : NULL,
            'approver_id'          => $row['approver'] ? $row['approver'] : NULL,
            ]);
        }
    }
}