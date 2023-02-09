<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use App\models\Applicant;
	use App\statuses;
	use App\Models\ErfHeaderRequest;

	class AdminApplicantModuleController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "first_name";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = false;
			$this->button_action_style = "button_icon";
			$this->button_add = false;
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = false;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "applicant_table";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Status","name"=>"status","join"=>"statuses,status_description"];
			$this->col[] = ["label"=>"Erf Number","name"=>"erf_number"];
			$this->col[] = ["label"=>"First Name","name"=>"first_name"];
			$this->col[] = ["label"=>"Last Name","name"=>"last_name"];
			$this->col[] = ["label"=>"Screen Date","name"=>"screen_date"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Created At","name"=>"created_at"];
			$this->col[] = ["label"=>"Update By","name"=>"updated_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Update At","name"=>"updated_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];

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
				$for_job_offer =  36;
				$cancelled     =  8;
				$this->addaction[] = ['title'=>'Update','url'=>CRUDBooster::mainpath('getEditApplicant/[id]'),'icon'=>'fa fa-pencil' , "showIf"=>"[status] != $for_job_offer && [status] != $cancelled"];
				$this->addaction[] = ['title'=>'Detail','url'=>CRUDBooster::mainpath('getDetailApplicant/[id]'),'icon'=>'fa fa-eye', "showIf"=>"[status] == $for_job_offer || [status] == $cancelled"];

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
				$this->index_button[] = ["label"=>"Add Applicant","icon"=>"fa fa-plus-circle","url"=>CRUDBooster::mainpath('add-applicant'),"color"=>"success"];
			}


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
	    	$cancelled        =  DB::table('statuses')->where('id', 8)->value('status_description');        
			$first_interview  =  DB::table('statuses')->where('id', 34)->value('status_description');  
			$final_interview  =  DB::table('statuses')->where('id', 35)->value('status_description');  
			$job_offer        =  DB::table('statuses')->where('id', 36)->value('status_description');    
 
			if($column_index == 1){
				if($column_value == $first_interview){
					$column_value = '<span class="label label-info">'.$first_interview.'</span>';
				}else if($column_value == $final_interview){
					$column_value = '<span class="label label-info">'.$final_interview.'</span>';
				}else if($column_value == $job_offer){
					$column_value = '<span class="label label-success">'.$job_offer.'</span>';
				}else if($column_value == $cancelled){
					$column_value = '<span class="label label-danger">'.$cancelled.'</span>';
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
			$erf_number  = $fields['erf_number'];
			$screen_date = $fields['screen_date'];
			$first_name  = $fields['first_name'];
			$last_name   = $fields['last_name'];

			$postdata['status']      = 34;
			$postdata['erf_number']  = $erf_number;
			$postdata['screen_date'] = $screen_date;
			$postdata['first_name']  = $first_name;
			$postdata['last_name']   = $last_name;
	        $postdata['created_by']  = CRUDBooster::myId();

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
			$erf_number = $fields['erf_number'];
			// dd($fields);
			$status = $fields['status'];
			if($status == 36){
				ErfHeaderRequest::where('reference_number',$erf_number)
				->update([
					'status_id'		 => 31
				]);	
			}
			$postdata['status']      = $status;
			$postdata['updated_by']  = CRUDBooster::myId();
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

		public function getAddApplicant() {
			if(!CRUDBooster::isCreate() && $this->global_privilege == false) {
				CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
			}
			$this->cbLoader();
			$data['page_title'] = 'Create New Applicant';
			$data['erf_number'] = DB::table('erf_header_request')->where('status_id', 30)->get();
			return $this->view("applicant.add-applicant", $data);
	
		}

		public function getEditApplicant($id){
			$this->cbLoader();
            if(!CRUDBooster::isRead() && $this->global_privilege==FALSE) {    
                CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
            }

			$data = array();
			$data['page_title'] = 'Edit Applicant Status';
			$data['applicant'] = Applicant::select(
						'applicant_table.*',
						'applicant_table.id as apid'
						)
				->where('applicant_table.id', $id)->first();
			$data['erf_number'] = DB::table('erf_header_request')->where('status_id', 30)->get();
			$data['statuses'] = Statuses::select(
					'statuses.*'
				  )
				  ->whereIn('id', [34,35,36,8])
				  ->get();

			return $this->view("applicant.edit_applicant_status", $data);
		}

		public function getDetailApplicant($id){
			$this->cbLoader();
            if(!CRUDBooster::isRead() && $this->global_privilege==FALSE) {    
                CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
            }

			$data = array();
			$data['page_title'] = 'Applicant Detail';
			$data['applicant'] = Applicant::
			leftjoin('statuses','applicant_table.status','=','statuses.id')
			->select(
						'applicant_table.*',
						'applicant_table.id as apid',
						'statuses.status_description as status_desc'
						)
				->where('applicant_table.id', $id)->first();
			$data['erf_number'] = DB::table('erf_header_request')->where('status_id', 30)->get();
			return $this->view("applicant.applicant_detail", $data);
		}


	}