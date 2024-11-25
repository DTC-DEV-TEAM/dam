<?php namespace App\Http\Controllers;

use Session;

use DB;
use Excel;
use CRUDBooster;
use App\Store;
use App\Channel;
use App\Users;
use App\Employees;
use App\ApprovalMatrix;
use App\Models\ErfHeaderRequest;
use App\HeaderRequest;
use App\Department;
use App\Imports\UserImport;
use App\Exports\ExportUsersList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\HeadingRowImport;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Mail\EmailResetPassword;
use Mail;
use Illuminate\Support\Str;

class AdminCmsUsersController extends \crocodicstudio\crudbooster\controllers\CBController {

	public function __construct() {
		// Register ENUM type
		DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
	}

	public function cbInit() {
		# START CONFIGURATION DO NOT REMOVE THIS LINE
		$this->limit				= "20";
		$this->orderby				= "id_cms_privileges,asc";
		$this->table				= 'cms_users';
		$this->primary_key			= 'id';
		$this->title_field			= "name";
		$this->button_action_style	= 'button_icon';	
		$this->button_import		= FALSE;	
		$this->button_export		= FALSE;	
		if(CRUDBooster::myPrivilegeId() == 4){ 
			$this->button_edit = false;
		}
		if(CRUDBooster::isSuperadmin()) {
		    $this->button_add = true;
		}else{
			$this->button_add = false;
		}
		# END CONFIGURATION DO NOT REMOVE THIS LINE
	
		# START COLUMNS DO NOT REMOVE THIS LINE
		$this->col = array();
		$this->col[] = array("label"=>"Name","name"=>"name");
		$this->col[] = array("label"=>"Email","name"=>"email");
		$this->col[] = array("label"=>"Privilege","name"=>"id_cms_privileges","join"=>"cms_privileges,name");
		$this->col[] = array("label"=>"Department","name"=>"department_id");
		//$this->col[] = array("label"=>"Store Name","name"=>"stores_id", "join"=>"stores,store_name");
		$this->col[] = array("label"=>"Photo","name"=>"photo","image"=>1);
		$this->col[] = array("label"=>"Status","name"=>"status");
		# END COLUMNS DO NOT REMOVE THIS LINE

		# START FORM DO NOT REMOVE THIS LINE
		$this->form = array();

		if(CRUDBooster::isSuperadmin() || CRUDBooster::myPrivilegeId() == 9 || CRUDBooster::myPrivilegeId() == 4 || CRUDBooster::myPrivilegeId() == 15) {
			$this->form[] = array("label"=>"Email","name"=>"email",'required'=>true,'type'=>'email','validation'=>'required|email|unique:cms_users,email,'.CRUDBooster::getCurrentId(), 'width'=>'col-sm-5');		
    		// $this->form[] = array("label"=>"Password","name"=>"password","type"=>"password","help"=>"Please leave empty if not changed", 'width'=>'col-sm-5');
			$this->form[] = [
				"label" => "Password",
				"name" => "password",
				"type" => "custom",
				'width'=>'col-sm-7',
				"help" => "Please leave empty if not changed",
				'html' => '<div class="form-group header-group-0" id="form-group-password" style="width:100%">
							<div class="col-md-9" style="display:flex;">
								<input type="password" name="password" class="form-control" id="password">
								<span class="password-toggle-icon" id="togglePassword" style="margin-top:10px; margin-right:10px">
									<i class="fa fa-eye"></i>
								</span>
							</div>
						  </div>'
			];
			$this->form[] = array("label"=>"Photo","name"=>"photo","type"=>"upload","help"=>"Recommended resolution is 200x200px",'validation'=>'image|max:1000','resize_width'=>90,'resize_height'=>90, 'width'=>'col-sm-5');
		    $this->form[] = array("label"=>"First Name","name"=>"first_name",'required'=>true,'validation'=>'required|min:2', 'width'=>'col-sm-5');
    		$this->form[] = array("label"=>"Last Name","name"=>"last_name",'required'=>true,'validation'=>'required|min:2', 'width'=>'col-sm-5');
    		$this->form[] = array("label"=>"Full Name","name"=>"name", "type"=>"hidden",'required'=>true,'validation'=>'required|min:3','width'=>'col-sm-5','readonly'=>true);
			$this->form[] = ["label"=>"Contact Person","name"=>"contact_person","type"=>"text","validation"=>"required|min:1|max:255",'width'=>'col-sm-5','placeholder'=>'Contact Person','readonly'=>true];
			$this->form[] = ["label"=>"Bill To (Company Name)","name"=>"bill_to","type"=>"text","validation"=>"required|min:1|max:255",'width'=>'col-sm-5','placeholder'=>'Bill To (Company Name)','readonly'=>true];
			$this->form[] = ["label"=>"Customer/Location Name","name"=>"customer_location_name","type"=>"text","validation"=>"required|min:1|max:255",'width'=>'col-sm-5','placeholder'=>'Customer/Location Name','readonly'=>true];
			$this->form[] = ['label'=>'Company Name','name'=>'company_name_id','validation'=>'required|min:0','width'=>'col-sm-5','value' => 'DIGITS','readonly'=>true];
			$this->form[] = ['label'=>'Department','name'=>'department_id','type'=>'select2-department','validation'=>'required','width'=>'col-sm-5','datatable'=>'departments,department_name','datatable_where'=>"status = 'ACTIVE'",'width'=>'col-sm-5'];
			$this->form[] = ['label'=>'Sub Department','name'=>'sub_department_id','type'=>'select2-sub-department','validation'=>'required','width'=>'col-sm-5','datatable'=>'sub_department,sub_department_name','parent_select'=>'department_id','width'=>'col-sm-5'];
			$this->form[] = ["label"=>"Position","name"=>"position_id","type"=>"text","validation"=>"required|min:1|max:255",'width'=>'col-sm-5','placeholder'=>'Position'];

		}else{
			$this->form[] = array("label"=>"Email","name"=>"email",'required'=>true,'type'=>'email','validation'=>'required|email|unique:cms_users,email,'.CRUDBooster::getCurrentId(), 'width'=>'col-sm-5', 'readonly'=>true);		
			$this->form[] = array("label"=>"Photo","name"=>"photo","type"=>"upload","help"=>"Recommended resolution is 200x200px",'validation'=>'image|max:1000','resize_width'=>90,'resize_height'=>90, 'width'=>'col-sm-5', 'readonly'=>true);
		    $this->form[] = array("label"=>"First Name","name"=>"first_name",'required'=>true,'validation'=>'required|min:3', 'width'=>'col-sm-5', 'readonly'=>true);
    		$this->form[] = array("label"=>"Last Name","name"=>"last_name",'required'=>true,'validation'=>'required|min:2', 'width'=>'col-sm-5', 'readonly'=>true);
			$this->form[] = array("label"=>"Role","name"=>"id_cms_privileges","type"=>"select","datatable"=>"cms_privileges,name", 'width'=>'col-sm-5', 'disabled'=>true);	
			if(!in_array(CRUDBooster::myPrivilegeId(),[11,12,14,15,24])){
				$this->form[] = array('label'=>'Approver','name'=>'approver_id','type'=>'select','datatable'=>'cms_users,name','datatable_where'=>"id_cms_privileges in (3,11,12,14,15,17,18,20,24)",'width'=>'col-sm-5', 'disabled'=>true);
			}
			// $this->form[] = array("label"=>"Password","name"=>"password","type"=>"password","help"=>"Please leave empty if not changed", 'width'=>'col-sm-5');
		
			// $this->form[] = [
			// 	"label" => "Password",
			// 	"name" => "password",
			// 	"type" => "custom",
			// 	'width'=>'col-sm-7',
			// 	"help" => "Please leave empty if not changed",
			// 	'html' => '<div class="form-group header-group-0" id="form-group-password" style="width:100%">
			// 				<div class="col-sm-9" style="display:flex;">
			// 					<input type="password" name="password" class="form-control" id="password">
			// 					<span class="password-toggle-icon" id="togglePassword" style="margin-top:10px; margin-right:10px">
			// 						<i class="fa fa-eye"></i>
			// 					</span>
			// 				</div>
			// 			  </div>'
			// ];
		}
		
		
		if((CRUDBooster::isSuperadmin() || CRUDBooster::myPrivilegeId() == 4 || CRUDBooster::myPrivilegeId() == 15) && (CRUDBooster::getCurrentMethod() == 'getEdit' || CRUDBooster::getCurrentMethod() == 'postEditSave')){
		    $this->form[] = array("label"=>"Status","name"=>"status","type"=>"select","dataenum"=>"ACTIVE;INACTIVE",'required'=>true, 'width'=>'col-sm-5');
		}
		
		if(CRUDBooster::myPrivilegeId() == 9){
			$this->form[] = array("label"=>"Privilege","name"=>"id_cms_privileges","required"=>true,"type"=>"select","datatable"=>"cms_privileges,name","datatable_where"=>"name LIKE '%REQUESTOR' || name LIKE '%OIC' || name LIKE '%AP CHECKER' || name LIKE '%TREASURY'", 'width'=>'col-sm-5');				
			// $this->form[] = array("label"=>"Channel","name"=>"channels_id","type"=>"select","datatable"=>"channels,channel_name", 'width'=>'col-sm-5');
			// $this->form[] = array("label"=>"Store Name","name"=>"stores_id","type"=>"check-box","datatable"=>"stores,store_name", 'width'=>'col-sm-10' );
			//$this->form[] = array("label"=>"Stores","name"=>"stores_id","type"=>"select","datatable"=>"stores,name_name", 'required'=>true,'width'=>'col-sm-5');				
		}elseif(CRUDBooster::myPrivilegeId() == 4 || CRUDBooster::myPrivilegeId() == 15){
			$this->form[] = array("label"=>"Privilege","name"=>"id_cms_privileges","required"=>true,"type"=>"select2","datatable"=>"cms_privileges,name", 'datatable_where'=>"id in (2,3,11,12)", 'width'=>'col-sm-5');	
			$this->form[] = array('label'=>'Approver','name'=>'approver_id','type'=>'select2','datatable'=>'cms_users,name','datatable_where'=>"id_cms_privileges in (3,11,12,14,15,17,18,20)",'width'=>'col-sm-5');
			$this->form[] = array('label'=>'Approver','name'=>'approver_id_manager','type'=>'select2','datatable'=>'cms_users,name','datatable_where'=>"id_cms_privileges in (11,12,14,15)",'width'=>'col-sm-5', 'class'=>'approver_one');
			$this->form[] = array("label"=>"Location","name"=>"location_id","type"=>"select2","required"=>true,"datatable"=>"locations,store_name", 'datatable_where'=>"store_status = 'ACTIVE'",'width'=>'col-sm-5');
			//$this->form[] = array("label"=>"ERF","name"=>"erf_id","type"=>"select2","required"=>true,"datatable"=>"erf_header_request,reference_number", 'datatable_where'=>"status_id = 32 && to_tag_employee = 1",'width'=>'col-sm-5');
			
		}elseif(CRUDBooster::isSuperadmin()) {

			$this->form[] = array("label"=>"Privilege","name"=>"id_cms_privileges","required"=>true,"type"=>"select2","datatable"=>"cms_privileges,name", 'width'=>'col-sm-5');	
			$this->form[] = array('label'=>'Approver','name'=>'approver_id','type'=>'select2','datatable'=>'cms_users,name','datatable_where'=>"id_cms_privileges in (3,11,12,14,15,16,17,18,20,24,26)",'width'=>'col-sm-5');
			$this->form[] = array('label'=>'Approver','name'=>'approver_id_manager','type'=>'select2','datatable'=>'cms_users,name','datatable_where'=>"id_cms_privileges in (11,12,14,15,24)",'width'=>'col-sm-5', 'class'=>'approver_one');
			//$this->form[] = array('label'=>'Approver','name'=>'approver_id_executive','type'=>'select2','datatable'=>'cms_users,name','datatable_where'=>"id_cms_privileges = '12'",'width'=>'col-sm-5');
			$this->form[] = array("label"=>"Location","name"=>"location_id","type"=>"select2","datatable"=>"locations,store_name", 'datatable_where'=>"store_status = 'ACTIVE'",'width'=>'col-sm-5');
			//$this->form[] = array("label"=>"Stores","name"=>"store_id","type"=>"check-box","datatable"=>"stores,bea_mo_store_name", 'datatable_where'=>"status = 'ACTIVE'", 'width'=>'col-sm-10' );
            $this->form[] = array("label"=>"Location to Pick","name"=>"location_to_pick","type"=>"check-box","required"=>true,"datatable"=>"warehouse_location_model,location", 'datatable_where'=>"id != '4'",'width'=>'col-sm-10' );
			//$this->form[] = array("label"=>"ERF","name"=>"erf_id","type"=>"select2","datatable"=>"erf_header_request,reference_number", 'datatable_where'=>"status_id = 32",'width'=>'col-sm-5');
	
		}

		
		# END FORM DO NOT REMOVE THIS LINE

		$this->button_selected = array();
        if(CRUDBooster::isUpdate() && (CRUDBooster::isSuperadmin() || CRUDBooster::myPrivilegeId() == 9 || CRUDBooster::myPrivilegeId() == 4 || CRUDBooster::myPrivilegeId() == 15))
        {
        	$this->button_selected[] = ['label'=>'Set Login Status OFFLINE ','icon'=>'fa fa-check-circle','name'=>'set_login_status_OFFLINE'];
        	$this->button_selected[] = ['label'=>'Set Status INACTIVE ','icon'=>'fa fa-check-circle','name'=>'set_status_INACTIVE'];
        	$this->button_selected[] = ['label'=>'Reset Password ','icon'=>'fa fa-check-circle','name'=>'reset_password'];
		}

		$this->table_row_color = array();     	          
	   // $this->table_row_color[] = ["condition"=>"[login_status_id] == 1","color"=>"success"];

		$this->table_row_color[] = ["condition"=>"[status] == INACTIVE","color"=>"danger"];
		
	    
	    $this->index_button = array();
        if(CRUDBooster::getCurrentMethod() == 'getIndex') {
			if(CRUDBooster::isSuperadmin()){
		         $this->index_button[] = ["label"=>"Export Lists","icon"=>"fa fa-files-o","url"=>CRUDBooster::mainpath('export'),"color"=>"primary"];
				$this->index_button[] = [
					"title"=>"Upload User Accounts",
					"label"=>"Upload User Accounts",
					"icon"=>"fa fa-download",
					"url"=>CRUDBooster::mainpath('user-account-upload')];
			}
			if(CRUDBooster::myPrivilegeId() == 4){
				$this->index_button[] = ["label"=>"Export Lists","icon"=>"fa fa-files-o","url"=>CRUDBooster::mainpath('export'),"color"=>"primary"];
			}
		}
		

		$this->load_js[] = asset("js/employee_master.js");
		// $this->load_js[] = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css';
		$this->style_css = NULL;
		$this->style_css = "
			.ui-datepicker-year, .ui-datepicker-month{
				color:	#337ab7 !important;
			}
			.password-toggle-icon {
			position: absolute;
			top: 50%;
			right: 10px;
			transform: translateY(-50%);
			cursor: pointer;
			}

			.password-toggle-icon i {
			font-size: 18px;
			line-height: 1;
			color: #333;
			transition: color 0.3s ease-in-out;
			margin-bottom: 20px;
			}

			.password-toggle-icon i:hover {
			color: #000;
			}
		";

		$this->script_js = "
		$(document).ready(function() {
		
			// $('#togglePassword').on('click', function() {
			// 	var password = $('#password');
			// 	var type = password.attr('type') === 'password' ? 'text' : 'password';
			// 	password.attr('type', type);
				
			// 	$(this).find('i').toggleClass('fa-eye fa-eye-slash');
			// });
			const passwordField = document.getElementById('password');
			const togglePassword = document.querySelector('.password-toggle-icon i');

			togglePassword.addEventListener('click', function () {
			if (passwordField.type === 'password') {
				passwordField.type = 'text';
				togglePassword.classList.remove('fa-eye');
				togglePassword.classList.add('fa-eye-slash');
			} else {
				passwordField.type = 'password';
				togglePassword.classList.remove('fa-eye-slash');
				togglePassword.classList.add('fa-eye');
			}
			});
			$('form').submit(function(){
				$('.btn.btn-success').attr('disabled', true);
				return true; 
            });

			$('.js-example-basic-multiple').select2();
			$('.js-example-basic-multiple').select2({theme: 'classic'});
			$('#department_id').select2();
			$('#sub_department_id').select2();
			// $('#id_cms_privileges').select2();

			let x = $(location).attr('pathname').split('/');
			let add_action = x.includes('add');
			let edit_action = x.includes('edit');
            
			if (add_action){
				$('#form-group-approver_id_manager').hide();
				$('#approver_id_manager').removeAttr('required');

				$('#form-group-approver_id_executive').hide();
				$('#approver_id_executive').removeAttr('required');

				$('#form-group-location_to_pick').hide();
				$('#location_to_pick').removeAttr('required');

				$('#form-group-erf_id').hide();
				$('#erf_id').removeAttr('required');

				$('#id_cms_privileges').change(function() {
					if($(this).val() == 1 || $(this).val() == 22){
						$('#form-group-approver_id_manager').hide();
						$('#approver_id_manager').removeAttr('required');

						$('#form-group-approver_id').hide();
				        $('#approver_id').removeAttr('required');

						$('#form-group-approver_id_executive').hide();
						$('#approver_id_executive').removeAttr('required');

						$('#form-group-location_id').hide();
						$('#location_id').removeAttr('required');

						$('#form-group-location_to_pick').hide();
				        $('#location_to_pick').removeAttr('required');
						
						$('#form-group-erf_id').hide();
				        $('#erf_id').removeAttr('required');

					}
					// else if($(this).val() == 2){
					// 	$('#form-group-erf_id').show();
					// 	//$('#erf_id').attr('required', 'required');

					// 	$('#form-group-approver_id_manager').hide();
					// 	$('#approver_id_manager').removeAttr('required');

					// 	$('#form-group-approver_id_executive').hide();
					// 	$('#approver_id_executive').removeAttr('required');

					// 	$('#form-group-location_to_pick').hide();
					// 	$('#location_to_pick').removeAttr('required');

					// }
					else if($(this).val() == 3 || $(this).val() == 18 || $(this).val() == 20 || $(this).val() == 26){
						$('#form-group-approver_id_manager').show();
						$('#approver_id_manager').attr('required', 'required');

						$('#form-group-approver_id').hide();
				        $('#approver_id').removeAttr('required');

						$('#form-group-approver_id_executive').hide();
						$('#approver_id_executive').removeAttr('required');

						$('#form-group-location_to_pick').hide();
				        $('#location_to_pick').removeAttr('required');

						$('#form-group-erf_id').hide();
				        $('#erf_id').removeAttr('required');

					}else if($(this).val() == 5 || $(this).val() == 9 || $(this).val() == 17){

						$('#form-group-location_to_pick').show();
						$('#location_to_pick').attr('required', 'required');

					}
					else if($(this).val() == 11 || $(this).val() == 15 || $(this).val() == 24){
						$('#form-group-approver_id_manager').hide();
						$('#approver_id_manager').removeAttr('required');

						$('#form-group-approver_id').hide();
				        $('#approver_id').removeAttr('required');

						$('#form-group-approver_id_executive').show();
						$('#approver_id_executive').attr('required', 'required');

						$('#form-group-location_to_pick').hide();
				        $('#location_to_pick').removeAttr('required');

						$('#form-group-erf_id').hide();
				        $('#erf_id').removeAttr('required');

					}else if($(this).val() == 12){
						$('#form-group-approver_id_manager').hide();
						$('#approver_id_manager').removeAttr('required');

						$('#form-group-approver_id').hide();
				        $('#approver_id').removeAttr('required');

						$('#form-group-approver_id_executive').hide();
						$('#approver_id_executive').removeAttr('required');

						$('#form-group-location_to_pick').hide();
				        $('#location_to_pick').removeAttr('required');

						$('#form-group-erf_id').hide();
				        $('#erf_id').removeAttr('required');

					}else if($(this).val() == 14){
						$('#form-group-approver_id_manager').hide();
						$('#approver_id_manager').removeAttr('required');

						$('#form-group-approver_id').hide();
				        $('#approver_id').removeAttr('required');

						$('#form-group-approver_id_executive').show();
						$('#approver_id_executive').attr('required', 'required');

						$('#form-group-location_to_pick').hide();
						$('#location_to_pick').removeAttr('required');

						$('#form-group-erf_id').hide();
				        $('#erf_id').removeAttr('required');
	
					}else{
						$('#form-group-approver_id').show();
				        $('#approver_id').attr('required', 'required');

						$('#form-group-approver_id_manager').hide();
						$('#approver_id_manager').removeAttr('required');

						$('#form-group-approver_id_executive').hide();
						$('#approver_id_executive').removeAttr('required');

						$('#form-group-location_id').show();
						$('#location_id').attr('required', 'required');

						$('#form-group-location_to_pick').hide();
				        $('#location_to_pick').removeAttr('required');

						$('#form-group-erf_id').hide();
				        $('#erf_id').removeAttr('required');
					}

				});

			}else if(edit_action){
				$('#form-group-approver_id_manager').hide();
				$('#approver_id_manager').removeAttr('required');

				$('#form-group-approver_id_executive').hide();
				$('#approver_id_executive').removeAttr('required');

				$('#form-group-erf_id').hide();
				$('#erf_id').removeAttr('required');

				var a = department_id.split(',').length;
				var b = department_id.split(',');
				var selectedValues = new Array();

				for (let i = 0; i < a; i++) {
					selectedValues[i] = b[i];
					$('#department_id').val(selectedValues);
				}

				$('#id_cms_privileges').change(function() {
					if($(this).val() == 1 || $(this).val() == 22){
						$('#form-group-approver_id_manager').hide();
						$('#approver_id_manager').removeAttr('required');

						$('#form-group-approver_id').hide();
				        $('#approver_id').removeAttr('required');

						$('#form-group-approver_id_executive').hide();
						$('#approver_id_executive').removeAttr('required');

						$('#form-group-location_id').hide();
						$('#location_id').removeAttr('required');

						$('#form-group-location_to_pick').hide();
						$('#location_to_pick').removeAttr('required');

						$('#form-group-erf_id').hide();
				        $('#erf_id').removeAttr('required');

					}
					// else if($(this).val() == 2){
					// 	$('#form-group-erf_id').show();
					// 	//$('#erf_id').attr('required', 'required');

					// 	$('#form-group-approver_id_manager').hide();
					// 	$('#approver_id_manager').removeAttr('required');

					// 	$('#form-group-approver_id_executive').hide();
					// 	$('#approver_id_executive').removeAttr('required');

					// 	$('#form-group-location_to_pick').hide();
					// 	$('#location_to_pick').removeAttr('required');

					// }
					else if($(this).val() == 3  || $(this).val() == 18 || $(this).val() == 20 || $(this).val() == 26){
						$('#form-group-approver_id_manager').show();
						$('#approver_id_manager').attr('required', 'required');

						$('#form-group-approver_id').hide();
				        $('#approver_id').removeAttr('required');

						$('#form-group-approver_id_executive').hide();
						$('#approver_id_executive').removeAttr('required');

						$('#form-group-location_to_pick').hide();
						$('#location_to_pick').removeAttr('required');

						$('#form-group-erf_id').hide();
				        $('#erf_id').removeAttr('required');

					}else if($(this).val() == 5 || $(this).val() == 9 || $(this).val() == 17){

						$('#form-group-location_to_pick').show();
						$('#location_to_pick').attr('required', 'required');

					}
		
					else if($(this).val() == 11 || $(this).val() == 15 || $(this).val() == 24){
						$('#form-group-approver_id_manager').hide();
						$('#approver_id_manager').removeAttr('required');

						$('#form-group-approver_id').hide();
				        $('#approver_id').removeAttr('required');

						$('#form-group-approver_id_executive').show();
						$('#approver_id_executive').attr('required', 'required');

						$('#form-group-location_to_pick').hide();
						$('#location_to_pick').removeAttr('required');

						$('#form-group-erf_id').hide();
				        $('#erf_id').removeAttr('required');

	
					}else if($(this).val() == 12){
						$('#form-group-approver_id_manager').hide();
						$('#approver_id_manager').removeAttr('required');

						$('#form-group-approver_id').hide();
				        $('#approver_id').removeAttr('required');

						$('#form-group-approver_id_executive').hide();
						$('#approver_id_executive').removeAttr('required');

						$('#form-group-location_to_pick').hide();
						$('#location_to_pick').removeAttr('required');

						$('#form-group-erf_id').hide();
				        $('#erf_id').removeAttr('required');

					}else if($(this).val() == 14){
						$('#form-group-approver_id_manager').hide();
						$('#approver_id_manager').removeAttr('required');

						$('#form-group-approver_id').hide();
				        $('#approver_id').removeAttr('required');

						$('#form-group-approver_id_executive').show();
						$('#approver_id_executive').attr('required', 'required');

						$('#form-group-location_to_pick').hide();
						$('#location_to_pick').removeAttr('required');

						$('#form-group-erf_id').hide();
				        $('#erf_id').removeAttr('required');

	
					}else{
						$('#form-group-approver_id').show();
				        $('#approver_id').attr('required', 'required');

						$('#form-group-approver_id_manager').hide();
						$('#approver_id_manager').removeAttr('required');

						$('#form-group-approver_id_executive').hide();
						$('#approver_id_executive').removeAttr('required');

						$('#form-group-location_id').show();
						$('#location_id').attr('required', 'required');

						$('#form-group-location_to_pick').hide();
						$('#location_to_pick').removeAttr('required');

						$('#form-group-erf_id').hide();
				        $('#erf_id').removeAttr('required');
					}

				});


				if($('#id_cms_privileges').val() == 1 || $('#id_cms_privileges').val() == 1){
					$('#form-group-approver_id_manager').hide();
					$('#approver_id_manager').removeAttr('required');

					$('#form-group-approver_id').hide();
					$('#approver_id').removeAttr('required');

					$('#form-group-approver_id_executive').hide();
					$('#approver_id_executive').removeAttr('required');

					$('#form-group-location_id').hide();
					$('#location_id').removeAttr('required');

					$('#form-group-location_to_pick').hide();
					$('#location_to_pick').removeAttr('required');

					$('#form-group-erf_id').hide();
				    $('#erf_id').removeAttr('required');

				}
				// if($('#id_cms_privileges').val() == 2){
				// 	$('#form-group-erf_id').show();

				// 	$('#form-group-approver_id_manager').hide();
				// 	$('#approver_id_manager').removeAttr('required');

				// 	$('#form-group-approver_id_executive').hide();
				// 	$('#approver_id_executive').removeAttr('required');

				// 	$('#form-group-location_to_pick').hide();
				// 	$('#location_to_pick').removeAttr('required');

				// }
				else if($('#id_cms_privileges').val() == 3 || $('#id_cms_privileges').val() == 18 || $('#id_cms_privileges').val() == 20 || $('#id_cms_privileges').val() == 26){
					$('#form-group-approver_id_manager').show();
					$('#approver_id_manager').attr('required', 'required');

					$('#form-group-approver_id').hide();
					$('#approver_id').removeAttr('required');

					$('#form-group-approver_id_executive').hide();
					$('#approver_id_executive').removeAttr('required');

					$('#form-group-location_to_pick').hide();
					$('#location_to_pick').removeAttr('required');

					$('#form-group-erf_id').hide();
				    $('#erf_id').removeAttr('required');

				}else if($('#id_cms_privileges').val() == 5 || $('#id_cms_privileges').val() == 9 || $('#id_cms_privileges').val() == 17){
					$('#form-group-location_to_pick').show();
					$('#location_to_pick').attr('required', 'required');

				}else if($('#id_cms_privileges').val() == 11 || $('#id_cms_privileges').val() == 15 || $('#id_cms_privileges').val() == 24){
					$('#form-group-approver_id_manager').hide();
					$('#approver_id_manager').removeAttr('required');

					$('#form-group-approver_id').hide();
					$('#approver_id').removeAttr('required');

					$('#form-group-approver_id_executive').show();
					$('#approver_id_executive').attr('required', 'required');

					$('#form-group-location_to_pick').hide();
					$('#location_to_pick').removeAttr('required');
					
					$('#form-group-erf_id').hide();
				    $('#erf_id').removeAttr('required');

				}else if($('#id_cms_privileges').val() == 12){
					$('#form-group-approver_id_manager').hide();
					$('#approver_id_manager').removeAttr('required');

					$('#form-group-approver_id').hide();
					$('#approver_id').removeAttr('required');

					$('#form-group-approver_id_executive').hide();
					$('#approver_id_executive').removeAttr('required');

					$('#form-group-location_to_pick').hide();
					$('#location_to_pick').removeAttr('required');

					$('#form-group-erf_id').hide();
				    $('#erf_id').removeAttr('required');

				}else if($('#id_cms_privileges').val() == 14){
					$('#form-group-approver_id_manager').hide();
					$('#approver_id_manager').removeAttr('required');

					$('#form-group-approver_id').hide();
					$('#approver_id').removeAttr('required');

					$('#form-group-approver_id_executive').show();
					$('#approver_id_executive').attr('required', 'required');

					$('#form-group-location_to_pick').hide();
					$('#location_to_pick').removeAttr('required');

					$('#form-group-erf_id').hide();
				    $('#erf_id').removeAttr('required');

				}else{
					$('#form-group-approver_id').show();
				    $('#approver_id').attr('required', 'required');

					$('#form-group-approver_id_manager').hide();
					$('#approver_id_manager').removeAttr('required');

					$('#form-group-approver_id_executive').hide();
					$('#approver_id_executive').removeAttr('required');

					$('#form-group-location_id').show();
					$('#location_id').attr('required', 'required');

					$('#form-group-location_to_pick').hide();
					$('#location_to_pick').removeAttr('required');

					$('#form-group-erf_id').hide();
				    $('#erf_id').removeAttr('required');

				}


			}

			$('input[name=\"submit\"]').click(function(){
				var strconfirm = confirm('Are you sure you want to save?');
				if (strconfirm == true) {
					return true;
				}else{
					return false;
					window.stop();
				}
			});
		});
		";	
	}

