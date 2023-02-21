<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use App\BodyRequest;
	use App\HeaderRequest;
	use App\MoveOrder;
	use App\Models\ReturnTransferAssets;
	use Maatwebsite\Excel\Facades\Excel;
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Reader\Exception;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	use PhpOffice\PhpSpreadsheet\IOFactory;
	use Illuminate\Support\Facades\Log;
	use Illuminate\Support\Facades\Redirect;
	use Illuminate\Contracts\Cache\LockTimeoutException;
	use App\Exports\ExportRequestByApprover;

	class AdminReportsController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->button_edit = true;
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
			$this->col[] = ["label"=>"Reference Number","name"=>"reference_number"];
			$this->col[] = ["label"=>"Mo Reference Number","name"=>"mo_reference_number"];
			$this->col[] = ["label"=>"Status Id","name"=>"status_id","join"=>"statuses,id"];
			$this->col[] = ["label"=>"Employee Name","name"=>"employee_name"];
			$this->col[] = ["label"=>"Company Name","name"=>"company_name"];
			$this->col[] = ["label"=>"Position","name"=>"position"];
			$this->col[] = ["label"=>"Department","name"=>"department"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];

			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ["label"=>"Reference Number","name"=>"reference_number","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Mo Reference Number","name"=>"mo_reference_number","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Status Id","name"=>"status_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"status,id"];
			//$this->form[] = ["label"=>"Employee Name","name"=>"employee_name","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Company Name","name"=>"company_name","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Position","name"=>"position","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Department","name"=>"department","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Store Branch","name"=>"store_branch","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Purpose","name"=>"purpose","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Conditions","name"=>"conditions","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Quantity Total","name"=>"quantity_total","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Cost Total","name"=>"cost_total","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Total","name"=>"total","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Approved By","name"=>"approved_by","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Approved At","name"=>"approved_at","type"=>"datetime","required"=>TRUE,"validation"=>"required|date_format:Y-m-d H:i:s"];
			//$this->form[] = ["label"=>"Created By","name"=>"created_by","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Updated By","name"=>"updated_by","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Rejected At","name"=>"rejected_at","type"=>"datetime","required"=>TRUE,"validation"=>"required|date_format:Y-m-d H:i:s"];
			//$this->form[] = ["label"=>"Requestor Comments","name"=>"requestor_comments","type"=>"textarea","required"=>TRUE,"validation"=>"required|string|min:5|max:5000"];
			//$this->form[] = ["label"=>"Request Type Id","name"=>"request_type_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"request_type,id"];
			//$this->form[] = ["label"=>"Privilege Id","name"=>"privilege_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"privilege,id"];
			//$this->form[] = ["label"=>"Approver Comments","name"=>"approver_comments","type"=>"textarea","required"=>TRUE,"validation"=>"required|string|min:5|max:5000"];
			//$this->form[] = ["label"=>"To Reco","name"=>"to_reco","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"It Comments","name"=>"it_comments","type"=>"textarea","required"=>TRUE,"validation"=>"required|string|min:5|max:5000"];
			//$this->form[] = ["label"=>"Recommended By","name"=>"recommended_by","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Recommended At","name"=>"recommended_at","type"=>"datetime","required"=>TRUE,"validation"=>"required|date_format:Y-m-d H:i:s"];
			//$this->form[] = ["label"=>"Cancelled By","name"=>"cancelled_by","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Cancelled At","name"=>"cancelled_at","type"=>"datetime","required"=>TRUE,"validation"=>"required|date_format:Y-m-d H:i:s"];
			//$this->form[] = ["label"=>"Purchased1 By","name"=>"purchased1_by","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Purchased1 At","name"=>"purchased1_at","type"=>"datetime","required"=>TRUE,"validation"=>"required|date_format:Y-m-d H:i:s"];
			//$this->form[] = ["label"=>"Purchased2 By","name"=>"purchased2_by","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Purchased2 At","name"=>"purchased2_at","type"=>"datetime","required"=>TRUE,"validation"=>"required|date_format:Y-m-d H:i:s"];
			//$this->form[] = ["label"=>"Ac Comments","name"=>"ac_comments","type"=>"textarea","required"=>TRUE,"validation"=>"required|string|min:5|max:5000"];
			//$this->form[] = ["label"=>"Mo By","name"=>"mo_by","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Mo At","name"=>"mo_at","type"=>"datetime","required"=>TRUE,"validation"=>"required|date_format:Y-m-d H:i:s"];
			//$this->form[] = ["label"=>"Print By","name"=>"print_by","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Print At","name"=>"print_at","type"=>"datetime","required"=>TRUE,"validation"=>"required|date_format:Y-m-d H:i:s"];
			//$this->form[] = ["label"=>"Picked By","name"=>"picked_by","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Picked At","name"=>"picked_at","type"=>"datetime","required"=>TRUE,"validation"=>"required|date_format:Y-m-d H:i:s"];
			//$this->form[] = ["label"=>"Print By Form","name"=>"print_by_form","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Print At Form","name"=>"print_at_form","type"=>"datetime","required"=>TRUE,"validation"=>"required|date_format:Y-m-d H:i:s"];
			//$this->form[] = ["label"=>"Received By","name"=>"received_by","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Received At","name"=>"received_at","type"=>"datetime","required"=>TRUE,"validation"=>"required|date_format:Y-m-d H:i:s"];
			//$this->form[] = ["label"=>"Closed By","name"=>"closed_by","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Closed At","name"=>"closed_at","type"=>"datetime","required"=>TRUE,"validation"=>"required|date_format:Y-m-d H:i:s"];
			//$this->form[] = ["label"=>"Quote Date","name"=>"quote_date","type"=>"date","required"=>TRUE,"validation"=>"required|date"];
			//$this->form[] = ["label"=>"Po Date","name"=>"po_date","type"=>"date","required"=>TRUE,"validation"=>"required|date"];
			//$this->form[] = ["label"=>"Po Number","name"=>"po_number","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Employee Dr Date","name"=>"employee_dr_date","type"=>"date","required"=>TRUE,"validation"=>"required|date"];
			//$this->form[] = ["label"=>"Dr Number","name"=>"dr_number","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Application","name"=>"application","type"=>"textarea","required"=>TRUE,"validation"=>"required|string|min:5|max:5000"];
			//$this->form[] = ["label"=>"Application Others","name"=>"application_others","type"=>"textarea","required"=>TRUE,"validation"=>"required|string|min:5|max:5000"];
			//$this->form[] = ["label"=>"Mo Plug","name"=>"mo_plug","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Closing Plug","name"=>"closing_plug","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"To Print","name"=>"to_print","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Location Id","name"=>"location_id","type"=>"select2","required"=>TRUE,"validation"=>"required|string|min:5|max:5000","datatable"=>"location,id"];
			//$this->form[] = ["label"=>"To Mo","name"=>"to_mo","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Mo So Num","name"=>"mo_so_num","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
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
		//customize index
		public function getIndex() {
			//First, Add an auth
			if(!CRUDBooster::isView()) CRUDBooster::redirect(CRUDBooster::adminPath(),trans('crudbooster.denied_access'));
			
			//Create your own query 
			$data = [];
			$data['page_title'] = 'Request Assets Status Reports';

			$result_one = BodyRequest::arrayone();
			$result_two = ReturnTransferAssets::arraytwo();
            $suppliesMarketing = [];
			$suppliesMarketingCon = [];
	
			foreach($result_one as $smVal){
				$suppliesMarketingCon['id'] = $smVal['requestid'];
				$suppliesMarketingCon['reference_number'] = $smVal['reference_number'];
				$suppliesMarketingCon['requested_by'] = $smVal['requestedby'];
				$suppliesMarketingCon['department'] = $smVal['department'] ? $smVal['department'] : $smVal['store_branch'];
				$suppliesMarketingCon['store_branch'] = $smVal['store_branch'] ? $smVal['store_branch'] : $smVal['department'];
				$suppliesMarketingCon['transaction_type'] = "REQUEST";
				$bodyStatus = $smVal['body_statuses_description'] ? $smVal['body_statuses_description'] : $smVal['status_description'];
				if(in_array($smVal['request_type_id'], [6,7])){
					$suppliesMarketingCon['status'] = $smVal['status_description'];
					$suppliesMarketingCon['description'] = $smVal['body_description'];
					$suppliesMarketingCon['request_quantity'] = $smVal['body_quantity'];
					$suppliesMarketingCon['request_type'] = $smVal['body_category_id'];
					$suppliesMarketingCon['mo_reference'] = $smVal['body_mo_so_num'];
					$suppliesMarketingCon['mo_item_code'] = $smVal['body_digits_code'];
					$suppliesMarketingCon['mo_item_description'] = $smVal['body_description'];
					$suppliesMarketingCon['mo_qty_serve_qty'] = $smVal['serve_qty'];
				}else{
					$suppliesMarketingCon['status'] = isset($smVal['mo_reference_number']) ? $smVal['mo_statuses_description'] : $bodyStatus;
					$suppliesMarketingCon['description'] = $smVal['body_description'];
					$suppliesMarketingCon['request_quantity'] = $smVal['body_quantity'];
					$suppliesMarketingCon['request_type'] = $smVal['body_category_id'];
					$suppliesMarketingCon['mo_reference'] = $smVal['mo_reference_number'];
					$suppliesMarketingCon['mo_item_code'] = $smVal['digits_code'];
					$suppliesMarketingCon['mo_item_description'] = $smVal['item_description'];
					$suppliesMarketingCon['mo_qty_serve_qty'] = $smVal['quantity'];
				}
				$suppliesMarketingCon['requested_date'] = $smVal['created_at'];
				$suppliesMarketingCon['transacted_by'] = $smVal['taggedby'];
				$suppliesMarketingCon['transacted_date'] = $smVal['transacted_date'];
				$suppliesMarketing[] = $suppliesMarketingCon;
			}

			$returnTransfer = [];
			$returnTransferCon = [];
			foreach($result_two as $rtVal){
				$returnTransferCon['id'] = $rtVal['requestid'];
				$returnTransferCon['reference_number'] = $rtVal['reference_no'];
				$returnTransferCon['requested_by'] = $rtVal['employee_name'];
				$returnTransferCon['department'] = $rtVal['department_name'] ? $rtVal['department_name'] : $rtVal['store_branch'];
				$returnTransferCon['store_branch'] = $rtVal['store_branch'] ? $rtVal['store_branch'] : $rtVal['department_name'];
				$returnTransferCon['status'] = $rtVal['status_description'];
				$returnTransferCon['description'] = $rtVal['description'];
				$returnTransferCon['request_quantity'] = $rtVal['quantity'];
				$returnTransferCon['transaction_type'] = $rtVal['request_type'];
				$returnTransferCon['request_type'] = $rtVal['request_name'];
				$returnTransferCon['mo_reference'] = $rtVal['reference_no'];
				$returnTransferCon['mo_item_code'] = $rtVal['digits_code'];
				$returnTransferCon['mo_item_description'] = $rtVal['description'];
				$returnTransferCon['mo_qty_serve_qty'] = $rtVal['quantity'];
				$returnTransferCon['requested_date'] = $rtVal['requested_date'];
				$returnTransferCon['transacted_by'] = $rtVal['receivedby'];
				$returnTransferCon['transacted_date'] = $rtVal['transacted_date'];
				$returnTransfer[] = $returnTransferCon;
			}
			//dd($returnTransfer);
			$data['finalData'] = array_merge($suppliesMarketing, $returnTransfer);

			$data['categories'] = DB::table('requests')->whereIn('id', [1,5,6,7])->where('status', 'ACTIVE')
													   ->orderby('request_name', 'asc')
													   ->get();

			return $this->view('assets.purchasing-reports',$data);
		}

		public function searchApplicant(Request $request){
			$fields = Request::all();
			
			$from = $fields['from'];
			$to = $fields['to'];
		    $category = $fields['category'];
			$data = [];
            $filters = [];
			$data['page_title'] = 'Applicant Detail';

			$applicant = BodyRequest::orderby('body_request.id','asc')->leftjoin('header_request', 'body_request.header_request_id', '=', 'header_request.id');
			if($from != '' && !is_null($from)){
				$applicant->whereBetween('header_request.approved_at',[$from,$to]);
				$filters['filter_column[header_request.approved_at][type]'] = '=';
				$filters['filter_column[header_request.approved_at][value][0]'] = $from;
                $filters['filter_column[header_request.approved_at][value][1]'] = $to;
			}
			if($category != '' && !is_null($category)){
				$applicant->where('header_request.request_type_id', $category);
				$filters['filter_column[header_request.request_type_id][type]'] = '=';
				$filters['filter_column[header_request.request_type_id][value]'] = $category;
			}
			$applicant
			->leftjoin('mo_body_request', 'body_request.id', '=', 'mo_body_request.body_request_id')
			->leftjoin('request_type', 'header_request.purpose', '=', 'request_type.id')
			->leftjoin('condition_type', 'header_request.conditions', '=', 'condition_type.id')
			->leftjoin('employees', 'header_request.employee_name', '=', 'employees.id')
			->leftjoin('companies', 'header_request.company_name', '=', 'companies.id')
			->leftjoin('departments', 'header_request.department', '=', 'departments.id')
			->leftjoin('positions', 'header_request.position', '=', 'positions.id')
			->leftjoin('locations', 'header_request.store_branch', '=', 'locations.id')
			->leftjoin('cms_users as requested', 'header_request.created_by','=', 'requested.id')
			->leftjoin('cms_users as approved', 'header_request.approved_by','=', 'approved.id')
			->leftjoin('cms_users as recommended', 'header_request.recommended_by','=', 'recommended.id')
			->leftjoin('cms_users as tagged', 'header_request.purchased2_by','=', 'tagged.id')
			->leftjoin('statuses', 'header_request.status_id', '=', 'statuses.id')
			->leftjoin('statuses as body_statuses', 'body_request.line_status_id', '=', 'body_statuses.id')
			->leftjoin('statuses as mo_statuses', 'mo_body_request.status_id', '=', 'mo_statuses.id')
			->select('body_request.*',
					'header_request.*',
					'mo_body_request.*',
					'header_request.id as requestid',
					'header_request.created_at as created',
					'request_type.*',
					'condition_type.*',
					'requested.name as requestedby',
					'employees.bill_to as employee_name',
					'companies.company_name as company_name',
					'departments.department_name as department',
					'locations.store_name as store_branch',
					'approved.name as approvedby',
					'recommended.name as recommendedby',
					'tagged.name as taggedby',
					'header_request.purchased2_at as transacted_date',
					'header_request.created_at as created_at',
					'statuses.status_description as status_description',
					'body_request.item_description as body_description',
					'body_request.digits_code as body_digits_code',
					'body_request.quantity as body_quantity',
					'mo_statuses.status_description as mo_statuses_description',
					'body_statuses.status_description as body_statuses_description',
					'body_request.category_id as body_category_id',
					'mo_body_request.body_request_id as mo_body_request_id',
					'mo_body_request.item_description as mo_item_description',
					'body_request.mo_so_num as body_mo_so_num'
					
				    );
		
					
			$data['result']        = $applicant->get();
			$data['from']          = $from;
			$data['to']            = $to;
			$data['category']      = $category;
			$data['filters'] = $filters;
			//dd($data['result'], $data['filters']);
			return $this->view("assets.purchasing-reports-view", $data);
		}

		public function requestExport(Request $request){
			$fields = Request::all();
			$filename = $fields['filename'];
			return Excel::download(new ExportRequestByApprover($fields), $filename.'.xlsx');

		}


	}