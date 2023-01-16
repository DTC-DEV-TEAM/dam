<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use App\Users;
	use App\HeaderRequest;
	use App\BodyRequest;
	use App\ApprovalMatrix;
	use App\StatusMatrix;

	class AdminHrRequisitionController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "employee_name";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = false;
			$this->button_action_style = "button_icon";
			$this->button_add = false;
			$this->button_edit = false;
			$this->button_delete = true;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "header_request";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Status","name"=>"status_id","join"=>"statuses,status_description"];
			$this->col[] = ["label"=>"Reference Number","name"=>"reference_number"];
			$this->col[] = ["label"=>"Request Type","name"=>"request_type_id","join"=>"requests,request_name"];
			$this->col[] = ["label"=>"Company Name","name"=>"company_name"];
			$this->col[] = ["label"=>"Employee Name","name"=>"employee_name","join"=>"cms_users,bill_to"];
			$this->col[] = ["label"=>"Department","name"=>"department","join"=>"departments,department_name"];
			$this->col[] = ["label"=>"Requested By","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Requested Date","name"=>"created_at"];
			$this->col[] = ["label"=>"Approved By","name"=>"approved_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Approved Date","name"=>"approved_at"];
			$this->col[] = ["label"=>"Rejected Date","name"=>"rejected_at"];
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
			if(CRUDBooster::getCurrentMethod() == 'getIndex'){

				$this->index_button[] = ["label"=>"IT Asset Request","icon"=>"fa fa-files-o","url"=>CRUDBooster::mainpath('add-it-requisition'),"color"=>"success"];

				// $this->index_button[] = ["label"=>"FA Request","icon"=>"fa fa-files-o","url"=>CRUDBooster::mainpath('add-requisition-fa'),"color"=>"success"];
				// $this->index_button[] = ["label"=>"Marketing Request","icon"=>"fa fa-files-o","url"=>CRUDBooster::mainpath('add-requisition-marketing'),"color"=>"success"];
				// $this->index_button[] = ["label"=>"Supplies Request","icon"=>"fa fa-files-o","url"=>CRUDBooster::mainpath('add-requisition-supplies'),"color"=>"success"];

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
	            
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	//Your code here
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before add data is execute
	    | ---------------------------------------------------------------------- 
	    | @arr
	    |
	    */
	    public function hook_before_add(&$postdata) {        
			$fields = Request::all();
			$emp_id = $fields['employee'];
			$employees          = DB::table('cms_users')->where('id', $emp_id)->first();
			if(in_array($employees->id_cms_privileges, [11,12,14,15])){ 
				$status = StatusMatrix::where('current_step', 2)
										->where('request_type', 1)
										->value('status_id');
			}else{
				$status	= StatusMatrix::where('current_step', 1)
										->where('request_type', 1)
										->value('status_id');
	
			}
			$purpose 			= $fields['purpose'];
			$condition 			= $fields['condition'];
			$quantity_total 	= $fields['quantity_total'];
			$cost_total 		= $fields['cost_total'];
			$total 				= $fields['total'];
			$request_type_id 	= $fields['request_type_id'];
			$requestor_comments = $fields['requestor_comments'];
			$application 		= $fields['application'];
			$application_others = $fields['application_others'];
			$count_header       = DB::table('header_request')->count();
			$header_ref         = str_pad($count_header + 1, 7, '0', STR_PAD_LEFT);			
			$reference_number	= "ARF-".$header_ref;
			dd($employees,$status);
			
			$postdata['reference_number']		 	= $reference_number;
			$postdata['employee_name'] 				= $employees->id;
			$postdata['company_name'] 				= $employees->company_name_id;
			$postdata['position'] 					= $employees->position_id;
			$postdata['department'] 				= $employees->department_id;
			$postdata['purpose'] 					= $purpose;
			$postdata['conditions'] 				= $condition;
			$postdata['quantity_total'] 			= $quantity_total;
			$postdata['cost_total'] 				= $cost_total;
			$postdata['total'] 						= $total;
			$postdata['requestor_comments'] 		= $requestor_comments;
			$postdata['created_by'] 				= $employees->id;
			$postdata['created_at'] 				= date('Y-m-d H:i:s');
			$postdata['request_type_id']		 	= $request_type_id;
			$postdata['privilege_id']		 		= $employees->id_cms_privileges;
			if(!empty($application)){
				$postdata['application'] 				= implode(", ",$application);
				$postdata['application_others'] 		= $application_others;
			}

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	    public function hook_after_add($id) {        
	        $fields = Request::all();
			$dataLines = array();
			$arf_header = DB::table('header_request')->where(['created_by' => CRUDBooster::myId()])->orderBy('id','desc')->first();
			$digits_code 		= $fields['supplies_digits_code'] ? $fields['supplies_digits_code'] : null;
			$item_description 	= $fields['item_description'];
			$category_id 		= $fields['category_id'];
			$sub_category_id 	= $fields['sub_category_id'];
			$app_id_others 		= $fields['app_id_others'];
			$quantity 			= $fields['quantity'];
			$image 				= $fields['image'];
			$request_type_id 	= $fields['request_type_id'];
			$app_count = 2;

			for($x=0; $x < count((array)$item_description); $x++) {

				$apps_array = array();
				$app_no = 'app_id'.$app_count;
				$app_id 			= $fields[$app_no];
				for($xxx=0; $xxx < count((array)$app_id); $xxx++) {
					array_push($apps_array,$app_id[$xxx]); 
				}
	
				$app_count++;
				if (!empty($image[$x])) {
					$extension1 =  $app_count.time() . '.' .$image[$x]->getClientOriginalExtension();
					$filename = $extension1;
					$image[$x]->move('vendor/crudbooster/',$filename);
				}

				if(in_array(CRUDBooster::myPrivilegeId(), [4,11,12,14,15])){ 
					if($category_id[$x] == "IT ASSETS"){
						HeaderRequest::where('id', $arf_header->id)->update([
							'to_reco'=> 1
						]);
					}					
				}

				$dataLines[$x]['header_request_id'] = $arf_header->id;
				$dataLines[$x]['digits_code'] 	    = $digits_code[$x];
				$dataLines[$x]['item_description'] 	= $item_description[$x];
				$dataLines[$x]['category_id'] 		= $category_id[$x];
				$dataLines[$x]['sub_category_id'] 	= $sub_category_id[$x];
				$dataLines[$x]['app_id'] 			= implode(", ",$apps_array);
				$dataLines[$x]['app_id_others'] 	= $app_id_others[$x];
				$dataLines[$x]['quantity'] 			= $quantity[$x];

				if($request_type_id == 5){
					$dataLines[$x]['to_reco'] = 0;
					
				}else{
					if (str_contains($item_description[$x], 'LAPTOP') || str_contains($item_description[$x], 'DESKTOP')) {
						$dataLines[$x]['to_reco'] = 1;
					}else{
						$dataLines[$x]['to_reco'] = 0;
					}
				}

				if (!empty($image[$x])) {

					$dataLines[$x]['image'] 			= 'vendor/crudbooster/'.$filename;
				}else{
					$dataLines[$x]['image'] 			= "";
				}
				$dataLines[$x]['created_at'] 		= date('Y-m-d H:i:s');
				unset($apps_array);
			}

			DB::beginTransaction();
			try {
				BodyRequest::insert($dataLines);
				DB::commit();
				//CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_pullout_data_success",['mps_reference'=>$pullout_header->reference]), 'success');
			} catch (\Exception $e) {
				DB::rollback();
				CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
			}

			CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_add_success",['reference_number'=>$arf_header->reference_number]), 'success');

			

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
 
        public function getAddItRequisition() {

			if(!CRUDBooster::isCreate() && $this->global_privilege == false) {
				CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
			}

			$this->cbLoader();
			$data['page_title'] = 'Create IT Asset Request';
			$data['conditions'] = DB::table('condition_type')->where('status', 'ACTIVE')->get();
			$data['departments'] = DB::table('departments')->where('status', 'ACTIVE')->get();
			$data['stores'] = DB::table('stores')->where('status', 'ACTIVE')->get();
			$data['departments'] = DB::table('departments')->where('status', 'ACTIVE')->get();
			$data['user'] = DB::table('cms_users')->where('id', CRUDBooster::myId())->first();
			$data['employeeinfos'] = DB::table('cms_users')
										 ->leftjoin('positions', 'cms_users.position_id', '=', 'positions.id')
										 ->leftjoin('departments', 'cms_users.department_id', '=', 'departments.id')
										 ->select( 'cms_users.*', 'positions.position_description as position_description', 'departments.department_name as department_name')
										 ->where('cms_users.id', $data['user']->id)->first();
			$data['categories'] = DB::table('category')->where('category_status', 'ACTIVE')->where('id', 5)->orderby('category_description', 'asc')->get();
			$data['sub_categories'] = DB::table('class')->where('class_status', 'ACTIVE')->where('category_id', 5)->orderby('class_description', 'asc')->get();
			$data['applications'] = DB::table('applications')->where('status', 'ACTIVE')->orderby('app_name', 'asc')->get();
			$data['companies'] = DB::table('companies')->where('status', 'ACTIVE')->get();
			$data['purposes'] = DB::table('request_type')->where('status', 'ACTIVE')->where('privilege', 'Employee')->get();
			$data['purposes'] = DB::table('request_type')->where('status', 'ACTIVE')->where('privilege', 'Employee')->get();
			$data['stores'] = DB::table('locations')->where('id', $data['user']->location_id)->first();
			$data['purposes'] = DB::table('request_type')->where('status', 'ACTIVE')->where('privilege', 'HR')->get();
			$data['new_employee'] = Users::where('new_employee_plug','=',1)->get();
			return $this->view("assets.add-hr-requisition", $data);
				
		}

		public function SearchUser(Request $request) {
			$request = Request::all();
			$search 		= $request['id'];

			$data = array();
			$data['status_no'] = 0;
			$data['message']   ='No Item Found!';
			$data['items'] = array();
			$items = DB::table('cms_users')
				->where('cms_users.id','=',$search)
				->leftjoin('departments', 'cms_users.department_id','=','departments.id')
				->leftjoin('sub_department', 'cms_users.sub_department_id','=','sub_department.id')
				->leftjoin('locations', 'cms_users.location_id', '=', 'locations.id')
				->select(	'cms_users.*',
				            'cms_users.id as id',
							'departments.*',
							'sub_department.*',
							'locations.*'
						)
				->first();
			$data['items'] = $items;

			echo json_encode($data);
			exit;  
		}

	}