	public function getProfile() {			

		$this->button_addmore = FALSE;
		$this->button_cancel  = FALSE;
		$this->button_show    = FALSE;			
		$this->button_add     = FALSE;
		$this->button_delete  = FALSE;	
		$this->button_save  = FALSE;	
		// $this->hide_form 	  = ['id_cms_privileges'];


		$data['page_title'] = trans("crudbooster.label_button_profile");
		$data['row']        = CRUDBooster::first('cms_users',CRUDBooster::myId());
		$data['approver_id'] = $data['row']->approver_id;		
		return $this->view('crudbooster::default.form',$data);				
	}

	public function hook_row_index($column_index,&$column_value) {	        
		if($column_index == 5){
			$departmentLists = $this->departmentListing($column_value);
			
			foreach ($departmentLists as $value) {
				$col_values .= '<span stye="display: block;" class="label label-info">'.$value.'</span><br>';
			}
			$column_value = $col_values;
		}
	}

	public function hook_before_add(&$postdata) {        
	    //Your code here
		$postdata['created_by']=CRUDBooster::myId();
	    if($postdata['photo'] == '' || $postdata['photo'] == NULL) {
	    	$postdata['photo'] = 'uploads/mrs-avatar.png';
	    }
	
		$postdata['status'] = 'ACTIVE';
		$postdata['name'] = $postdata['first_name'].' '.$postdata['last_name'];
		$postdata['user_name'] = $postdata['last_name'].''.substr($postdata['first_name'], 0, 1);
		$postdata['bill_to'] = $postdata['last_name'].', '.$postdata['first_name'];
		
        if(in_array($postdata['id_cms_privileges'], [3,18,20,26])){
			$postdata['approver_id'] = $postdata['approver_id_manager'];
			$postdata['approver_id_manager'] = $postdata['approver_id_manager'];
			$postdata['approver_id_executive'] = NULL;
		}else if(in_array($postdata['id_cms_privileges'], [11,14,24])){
			$postdata['approver_id'] = $postdata['approver_id_executive'];
			$postdata['approver_id_manager'] = NULL;
			$postdata['approver_id_executive'] = $postdata['approver_id_executive'];
		}else{
			$postdata['approver_id'] = $postdata['approver_id'];
			$postdata['approver_id_manager'] = NULL;
			$postdata['approver_id_executive'] = NULL;
		}
	
		//LOCATION
		$locationToPickData1 = array();
		$locationToPick = json_encode($postdata['location_to_pick'], true);
		$locationToPickArray1 = explode(",", $locationToPick);

		foreach ($locationToPickArray1 as $key => $value) {
			$locationToPickData1[$key] = preg_replace("/[^0-9]/","",$value);
		}

		$postdata['location_to_pick'] = implode(",", $locationToPickData1);

        //DEPARTMENT
		$departmentIds = array();
		$department = json_encode($postdata['department_id'], true);
		$departmentArray1 = explode(",", $department);

		foreach ($departmentArray1 as $key => $value) {
			$departmentIds[$key] = preg_replace("/[^0-9]/","",$value);
		}

		$postdata['department_id'] = implode(",", $departmentIds);

		 //SUB DEPARTMENT
		 $subDepartmentIds = array();
		 $subDepartment = json_encode($postdata['sub_department_id'], true);
		 $subDepartmentArray1 = explode(",", $subDepartment);
 
		 foreach ($subDepartmentArray1 as $key => $subValue) {
			 $subDepartmentIds[$key] = preg_replace("/[^0-9]/","",$subValue);
		 }
 
		 $postdata['sub_department_id'] = implode(",", $subDepartmentIds);

	}

