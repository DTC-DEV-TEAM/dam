<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use App\Users;
	use App\StatusMatrix;
	use App\Models\ItemHeaderSourcing;
	use App\Models\ItemBodySourcing;
	use App\Mail\Email;
	use Mail;

	class AdminItemSourcingForApprovalController extends \crocodicstudio\crudbooster\controllers\CBController {
		private $forApproval;
		private $forQuotation;
		private $closed;
	
		
		public function __construct() {
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
			$this->forApproval      =  1;        
			$this->forQuotation     =  37;  
			$this->closed           =  13;   
		}
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
			$this->button_edit = true;
			$this->button_delete = false;
			$this->button_detail = false;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "item_sourcing_header";
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
	        if(CRUDBooster::isSuperadmin()){
				$pending  = 1;
				$query->orderBy('item_sourcing_header.status_id', 'DESC')->where('item_sourcing_header.status_id', $pending)->orderBy('item_sourcing_header.id', 'DESC');
			
			}else{
				$pending  = 1;
				$approvalMatrix = Users::where('cms_users.approver_id', CRUDBooster::myId())->get();
				$approval_array = array();
				foreach($approvalMatrix as $matrix){
				    array_push($approval_array, $matrix->id);
				}
				$approval_string = implode(",",$approval_array);
				$userslist = array_map('intval',explode(",",$approval_string));
				$query->whereIn('item_sourcing_header.created_by', $userslist)
				->where('item_sourcing_header.status_id', $pending) 
				->orderBy('item_sourcing_header.id', 'DESC');

			}
	            
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	$forApproval        = DB::table('statuses')->where('id', $this->forApproval)->value('status_description');        
	
			if($column_index == 1){
				if($column_value == $forApproval){
					$column_value = '<span class="label label-warning">'.$forApproval.'</span>';
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

			$arf_header = ItemHeaderSourcing::where(['id' => $id])->first();
			$arf_body = ItemBodySourcing::where(['header_request_id' => $id])->get();
            //dd($arf_header);
			$email_recipients = array();
			$reference_number = array();
			$item_description = array();
			$item_category = array();
			$sub_category = array();

			if($approval_action  == 1){
				$postdata['status_id']		    = 37;
				$postdata['approver_comments'] 	= $approver_comments;
				$postdata['approved_by'] 		= CRUDBooster::myId();
				$postdata['approved_at'] 		= date('Y-m-d H:i:s');

				foreach($arf_body as $body_arf){
					if($body_arf->category_id == "IT ASSETS"){
						$postdata['to_reco'] 	= 1;
					}
					array_push($item_description, $body_arf->item_description);
					array_push($item_category, $body_arf->category_id);
					array_push($sub_category, $body_arf->sub_category_id);
				}
				$employee_name = DB::table('cms_users')->where('id', $arf_header->employee_name)->first();
				$approver_name = DB::table('cms_users')->where('id', $employee_name->approver_id)->first();
				$department_name = DB::table('departments')->where('id', $employee_name->department_id)->first();
				//$purchasing = "purchasing@digits.ph";
				$fhil = "fhilipacosta@digits.ph";

				$infos['assign_to'] = $employee_name->bill_to;
				$infos['reference_number'] = $arf_header->reference_number;
				$infos['date_needed'] = $arf_header->date_needed;
				$infos['suggested_supplier'] = $arf_header->suggested_supplier;
				$infos['department'] = $department_name->department_name;
				$infos['items'] = $arf_body;
			
				Mail::to($employee_name->email)
						//->cc([$fhil,$approver_name->email])
	                    ->send(new Email($infos));
			}else{
				$postdata['status_id'] 			= 5;
				$postdata['approver_comments'] 	= $approver_comments;
				$postdata['approved_by'] 		= CRUDBooster::myId();
				$postdata['rejected_at'] 		= date('Y-m-d H:i:s');

			}

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
			$data['page_title'] = 'Item Sourcing Detail';
			$data['Header'] = ItemHeaderSourcing::
				  leftjoin('request_type', 'item_sourcing_header.purpose', '=', 'request_type.id')
				->leftjoin('condition_type', 'item_sourcing_header.conditions', '=', 'condition_type.id')
				->leftjoin('cms_users as employees', 'item_sourcing_header.employee_name', '=', 'employees.id')
				->leftjoin('companies', 'item_sourcing_header.company_name', '=', 'companies.id')
				->leftjoin('departments', 'item_sourcing_header.department', '=', 'departments.id')
				->leftjoin('locations', 'employees.location_id', '=', 'locations.id')
				->leftjoin('cms_users as requested', 'item_sourcing_header.created_by','=', 'requested.id')
				->leftjoin('cms_users as approved', 'item_sourcing_header.approved_by','=', 'approved.id')
				->leftjoin('cms_users as recommended', 'item_sourcing_header.recommended_by','=', 'recommended.id')
				->leftjoin('cms_users as processed', 'item_sourcing_header.purchased2_by','=', 'processed.id')
				->leftjoin('cms_users as picked', 'item_sourcing_header.picked_by','=', 'picked.id')
				->leftjoin('cms_users as received', 'item_sourcing_header.received_by','=', 'received.id')
				->leftjoin('cms_users as closed', 'item_sourcing_header.closed_by','=', 'closed.id')
				->select(
						'item_sourcing_header.*',
						'item_sourcing_header.id as requestid',
						'item_sourcing_header.created_at as created',
						'request_type.*',
						'condition_type.*',
						'requested.name as requestedby',
						'employees.bill_to as employee_name',
						'item_sourcing_header.employee_name as header_emp_name',
						'item_sourcing_header.created_by as header_created_by',
						'departments.department_name as department',
						'item_sourcing_header.store_branch as store_branch',
						'approved.name as approvedby',
						'recommended.name as recommendedby',
						'picked.name as pickedby',
						'received.name as receivedby',
						'processed.name as processedby',
						'closed.name as closedby',
						'item_sourcing_header.created_at as created_at'
						)
				->where('item_sourcing_header.id', $id)->first();
		
			$data['Body'] = ItemBodySourcing::
				select(
				  'item_sourcing_body.*'
				)
				->where('item_sourcing_body.header_request_id', $id)
				->get();
	
			return $this->view("item-sourcing.item-sourcing-for-approval", $data);
		}


	}