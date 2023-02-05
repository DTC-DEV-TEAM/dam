<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use App\Users;
	use App\Models\ErfHeaderRequest;
	use App\Models\ErfBodyRequest;
	use App\ApprovalMatrix;
	use App\StatusMatrix;
	use App\Models\ErfHeaderDocuments;
	use Illuminate\Support\Facades\Response;
	use App\HeaderRequest;
	use App\BodyRequest;

	class AdminErfApprovalController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->button_edit = true;
			$this->button_delete = false;
			$this->button_detail = false;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "erf_header_request";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Status","name"=>"status_id","join"=>"statuses,status_description"];
			$this->col[] = ["label"=>"Reference Number","name"=>"reference_number"];
			$this->col[] = ["label"=>"Company Name","name"=>"company"];
			$this->col[] = ["label"=>"Department","name"=>"department","join"=>"departments,department_name"];
			$this->col[] = ["label"=>"Position","name"=>"position"];
			$this->col[] = ["label"=>"Work Location","name"=>"work_location"];
			$this->col[] = ["label"=>"Requested Date","name"=>"date_requested"];
			$this->col[] = ["label"=>"Date Needed","name"=>"date_needed"];
			$this->col[] = ["label"=>"Requested By","name"=>"created_by","join"=>"cms_users,name"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];

			# END FORM DO NOT REMOVE THIS LINE

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
	        $this->script_js = NULL;


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
	        $this->style_css = NULL;
	        
	        
	        
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
			if(CRUDBooster::isSuperadmin()){

				$pending  = 1;
				$query->orderBy('erf_header_request.status_id', 'DESC')->where('erf_header_request.status_id', $pending)->orderBy('erf_header_request.id', 'DESC');
			
			}else{
				$pending  = 1;
				$approvalMatrix = Users::where('cms_users.approver_id', CRUDBooster::myId())->get();
				$approval_array = array();
				foreach($approvalMatrix as $matrix){
				    array_push($approval_array, $matrix->id);
				}
				$approval_string = implode(",",$approval_array);
				$userslist = array_map('intval',explode(",",$approval_string));
				$query->whereIn('erf_header_request.created_by', $userslist)
				->where('erf_header_request.status_id', $pending) 
				->orderBy('erf_header_request.id', 'DESC');

			}
	            
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
			$pending  =  		DB::table('statuses')->where('id', 1)->value('status_description');       
			if($column_index == 1){
				if($column_value == $pending){
					$column_value = '<span class="label label-warning">'.$pending.'</span>';
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
	        //Your code here

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
	        $fields = Request::all();
			$dataLines = array();
			$approval_action 		= $fields['approval_action'];
			$approver_comments 		= $fields['additional_notess'];
           
			$erf_header = ErfHeaderRequest::where(['id' => $id])->first();
			$erf_body = ErfBodyRequest::where(['header_request_id' => $id])->get();
			$req_type = ErfBodyRequest::where(['header_request_id' => $id])->groupBy('request_type_id')->get();
			$latestRequest = DB::table('header_request')->select('id')->orderBy('id','DESC')->first();
			$latestRequestId = $latestRequest->id != NULL ? $latestRequest->id : 0;
			
			if($approval_action  == 1){
				ErfHeaderRequest::where('id',$id)
				->update([
					'status_id'		                    => 29,
				    'approver_comments'	                => $approver_comments,
				    'approved_immediate_head_by' 		=> CRUDBooster::myId(),
				    'approved_immediate_head_at' 		=> date('Y-m-d H:i:s'),
				]);	
				//add in arf heaader request table
			$count_header       = DB::table('header_request')->count();
			$status		 		= StatusMatrix::where('current_step', 2)
								  ->where('request_type', $erf_header->request_type_id)
								  ->value('status_id');
		    $arfHeaderSave = [];
			$arfHeaderContainer = [];
			foreach($req_type as $arfHeadKey => $arfHeadVal){
				if($arfHeadVal['request_type_id'] == 1){
					$arfHeaderContainer['status_id']              = 4;
					$arfHeaderContainer['application'] 			  = $erf_header->application;
				    $arfHeaderContainer['application_others'] 	  = $erf_header->application_others;
					$arfHeaderContainer['to_reco']                = 1;
				}else{
					$arfHeaderContainer['status_id']              = 7;
					$arfHeaderContainer['application'] 			  = NULL;
				    $arfHeaderContainer['application_others'] 	  = NULL;  
					$arfHeaderContainer['to_reco']                = 0;
				}
				$arfHeaderContainer['reference_number']		      = "ARF-".str_pad($count_header + 1, 7, '0', STR_PAD_LEFT);
				$count_header ++;
				$arfHeaderContainer['employee_name' ]		      = $erf_header->reference_number;
				$arfHeaderContainer['company_name'] 			  = "DIGITS";
				$arfHeaderContainer['position'] 				  = $erf_header->position;
				$arfHeaderContainer['department' ]				  = $erf_header->department;
				$arfHeaderContainer['store_branch']		          = NULL;
				$arfHeaderContainer['purpose'] 				      = 6;
				$arfHeaderContainer['conditions'] 				  = NULL;
				$arfHeaderContainer['quantity_total'] 			  = $arfHeadVal->quantity;
				$arfHeaderContainer['cost_total'] 				  = NULL;
				$arfHeaderContainer['total'] 					  = NULL;
				$arfHeaderContainer['requestor_comments'] 		  = NULL;
				$arfHeaderContainer['created_by'] 				  = NULL;
				$arfHeaderContainer['created_at'] 				  = date('Y-m-d H:i:s');
				$arfHeaderContainer['request_type_id']		 	  = $arfHeadVal['request_type_id'];
				$arfHeaderContainer['privilege_id']		 	      = NULL;
			
				$arfHeaderSave[] = $arfHeaderContainer;
			}
			HeaderRequest::insert($arfHeaderSave);
			$itId = DB::table('header_request')->select('*')->where('id','>', $latestRequestId)->where('request_type_id',1)->first();
			$faId = DB::table('header_request')->select('*')->where('id','>', $latestRequestId)->where('request_type_id',5)->first();
			$SuppliesId = DB::table('header_request')->select('*')->where('id','>', $latestRequestId)->where('request_type_id',7)->first();
	
			$resultArrforIT = [];
			foreach($erf_body as $item){
				if($item['request_type_id'] == 1){
					for($i = 0; $i < $item['request_type_id']; $i++){
						$t = $item;
						$t['header_request_id'] = $itId->id;
						$resultArrforIT[] = $t;
					}
				}
			}

			$resultArrforFA = [];
			foreach($erf_body as $itemFa){
				if($itemFa['request_type_id'] == 5){
					for($x = 0; $x < $itemFa['request_type_id']; $x++){
						$fa = $itemFa;
						$fa['header_request_id'] = $faId->id;
						$resultArrforFA[] = $fa;
					}
				}
			}

			$resultArrforSu = [];	
			foreach($erf_body as $itemSu){
				if($itemSu['request_type_id'] == 7){
					for($s = 0; $s < $itemSu['request_type_id']; $s++){
						$su = $itemSu;
						$su['header_request_id'] = $SuppliesId->id;
						$resultArrforSu[] = $su;
					}
				}
			}
			$arf_ids = [];
			if($itId->id){
				array_push($arf_ids, $itId->id);
			}
			if($faId->id){
				array_push($arf_ids, $faId->id);
			}
			if($SuppliesId->id){
				array_push($arf_ids, $SuppliesId->id);
			}

			$arf_id =  implode(", ",$arf_ids);

			ErfHeaderRequest::where('id',$id)
				->update([
					'arf_id'                     => $arf_id,
					'to_tag_employee'            => 1
				]);	

			//save items in Body Request
			$insertData = [];
			$insertContainer = [];
			foreach($erf_body as $key => $val){
				$insertContainer['header_request_id']   = $val['header_request_id'];
				$insertContainer['digits_code'] 	    = NULL;
				$insertContainer['item_description'] 	= $val['item_description'];
				$insertContainer['category_id'] 		= $val['category_id'];
				$insertContainer['sub_category_id'] 	= $val['sub_category_id'];
				$insertContainer['app_id'] 			    = NULL;
				$insertContainer['app_id_others'] 	    = NULL;
				$insertContainer['quantity'] 			= $val['quantity'];
				$insertContainer['unit_cost'] 		    = NULL;
				if($request_type_id == 5){
					$insertContainer['to_reco'] = 0;
				}else{
					if (str_contains($val['sub_category_id'], 'LAPTOP') || str_contains($val['sub_category_id'], 'DESKTOP')) {
						$insertContainer['to_reco'] = 1;
					}else{
						$insertContainer['to_reco'] = 0;
					}
				}
				$insertContainer['created_at'] 		= date('Y-m-d H:i:s');
				$insertData[] = $insertContainer;
			}
		
			DB::beginTransaction();
			try {
				BodyRequest::insert($insertData);
				DB::commit();
			} catch (\Exception $e) {
				DB::rollback();
				CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
			}
			
		
			CRUDBooster::redirect(CRUDBooster::mainpath(), trans('Successfully Added!'), 'success');
				
			}else{
				ErfHeaderRequest::where('id',$id)
				->update([
					'status_id'		                    => 5,
				    'approver_comments'	                => $approver_comments,
				    'approved_immediate_head_by' 		=> CRUDBooster::myId(),
				    'approved_immediate_head_at' 		=> date('Y-m-d H:i:s'),
				]);	
			}
			CRUDBooster::redirect(CRUDBooster::mainpath(), trans('Successfully Rejected!'), 'success');
			
		
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

		public function getEdit($id){
			
			$this->cbLoader();
            if(!CRUDBooster::isRead() && $this->global_privilege==FALSE) {    
                CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
            }

			$data = array();

			$data['page_title'] = 'View Erf For Approval';

			$data['Header'] = ErfHeaderRequest::
				leftjoin('companies', 'erf_header_request.company', '=', 'companies.id')
				->leftjoin('departments', 'erf_header_request.department', '=', 'departments.id')
				->select(
						'erf_header_request.*',
						'erf_header_request.id as requestid',
						'departments.department_name as department'
						)
				->where('erf_header_request.id', $id)->first();
		
			$res_req = explode(",",$data['Header']->required_exams);
			$interact_with = explode(",",$data['Header']->employee_interaction);
			$asset_usage = explode(",",$data['Header']->asset_usage);
			$application = explode(",",$data['Header']->application);
			$data['required_exams'] = $res_req;
			$data['interaction'] = $interact_with;
			$data['asset_usage'] = $asset_usage;
			$data['application'] = $application;
			$data['Body'] = ErfBodyRequest::
				select(
				  'erf_body_request.*'
				)
				->where('erf_body_request.header_request_id', $id)
				->get();
			$data['erf_header_documents'] = ErfHeaderDocuments::select(
					'erf_header_documents.*'
				  )
				  ->where('erf_header_documents.header_id', $id)
				  ->get();
	
			return $this->view("erf.approved_erf", $data);
		}

		public function getDownload($id) {
			$getFile = DB::table('erf_header_documents')->where('id',$id)->first();
			$file= public_path(). "/vendor/crudbooster/erf_folder/".$getFile->file_name;

			$headers = array(
					'Content-Type: application/pdf',
					);

			return Response::download($file, $getFile->file_name, $headers);
		}


	}