	public function hook_after_add($id) {        
		$details = Users::where(['created_by' => CRUDBooster::myId()])->orderBy('id','desc')->first();

		// $getArfids = ErfHeaderRequest::where(['id' => $details->erf_id])->first();

		// $arf_array = array();
		// array_push($arf_array, $getArfids->arf_id);
		// $arf_string = implode(",",$arf_array);
		// $finalArfs = array_map('intval',explode(",",$arf_string));

		// for ($i = 0; $i < count($finalArfs); $i++) {
		// 	HeaderRequest::where(['id' => $finalArfs[$i]])
		// 	   ->update([
		// 			   'employee_name' => $details->id, 
		// 			   'created_by' => $details->id,
		// 			   'to_update_id' => $details->id,
		// 			   ]);
		// }
		// ErfHeaderRequest::where('id',$details->erf_id)
		// ->update([
		// 	'to_tag_employee'            => NULL
		// ]);	
    }

	public function hook_before_edit(&$postdata,$id) {    
            $postdata['name'] = $postdata['first_name'].' '.$postdata['last_name'];
    		$postdata['user_name'] = $postdata['last_name'].''.substr($postdata['first_name'], 0, 1);
			if($postdata['status']){
				$postdata['status'] = $postdata['status'];
			}else{
				$postdata['status'] = 'ACTIVE';
			}
			$postdata['bill_to'] = $postdata['last_name'].', '.$postdata['first_name'];

			$currentApprover = DB::table('cms_users')->where('email',$postdata['email'])->first();

			if($postdata['approver_id'] || $postdata['approver_id_manager'] || $postdata['approver_id_executive']){
				if(in_array($postdata['id_cms_privileges'], [3,18,20,26])){
					$postdata['approver_id'] = $postdata['approver_id_manager'];
					$postdata['approver_id_manager'] = $postdata['approver_id_manager'];
					$postdata['approver_id_executive'] = NULL;
				}else if(in_array($postdata['id_cms_privileges'], [11,14,24])){
					$postdata['approver_id'] = $postdata['approver_id_executive'];
					$postdata['approver_id_manager'] = NULL;
					$postdata['approver_id_executive'] = $postdata['approver_id_executive'];
				}else{
					$postdata['approver_id'] = $postdata['approver_id'];
					$postdata['approver_id_manager'] = NULL;
					$postdata['approver_id_executive'] = NULL;
				}
			}else{
				$postdata['approver_id'] = $currentApprover->approver_id;
			}

			//LOCATION
			$locationToPickData1 = array();
    		$locationToPick = json_encode($postdata['location_to_pick'], true);
    		$locationToPickArray1 = explode(",", $locationToPick);
    
    		foreach ($locationToPickArray1 as $key => $value) {
    			$locationToPickData1[$key] = preg_replace("/[^0-9]/","",$value);
    		}
    
    		$postdata['location_to_pick'] = implode(",", $locationToPickData1);

			//DEPARTMENT
			$departmentIds = array();
			$department = json_encode($postdata['department_id'], true);
			$departmentArray1 = explode(",", $department);

			foreach ($departmentArray1 as $key => $value) {
				$departmentIds[$key] = preg_replace("/[^0-9]/","",$value);
			}

			$postdata['department_id'] = implode(",", $departmentIds);

			//SUB DEPARTMENT
			$subDepartmentIds = array();
			$subDepartment = json_encode($postdata['sub_department_id'], true);
			$subDepartmentArray1 = explode(",", $subDepartment);
	
			foreach ($subDepartmentArray1 as $key => $subValue) {
				$subDepartmentIds[$key] = preg_replace("/[^0-9]/","",$subValue);
			}
	
			$postdata['sub_department_id'] = implode(",", $subDepartmentIds);

    	    $postdata['updated_by']=CRUDBooster::myId();
    	    $postdata['id']=$id;
  
    }

