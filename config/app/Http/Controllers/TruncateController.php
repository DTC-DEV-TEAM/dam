<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use CRUDBooster;

class TruncateController extends \crocodicstudio\crudbooster\controllers\CBController
{
    public function dbtruncate(){
        if(!CRUDBooster::isSuperadmin()) {    
            CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"),'danger');
        }
        DB::table('assets_header_images')->truncate();
        DB::table('assets_inventory_body')->truncate();
        DB::table('assets_inventory_body_for_approval')->truncate();
        DB::table('assets_inventory_header')->truncate();
        DB::table('assets_inventory_header_for_approval')->truncate();
        return "Truncated Successfully";

        if(app()->environment('production')) {
            return "Production";
        }else if(app()->environment('staging')){
            return "Staging";
        }else{
            return "Local";
        }
    }
}
