<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use DB;
use CRUDBooster;
use App\Mail\EmailAssets;
use Mail;

class SendBulkQueueEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $infos;
    public $timeout = 7200;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($infos)
    {
        $this->infos = $infos;
        $this->email = $infos->email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(){
        // $email = DB::table('mo_body_request')
        // ->leftjoin('cms_users', 'mo_body_request.request_created_by', '=', 'cms_users.id')
        // ->select( 
        //          'mo_body_request.*',
        //          'cms_users.email',
        //          'cms_users.bill_to',
        //         )
        // ->whereNull('mo_body_request.return_flag')
        // //->whereIn('mo_body_request.request_created_by',[1099,1109,956,601,863])
        // ->whereIn('mo_body_request.request_created_by',[601])
        // ->groupBy('mo_body_request.request_created_by')
        // ->get();

        // $deployedAssets = DB::table('mo_body_request')
        // ->leftjoin('cms_users', 'mo_body_request.request_created_by', '=', 'cms_users.id')
        // ->leftjoin('statuses', 'mo_body_request.status_id', '=', 'statuses.id')
        // ->select( 
        //          'mo_body_request.*',
        //          'cms_users.email',
        //          'cms_users.bill_to',
        //          'statuses.status_description'
        //         )
        // ->whereNull('mo_body_request.return_flag')
        // //->whereIn('mo_body_request.request_created_by',[1099,1109,956,601,863])
        // ->whereIn('mo_body_request.request_created_by',[601])
        // ->get()->toArray();

        // $finalDataAssets = array();
        // foreach($email as $emailKey => $emailData){
        //     foreach($deployedAssets as $dKey => $dData){
        //         if($emailData->request_created_by === $dData->request_created_by){
        //             $emailData->assets[] = $dData;
        //         }
        //     }
        //     $finalDataAssets[] = $emailData;
        // }
        // $input['subject'] = $this->details['subject'];
        // foreach($finalDataAssets as $key => $infos){
            Mail::to($infos->email)
            //->cc(['bpg@digits.ph'])
            ->send(new EmailAssets($infos));
        //}
    }
}