	public function hook_after_edit($id) {
		$details = Users::where(['id' => $id])->orderBy('id','desc')->first();

		// $getArfids = ErfHeaderRequest::where(['id' => $details->erf_id])->first();
		// $getOldErfids = ErfHeaderRequest::where(['id' => $details->to_update_id])->first();
        // ErfHeaderRequest::where('id',$details->to_update_id)
		// ->update([
		// 	'to_tag_employee'            => 1
		// ]);	
		// HeaderRequest::where(['to_update_id' => $details->id])
		// 	   ->update([
		// 			   'employee_name' => $getOldErfids->reference_number, 
		// 			   'created_by' => NULL
		// 			   ]);

		// $arf_array = array();
		// array_push($arf_array, $getArfids->arf_id);
		// $arf_string = implode(",",$arf_array);
		// $finalArfs = array_map('intval',explode(",",$arf_string));

		// for ($i = 0; $i < count($finalArfs); $i++) {
		// 	HeaderRequest::where(['id' => $finalArfs[$i]])
		// 	   ->update([
		// 			   'employee_name' => $details->id, 
		// 			   'created_by' => $details->id,
		// 			   'to_update_id' => $details->id,
		// 			   ]);
		// }
		// ErfHeaderRequest::where('id',$details->erf_id)
		// ->update([
		// 	'to_tag_employee'            => NULL
		// ]);	
		// Users::where('id',$id)
		// ->update([
		// 	'to_update_id'            => $getArfids->id
		// ]);
		
	}
    

