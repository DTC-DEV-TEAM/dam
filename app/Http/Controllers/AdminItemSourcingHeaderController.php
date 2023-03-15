<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use App\StatusMatrix;
	use App\Models\ItemHeaderSourcing;
	use App\Models\ItemBodySourcing;
	use App\Models\ItemSourcingComments;
	use App\HeaderRequest;
	use App\BodyRequest;
	use App\Mail\Email;
	use Mail;

	class AdminItemSourcingHeaderController extends \crocodicstudio\crudbooster\controllers\CBController {
		private $forApproval;
		private $cancelled;
		private $closed;
		private $forDiscussion;
		private $forSourcing;
		private $forStreamlining;
		private $forItemCreation;
		private $forArfCreation;

		public function __construct() {
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
			$this->forApproval      =  1;    
			$this->cancelled        =  8;
			$this->closed           =  13;      
			$this->forDiscussion    =  37;  
			$this->forSourcing      =  38;
			$this->forStreamlining  =  39;   
			$this->forItemCreation  =  40;        
			$this->forArfCreation   =  41;
		
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
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = true;
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
			if(CRUDBooster::isUpdate()) {
				$this->addaction[] = ['title'=>'Cancel Request','url'=>CRUDBooster::mainpath('getRequestCancelNis/[id]'),'icon'=>'fa fa-times', "showIf"=>"[status_id] == $this->forApproval"];
				//$this->addaction[] = ['title'=>'Detail','url'=>CRUDBooster::mainpath('detail-sourcing'),'icon'=>'fa fa-eye'];

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
				$this->index_button[] = ["label"=>"Supplies","icon"=>"fa fa-plus-circle","url"=>CRUDBooster::mainpath('add-item-sourcing-supplies'),"color"=>"success"];
				$this->index_button[] = ["label"=>"FA","icon"=>"fa fa-plus-circle","url"=>CRUDBooster::mainpath('add-item-sourcing-fa'),"color"=>"success"];
				$this->index_button[] = ["label"=>"IT","icon"=>"fa fa-plus-circle","url"=>CRUDBooster::mainpath('add-item-sourcing-it'),"color"=>"success"];
				$this->index_button[] = ["label"=>"Services","icon"=>"fa fa-plus-circle","url"=>CRUDBooster::mainpath('add-item-sourcing-services'),"color"=>"success"];
				$this->index_button[] = ["label"=>"Subscription","icon"=>"fa fa-plus-circle","url"=>CRUDBooster::mainpath('add-item-sourcing-subscription'),"color"=>"success"];
				$this->index_button[] = ["label"=>"Mkt","icon"=>"fa fa-plus-circle","url"=>CRUDBooster::mainpath('add-item-sourcing-mkt'),"color"=>"success"];
			
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

			});";


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
			$this->load_js[] = asset("datetimepicker/bootstrap-datetimepicker.min.js");
	        
	        
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
	        $this->load_css[] = asset("datetimepicker/bootstrap-datetimepicker.min.css");
			$this->load_css[] = asset("css/chatbox.css");
	        
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

				$released  = 		DB::table('statuses')->where('id', 12)->value('id');

				$query->whereNull('item_sourcing_header.deleted_at')
					  ->orderBy('item_sourcing_header.status_id', 'ASC')
					  ->orderBy('item_sourcing_header.id', 'DESC');

			}else{

				$user = DB::table('cms_users')->where('id', CRUDBooster::myId())->first();

				$query->where(function($sub_query){

					$user = DB::table('cms_users')->where('id', CRUDBooster::myId())->first();

					$released  = 		DB::table('statuses')->where('id', 12)->value('id');

					$sub_query->where('item_sourcing_header.created_by', CRUDBooster::myId())
					         
							  ->whereNull('item_sourcing_header.deleted_at')
							  ->orderBy('item_sourcing_header.reference_number', 'ASC')
					          ->orderBy('item_sourcing_header.id', 'DESC');
					// $sub_query->orwhere('item_sourcing_header.employee_name', $user->id)
	
					// 		  ->whereNull('item_sourcing_header.deleted_at');

				});

				$query->orderBy('item_sourcing_header.status_id', 'ASC')->orderBy('item_sourcing_header.id', 'DESC');
				//$query->orderByRaw('FIELD( item_sourcing_header.status_id, "For Approval")');
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
			$cancelled          = DB::table('statuses')->where('id', $this->cancelled)->value('status_description');   
			$closed             = DB::table('statuses')->where('id', $this->closed)->value('status_description');  
			$forDiscussion      = DB::table('statuses')->where('id', $this->forDiscussion)->value('status_description');  
			$forSourcing        = DB::table('statuses')->where('id', $this->forSourcing)->value('status_description');  
			$forStreamlining    = DB::table('statuses')->where('id', $this->forStreamlining)->value('status_description');
			$forItemCreation    = DB::table('statuses')->where('id', $this->forItemCreation)->value('status_description');
			$forArfCreation     = DB::table('statuses')->where('id', $this->forArfCreation)->value('status_description');
		
			
			if($column_index == 1){
				if($column_value == $forApproval){
					$column_value = '<span class="label label-warning">'.$forApproval.'</span>';
				}else if($column_value == $forDiscussion){
					$column_value = '<span class="label label-info">'.$forDiscussion.'</span>';
				}else if($column_value == $closed){
					$column_value = '<span class="label label-success">'.$closed.'</span>';
				}else if($column_value == $cancelled){
					$column_value = '<span class="label label-danger">'.$cancelled.'</span>';
				}else if($column_value == $forStreamlining){
					$column_value = '<span class="label label-info">'.$forStreamlining.'</span>';
				}else if($column_value == $forSourcing){
					$column_value = '<span class="label label-info">'.$forSourcing.'</span>';
				}else if($column_value == $forItemCreation){
					$column_value = '<span class="label label-info">'.$forItemCreation.'</span>';
				}else if($column_value == $forArfCreation){
					$column_value = '<span class="label label-info">'.$forArfCreation.'</span>';
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
	        $fields = Request::all();

			$dataLines = array();

			$employee_name 		     = $fields['employee_name'];
			$company_name 		     = $fields['company_name'];
			$position 			     = $fields['position'];
			$date_needed             = $fields['date_needed'];
			$department 		     = $fields['department'];
			$store_branch 		     = $fields['store_branch'];
			$store_branch_id         = $fields['store_branch_id'];
	
			$quantity_total 	     = $fields['quantity_total'];
			$cost_total 		     = $fields['cost_total'];
			$total 				     = $fields['total'];
			$request_type_id 	     = $fields['request_type_id'];
			$requestor_comments      = $fields['requestor_comments'];
			$suggested_supplier      = $fields['suggested_supplier'];

			$count_header            = DB::table('item_sourcing_header')->count();
			$header_ref              = str_pad($count_header + 1, 7, '0', STR_PAD_LEFT);			
			$reference_number	     = "NIS-".$header_ref;
			$employees               = DB::table('cms_users')->where('id', CRUDBooster::myId())->first();
			$pending                 = DB::table('statuses')->where('id', 1)->value('id');
			$approved                = DB::table('statuses')->where('id', 4)->value('id');

			if(in_array(CRUDBooster::myPrivilegeId(), [11,12,14,15])){ 

				$postdata['status_id']		 			= 37;
			}else{
				$postdata['status_id']		 			= 1;
	
			}
				
			$postdata['reference_number']		 	= $reference_number;
			$postdata['employee_name'] 				= $employees->id;
			$postdata['company_name'] 				= $employees->company_name_id;
			$postdata['position'] 					= $employees->position_id;
			$postdata['date_needed'] 				= $date_needed;
			$postdata['department'] 				= $employees->department_id;
			if(CRUDBooster::myPrivilegeId() == 8){
				$postdata['store_branch'] 			= $employees->location_id;
			}else{
				$postdata['store_branch'] 			= NULL;
			}
			
			$postdata['quantity_total'] 			= $quantity_total;
			$postdata['cost_total'] 				= $cost_total;
			$postdata['total'] 						= $total;
			$postdata['created_by'] 				= CRUDBooster::myId();
			$postdata['created_at'] 				= date('Y-m-d H:i:s');
			$postdata['request_type_id']		 	= $request_type_id;

			
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
			$nis_header = DB::table('item_sourcing_header')->where(['created_by' => CRUDBooster::myId()])->orderBy('id','desc')->first();

			$item_description 	= $fields['item_description'];
			$category_id 		= $fields['category_id'];
			$sub_category_id 	= $fields['sub_category_id'];
			$class_id 			= $fields['class_id'];
			$sub_class_id 	    = $fields['sub_class_id'];
			$brand 			    = $fields['brand'];
			$model 		     	= $fields['model'];
			$size 			    = $fields['size'];
			$actual_color       = $fields['actual_color'];
			$quantity 			= $fields['quantity'];
			$budget 	        = $fields['budget'];
			$requestor_comments      = $fields['requestor_comments'];
		 
	
			ItemBodySourcing::Create([
				'header_request_id' => $nis_header->id,
				'item_description' 	=> $item_description,
				'category_id' 		=> $category_id,
				'sub_category_id' 	=> $sub_category_id,
				'class_id' 	        => $class_id,
				'sub_class_id' 	    => $sub_class_id,
				'sub_category_id' 	=> $sub_category_id,
				'brand' 	        => $brand,
				'model' 	        => $model,
				'size' 	            => $size,
				'actual_color' 	    => $actual_color,
				'quantity' 			=> $quantity,
				'budget' 		    => $budget,
				'created_at' 		=> date('Y-m-d H:i:s'),
			]
	
			);

			if($requestor_comments){
				ItemSourcingComments::Create(
				    [
					'item_header_id' => $nis_header->id,
					'user_id'        => CRUDBooster::myId(),
					'comments'       => $requestor_comments,
					'created_at' 	 => date('Y-m-d H:i:s'),
				    ]
				);   
			}

			DB::beginTransaction();
	
			// try {
				
			// 	DB::commit();
			// 	//CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_pullout_data_success",['mps_reference'=>$pullout_header->reference]), 'success');
			// } catch (\Exception $e) {
			// 	DB::rollback();
			// 	CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
			// }
			
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
        
		//Supplies
		public function getAddItemSourcingSupplies() {

			if(!CRUDBooster::isCreate() && $this->global_privilege == false) {
				CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
			}

			$this->cbLoader();
			$data['page_title'] = 'Create Supplies Item Sourcing';
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
			$data['categories'] = DB::table('new_category')->where('category_status', 'ACTIVE')->orderby('category_description', 'asc')->get();
			$data['budget_range'] = DB::table('sub_masterfile_budget_range')->where('status', 'ACTIVE')->get();
			$privilegesMatrix = DB::table('cms_privileges')->where('id', '!=', 8)->get();
			$privileges_array = array();
			foreach($privilegesMatrix as $matrix){
				array_push($privileges_array, $matrix->id);
			}
			$privileges_string = implode(",",$privileges_array);
			$privilegeslist = array_map('intval',explode(",",$privileges_string));

			if(in_array(CRUDBooster::myPrivilegeId(), $privilegeslist)){ 
				$data['purposes'] = DB::table('request_type')->where('status', 'ACTIVE')->where('privilege', 'Employee')->get();
				return $this->view("item-sourcing.add-item-sourcing", $data);

			}else{ 
				$data['purposes'] = DB::table('request_type')->where('status', 'ACTIVE')->where('privilege', 'Employee')->get();
				$data['stores'] = DB::table('locations')->where('id', $data['user']->location_id)->first();
				return $this->view("item-sourcing.add-store-item-sourcing", $data);
			}
				
		}

		public function getDetail($id){
			$this->cbLoader();
            if(!CRUDBooster::isRead() && $this->global_privilege==FALSE) {    
                CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
            }

			$data = array();
			$data['page_title'] = 'Item Sourcing Detail';
			$data['Header'] = ItemHeaderSourcing::leftjoin('cms_users as employees', 'item_sourcing_header.employee_name', '=', 'employees.id')
				->leftjoin('companies', 'item_sourcing_header.company_name', '=', 'companies.id')
				->leftjoin('departments', 'item_sourcing_header.department', '=', 'departments.id')
				->leftjoin('locations', 'employees.location_id', '=', 'locations.id')
				->leftjoin('cms_users as requested', 'item_sourcing_header.created_by','=', 'requested.id')
				->leftjoin('cms_users as approved', 'item_sourcing_header.approved_by','=', 'approved.id')
				->leftjoin('cms_users as processed', 'item_sourcing_header.processed_by','=', 'processed.id')
				->leftjoin('cms_users as closed', 'item_sourcing_header.closed_by','=', 'closed.id')
				->select(
						'item_sourcing_header.*',
						'item_sourcing_header.id as requestid',
						'item_sourcing_header.created_at as created',
						'requested.name as requestedby',
						'employees.bill_to as employee_name',
						'item_sourcing_header.employee_name as header_emp_name',
						'item_sourcing_header.created_by as header_created_by',
						'departments.department_name as department',
						'item_sourcing_header.store_branch as store_branch',
						'approved.name as approvedby',
						'processed.name as processedby',
						'closed.name as closedby',
						'item_sourcing_header.created_at as created_at'
						)
				->where('item_sourcing_header.id', $id)->first();
		
			$data['Body'] = ItemBodySourcing::leftjoin('new_category', 'item_sourcing_body.category_id', '=', 'new_category.id')
			    ->leftjoin('new_sub_category', 'item_sourcing_body.sub_category_id', '=', 'new_sub_category.id')
				->leftjoin('new_class', 'item_sourcing_body.class_id', '=', 'new_class.id')
				->leftjoin('new_sub_class', 'item_sourcing_body.sub_class_id', '=', 'new_sub_class.id')
				->select(
				  'item_sourcing_body.*',
				  'item_sourcing_body.id as body_id',
				  'new_category.*',
				  'new_sub_category.*',
				  'new_class.*',
				  'new_sub_class.*',
				)
				->where('item_sourcing_body.header_request_id', $id)
				->get();
			$data['comments'] = ItemSourcingComments::
				leftjoin('cms_users', 'item_sourcing_comments.user_id', '=', 'cms_users.id')
				->select(
					'item_sourcing_comments.*',
					'cms_users.name'
				  )
				  ->where('item_sourcing_comments.item_header_id', $id)
				  ->get();

			return $this->view("item-sourcing.item-sourcing-detail", $data);
		}

		public function createArf(Request $request){
			$fields = Request::all();
			
		    $headerId        = $fields['header_id'];
			$bodyIds         = $fields['body_ids'];
			$request_type_id = array_unique($fields['request_type_id']);
			$ids = implode(',',$bodyIds);
			$bodyIdLists = array_map('intval',explode(",",$ids));
			$item_sourcing_header = ItemHeaderSourcing::where(['id' => $headerId])->first();
			$item_sourcing_body = ItemBodySourcing::whereIn('id',$bodyIdLists)->get();

			$latestRequest = DB::table('header_request')->select('id')->orderBy('id','DESC')->first();
			$latestRequestId = $latestRequest->id != NULL ? $latestRequest->id : 0;
			
			//add in arf heaader request table
			$count_header       = DB::table('header_request')->count();
		
			$arfHeaderSave = [];
			$arfHeaderContainer = [];
			foreach($request_type_id as $arfHeadKey => $arfHeadVal){
				if($arfHeadVal == 1){
					$arfHeaderContainer['status_id']              = $this->forItReco;
					$arfHeaderContainer['application'] 			  = $item_sourcing_header->application;
					$arfHeaderContainer['application_others'] 	  = $item_sourcing_header->application_others;
					$arfHeaderContainer['to_reco']                = 1;
				}else{
					$arfHeaderContainer['status_id']              = $this->forTagging;
					$arfHeaderContainer['application'] 			  = NULL;
					$arfHeaderContainer['application_others'] 	  = NULL;  
					$arfHeaderContainer['to_reco']                = 0;
				}
				$arfHeaderContainer['reference_number']		      = "ARF-".str_pad($count_header + 1, 7, '0', STR_PAD_LEFT);
				$count_header ++;
				$arfHeaderContainer['employee_name' ]		      = $item_sourcing_header->employee_name;
				$arfHeaderContainer['company_name'] 			  = $item_sourcing_header->company_name;
				$arfHeaderContainer['position'] 				  = $item_sourcing_header->position;
				$arfHeaderContainer['department' ]				  = $item_sourcing_header->department;
				$arfHeaderContainer['store_branch']		          = $item_sourcing_header->store_branch;
				$arfHeaderContainer['purpose'] 				      = 2;
				$arfHeaderContainer['conditions'] 				  = $item_sourcing_header->conditions;
				$arfHeaderContainer['quantity_total'] 			  = $item_sourcing_header->quantity_total;
				$arfHeaderContainer['cost_total'] 				  = $item_sourcing_header->cost_total;
				$arfHeaderContainer['total'] 					  = $item_sourcing_header->total;
				$arfHeaderContainer['requestor_comments'] 		  = $item_sourcing_header->requestor_comments;
				$arfHeaderContainer['created_by'] 				  = CRUDBooster::myId();
				$arfHeaderContainer['created_at'] 				  = date('Y-m-d H:i:s');
				$arfHeaderContainer['approved_by'] 		          = $item_sourcing_header->approver_by;
				$arfHeaderContainer['approved_at'] 		          = date('Y-m-d H:i:s');
				$arfHeaderContainer['request_type_id']		 	  = $arfHeadVal;
				$arfHeaderContainer['privilege_id']		 	      = NULL;
				$arfHeaderContainer['if_from_item_source' ]		  = $item_sourcing_header->reference_number;
			
				$arfHeaderSave[] = $arfHeaderContainer;
			}
			HeaderRequest::insert($arfHeaderSave);
			$itId = DB::table('header_request')->select('*')->where('id','>', $latestRequestId)->where('request_type_id',1)->first();
			$faId = DB::table('header_request')->select('*')->where('id','>', $latestRequestId)->where('request_type_id',5)->first();
			$SuppliesId = DB::table('header_request')->select('*')->where('id','>', $latestRequestId)->where('request_type_id',7)->first();
			$MarketingId = DB::table('header_request')->select('*')->where('id','>', $latestRequestId)->where('request_type_id',6)->first();
	
			$resultArrforIT = [];
			foreach($item_sourcing_body as $item){
				if($item['request_type_id'] == 1){
					for($i = 0; $i < $item['request_type_id']; $i++){
						$t = $item;
						$t['header_request_id'] = $itId->id;
						$resultArrforIT[] = $t;
					}
				}
			}

			$resultArrforFA = [];
			foreach($item_sourcing_body as $itemFa){
				if($itemFa['request_type_id'] == 5){
					for($x = 0; $x < $itemFa['request_type_id']; $x++){
						$fa = $itemFa;
						$fa['header_request_id'] = $faId->id;
						$resultArrforFA[] = $fa;
					}
				}
			}

			$resultArrforSu = [];	
			foreach($item_sourcing_body as $itemSu){
				if($itemSu['request_type_id'] == 7){
					for($s = 0; $s < $itemSu['request_type_id']; $s++){
						$su = $itemSu;
						$su['header_request_id'] = $SuppliesId->id;
						$resultArrforSu[] = $su;
					}
				}
			}

			$resultArrforMarketing = [];	
			foreach($item_sourcing_body as $itemMkt){
				if($itemMkt['request_type_id'] == 6){
					for($m = 0; $m < $itemMkt['request_type_id']; $m++){
						$mkt = $itemMkt;
						$mkt['header_request_id'] = $MarketingId->id;
						$resultArrforMarketing[] = $mkt;
					}
				}
			}


			//save items in Body Request
			$insertData = [];
			$insertContainer = [];
			foreach($item_sourcing_body as $key => $val){
				$insertContainer['header_request_id']   = $val['header_request_id'];
				$insertContainer['digits_code'] 	    = $val['digits_code'];
				$insertContainer['item_description'] 	= $val['reco_item_description'];
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

			//make array base on general quantity
			$itAssets = [];
			foreach($insertData as $itItem){
				if($itItem['category_id'] == "IT ASSETS"){
					for($i = 0; $i < $itItem['quantity']; $i++){
						// make sure the quantity is now 1 and not the original > 1 value
						$it = $itItem;
						$it['quantity'] = 1;
						$itAssets[] = $it;
					}
				}
			}
			$faAssets = [];
			foreach($insertData as $faItem){
				if($faItem['category_id'] == "FIXED ASSETS"){
					for($j = 0; $j < $faItem['quantity']; $j++){
						// make sure the quantity is now 1 and not the original > 1 value
						$fa = $faItem;
						$fa['quantity'] = 1;
						$faAssets[] = $fa;
					}
				}
			}

			$suppAssets = [];
			foreach($insertData as $suppItem){
				if($suppItem['category_id'] == "SUPPLIES"){
					// make sure the quantity is now 1 and not the original > 1 value
					$sp = $suppItem;
					$sp['quantity'] = $suppItem['quantity'];
					$suppAssets[] = $sp;
					
				}
			}

			$mktAssets = [];
			foreach($insertData as $mktItem){
				if($mktItem['category_id'] == "MARKETING"){
					// make sure the quantity is now 1 and not the original > 1 value
					$mkt = $mktItem;
					$mkt['quantity'] = $mktItem['quantity'];
					$mktAssets[] = $mkt;
					
				}
			}
			$insertData = array_merge($itAssets, $faAssets,$suppAssets, $mktAssets);
            
			//update flag in item sourcing body
			for ($i = 0; $i < count($bodyIds); $i++) {
				ItemBodySourcing::where(['id' => $bodyIds[$i]])
				   ->update([
					       'if_arf_created'   => 1, 
				           ]);
			}

			BodyRequest::insert($insertData);

			$message = ['status'=>'success', 'message' => 'Created Successfully!'];
			echo json_encode($message);
			
		}

		public function RemoveItemSource(Request $request)
		{
	       
			$data = 				Request::all();	
			$headerID = 			$data['headerID'];
			$bodyID = 				$data['bodyID'];
			$quantity_total = 		$data['quantity_total']; 
       
			ItemHeaderSourcing::where('id', $headerID)
			->update([
				'quantity_total'=> 		$quantity_total
			]);	


			ItemBodySourcing::where('id', $bodyID)
			->update([
				'deleted_at'=> 		date('Y-m-d H:i:s'),
				'deleted_by'=> 		CRUDBooster::myId()
			]);	

			$bodyCount = DB::table('item_sourcing_body')->where('header_request_id',$headerID)->whereNull('item_sourcing_body.deleted_at')->count();

			if($bodyCount == 0){
			ItemHeaderSourcing::where('id', $headerID)
				->update([
					'status_id'=> 8,
					'cancelled_by'=> CRUDBooster::myId(),
					'cancelled_at'=> date('Y-m-d H:i:s')
				]);	
			
			}
			$message = ['status'=>'success', 'message' => 'Cancelled Successfully!'];
			echo json_encode($message);
			
		}

		public function getRequestCancelNis($id) {

			ItemHeaderSourcing::where('id',$id)
			->update([
					'status_id'=> 8,
					'cancelled_by'=> CRUDBooster::myId(),
					'cancelled_at'=> date('Y-m-d H:i:s')	
			]);	
			ItemBodySourcing::where('header_request_id', $id)
			->update([
				'deleted_at'=> 		date('Y-m-d H:i:s'),
				'deleted_by'=> 		CRUDBooster::myId()
			]);	
			
			CRUDBooster::redirect(CRUDBooster::mainpath(), trans("Request has been cancelled successfully!"), 'success');
		}


		public function SubCategories(Request $request){
			$data = Request::all();	
			$id = $data['id'];
			$categories = DB::table('new_category')->where('category_description', $id)->first();

			$subcategories = DB::table('new_sub_category')
							->select('new_sub_category.*')
							->where('category_id', $id)
							->where('sub_status', "ACTIVE")
							->orderby('sub_category_description', 'ASC')->get();
	
			return($subcategories);
		}

		public function Class(Request $request){
			$data = Request::all();	
			$id = $data['id'];
			$sub_categories = DB::table('new_sub_category')->where('sub_category_description', $id)->first();

			$subcategories = DB::table('new_class')
							->select('new_class.*')
							->where('sub_category_id', $id)
							->where('class_status', "ACTIVE")
							->orderby('class_description', 'ASC')->get();
	
			return($subcategories);
		}

		public function SubClass(Request $request){
			$data = Request::all();	
			$id = $data['id'];
			$class = DB::table('new_class')->where('class_description', $id)->first();

			$subcategories = DB::table('new_sub_class')
							->select('new_sub_class.*')
							->where('class_id', $id)
							->where('sub_class_status', "ACTIVE")
							->orderby('sub_class_description', 'ASC')->get();
	
			return($subcategories);
		}

		public function saveMessage(Request $request){
            $fields   = Request::all();
			$id       = $fields['header_id'];
			$comments = $fields['message'];

			$comment = new ItemSourcingComments();
			
			$comment->item_header_id = $id;
			$comment->user_id = CRUDBooster::myId();
			$comment->comments = $comments;
			$comment->created_at = date('Y-m-d H:i:s');
			$comment->save();

			$item_sourcing_header = ItemHeaderSourcing::where(['id' => $id])->first();

			$config['content'] = "Item Source Messages (".$item_sourcing_header->reference_number.")";
			$config['to'] = $link = CRUDBooster::adminPath('item-sourcing-header/detail/'.$id.'');
			$config['id_cms_users'] = [$item_sourcing_header->created_by, $item_sourcing_header->processed_by, $item_sourcing_header->approved_by]; //The Id of the user that is going to receive notification. This could be an array of id users [1,2,3,4,5]
			CRUDBooster::sendNotification($config);

			$data = array();
			$data['status'] = 'error';
			$data['message'] = $comment;
			$data['comment_by'] = CRUDBooster::myName();
			if(!empty($comment)){
				$data['status'] = 'success';
			}
			return json_encode($data);
		}

		public function editItemSource(Request $request){
			$fields           = Request::all();
			$id               = $fields['id'];
			$item_description = $fields['item_description'];
			$brand            = $fields['brand'];
			$model            = $fields['model'];
			$size             = $fields['size'];
			$actual_color     = $fields['actual_color'];
			$quantity         = $fields['quantity'];
			$header_id        = $fields['headerID'];
     
			ItemBodySourcing::where(['id' => $id])
				->update([
						'item_description'           => $item_description, 
						'brand'                      => $brand,
						'model'                      => $model,
						'size'                       => $size,
						'actual_color'               => $actual_color,
						'quantity'                   => $quantity,
						'updated_by'                 => CRUDBooster::myId(),
						]);
		    $item_source_header = ItemHeaderSourcing::where(['id' => $header_id])->first();
			$employee_name = DB::table('cms_users')->where('id', CRUDBooster::myId())->first();
			$approver_name = DB::table('cms_users')->where('id', $employee_name->approver_id)->first();
			$department_name = DB::table('departments')->where('id', $employee_name->department_id)->first();
			$fhil = "fhilipacosta@digits.ph";

			$infos['assign_to'] = $employee_name->bill_to;
			$infos['reference_number'] = $item_source_header->reference_number;
			$infos['item_description'] = $item_description;
			$infos['brand'] = $brand;
			$infos['model'] = $model;
			$infos['size'] = $size;
			$infos['actual_color'] = $actual_color;
			$infos['quantity'] = $quantity;
		
			Mail::to($employee_name->email)
					//->cc([$fhil])
					->send(new Email($infos));
			$message = ['status'=>'success', 'message' => 'Update Successfully!'];
			echo json_encode($message);
		}

	}
?>