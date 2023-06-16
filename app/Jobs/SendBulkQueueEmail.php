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
    protected $details;
   
    public $timeout = 7200;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(){
        ini_set('memory_limit','-1');
        ini_set('max_execution_time', 0);
        $email = DB::table('mo_body_request')
        ->leftjoin('cms_users', 'mo_body_request.request_created_by', '=', 'cms_users.id')
        ->select( 
                 'mo_body_request.*',
                 'cms_users.email',
                 'cms_users.bill_to',
                )
        ->whereNull('mo_body_request.return_flag')
        ->whereIn('mo_body_request.request_created_by',[839,927,929,940,1035,969,977,994,1048,966,865,998,1067,963,862,965,997,951,868,1104,1100,1045,1017,980])
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
        ->whereIn('mo_body_request.request_created_by',[839,927,929,940,1035,969,977,994,1048,966,865,998,1067,963,862,965,997,951,868,1104,1100,1045,1017,980])
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
        // dd($finalDataAssets);
        foreach($finalDataAssets as $key => $infos){
            Mail::to($infos->email)
            ->cc(['marvinmosico@digits.ph'])
            ->send(new EmailAssets($infos));
        }
    }
}
