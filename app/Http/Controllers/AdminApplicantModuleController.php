<?php namespace App\Http\Controllers;

	use Request;
	use DB;
	use CRUDBooster;
	use App\Models\Applicant;
	use App\Statuses;
	use App\Models\ErfHeaderRequest;
	use App\Imports\ApplicantUpload;
	use App\Exports\ExportApplicantMultipleSheet;
	use Maatwebsite\Excel\Facades\Excel;
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

	class AdminApplicantModuleController extends \crocodicstudio\crudbooster\controllers\CBController {
		private $cancelled;
		private $rejected;
		private $first_interview;
		private $final_interview;
		private $job_offer;
		private $jo_done;
		private $new_applicant;
		
		public function __construct() {
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
			$this->cancelled        =  8; 
			$this->rejected         =  5;        
			$this->first_interview  =  34;  
			$this->final_interview  =  35;  
			$this->job_offer        =  36;    
			$this->jo_done          =  31; 
			$this->new_applicant    =  47;   
		}
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
				$for_job_offer =  $this->jo_done;
				$cancelled     =  $this->cancelled;

				$this->addaction[] = ['title'=>'Update','url'=>CRUDBooster::mainpath('edit-applicant'),'icon'=>'fa fa-pencil' , "showIf"=>"[status] != $for_job_offer && [status] != $cancelled && [status] != $this->rejected"];
				$this->addaction[] = ['title'=>'Detail','url'=>CRUDBooster::mainpath('detail-applicant'),'icon'=>'fa fa-eye', "showIf"=>"[status] == $for_job_offer || [status] == $cancelled"];

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
				$this->index_button[] = ["label"=>"Import","icon"=>"fa fa-upload","url"=>CRUDBooster::mainpath('applicant-upload'),"color"=>"success"];
	
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
			$this->script_js = "
			$(document).ready(function() {
				$(\"#myModal\").modal('hide');
				$('#export').click(function(event) {
					event.preventDefault();
					$(\"#myModal\").modal('show');
				});
				$(\".modal\").on(\"hidden.bs.modal\", function(){
					$(\"#start_date\").val(\"\");
					$(\"#start_end\").val(\"\");
				 });
			});
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
	        $this->load_css[] = asset("css/font-family.css");
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
	    	$cancelled        =  DB::table('statuses')->where('id', $this->cancelled)->value('status_description');  
			$rejected         =  DB::table('statuses')->where('id', $this->rejected)->value('status_description');     
			$first_interview  =  DB::table('statuses')->where('id', $this->first_interview)->value('status_description');  
			$final_interview  =  DB::table('statuses')->where('id', $this->final_interview)->value('status_description');  
			$job_offer        =  DB::table('statuses')->where('id', $this->job_offer)->value('status_description');    
			$jo_done          =  DB::table('statuses')->where('id', $this->jo_done)->value('status_description'); 
			$new_applicant    =  DB::table('statuses')->where('id', $this->new_applicant)->value('status_description');   
			if($column_index == 1){
				if($column_value == $new_applicant){
					$column_value = '<span class="label label-info">'.$new_applicant.'</span>';
				}else if($column_value == $first_interview){
					$column_value = '<span class="label label-info">'.$first_interview.'</span>';
				}else if($column_value == $final_interview){
					$column_value = '<span class="label label-info">'.$final_interview.'</span>';
				}else if($column_value == $jo_done){
					$column_value = '<span class="label label-success">'.$jo_done.'</span>';
				}else if($column_value == $cancelled){
					dd($column_value);
					$column_value = '<span class="label label-danger">'.$cancelled.'</span>';
				}else if($column_value == $rejected){
					$column_value = '<span class="label label-danger">'.$rejected.'</span>';
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
			$erf_number   = $fields['erf_number'];
			$screen_date  = $fields['screen_date'];
			$first_name   = $fields['first_name'];
			$last_name    = $fields['last_name'];
			$job_portal   = $fields['job_portal'];
			$remarks      = $fields['remarks'];

			//Jo Done
			$checkRowDbJoDone = DB::table('applicant_table')->select(DB::raw("(full_name) AS fullname"))->where('erf_number',$erf_number)->get()->toArray();
			$checkRowDbColumnJoDone = array_column($checkRowDbJoDone, 'fullname');

			if (in_array(strtolower(str_replace(' ', '', trim($first_name))).''.strtolower(str_replace(' ', '', trim($last_name))), $checkRowDbColumnJoDone)) {
				return CRUDBooster::redirect(CRUDBooster::mainpath("add-applicant"),"Applicant Already Exist in this ERF!","danger");
			}else{
				$postdata['status']      = $this->new_applicant;
				$postdata['erf_number']  = $erf_number;
				$postdata['screen_date'] = $screen_date;
				$postdata['first_name']  = $first_name;
				$postdata['last_name']   = $last_name;
				$postdata['job_portal']  = $job_portal;
				$postdata['remarks']     = $remarks;
				$postdata['full_name']   = strtolower(str_replace(' ', '', trim($postdata['first_name']))).''.strtolower(str_replace(' ', '', trim($postdata['last_name'])));
				$postdata['created_by']  = CRUDBooster::myId();

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
			$update_remarks = $fields['update_remarks'];
			//Jo Done
			$checkRowDbJoDone = DB::table('applicant_table')->select('*')->where('erf_number', $erf_number)->where('status', 31)->get()->count();

			if ($checkRowDbJoDone != 0 && $status == 36) {
				return CRUDBooster::redirect(CRUDBooster::mainpath(),"Invalid! Erf has done JO!","danger");
			}else{
				if($status == 36){
					ErfHeaderRequest::where('reference_number',$erf_number)
					->update([
						'status_id'		 => 31
					]);	
					$postdata['status']              = 31;
					$postdata['update_remarks']      = $update_remarks;
				}else{
					$postdata['status']              = $status;
					$postdata['update_remarks']      = $update_remarks;
				}

				if($status == 34){
					$postdata['first_interview'] = date('Y-m-d H:i:s');   
				}else if($status == 35){
					$postdata['final_interview'] = date('Y-m-d H:i:s'); 
				}else if($status == 36){
					$postdata['job_offer']       = date('Y-m-d H:i:s'); 
				}else if($status == 42){
					$postdata['for_comparison']  = date('Y-m-d H:i:s'); 
				}else if($status == 8){
					$postdata['cancelled']       = date('Y-m-d H:i:s'); 
				}else if($status == 5){
					$postdata['rejected']        = date('Y-m-d H:i:s'); 
				}
				
				$postdata['updated_by']  = CRUDBooster::myId();
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

		//customize index
		public function getIndex() {
			$this->cbLoader();
			 if(!CRUDBooster::isView() && $this->global_privilege == false) CRUDBooster::redirect(CRUDBooster::adminPath(),trans('crudbooster.denied_access'));
			 $data = [];
			 $data['page_title'] = 'Applicant';
			 $data['getData'] = Applicant::
			   leftjoin('statuses', 'applicant_table.status', '=', 'statuses.id')
			 ->leftjoin('cms_users as created', 'applicant_table.created_by', '=', 'created.id')
			 ->leftjoin('cms_users as updated_by', 'applicant_table.updated_by', '=', 'updated_by.id')
			 ->leftjoin('erf_header_request', 'applicant_table.erf_number', '=', 'erf_header_request.reference_number')
			 ->select(
				'applicant_table.*',
				'applicant_table.id as apid',
				'statuses.status_description as status_description',
				'created.name as created_name',
				'updated_by.name as updated_by',
				'erf_header_request.approved_hr_at'
				)
				->orderByRaw('FIELD( applicant_table.status, 47,34,35,31,42,8,5)')
				->get();
			$data['erf_number'] = DB::table('erf_header_request')->get();
			$data['statuses'] = Statuses::select(
				'statuses.*'
			  )
			  ->whereIn('id', [47,34,35,36,8,42,5])
			  ->get();
			  //dd($data['getData']);
			 return $this->view('applicant.applicant_index',$data);
		  }

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
				  ->whereIn('id', [5,34,35,36,8,42,47])
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

		public function applicantUploadView() {
			$data['page_title']= 'Applicant Upload';
			return view('applicant.applicant-import', $data)->render();
		}

		public function applicantUpload(Request $request) {
			$data = Request::all();	
			$file = $data['import_file'];
			$path_excel = $file->store('temp');
			$path = storage_path('app').'/'.$path_excel;

			try {
				Excel::import(new ApplicantUpload, $path);	
			    CRUDBooster::redirect(CRUDBooster::adminpath('applicant_module'), trans("Upload Successfully!"), 'success');
			} catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
				$failures = $e->failures();
				
				$error = [];
				foreach ($failures as $failure) {
					$line = $failure->row();
					foreach ($failure->errors() as $err) {
						$error[] = $err . " on line: " . $line; 
					}
				}
				
				$errors = collect($error)->unique()->toArray();
		
			}
			CRUDBooster::redirect(CRUDBooster::adminpath('applicant_module'), $errors[0], 'danger');
		}

		

		public function searchApplicant(Request $request){
			$fields = Request::all();
			$erf_number = $fields['erf_number'];
			$status = $fields['status'];
			$screen_date = $fields['screen_date'];
		
			$data = [];
            $filters = [];
			$data['page_title'] = 'Applicant Detail';
			$applicant = Applicant::orderby('applicant_table.id','desc');
				
			if($erf_number != '' && !is_null($erf_number)){
				$applicant->where('applicant_table.erf_number',$erf_number);
				$filters['filter_column[applicant_table.erf_number][type]'] = '=';
				$filters['filter_column[applicant_table.erf_number][value]'] = $erf_number;
			}
			if($status != '' && !is_null($status)){
				$applicant->where('applicant_table.status',$status);
				$filters['filter_column[applicant_table.status][type]'] = '=';
				$filters['filter_column[applicant_table.status][value]'] = $status;
			}
			if($screen_date != '' && !is_null($screen_date)){
				$applicant->whereDate('applicant_table.screen_date',$screen_date);
				$filters['filter_column[applicant_table.screen_date][type]'] = '=';
				$filters['filter_column[applicant_table.screen_date][value]'] = $screen_date;
			}
			$applicant->leftjoin('statuses', 'applicant_table.status', '=', 'statuses.id')
				->leftjoin('cms_users as created', 'applicant_table.created_by', '=', 'created.id')
			->select(
				'applicant_table.*',
				'applicant_table.id as apid',
				'statuses.status_description as status_description',
				'created.name as created_name'
				);
			$data['result'] = $applicant->get();
			$data['filters'] = $filters;

			return $this->view("applicant.applicant_search_view", $data);
		}

		public function applicantExport(Request $request){
			$fields = Request::all();
			$filename = $fields['filename'];
			return Excel::download(new ExportApplicantMultipleSheet($fields), $filename.'.xlsx');

		}

		function downloadApplicantTemplate() {
			//FIRST SHEET
			$arrHeader = [
				"erf_number"         => "erf_number",
				"status"             => "status",
				"first_name"         => "first_name",
				"last_name"          => "last_name",
				"screen_date"        => "screen_date",
				"Job Portal"         => "job_portal",
				"Remarks"            => "remarks"
			];
			$arrData = [
				"erf_number"         => "ERF-0000001",
				"status"             => "1ST Interviewed",
				"first_name"         => "John",
				"last_name"          => "Doe",
				"screen_date"        => "2023-01-01",
				"job_portal"         => "Indeed",
				"remarks"            => "Remarks"
			];
			//2ND SHEET
			// $statusHeader = [
			// 	"statuses"           => "statuses",
			// ];
			// $statusData = [
			// 	["statuses"          => "1st Interviewed"],
			// 	["statuses"          => "Final Interview"],
			// 	["statuses"          => "Job Offer"],
			// 	["statuses"          => "Cancelled"],
			// 	["statuses"          => "For Comparison"],
			// 	["statuses"          => "Rejected"]	
			// ];
			$spreadsheet = new Spreadsheet();
			//$spreadsheet->setActiveSheetIndex(0);
			$spreadsheet->getActiveSheet()->fromArray(array_values($arrHeader), null, 'A1');
			$spreadsheet->getActiveSheet()->fromArray($arrData, null, 'A2');
			$spreadsheet->getActiveSheet()->setTitle('applicant-template');
			//$spreadsheet->createSheet();

			// Add some data to the second sheet, resembling some different data types
			// $spreadsheet->setActiveSheetIndex(1);
			// $spreadsheet->getActiveSheet()->fromArray(array_values($statusHeader), null, 'A1');
			// $spreadsheet->getActiveSheet()->fromArray($statusData, null, 'A2');
			// $spreadsheet->getActiveSheet()->setTitle('Status');

			$filename = "applicant-template-".date('Y-m-d');
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
			header('Cache-Control: max-age=0');
			$writer = new Xlsx($spreadsheet);
			$writer->save('php://output');
		}

	}