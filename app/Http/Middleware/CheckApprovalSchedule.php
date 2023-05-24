<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\OrderSchedule;
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
        $usersPrivilege = DB::table('cms_privileges')->select('id')->whereNull('cannot_create')->get();
        
        if($usersPrivilege->isNotEmpty()){
            return $next($request);
        }else{
            return response()->view('assets.add-service-unavailable');
        }   
    }
}
