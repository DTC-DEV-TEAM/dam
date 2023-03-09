<?php

namespace App\Imports;

use App\Models\Applicant;
use App\Models\ErfHeaderRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
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
use Carbon\Carbon;
use Illuminate\Support\Str;
class ApplicantUpload implements ToCollection, SkipsEmptyRows, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return Users|null
     */
    public function collection(Collection $rows)
    {
        $assoc_array = array();
        foreach ($rows->toArray() as $row){
            $getStatus =  DB::table('statuses')->where(DB::raw('LOWER(REPLACE(`status_description`," ",""))'), strtolower(str_replace(' ', '', $row['status'])))->value('id');
            if($getStatus === 36){
                $new_key = $row['erf_number'] . $row['status'];
                // Presence of combination of company_code and clerk_code in the assoc_array indicates that
                // there is duplicate entry in the Excel being imported. So, abort the process and report this to user.
                if (array_key_exists($new_key, $assoc_array)) {
                    return CRUDBooster::redirect(CRUDBooster::mainpath(),"Duplicate Jo in more than 1 Applicant not allowed!","danger");
                }
    
                $assoc_array[$new_key] = $value;
            }
            
       
            if($getStatus == 36){
                $status = 31;
                ErfHeaderRequest::where('reference_number',$row['erf_number'])
				->update([
					'status_id'		 => 31
				]);	
            }else{
                $status = $getStatus;
            }
            $save = Applicant::updateOrcreate([
                'full_name'   => strtolower(str_replace(' ', '', $row['first_name'])).''.strtolower(str_replace(' ', '', $row['last_name'])),
            ],
            [
                'erf_number'  => $row['erf_number'],
                'status'      => $status,
                'first_name'  => $row['first_name'],
                'last_name'   => $row['last_name'],
                'full_name'   => strtolower(str_replace(' ', '', $row['first_name'])).''.strtolower(str_replace(' ', '', $row['last_name'])),
                'screen_date' => Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['screen_date'])),
          
                
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

    public function prepareForValidation($data, $index)
    {
        //Statuses
        $data['status_exist']['check'] = false;
        $checkRowDb = DB::table('statuses')->select(DB::raw("LOWER(REPLACE(`status_description`,' ','')) AS status"))->whereIn('id', [8,34,35,36])->get()->toArray();
        $checkRowDbColumn = array_column($checkRowDb, 'status');

        if(!empty($data['status'])){
            if(in_array(strtolower(str_replace(' ', '', $data['status'])), $checkRowDbColumn)){
                $data['status_exist']['check'] = true;
            }
        }else{
            $data['status_exist']['check'] = true;
        }

         //Erf number
         $data['check_erf_exist']['check'] = false;
         $checkRowDbErf = DB::table('erf_header_request')->select(DB::raw("(reference_number) AS erf_number"))->where('status_id', 30)->get()->toArray();
         $checkRowDbColumnErf = array_column($checkRowDbErf, 'erf_number');
     
         if(!empty($data['erf_number'])){
             if(in_array($data['erf_number'], $checkRowDbColumnErf)){
                 $data['check_erf_exist']['check'] = true;
             }
         }else{
             $data['check_erf_exist']['check'] = true;
         }

          //applicant jo done
          $data['check_jo_done_exist']['check'] = false;
          $checkRowDbJoDone = DB::table('applicant_table')->select(DB::raw("(full_name) AS fullname"))->where('status', 31)->get()->toArray();
          $checkRowDbColumnJoDone = array_column($checkRowDbJoDone, 'fullname');

          if(!empty($data['first_name']) && !empty($data['last_name'])){
              if(in_array(strtolower(str_replace(' ', '', $data['first_name'])).''.strtolower(str_replace(' ', '', $data['last_name'])), $checkRowDbColumnJoDone)){
                  $data['check_jo_done_exist']['check'] = true;
              }
          }else{
              $data['check_jo_done_exist']['check'] = true;
          }

           //applicant cancelled
           $data['check_cancelled_exist']['check'] = false;
           $checkRowDbCancelled = DB::table('applicant_table')->select(DB::raw("(full_name) AS fullname"))->where('status', 8)->get()->toArray();
           $checkRowDbColumnCancelled = array_column($checkRowDbCancelled, 'fullname');
 
           if(!empty($data['first_name']) && !empty($data['last_name'])){
               if(in_array(strtolower(str_replace(' ', '', $data['first_name'])).''.strtolower(str_replace(' ', '', $data['last_name'])), $checkRowDbColumnCancelled)){
                   $data['check_cancelled_exist']['check'] = true;
               }
           }else{
               $data['check_cancelled_exist']['check'] = true;
           }
        
        return $data;
    }

    public function rules(): array
    {
        return [
            '*.first_name'   => 'required',
            '*.last_name'    => 'required',
            '*.status_exist' => function($attribute, $value, $onFailure) {
                if ($value['check'] === false) {
                    $onFailure('Invalid Status! Please refer to valid status in system');
                }
            },
            '*.check_erf_exist' => function($attribute, $value, $onFailure) {
                if ($value['check'] === false) {
                    $onFailure('ERF not verified, Please refer to Verified ERF in system');
                }
            },
            '*.check_jo_done_exist' => function($attribute, $value, $onFailure) {
                if ($value['check'] === true) {
                    $onFailure('Applicant already Hired!');
                }
            },
            '*.check_cancelled_exist' => function($attribute, $value, $onFailure) {
                if ($value['check'] === true) {
                    $onFailure('Applicant already Exist/Cancelled!');
                }
            },
            '*.status'       => 'required',
            '*.erf_number'   => 'required',
            '*.screen_date'  => 'required',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.status.status_exist'             => 'Invalid Status :input.',
            '*.erf_number.check_erf_exist'      => 'ERF not veried :input.',
            '*.erf_number.required'             => 'Erf Number field is required.',
            '*.first_name.required'             => 'First Name field is required!.',
            '*.last_name.required'              => 'Last Name field is required!.',
            '*.screen_date.required'            => 'Screen Date field is required.',
        ];
    }
}