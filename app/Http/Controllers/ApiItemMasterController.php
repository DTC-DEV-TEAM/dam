<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiItemMasterController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "assets";        
				$this->permalink   = "item_master";    
				$this->method_type = "get";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
				$query->whereIn('assets.category_id',[1,6,7,8])->whereNotIn('assets.status',['EOL-DIGITS','INACTIVE']);
		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process

		    }

		}