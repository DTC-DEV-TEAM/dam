<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\OrderSchedule;
use App\HeaderRequest;
use CRUDBooster;
use DB;

class CheckApprovalSchedule
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      
        $current_date = Carbon::now();
        $id = $request->route()->parameter('id');
        $userRequest = HeaderRequest::where('id',$id)->first();

        $current_schedule = OrderSchedule::where('status','ACTIVE')->orderBy('id','desc')->first();
        $privileges_list = array_map('intval',explode(",",$current_schedule->privilege_id));
        $approver_list = array_map('intval',explode(",",$current_schedule->approver_id));

        $latest_inactive_date = OrderSchedule::where('status','INACTIVE')->orderBy('id','desc')->first();
        $open_date = strtoupper(date('F d, Y g:i A', strtotime($latest_inactive_date->end_date. ' + 4 days + 18 hours + 5 minutes')));
        $data['open_date'] = $open_date;

        if($current_date->between(Carbon::parse($current_schedule->start_date), Carbon::parse($current_schedule->end_date))){
            if(empty($privileges_list) ||  in_array($userRequest->privilege_id, $privileges_list) || $privileges_list['0'] == 0) { //additional code 20200624
                return $next($request);
            }
            else {
                return response()->view('errors.page-approval-expired');
            }
            
        }
        
        //update order schedule
        $order_update = OrderSchedule::where('status','ACTIVE')->update(['status'=>'INACTIVE']);
        \Log::info('Deactivate schedules: '.$order_update);
        return response()->view('errors.page-approval-expired',$data);
        // $usersPrivilege = DB::table('cms_privileges')->select('id')->whereNull('cannot_create')->get();
        
        // if($usersPrivilege->isNotEmpty()){
        //     return $next($request);
        // }else{
        //     return response()->view('assets.add-service-unavailable');
        // }   
    }
}
