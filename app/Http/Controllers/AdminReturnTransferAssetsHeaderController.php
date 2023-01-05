<?php namespace App\Http\Controllers;

	use Session;
	//use Request;
	use Illuminate\Http\Request;
	use DB;
	use CRUDBooster;
	use App\Models\Requests;
	use App\Models\ReturnTransferAssets;
	use App\Models\ReturnTransferAssetsHeader;
	use App\MoveOrder;
	class AdminReturnTransferAssetsHeaderController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = false;
			$this->button_action_style = "button_icon";
			$this->button_add = false;
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "return_transfer_assets_header";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Status","name"=>"status","join"=>"statuses,status_description"];
			$this->col[] = ["label"=>"Name","name"=>"requestor_name","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Return Type","name"=>"request_type_id","join"=>"requests,request_name"];
			$this->col[] = ["label"=>"Type of Request","name"=>"request_type"];
			$this->col[] = ["label"=>"Requested Date","name"=>"requested_date"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];

			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ["label"=>"Status","name"=>"status","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Reference No","name"=>"reference_no","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Asset Code","name"=>"asset_code","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Digits Code","name"=>"digits_code","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Description","name"=>"description","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Asset Type","name"=>"asset_type","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Transacted By","name"=>"transacted_by","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Transacted Date","name"=>"transacted_date","type"=>"datetime","required"=>TRUE,"validation"=>"required|date_format:Y-m-d H:i:s"];
			# OLD END FORM

			/* 
	        | ---------------------------------------------------------------------- 
	        | Sub Module
	        | ----------------------------------------------------------------------     
			| @label          = Label of action 
			| @path           = Path of sub module
			| @foreign_key 	  = foreign key of sub table/module
			| @button_color   = Bootstrap Class (primary,success,warning,danger)
			| @button_icon    = Font Awesome Class  
			| @parent_columns = Sparate with comma, e.g : name,created_at
	        | 
	        */
	        $this->sub_module = array();


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Action Button / Menu
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
	        | @icon        = Font awesome class icon. e.g : fa fa-bars
	        | @color 	   = Default is primary. (primary, warning, succecss, info)     
	        | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
	        | 
	        */
	        $this->addaction = array();
			if(CRUDBooster::isUpdate()) {

				$pending           = DB::table('statuses')->where('id', 1)->value('id');
				$released  = 		DB::table('statuses')->where('id', 12)->value('id');

				$this->addaction[] = ['title'=>'Cancel Request','url'=>CRUDBooster::mainpath('getRequestCancelReturn/[id]'),'icon'=>'fa fa-times', "showIf"=>"[status] == $pending"];
			
				//$this->addaction[] = ['title'=>'Receive Asset','url'=>CRUDBooster::mainpath('getRequestReceive/[id]'),'icon'=>'fa fa-check', "showIf"=>"[status_id] == $released"];
			}

	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Button Selected
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @icon 	   = Icon from fontawesome
	        | @name 	   = Name of button 
	        | Then about the action, you should code at actionButtonSelected method 
	        | 
	        */
	        $this->button_selected = array();

	                
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add alert message to this module at overheader
	        | ----------------------------------------------------------------------     
	        | @message = Text of message 
	        | @type    = warning,success,danger,info        
	        | 
	        */
	        $this->alert        = array();
	                

	        
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add more button to header button 
	        | ----------------------------------------------------------------------     
	        | @label = Name of button 
	        | @url   = URL Target
	        | @icon  = Icon from Awesome.
	        | 
	        */
	        $this->index_button = array();
			if(CRUDBooster::getCurrentMethod() == 'getIndex'){
				$this->index_button[] = ["label"=>"Return Assets","icon"=>"fa fa-files-o","url"=>CRUDBooster::mainpath('return-assets'),"color"=>"success"];
			}


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Customize Table Row Color
	        | ----------------------------------------------------------------------     
	        | @condition = If condition. You may use field alias. E.g : [id] == 1
	        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.        
	        | 
	        */
	        $this->table_row_color = array();     	          

	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | You may use this bellow array to add statistic at dashboard 
	        | ---------------------------------------------------------------------- 
	        | @label, @count, @icon, @color 
	        |
	        */
	        $this->index_statistic = array();



	        /*
	        | ---------------------------------------------------------------------- 
	        | Add javascript at body 
	        | ---------------------------------------------------------------------- 
	        | javascript code in the variable 
	        | $this->script_js = "function() { ... }";
	        |
	        */
	        $this->script_js = "

			$('.fa.fa-times').click(function(){

				var strconfirm = confirm('Are you sure you want to cancel this request?');
				if (strconfirm == true) {
					return true;
				}else{
					return false;
					window.stop();

				}

			})
			";


            /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code before index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it before index table
	        | $this->pre_index_html = "<p>test</p>";
	        |
	        */
	        $this->pre_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code after index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it after index table
	        | $this->post_index_html = "<p>test</p>";
	        |
	        */
	        $this->post_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include Javascript File 
	        | ---------------------------------------------------------------------- 
	        | URL of your javascript each array 
	        | $this->load_js[] = asset("myfile.js");
	        |
	        */
	        $this->load_js = array();
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Add css style at body 
	        | ---------------------------------------------------------------------- 
	        | css code in the variable 
	        | $this->style_css = ".style{....}";
	        |
	        */
	        $this->style_css = "
			.fa.fa-times{
				color:#df4759;
				font-size:15px;
				margin-top: 2px;
			}
			";
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include css File 
	        | ---------------------------------------------------------------------- 
	        | URL of your css each array 
	        | $this->load_css[] = asset("myfile.css");
	        |
	        */
	        $this->load_css = array();
	        
	        
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for button selected
	    | ---------------------------------------------------------------------- 
	    | @id_selected = the id selected
	    | @button_name = the name of button
	    |
	    */
	    public function actionButtonSelected($id_selected,$button_name) {
	        //Your code here
	            
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate query of index result 
	    | ---------------------------------------------------------------------- 
	    | @query = current sql query 
	    |
	    */
	    public function hook_query_index(&$query) {
	        //Your code here
	            
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	$pending      =  	 DB::table('statuses')->where('id', 1)->value('status_description');
			$cancelled    =  	 DB::table('statuses')->where('id', 8)->value('status_description');
			$forturnover  =      DB::table('statuses')->where('id', 24)->value('status_description');
			$toClose      =      DB::table('statuses')->where('id', 25)->value('status_description');
			$closed       =      DB::table('statuses')->where('id', 13)->value('status_description');
			if($column_index == 1){
				if($column_value == $pending){
					$column_value = '<span class="label label-warning">'.$pending.'</span>';
				}else if($column_value == $cancelled){
					$column_value = '<span class="label label-danger">'.$cancelled.'</span>';
				}else if($column_value == $forturnover){
					$column_value = '<span class="label label-info">'.$forturnover.'</span>';
				}else if($column_value == $toClose){
					$column_value = '<span class="label label-info">'.$toClose.'</span>';
				}else if($column_value == $closed){
					$column_value = '<span class="label label-success">'.$closed.'</span>';
				}
			}
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before add data is execute
	    | ---------------------------------------------------------------------- 
	    | @arr
	    |
	    */
	    public function hook_before_add(&$postdata) {        
	        
		

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	    public function hook_after_add($id) {        
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before update data is execute
	    | ---------------------------------------------------------------------- 
	    | @postdata = input post data 
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_edit(&$postdata,$id) {        
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_edit($id) {
	        //Your code here 

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_delete($id) {
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_delete($id) {
	        //Your code here

	    }

		public function getReturnAssets(){
			
			if(!CRUDBooster::isCreate() && $this->global_privilege == false) {
				CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
			}

			$this->cbLoader();

			$data = array();

			$data['page_title'] = 'Return Request';

			$closed =  	DB::table('statuses')->where('id', 13)->value('id');
			$for_closing =  	DB::table('statuses')->where('id', 19)->value('id');

			$data['mo_body'] = MoveOrder::leftjoin('header_request', 'mo_body_request.header_request_id', '=', 'header_request.id')
				->leftjoin('request_type', 'header_request.purpose', '=', 'request_type.id')
				->leftjoin('requests', 'header_request.request_type_id', '=', 'requests.id')
				->leftjoin('condition_type', 'header_request.conditions', '=', 'condition_type.id')
				->leftjoin('cms_users as employees', 'header_request.employee_name', '=', 'employees.id')
				->leftjoin('companies', 'header_request.company_name', '=', 'companies.id')
				->leftjoin('departments', 'header_request.department', '=', 'departments.id')
				->leftjoin('positions', 'header_request.position', '=', 'positions.id')
				->leftjoin('locations', 'header_request.store_branch', '=', 'locations.id')
				->leftjoin('cms_users as requested', 'header_request.created_by','=', 'requested.id')
				->leftjoin('cms_users as approved', 'header_request.approved_by','=', 'approved.id')
				->leftjoin('cms_users as recommended', 'header_request.recommended_by','=', 'recommended.id')
				->leftjoin('cms_users as tagged', 'header_request.purchased2_by','=', 'tagged.id')
			
				->select(
						'header_request.*',
						'mo_body_request.*',
						'mo_body_request.id as mo_id',
						'header_request.id as requestid',
						'header_request.created_at as created',
						'request_type.*',
						'condition_type.*',
						'requested.name as requestedby',
						'employees.bill_to as employee_name',
						'employees.company_name_id as company_name',
						'departments.department_name as department',
						'requests.request_name as asset_type',
						'locations.store_name as store_branch',
						'approved.name as approvedby',
						'recommended.name as recommendedby',
						'tagged.name as taggedby',
						'header_request.created_at as created_at'
						)
				->where('mo_body_request.request_created_by', CRUDBooster::myId())
				->whereIn('mo_body_request.status_id', [$closed, $for_closing])
				->whereNull('mo_body_request.return_flag')
				->get();
           
			return $this->view("assets.return-assets", $data);
		}

		public function saveReturnAssets(Request $request){
			$moId = $request['Ids'];
			$rid = $request['request_type_id'];
			$request_type_id = array_unique($rid);
			
			$getData = MoveOrder::leftjoin('header_request', 'mo_body_request.header_request_id', '=', 'header_request.id')
			->leftjoin('requests', 'header_request.request_type_id', '=', 'requests.id')
			->select(
				'header_request.*',
				'mo_body_request.*',
				'mo_body_request.id as mo_id',
				'requests.*',
				)
			->whereIn('mo_body_request.id', $moId)
			->get();

			//Get Latest ID
			$callStart = $this->call_start;
			$latestRequest = DB::table('return_transfer_assets_header')->select('id')->orderBy('id','DESC')->first();
			$latestRequestId = $latestRequest->id != NULL ? $latestRequest->id : 0;
			// Header Area
			$conHeader = [];
			$conHeaderSave = [];
			foreach($request_type_id as $hKey => $hData){
				$conHeader['status'] = 1;
				$conHeader['requestor_name'] = CRUDBooster::myId();
				$conHeader['request_type_id'] = $hData;
				$conHeader['request_type'] = "RETURN";
				$conHeader['requested_by'] = CRUDBooster::myId(); 
				$conHeader['requested_date'] = date('Y-m-d H:i:s');
				if($hData == 1){
					$conHeader['location_to_pick'] = 3; 
				}else{
					$conHeader['location_to_pick'] = NULL; 
				}
				$conHeaderSave[] = $conHeader;
			}
		
			ReturnTransferAssetsHeader::insert($conHeaderSave);
			$itId = DB::table('return_transfer_assets_header')->select('id')->where('id','>', $latestRequestId)->where('request_type_id',1)->first();
			$faId = DB::table('return_transfer_assets_header')->select('id')->where('id','>', $latestRequestId)->where('request_type_id',5)->first();
			
			$resultArrforIT = [];
			foreach($getData as $item){
				if(strtolower($item['request_type_id']) == 1){
					for($i = 0; $i < $item['request_type_id']; $i++){
						$t = $item;
						$t['return_header'] = $itId->id;
						$resultArrforIT[] = $t;
					}
				}
			}
			$resultArrforFA = [];
			foreach($getData as $itemFa){
				if(strtolower($itemFa['request_type_id']) == 5){
					for($i = 0; $i < $itemFa['request_type_id']; $i++){
						$fa = $itemFa;
						$fa['return_header'] = $faId->id;
						$resultArrforFA[] = $fa;
					}
				}
			}
			
			$finalReturnData = array_merge($resultArrforIT, $resultArrforFA);
			// Body Area
			$container = [];
			$containerSave = [];
			$count_header       = DB::table('return_transfer_assets')->count();
			foreach($getData as $rKey => $rData){		
				$container['status'] = 1;
				$container['return_header_id'] = $rData['return_header'];
				if($rData['request_type_id'] == 1){
					$container['reference_no'] = "1".str_pad($count_header + 1, 6, '0', STR_PAD_LEFT)."ITAR";
					$count_header++;
					$container['location_to_pick'] = 3;
				}else{
					$container['reference_no'] = "1".str_pad($count_header + 1, 6, '0', STR_PAD_LEFT)."FAR";
					$count_header++;
					$container['location_to_pick'] = NULL;
				}
				$container['asset_code'] =  $rData['asset_code'];
				$container['digits_code'] = $rData['digits_code'];
				$container['description'] = $rData['item_description'];
				$container['asset_type'] = $rData['request_name'];
				$container['requested_by'] = CRUDBooster::myId(); 
				$container['requested_date'] = date('Y-m-d H:i:s');
				$containerSave[] = $container;
			}
			ReturnTransferAssets::insert($containerSave);

			for ($i = 0; $i < count($moId); $i++) {
				MoveOrder::where(['id' => $moId[$i]])
				   ->update([
						   'return_flag' => 1,
				           ]);
			}

			$message = ['status'=>'success', 'message' => 'Send Successfully!','redirect_url'=>CRUDBooster::mainpath()];
			echo json_encode($message);
		}

		public function getRequestCancelReturn($id) {
			ReturnTransferAssetsHeader::where('id',$id)
				->update([
					    'status' => 8
				]);	

			$getAssetCode = ReturnTransferAssets::where('return_header_id',$id)->get();
			$arrCode = [];
			foreach($getAssetCode as $code){
              array_push($arrCode, $code['asset_code']);
			}

			for ($i = 0; $i < count($arrCode); $i++) {
				MoveOrder::where('asset_code',$arrCode[$i])
				->update([
						'return_flag'=> NULL,
				]);	
		    }
			ReturnTransferAssets::where('return_header_id',$id)
				->update([
					    'status' => 8,
						'archived'=> date('Y-m-d H:i:s'),
				]);	

			CRUDBooster::redirect(CRUDBooster::mainpath(), trans("Request has been cancelled successfully!"), 'info');
		}
	}