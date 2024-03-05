<?php namespace App\Http\Controllers;

	use Session;
	//use Request;
	use DB;
	use CRUDBooster;

	use App\HeaderRequest;
	use App\BodyRequest;
	use App\ApprovalMatrix;
	use App\StatusMatrix;
	use App\Users;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Input;
	use Illuminate\Support\Facades\Log;
	use Illuminate\Support\Facades\Redirect;
	use App\MoveOrder;
	use Maatwebsite\Excel\Facades\Excel;
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Reader\Exception;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	use PhpOffice\PhpSpreadsheet\IOFactory;

	class AdminRequestHistoryController extends \crocodicstudio\crudbooster\controllers\CBController {

        public function __construct() {
			// Register ENUM type
			//$this->request = $request;
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
		}

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "employee_name";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
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
			//$this->col[] = ["label"=>"Updated By","name"=>"updated_by","join"=>"cms_users,name"];
			//$this->col[] = ["label"=>"Updated Date","name"=>"updated_at"];

			$this->col[] = ["label"=>"Approved By","name"=>"approved_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Approved Date","name"=>"approved_at"];
			$this->col[] = ["label"=>"Rejected Date","name"=>"rejected_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Reference Number','name'=>'reference_number','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Status Id','name'=>'status_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'status,id'];
			$this->form[] = ['label'=>'Employee Name','name'=>'employee_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Company Name','name'=>'company_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Position','name'=>'position','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Department','name'=>'department','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Store Branch','name'=>'store_branch','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Purpose','name'=>'purpose','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Conditions','name'=>'conditions','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Quantity Total','name'=>'quantity_total','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Cost Total','name'=>'cost_total','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Total','name'=>'total','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Approved By','name'=>'approved_by','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Approved At','name'=>'approved_at','type'=>'datetime','validation'=>'required|date_format:Y-m-d H:i:s','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Created By','name'=>'created_by','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Updated By','name'=>'updated_by','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Rejected At','name'=>'rejected_at','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Requestor Comments','name'=>'requestor_comments','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Request Type Id','name'=>'request_type_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'request_type,id'];
			$this->form[] = ['label'=>'Privilege Id','name'=>'privilege_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'privilege,id'];
			$this->form[] = ['label'=>'Approver Comments','name'=>'approver_comments','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
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
			if(CRUDBooster::isUpdate()) {

				//$processing  = 		DB::table('statuses')->where('id', 11)->value('id');

				//$this->addaction[] = ['title'=>'Update','url'=>CRUDBooster::mainpath('getRequestPurchasing/[id]'),'icon'=>'fa fa-pencil', "showIf"=>"[purchased2_by] == null"];
				
				//$this->addaction[] = ['title'=>'Print','url'=>CRUDBooster::mainpath('getRequestPrintPickList/[id]'),'icon'=>'fa fa-print', "showIf"=>"[purchased3_by] != null"];
				
				//$this->addaction[] = ['title'=>'Print','url'=>CRUDBooster::mainpath('getRequestPrint/[id]'),'icon'=>'fa fa-print', "showIf"=>"[purchased2_by] != null"];
				//$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('getRequestEdit/[id]'),'icon'=>'fa fa-pencil', "showIf"=>"[status_id] == $Rejected"]; //, "showIf"=>"[status_level1] == $inwarranty"
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
			$this->index_button[] = ["label"=>"Export Lists","icon"=>"fa fa-download","url"=>CRUDBooster::mainpath('export').'/'.$userslist,"color"=>"primary"];


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
	        $this->load_css[] = asset("css/font-family.css");
	        
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
		
				$query->whereNull('header_request.deleted_at')->orderBy('header_request.status_id', 'DESC')->orderBy('header_request.id', 'DESC');
			
			}else if(CRUDBooster::myPrivilegeId() == 2){ 
				
				$query->where('header_request.created_by', CRUDBooster::myId())->whereNull('header_request.deleted_at')->orderBy('header_request.status_id', 'asc')->orderBy('header_request.id', 'DESC');
			
			}else if(in_array(CRUDBooster::myPrivilegeId(), [3, 11, 12, 14,15])){ 

				$approvalMatrix = Users::where('cms_users.approver_id', CRUDBooster::myId())->get();
				
				$approval_array = array();
				foreach($approvalMatrix as $matrix){
				    array_push($approval_array, $matrix->id);
				}
				$approval_string = implode(",",$approval_array);
				$usersmentlist = array_map('intval',explode(",",$approval_string));

				$user_data         = DB::table('cms_users')->where('id', CRUDBooster::myId())->first();
				$query->whereIn('header_request.created_by', $usersmentlist)
				//->whereIn('header_request.company_name', explode(",",$user_data->company_name_id))
				->where('header_request.approved_by','!=', null)
				->whereNull('header_request.deleted_at')
				->orderBy('header_request.id', 'ASC');

			}else if(CRUDBooster::myPrivilegeId() == 5 || CRUDBooster::myPrivilegeId() == 17){ 

				$query
				->where('header_request.request_type_id', 1)
				->where('header_request.purchased2_by', '!=', null)
				->where('header_request.to_reco', 1)
				->whereNull('header_request.deleted_at')
				->orderBy('header_request.id', 'ASC');

			}else if(CRUDBooster::myPrivilegeId() == 7){ 

				$query->whereNotNull('header_request.closed_by')->where('header_request.closed_by', CRUDBooster::myId())->whereNull('header_request.deleted_at')->orderBy('header_request.status_id', 'asc')->orderBy('header_request.id', 'DESC');

			}else if(CRUDBooster::myPrivilegeId() == 9){ 

				$query->whereNotNull('header_request.purchased2_by')->where('header_request.purchased2_by', CRUDBooster::myId())->orWhere('header_request.picked_by', CRUDBooster::myId())->whereNull('header_request.deleted_at')->orderBy('header_request.status_id', 'asc')->orderBy('header_request.id', 'DESC');

			}else if(in_array(CRUDBooster::myPrivilegeId(), [18,19])){ 

				$query->where('header_request.request_type_id', 7)->where('header_request.purchased2_by', CRUDBooster::myId())->orWhere('header_request.picked_by', CRUDBooster::myId())->whereNull('header_request.deleted_at')->orderBy('header_request.status_id', 'asc')->orderBy('header_request.id', 'DESC');

			}
	            
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	//Your code here
			$pending  =  		DB::table('statuses')->where('id', 1)->value('status_description');
			$approved =  		DB::table('statuses')->where('id', 4)->value('status_description');
			$rejected =  		DB::table('statuses')->where('id', 5)->value('status_description');
			$it_reco  = 		DB::table('statuses')->where('id', 7)->value('status_description');
			$cancelled  = 		DB::table('statuses')->where('id', 8)->value('status_description');
			$released  = 		DB::table('statuses')->where('id', 12)->value('status_description');
			$processing  = 		DB::table('statuses')->where('id', 11)->value('status_description');
			$closed  = 			DB::table('statuses')->where('id', 13)->value('status_description');
			$for_picklist =  	DB::table('statuses')->where('id', 14)->value('status_description');
			$picked =  			DB::table('statuses')->where('id', 15)->value('status_description');
			$received  = 		DB::table('statuses')->where('id', 16)->value('status_description');
			$for_printing =  	DB::table('statuses')->where('id', 17)->value('status_description');
			$for_printing_adf = DB::table('statuses')->where('id', 18)->value('status_description');
			$for_closing = 		DB::table('statuses')->where('id', 19)->value('status_description');

			if($column_index == 2){

				if($column_value == $pending){
					$column_value = '<span class="label label-warning">'.$pending.'</span>';
				}else if($column_value == $approved){
					$column_value = '<span class="label label-info">'.$approved.'</span>';
				}else if($column_value == $rejected){
					$column_value = '<span class="label label-danger">'.$rejected.'</span>';
				}else if($column_value == $it_reco){
					$column_value = '<span class="label label-info">'.$it_reco.'</span>';
				}else if($column_value == $cancelled){
					$column_value = '<span class="label label-danger">'.$cancelled.'</span>';
				}else if($column_value == $released){
					$column_value = '<span class="label label-info">'.$released.'</span>';
				}else if($column_value == $processing){
					$column_value = '<span class="label label-info">'.$processing.'</span>';
				}else if($column_value == $closed){
					$column_value = '<span class="label label-success">'.$closed.'</span>';
				}else if($column_value == $for_picklist){
					$column_value = '<span class="label label-info">'.$for_picklist.'</span>';
				}else if($column_value == $picked){
					$column_value = '<span class="label label-info">'.$picked.'</span>';
				}else if($column_value == $received){
					$column_value = '<span class="label label-info">'.$received.'</span>';
				}else if($column_value == $for_printing){
					$column_value = '<span class="label label-info">'.$for_printing.'</span>';
				}elseif($column_value == $for_printing_adf){

					$column_value = '<span class="label label-info">'.$for_printing_adf.'</span>';

				}elseif($column_value == $for_closing){
					$column_value = '<span class="label label-info">'.$for_closing.'</span>';
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

		public function getDetail($id){
			$this->cbLoader();
            if(!CRUDBooster::isRead() && $this->global_privilege==FALSE) {    
                CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
            }


			$data = array();

			$data['page_title'] = 'View Request';

			//$HeaderID = MoveOrder::where('id', $id)->first();

			$data['Header'] = HeaderRequest::
				  leftjoin('request_type', 'header_request.purpose', '=', 'request_type.id')
				->leftjoin('condition_type', 'header_request.conditions', '=', 'condition_type.id')
				->leftjoin('cms_users as employees', 'header_request.employee_name', '=', 'employees.id')
				->leftjoin('companies', 'header_request.company_name', '=', 'companies.id')
				->leftjoin('departments', 'header_request.department', '=', 'departments.id')
				->leftjoin('locations', 'employees.location_id', '=', 'locations.id')
				->leftjoin('cms_users as requested', 'header_request.created_by','=', 'requested.id')
				->leftjoin('cms_users as approved', 'header_request.approved_by','=', 'approved.id')
				->leftjoin('cms_users as recommended', 'header_request.recommended_by','=', 'recommended.id')
				->leftjoin('cms_users as processed', 'header_request.purchased2_by','=', 'processed.id')
				->leftjoin('cms_users as picked', 'header_request.picked_by','=', 'picked.id')
				->leftjoin('cms_users as received', 'header_request.received_by','=', 'received.id')
				->leftjoin('cms_users as closed', 'header_request.closed_by','=', 'closed.id')
				->select(
						'header_request.*',
						'header_request.id as requestid',
						'header_request.created_at as created',
						'request_type.*',
						'condition_type.*',
						'requested.name as requestedby',
						'employees.bill_to as employee_name',
						'employees.company_name_id as company_name',
						'departments.department_name as department',
						'locations.store_name as store_name',
						'approved.name as approvedby',
						'recommended.name as recommendedby',
						'picked.name as pickedby',
						'received.name as receivedby',
						'processed.name as processedby',
						'closed.name as closedby',
						'header_request.created_at as created_at'
						)
				->where('header_request.id', $id)->first();

			$data['Body'] = BodyRequest::
				select(
				  'body_request.*'
				)
				->where('body_request.header_request_id', $id)
				//->whereNull('deleted_at')
				->get();

			$data['Body1'] = BodyRequest::
				select(
				  'body_request.*'
				)
				->where('body_request.header_request_id', $id)
				->wherenotnull('body_request.digits_code')
				->orderby('body_request.id', 'desc')
				->get();

			$data['MoveOrder'] = MoveOrder::
				select(
				  'mo_body_request.*',
				  'statuses.status_description as status_description'
				)
				->where('mo_body_request.header_request_id', $id)
				->leftjoin('statuses', 'mo_body_request.status_id', '=', 'statuses.id')
				->orderby('mo_body_request.id', 'desc')
				->get();
	
			//dd($data['MoveOrder']->count());

			$data['BodyReco'] = DB::table('recommendation_request')
				->select(
				  'recommendation_request.*'
				)
				->where('recommendation_request.header_request_id', $id)
				->get();				

			$data['recommendations'] = DB::table('recommendations')->where('status', 'ACTIVE')->get();		
		
			return $this->view("assets.detail-history", $data);
		}


		public function getRequestPrintPickList($id){
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}  


			$data = array();

			$data['page_title'] = 'Print Picklist';

			$data['Header'] = HeaderRequest::
				  leftjoin('request_type', 'header_request.purpose', '=', 'request_type.id')
				->leftjoin('condition_type', 'header_request.conditions', '=', 'condition_type.id')
				->leftjoin('employees', 'header_request.employee_name', '=', 'employees.id')
				->leftjoin('companies', 'header_request.company_name', '=', 'companies.id')
				->leftjoin('departments', 'header_request.department', '=', 'departments.id')
				->leftjoin('positions', 'header_request.position', '=', 'positions.id')
				->leftjoin('stores', 'header_request.store_branch', '=', 'stores.id')
				->leftjoin('cms_users as requested', 'header_request.created_by','=', 'requested.id')
				->leftjoin('cms_users as approved', 'header_request.approved_by','=', 'approved.id')
				->leftjoin('cms_users as recommended', 'header_request.recommended_by','=', 'recommended.id')
				->leftjoin('cms_users as processed', 'header_request.purchased2_by','=', 'processed.id')
				->select(
						'header_request.*',
						'header_request.id as requestid',
						'header_request.created_at as created',
						'request_type.*',
						'condition_type.*',
						'requested.name as requestedby',
						'employees.bill_to as employee_name',
						'companies.company_name as company_name',
						'departments.department_name as department',
						'positions.position_description as position',
						'stores.bea_mo_store_name as store_branch',
						'approved.name as approvedby',
						'recommended.name as recommendedby',
						'processed.name as processedby',
						'header_request.created_at as created_at'
						)
				->where('header_request.id', $id)->first();

			$data['Body'] = BodyRequest::
				select(
				  'body_request.*'
				)
				->where('body_request.header_request_id', $id)
				->orderby('body_request.id', 'desc')
				->get();

			$data['BodyReco'] = DB::table('recommendation_request')
				->select(
				  'recommendation_request.*'
				)
				->where('recommendation_request.header_request_id', $id)
				->get();				

			$data['recommendations'] = DB::table('recommendations')->where('status', 'ACTIVE')->get();

			return $this->view("assets.print-picklist", $data);
		}

		public function getExport(){
			$approvalMatrix = Users::where('cms_users.approver_id', CRUDBooster::myId())->get();
			$approval_array = array();
			foreach($approvalMatrix as $matrix){
				array_push($approval_array, $matrix->id);
			}
			$approval_string = implode(",",$approval_array);
			$userslist = array_map('intval',explode(",",$approval_string));
			$requests = BodyRequest::requestHistory($userslist);
			$lists = [];
			$listsCon = [];
			foreach($requests as $item){
				$listsCon['id']                 = $item['requestid'];
				$listsCon['reference_number']   = $item['reference_number'];
				$listsCon['requested_by']       = $item['requestedby'];
				$listsCon['department']         = $item['department'] ? $item['department'] : $item['store_branch'];
				$listsCon['store_branch']       = $item['store_branch'] ? $item['store_branch'] : $item['department'];
				$bodyStatus = $item['body_statuses_description'] ? $item['body_statuses_description'] : $item['status_description'];
				if(in_array($item['request_type_id'], [6,7])){
					$listsCon['status']              = $item['status_description'];
					$listsCon['body_digits_code']    = $item['body_digits_code'];
					$listsCon['description']         = $item['body_description'];
					$listsCon['request_quantity']    = $item['body_quantity'];
					$listsCon['request_type']        = $item['body_category_id'];
					$listsCon['mo_reference']        = $item['body_mo_so_num'];
					$listsCon['mo_item_code']        = $item['body_digits_code'];
					$listsCon['mo_item_description'] = $item['body_description'];
					$listsCon['mo_qty_serve_qty']    = $item['serve_qty'];
				}else{
					$listsCon['status']              = isset($item['mo_reference_number']) ? $item['mo_statuses_description'] : $bodyStatus;
					$listsCon['body_digits_code']    = $item['body_digits_code'];
					$listsCon['description']         = $item['body_description'];
					$listsCon['request_quantity']    = $item['body_quantity'];
					$listsCon['request_type']        = $item['body_category_id'];
					$listsCon['mo_reference']        = $item['mo_reference_number'];
					$listsCon['mo_item_code']        = $item['mo_digits_code'];
					$listsCon['mo_item_description'] = $item['mo_item_description'];
					$listsCon['mo_qty_serve_qty']    = $item['quantity'];
				}
				$listsCon['requested_date']          = $item['created_at'];
				$listsCon['approved_by']             = $item['approvedby'];
				$listsCon['approved_at']             = $item['approved_at'];
				$listsCon['transacted_by']           = $item['taggedby'];
				$listsCon['replenish_qty']           = $item['replenish_qty'];
				$listsCon['reorder_qty']             = $item['reorder_qty'];
				$listsCon['fulfill_qty']             = $item['serve_qty'];
				$listsCon['transacted_date']         = $item['transacted_date'];
				$lists[]                          = $listsCon;
			}
			$dataExport[] = [
							'Reference number',
							'Requested by',
							'Department',
							'Store branch',
							'Status',
							'Digits node',
							'Description',
							'Request quantity',
							'Request type',
							'Mo reference',
							'Mo item code',
							'Mo item description',
							'Mo qty fulfill',
							'Requested date',
							'Approved by',
							'Approved at',
							'Transacted by',
							'Replenish qty',
							'Reorder qty',
							'Fulfill qty',
							'Transacted date'
							];
			foreach($lists as $exportList){
				$dataExport[]= [
					'Reference number'       => $exportList['reference_number'],
					'Requested by'           => $exportList['requested_by'],
					'Department'             => $exportList['department'],
					'Store branch'           => $exportList['store_branch'],
					'Status'                 => $exportList['status'],
					'Digits node'            => $exportList['body_digits_code'],
					'Description'            => $exportList['description'],
					'Request quantity'       => $exportList['request_quantity'],
					'Request type'           => $exportList['request_type'],
					'Mo reference'           => $exportList['mo_reference'],
					'Mo item code'           => $exportList['mo_item_code'],
					'Mo item description'    => $exportList['mo_item_description'],
					'Mo qty fulfill'         => $exportList['mo_qty_serve_qty'],
					'Requested date'         => $exportList['requested_date'],
					'Approved by'            => $exportList['approved_by'],
					'Approved at'            => $exportList['approved_at'],
					'Transacted by'          => $exportList['transacted_by'],
					'Replenish qty'          => $exportList['replenish_qty'],
					'Reorder qty'            => $exportList['reorder_qty'],
					'Fulfill qty'            => $exportList['fulfill_qty'],
					'Transacted date'        => $exportList['transacted_date']
				];
			}

			$this->ExportExcel($dataExport);
	
		}

		public function ExportExcel($data){
			ini_set('max_execution_time', 0);
			ini_set('memory_limit', '4000M');
			try {
				$spreadSheet = new Spreadsheet();
				$spreadSheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
				$spreadSheet->getActiveSheet()->fromArray($data);
				$Excel_writer = new Xlsx($spreadSheet);
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="Request-hisory.xlsx"');
				header('Cache-Control: max-age=0');
				ob_end_clean();
				$Excel_writer->save('php://output');
				exit();
			} catch (Exception $e) {
				return;
			}
		}
	}