    public function hook_after_delete($id) {
		//Your code here
		DB::table('cms_users')->where('id', $id)->update(['status' => 'INACTIVE']);
	}

    public function hook_query_index(&$query) {
        //Your code here
        if(!CRUDBooster::isSuperadmin()) {
        	if(CRUDBooster::myPrivilegeId() == 4 || CRUDBooster::myPrivilegeId() == 15){
        		$query->where('cms_users.id_cms_privileges','!=','1');
        	}
        	else{
        		$query->where('cms_users.id',"'".CRUDBooster::myId()."'");
        	}
        }    
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
        if($button_name == 'set_login_status_OFFLINE') {
			DB::table('cms_users')->whereIn('id',$id_selected)->update(['login_status_id'=>'2']);	
		}
		if($button_name == 'set_status_INACTIVE') {
			DB::table('cms_users')->whereIn('id',$id_selected)->update(['status'=>'INACTIVE']);	
		}
		if($button_name == 'reset_password') {
			DB::beginTransaction();
		    DB::table('cms_users')->whereIn('id',$id_selected)->update([
		    	'password'			=> bcrypt('qwerty'),
		    	'reset_password'	=> 1	
		    ]);
		    DB::commit();	
		}  
    }

    public function showChangePasswordForm(){
    	if(CRUDBooster::myId()){
    		$array_data['data'] = "Reset Password";
    		return view('changepassword',$array_data);
    	}
        else{
        	return view('crudbooster::login');
        }
    }

