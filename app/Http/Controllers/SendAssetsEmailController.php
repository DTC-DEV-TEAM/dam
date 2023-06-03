<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use CRUDBooster;
use App\Mail\EmailAssets;
use Mail;

class SendAssetsEmailController extends Controller
{
    public function sendEmails(){
        if(!CRUDBooster::isSuperadmin()) {    
            CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"),'danger');
        }
        
        $email = DB::table('mo_body_request')
        ->leftjoin('cms_users', 'mo_body_request.request_created_by', '=', 'cms_users.id')
        ->select( 
                 'mo_body_request.*',
                 'cms_users.email',
                 'cms_users.bill_to',
                )
        ->whereNull('mo_body_request.return_flag')
        ->whereIn('mo_body_request.request_created_by',[601])
        ->groupBy('mo_body_request.request_created_by')
        ->get();

        $deployedAssets = DB::table('mo_body_request')
        ->leftjoin('cms_users', 'mo_body_request.request_created_by', '=', 'cms_users.id')
        ->leftjoin('statuses', 'mo_body_request.status_id', '=', 'statuses.id')
        ->select( 
                 'mo_body_request.*',
                 'cms_users.email',
                 'cms_users.bill_to',
                 'statuses.status_description'
                )
        ->whereNull('mo_body_request.return_flag')
        ->whereIn('mo_body_request.request_created_by',[601])
        ->get()->toArray();

        $finalDataAssets = array();
        foreach($email as $emailKey => $emailData){
            foreach($deployedAssets as $dKey => $dData){
                if($emailData->request_created_by === $dData->request_created_by){
                    $emailData->assets[] = $dData;
                }
            }
            $finalDataAssets[] = $emailData;
        }
        //dd($finalDataAssets);
        foreach($finalDataAssets as $key => $infos){
            Mail::to($infos->email)
            //->cc(['bpg@digits.ph'])
            ->send(new EmailAssets($infos));
        }
     
    	// send all mail in the queue.
        $job = (new \App\Jobs\SendBulkQueueEmail($infos))
            ->delay(
            	now()
            	->addSeconds(2)
            ); 

        dispatch($job);

        echo "Bulk mail send successfully in the background...";
        
    }
}