    public function changePassword(Request $request){

 		$users = DB::table('cms_users')->where('id',CRUDBooster::myId())->first();

        if (!(\Hash::check($request->input('current-password'), $users->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Your current password does not matches with the password you provided. Please try again.");
        }
 
        if(strcmp($request->input('current-password'), $request->input('new-password')) == 0){
            //Current password and new password are same
            return redirect()->back()->with("error","Your new password cannot be same as your current password. Please choose a different password.");
        }

        /*
        $this->validate($request, [
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ]);
		*/
        
        \Validator::make($request->all(), [
		    'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
		])->validate();
 		
        //Change Password
        try{
	        DB::beginTransaction();
		    DB::table('cms_users')->where('id',CRUDBooster::myId())->update([
		    	'password'			=> bcrypt($request->input('new-password')),
		    	'reset_password'	=> 0	
		    ]);
		    DB::commit();
		   /* Transaction successful. */
	    }
	    catch (\Exception $error_msg){
	        $error_code = $error_msg->errorInfo[1];
	        DB::rollback();
	    }
 
        return redirect()->back()->with("success","Your password has been changed successfully !");
 
	}

	public function storeListing($ids) {
		$stores = explode(",", $ids);
		return Store::whereIn('id', $stores)->pluck('store_name');
	}

	public function userListing($ids) {
		$users = explode(",", $ids);
		return Users::whereIn('id', $users)->pluck('name');
	}

	public function departmentListing($ids) {
		$department = explode(",", $ids);
		return Department::whereIn('id', $department)->pluck('department_name');
	}
	
	public function uploadUserAccountTemplate() {
		// Excel::create('user-account-upload-'.date("Ymd").'-'.date("h.i.sa"), function ($excel) {
		// 	$excel->sheet('useraccount', function ($sheet) {
		// 		$sheet->row(1, array('FIRST NAME', 'LAST NAME', 'EMAIL', 'PRIVILEGE', 'CHANNEL', 'STORES ID'));
		// 		$sheet->row(2, array('John', 'Doe', 'johndoe@digits.ph','Requestor','Retail','1'));
		// 	});
		// })->download('csv');
		$filename = "user-account-upload".date("Ymd")."-".date("h.i.sa"). ".csv";
	
			header("Content-Disposition: attachment; filename=\"$filename\"");
			header("Content-Type: text/csv; charset=UTF-16LE");
	
			$out = fopen("php://output", 'w');
			$flag = false;

			if(!$flag) {
				// display field/column names as first row
				fputcsv($out, array('EMAIL', 'PRIVILEGE', 'FIRST NAME', 'LAST NAME', 'DEPARTMENT', 'SUB DEPARTMENT', 'POSITION', 'APPROVER', 'LOCATION'));
				$flag = true;
			}
			
			fputcsv($out, array('johndoe@digits.ph', 'Employee', 'John', 'Doe', 'BPG', 'BPG-SYSTEM', 'Associate Software Developer' , 'Mike Rodelas', 'DIGITS HEAD OFFICE'));
			fclose($out);
			
			exit;
	}

	public function uploadUserAccount() {
	    // if(!CRUDBooster::isSuperadmin()) {    
		// 	CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
		// }
		$data['page_title']= 'User Account Upload';
		return view('user-account.user_account_upload', $data)->render();
	}

	public function userAccountUpload(Request $request) {
		$path_excel = $request->file('import_file')->store('temp');
		$path = storage_path('app').'/'.$path_excel;
		$headings = array_filter((new HeadingRowImport)->toArray($path)[0][0]);

		if (count($headings) !== 9) {
			CRUDBooster::redirect(CRUDBooster::adminpath('users'), 'Template column not match, please refer to downloaded template.', 'danger');
		} else {
			$is_diff = array_diff([ "email", "privilege", "first_name","last_name",
			"department", "sub_department", "position", "approver", "location"], $headings);

			if (count($is_diff) > 0) {
				CRUDBooster::redirect(CRUDBooster::adminpath('users'), 'Invalid Column Field, please refer to downloaded template.', 'danger');
			} else {
				try {
					Excel::import(new UserImport, $path);	
					CRUDBooster::redirect(CRUDBooster::adminpath('users'), 'Import Successfully!', 'success');
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
				CRUDBooster::redirect(CRUDBooster::adminpath('users'), $errors[0], 'danger');
	       }
		}
	}

	//Get Customer view
	public function getDetail($id){
		$this->cbLoader();
		if(!CRUDBooster::isRead() && $this->global_privilege==FALSE) {    
			CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
		}
		$data = [];
		$data['page_title'] = 'View User Details';
		$data['users'] = Users::user($id);
		return $this->view("user-account.view_user", $data);
	}

	public function getExport(){
		return Excel::download(new ExportUsersList, 'DAM-UsersList.xlsx');
	}

	public function showChangeForcePasswordForm(){
		$data['page_title'] = 'Change Password';
		return view('user-account.change-force-password-form',$data);
	}

	public function postUpdatePassword(Request $request) {
		$fields = $request->all();
		$user = DB::table('cms_users')->where('id',$fields['user_id'])->first();
	
		if (Hash::check($fields['current_password'], $user->password)){
			//Check if password exist in history
			$passwordHistory = DB::table('cms_password_histories')->where('cms_user_id',$fields['user_id'])->get()->toArray();
			$isExist = array_column($passwordHistory, 'cms_user_old_pass');
			if(!self::checkPasswordInArray($fields['new_password'], $isExist)) {
				$validator = \Validator::make($request->all(), [
					'current_password' => 'required',
					'new_password' => 'required',
					'confirm_password' => 'required|same:new_password'
				]);
			
				if ($validator->fails()) {
					return redirect()->to('admin/statistic_builder/dashboard')
							->withErrors($validator)
							->withInput();
				}
				DB::table('cms_users')->where('id', $fields['user_id'])
				->update([
					'password'=>Hash::make($fields['new_password']),
					'last_password_updated' => Carbon::now()->format('Y-m-d'),
					'waiver_count' => 0
				]);
				$newPass = DB::table('cms_users')->where('id',$fields['user_id'])->first();
				Session::put('admin-password', $newPass->password);
				Session::put('check-user',false);
				//Save password history
				DB::table('cms_password_histories')->insert([
					'cms_user_id' => $newPass->id,
					'cms_user_old_pass' => $newPass->password,
					'created_at' => date('Y-m-d h:i:s')
				]);

				return response()->json(['message' => 'Password Updated, You Will Be Logged-Out.', 'status'=>'success']);
			}else{
				return response()->json(['message' => 'Password already used! Please try another password', 'status'=>'error']);
			}
		}else{
			return response()->json(['message' => 'Incorrect Current Password.', 'status'=>'error']);
		}
		
	}

	public function waiveChangePassword(Request $request){
		$user = DB::table('cms_users')->where('id',CRUDBooster::myId())->first();
		DB::table('cms_users')->where('id', CRUDBooster::myId())
			->update([
				'last_password_updated' => Carbon::now()->format('Y-m-d'),
				'waiver_count' => DB::raw('COALESCE(waiver_count, 0) + 1')
			]);
		Session::put('admin-password', $user->password);
		Session::put('check-user',false);
		return response()->json(['message' => 'Waive completed!', 'status'=>'success']);
	}

	public function checkPassword(Request $request) {
		$data = [];
		$fields = $request->all();
		$user = DB::table('cms_users')->where('id',$fields['id'])->first();
		if (Hash::check($fields['password'], $user->password)){
			$data['items'] = 1;
		}else{
			$data['items'] = 0;
		}
	
		return json_encode($data);
	}

	public function checkWaive(Request $request) {
		$data = [];
		$fields = $request->all();
		$user = DB::table('cms_users')->where('id',$fields['id'])->first();
		if ($user->waiver_count === 4){
			$data['items'] = 0;
		}else{
			$data['items'] = 1;
		}
	
		return json_encode($data);
	}

	// Function to check if the new password matches any hashed password
	function checkPasswordInArray($newPassword, $hashedPasswords) {
		foreach ($hashedPasswords as $hashedPassword) {
			if (Hash::check($newPassword, $hashedPassword)) {
				return true; // Password exists in the array
			}
		}
		return false; // Password does not exist
	}

	public function showChangePassword(){
		$data['page_title'] = 'Change Password';
		return view('user-account.change-password',$data);
	}

	//RESET PASSWORD
	public function postSendEmailResetPassword(Request $request){
		$key = Str::random(32);
        $iv = Str::random(16);
        
        $emailExist = DB::table('cms_users')->where('email',$request->email)->exists();
        if(!$emailExist){
			return redirect()->route('getForgot')->with('message', trans("passwords.user"), 'danger');
		}
        $encryptedEmail = openssl_encrypt($request->email, 'aes-256-cbc', $key, 0, $iv);
        $encryptedEmailBase64 = base64_encode($encryptedEmail);

        session(['encryption_key' => $key, 'encryption_iv' => $iv]);
       
        $cleanEncryptedEmail = str_replace('/', '_', $encryptedEmailBase64);

		Mail::to($request->email)
		->send(new EmailResetPassword($cleanEncryptedEmail));
		return redirect()->route('getLogin')->with('message', trans("passwords.sent"),'success');
	}

	public function getResetView($email){
		$data['page_title'] = 'Reset Password Form';
		$data['email'] = $email;
		return view('user-account.reset-password',$data);
    }

	public function postSaveResetPassword(Request $request){
		$key = session('encryption_key');
        $iv = session('encryption_iv');

        if (!$key || !$iv) {
            return json_encode(["message"=>"Request expired, please request another one", "status"=>"error", 'redirect'=>url('admin/login')]);
        }

        $encryptedEmail = base64_decode(str_replace('_', '/', $request->email));
        $decryptedEmail = openssl_decrypt($encryptedEmail, 'aes-256-cbc', $key, 0, $iv);
	
        if ($decryptedEmail === false) {
            return json_encode(["message"=>"Request expired, please request another one", "status"=>"error", 'redirect'=>url('admin/login')]);
        }
		//Check if password exist in history
		$user = DB::table('cms_users')->where('email',$decryptedEmail)->first();
		$passwordHistory = DB::table('cms_password_histories')->where('cms_user_id',$user->id)->get()->toArray();
		$isExist = array_column($passwordHistory, 'cms_user_old_pass');

		if(!self::checkPasswordInArray($request->get('new_password'), $isExist)) {
			$user = Users::where('email', $decryptedEmail)->first();
			$request->validate([
				'new_password' => 'required',
				'confirm_password' => 'required|same:new_password'
			]);

			$user->waiver_count = 0;
			$user->	last_password_updated = now();
			$user->password = Hash::make($request->get('new_password'));
			$user->save();

			DB::table('cms_password_histories')->insert([
				'cms_user_id' => $user->id,
				'cms_user_old_pass' => $user->password,
				'created_at' => date('Y-m-d h:i:s')
			]);

			session()->forget('encryption_key');
			session()->forget('encryption_iv');
			return json_encode(["message"=>"Password successfully reset, you will be redirect to login!", "status"=>"success", 'redirect'=>url('admin/login')]);
		}else{
			return json_encode(["message"=>"Password not available, please try another one!"]);
		}
	}
	
}
