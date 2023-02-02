@extends('crudbooster::admin_template')
    @push('head')
        <style type="text/css">   

            .select2-selection__choice{
                    font-size:14px !important;
                    color:black !important;
            }
            .select2-selection__rendered {
                line-height: 31px !important;
            }
            .select2-container .select2-selection--single {
                height: 35px !important;
            }
            .select2-selection__arrow {
                height: 34px !important;
            }
            .firstRow {
                border: 1px solid rgba(39, 38, 38, 0.5);
                padding: 10px;
                margin-left: 10px;
                border-radius: 3px;
                opacity: 2;
            }

            .firstRow {
                padding: 10px;
                margin-left: 10px;
            }

            .finput {
                border:none;
                border-bottom: 1px solid rgba(18, 17, 17, 0.5);
            }

            input.finput:read-only {
                background-color: #fff;
            }

            input.sinput:read-only {
                background-color: #fff;
            }

            input.addinput:read-only {
                background-color: #f5f5f5;
            }

            .input-group-addon {
                background-color: #f5f5f5 !important;
            }

           
        </style>
    @endpush
@section('content')
@if(g('return_url'))
	<p class="noprint"><a title='Return' href='{{g("return_url")}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@else
	<p class="noprint"><a title='Main Module' href='{{CRUDBooster::mainpath()}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@endif

<div class='panel panel-default'>
    <div class='panel-heading'>
        Asset Form
    </div>

    <form action="{{ CRUDBooster::mainpath('add-save') }}" method="POST" id="ERFRequest" enctype="multipart/form-data">
        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
        <input type="hidden" value="1" name="request_type_id" id="request_type_id">

        <div class='panel-body'>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label require"> COMPANY</label>
                        <input type="text" class="form-control finput"  id="company" name="company" value="Digits Trading Corp."  required readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label"><span style="color:red">*</span> REQUESTED DATE</label>
                        <input class="form-control finput date" type="text" placeholder="Select Requested Date" name="requested_date" id="requested_date">
                    </div>
                </div>

                
            </div>


            <div class="row">
                <div class="col-md-6">
                <div class="form-group">
                        <label class="control-label"><span style="color:red">*</span> DEPARTMENT</label>
                            <select required selected data-placeholder="-- Please Select Department --" id="department" name="department" class="form-select select2" style="width:100%;">
                                @foreach($departments as $res)
                                    <option value=""></option>
                                    <option value="{{ $res->id }}">{{ $res->department_name }}</option>
                                @endforeach
                            </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label"><span style="color:red">*</span> DATE NEEDED</label>
                        <input class="form-control finput date" type="text" placeholder="Select Needed Date" name="date_needed" id="date_needed">
                    </div>
                </div>
               
            </div>
            <div class="row"> 
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label"><span style="color:red">*</span> POSITION</label>
                        <input type="text" class="form-control finput"  id="position" name="position"  required>                                   
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label"><span style="color:red">*</span> WORK LOCATION</label>
                        <input type="text" class="form-control finput"  id="work_location" name="work_location"  required>                                   
                    </div>
                </div>
                
            </div>
            <div class="row"> 
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label"><span style="color:red">*</span> SALARY RANGE</label>
                        <input type="text" class="form-control finput"  id="salary_range" name="salary_range"  required>                                   
                    </div>
                </div>
                
            </div>
            <hr/>
            <div class="row"> 
                <div class="col-md-6">
                    <label class="require control-label"><span style="color:red">*</span> SCHEDULE</label><br>
                        @foreach($schedule as $data)
                        <div class="col-md-6">
                            <label class="checkbox-inline control-label col-md-6" >
                            <input type="checkbox" required  class="schedule" name="schedule" id="schedule" value="{{$data->schedule_description}}" >{{$data->schedule_description}}
                            </label>
                        </div>
                        @endforeach
                </div>
                <div class="col-md-6">
                    <label class="require control-label"><span style="color:red">*</span> ALLOW WFH</label><br>
                        @foreach($allow_wfh as $data)
                        <div class="col-md-6">
                            <label class="checkbox-inline control-label col-md-8" ><br>
                            <input type="checkbox" required   class="allow_wfh" name="allow_wfh" value="{{$data->description}}" >{{$data->description}}
                            </label>
                        </div>
                        @endforeach
                </div>
            </div>
            <br>
            <div class="row"> 
                <div class="col-md-6">
                    <label class="require control-label"><span style="color:red">*</span> MANPOWER</label><br>
                        @foreach($manpower as $data)
                        <div class="col-md-6">
                            <label class="checkbox-inline control-label col-md-6" ><br>
                             <input type="checkbox" required   class="manpower" name="manpower" value="{{$data->description}}" >{{$data->description}}
                            </label>
                        </div>
                        @endforeach
                </div>
                <div class="col-md-6">
                    <label class="require control-label"><span style="color:red">*</span> MANPOWER TYPE</label><br>
                        @foreach($manpower_type as $data)
                        <div class="col-md-6">
                            <label class="checkbox-inline control-label col-md-6" ><br>
                            <input type="checkbox" required   class="manpower_type" name="manpower_type" value="{{$data->description}}" >{{$data->description}}
                            </label>
                        </div>
                        @endforeach
                </div>
            </div>
            <br>
            <div class="row"> 
                <div class="col-md-6">
                    <label class="require control-label"><span style="color:red">*</span> REQUIRED EXAM</label><br>
                        @foreach($required_exams as $data)
                        <div class="col-md-6">
                            <label class="checkbox-inline control-label col-md-12" ><br>
                             <input type="checkbox" required   class="required_exams" name="required_exams[]" value="{{$data->description}}" >{{$data->description}}
                            </label>
                        </div>
                        @endforeach
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label> QUALIFICATIONS</label>
                        <textarea placeholder=" Qualifications ..." rows="3" class="form-control finput" name="qualifications"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label> JOB DESCRIPTIONS</label>
                        <textarea placeholder=" Job Descriptions ..." rows="3" class="form-control finput" name="job_descriptions"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label"><span style="color:red">*</span> Please attach the following documents:</label>
                        <p>Job Description <br>
                        Specialty Test / Additional Tests with Answer Key (Optional)
                        </p> 
                        <input type="file" class="form-control finput" style="" name="documents[]" id="documents" multiple accept=".xlsx, .pdf">
                        <div class="gallery" style="margin-bottom:5px; margin-top:15px"></div>
                        <a class="btn btn-xs btn-danger" style="display:none; margin-left:10px" id="removeImageHeader" href="#"><i class="fa fa-remove"></i></a>
                    </div>
                </div> 
            </div>
        
            <hr>

            <div class="row">
                <div class="col-md-12">
                    <div class="box-header text-center">
                        <h3 class="box-title"><b> REQUIRED ASSETS</b></h3>
                    </div>
                                <div class="box-body no-padding">
                                    <div class="table-responsive">
                                        <div class="pic-container">
                                            <div class="pic-row">
                                                <table class="table table-bordered" id="asset-items">
                                                    <tbody id="bodyTable">
                                                        <tr class="tbl_header_color dynamicRows">
                                                            <th width="30%" class="text-center">{{ trans('message.table.item_description') }}</th>
                                                            <th width="25%" class="text-center">{{ trans('message.table.category_id_text') }}</th>                                                         
                                                            <th width="20%" class="text-center">{{ trans('message.table.application_id_text') }}</th> 
                                                            <th width="7%" class="text-center">{{ trans('message.table.quantity_text') }}</th> 
                                                           <!-- <th width="8%" class="text-center">{{ trans('message.table.image') }}</th>  -->
                                                            <th width="5%" class="text-center">{{ trans('message.table.action') }}</th>
                                                        </tr>
                                                        
                                                        <tr id="tr-table">
                                                            <tr>
                                            
                                                            </tr>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr id="tr-table1" class="bottom">
            
                                                            <td colspan="3">
                                                                <input type="button" id="add-Row" name="add-Row" class="btn btn-primary add" value='Add Item' />
                                                            </td>
                                                            <td align="left" colspan="1">
                                                                <input type='number' name="quantity_total" class="form-control text-center" id="quantity_total" readonly>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                
                                    </div>
                                    <br>
                                </div>
                </div>

                <div class="col-md-12" id="application_div">
                    <hr/>
                    <div class="row"> 
                        <label class="require control-label col-md-2" required>*{{ trans('message.form-label.application') }}</label>
                            @foreach($applications as $data)
                                <div class="col-md-2">
                                    <label class="checkbox-inline control-label col-md-12"><input type="checkbox"  class="application" id="{{$data->app_name}}" name="application[]" value="{{$data->app_name}}" >{{$data->app_name}}</label>
                                    <br>
                                </div>
                            @endforeach
                    </div>
                    <hr/>
                </div>

                <div class="col-md-12" id="application_others_div">
                    <div class="row">
                        <label class="require control-label col-md-2">*{{ trans('message.form-label.application_others') }}</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control"  id="application_others" name="application_others"  required placeholder="e.g. VIBER, WHATSAPP, TELEGRAM" onkeyup="this.value = this.value.toUpperCase();">
                        </div>
                    </div>
                    <hr/>
                </div>
                <br>  
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label class="require control-label"><span style="color:red">*</span> Does the Employee need to shared files?</label><br>
                    @foreach($shared_files as $data)
                    <div class="col-md-6">
                        <label class="checkbox-inline control-label col-md-6" ><br>
                            <input type="checkbox" required   class="shared_files" name="shared_files" value="{{$data->description}}" >{{$data->description}}
                        </label>
                    </div>
                    @endforeach
                </div>
           
       
                <div class="col-md-6">
                    <label class="require control-label"><span style="color:red">*</span> Who will the employee interact with?</label><br>
                    @foreach($interact_with as $data)
                    <div class="col-md-4">
                        <label class="checkbox-inline control-label col-md-12" ><br>
                            <input type="checkbox" required   class="employee_interaction" name="employee_interaction[]" value="{{$data->description}}" >{{$data->description}}
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <label class="require control-label"><span style="color:red">*</span> What will you be using the PC for?</label><br>
                    @foreach($asset_usage as $data)
                    <div class="col-md-6">
                        <label class="checkbox-inline control-label col-md-12" ><br>
                            <input type="checkbox" required   class="asset_usage" name="asset_usage[]" value="{{$data->description}}" >{{$data->description}}
                        </label>
                    </div>
                    @endforeach
                </div>
           
       
                <div class="col-md-6">
                    <label class="require control-label"><span style="color:red">*</span> Email Domain</label><br>
                    @foreach($email_domain as $data)
                    <div class="col-md-6">
                        <label class="checkbox-inline control-label col-md-6" ><br>
                            <input type="checkbox" required multiple class="email_domain" name="email_domain" value="{{$data->description}}" >{{$data->description}}
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
                
        </div>
        <div class='panel-footer'>
            <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
            <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> {{ trans('message.form.save') }}</button>
        </div>
    </form>
</div>

@endsection
@push('bottom')
<script type="text/javascript">

    function preventBack() {
        window.history.forward();
    }
    window.onunload = function() {
        null;
    };
    //preview image before save
    $(function() {
    // Multiple images preview in browser
    var imagesPreview = function(input, placeToInsertImagePreview) {

        if (input.files) {
            var filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function(event) {
                    $($.parseHTML('<img height="120px" class="header_images" width="180px;" hspace="10" data-action="zoom">')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                }

                reader.readAsDataURL(input.files[i]);
            }
        }

    };

        $('#si_dr').on('change', function() {
            imagesPreview(this, 'div.gallery');
            $("#removeImageHeader").toggle(); 
        });
    });
    setTimeout("preventBack()", 0);
    $('#department').select2({
                placeholder_text_single : "- Select Sub Category -"});
    var tableRow = 1;

    $("#application_div").hide();
    $("#application_others_div").hide();

    $("#application_others").removeAttr('required');
    $(".application").removeAttr('required');
    
    $(".date").datetimepicker({
                    viewMode: "days",
                    format: "YYYY-MM-DD",
                    dayViewHeaderFormat: "MMMM YYYY",
            });

    $('#OTHERS').change(function() {

        var ischecked= $(this).is(':checked');

        if(ischecked == false){
            $("#application_others_div").hide();
            $("#application_others").removeAttr('required');
        }else{
            $("#application_others_div").show();
            $("#application_others").attr('required', 'required');
        }	

    });

    var app_count = 0;

    $('.application').change(function() {
        var ischecked= $(this).is(':checked');
        if(ischecked == false){
            app_count--;
        }else{
            app_count++;
        }

    });

    //checkbox validations
    $("input[name^='schedule'], input[name^='allow_wfh'], input[name^='manpower'], input[name^='manpower_type'], input[name^='shared_files'], input[name^='email_domain']").on('click', function() {
        var $box = $(this);
        if ($box.is(':checked')) {
            var group = "input:checkbox[name='" + $box.attr("name") + "']";
            $(group).prop("checked", false);
            $box.prop('checked', true);
        } else {
            $box.prop('checked', false);
        }
    });


    $(document).ready(function() {

        const fruits = [];

        $("#add-Row").click(function() {

            var description = "";
            var count_fail = 0;

            $('.itemDesc').each(function() {
                description = $(this).val();
                if (description == null) {

                    alert("Please fill Item Description !");
                    count_fail++;

                } else if (description == "") {

                    alert("Please fill Item Description !");
                    count_fail++;

                }else{
                    count_fail = 0;
                }
            });
            
            tableRow++;

            if(count_fail == 0){

                var newrow =
                '<tr>' +

                    '<td >' +
                    '  <input type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control finput itemDesc" data-id="' + tableRow + '" id="itemDesc' + tableRow + '"  name="item_description[]"  required maxlength="100">' +
                    '</td>' +  

                    '<td>'+
                        '<select class="form-control drop'+ tableRow + '" name="category_id[]" data-id="' + tableRow + '" id="category_id' + tableRow + '" required>' +
                        //'  <option value="">- Select Category -</option>' +
                        '        @foreach($categories as $data)'+
                        '        <option value="{{$data->category_description}}">{{$data->category_description}}</option>'+
                        '         @endforeach'+
                        '</select>'+
                    '</td>' +


                    '<td>'+
                        '<select selected data-placeholder="- Select Sub Category -" class="form-control sub_category_id" name="sub_category_id[]" data-id="' + tableRow + '" id="sub_category_id' + tableRow + '" required style="width:100%">' +
                        '  <option value=""></option>' +
                        '        @foreach($sub_categories as $data)'+
                        '        <option value="{{$data->class_description}}">{{$data->class_description}}</option>'+
                        '         @endforeach'+
                        '</select>'+
                    '</td>' +

                    '<td><input class="form-control text-center quantity_item" type="number" required name="quantity[]" id="quantity' + tableRow + '" data-id="' + tableRow  + '"  value="1" min="0" max="9999999999" step="any" onKeyPress="if(this.value.length==4) return false;" oninput="validity.valid;"></td>' +
  
                    '<td>' +
                        '<button id="deleteRow" name="removeRow" data-id="' + tableRow + '" class="btn btn-danger removeRow"><i class="glyphicon glyphicon-trash"></i></button>' +
                    '</td>' +

                '</tr>';
                $(newrow).insertBefore($('table tr#tr-table1:last'));

                $('.js-example-basic-multiple').select2();
                $('.sub_category_id').select2({
                placeholder_text_single : "- Select Sub Category -"});
                $('#app_id'+tableRow).change(function(){

                        if($('#app_id'+$(this).attr("data-id")).val() != null){
                            var arrx = $(this).val();
                            execute = 0;
                        }else{
                            var arrx = "";
                            execute++;
                        }
                        var s = arrx;

                        if(s.includes("OTHERS")){
                
                            $('#AppOthers'+$(this).attr("data-id")).show();
                            $('#AppOthers'+$(this).attr("data-id")).attr('required', 'required');

                        }else{

                            $('#AppOthers'+$(this).attr("data-id")).hide();
                            $('#AppOthers'+$(this).attr("data-id")).removeAttr('required');

                        }

                });

                $(document).on('keyup', '#itemDesc'+tableRow, function(ev) {

                    var category =  $('#category_id'+$(this).attr("data-id")).val();
                    var description = this.value;

                    if(description.includes("LAPTOP") && category == "IT ASSETS"){
                    
                        // alert(description);

                        $('#app_id'+$(this).attr("data-id")).attr('disabled', false);

                    }else{
                    
                        $('#app_id'+$(this).attr("data-id")).attr('disabled', true);
                    }


                });


                $('#AppOthers'+tableRow).hide();
                $('#AppOthers'+tableRow).removeAttr('required');

                $('.sub_category_id').change(function(){

                    var sub_category_id =  this.value;
                    var fruits = [];
                    $(".sub_category_id :selected").each(function() {
                        fruits.push(this.value.toLowerCase().replace(/\s/g, ''));
                    });
                    console.log(fruits);
                    if( fruits.includes("laptop") || fruits.includes("desktop")){

                        $("#application_div").show();
                    }else{

                        $("#application_div").hide();
                        $("#application_others_div").hide();

                        $("#application_others").removeAttr('required');
                        //$(".application").removeAttr('required');

                    }

                });

                $("#quantity_total").val(calculateTotalQuantity());
                
            }

        });
        
        //deleteRow
        $(document).on('click', '.removeRow', function() {
        
            var id_data = $(this).attr("data-id");

            if($("#sub_category_id"+id_data).val().toLowerCase().replace(/\s/g, '') == "laptop" || $("#sub_category_id"+id_data).val().toLowerCase().replace(/\s/g, '') == "desktop"){

                    $("#application_div").hide();
                    $("#application_div").val("");
                    $("#application_others_div").hide();
                    $("#application_others").removeAttr('required');

            }

            if ($('#asset-items tbody tr').length != 1) { //check if not the first row then delete the other rows
                tableRow--;

                $(this).closest('tr').remove();

                $("#quantity_total").val(calculateTotalQuantity());

                return false;
            }
        });

    });


    $(document).on('keyup', '.quantity_item', function(ev) {

        $("#quantity_total").val(calculateTotalQuantity());

    });

    $(document).on('keyup', '.cost_item', function(ev) {
            var id = $(this).attr("data-id");
            var rate = parseFloat($(this).val());

            var qty = $("#quantity" + id).val();
            var price = calculatePrice(qty, rate).toFixed(2); // this is for total Value in row

            $("#total_unit_cost" + id).val(price);
            $("#quantity_total").val(calculateTotalQuantity());
            $("#cost_total").val(calculateTotalValue());
            $("#total").val(calculateTotalValue2());

            var total_checker = $("#total").val();

    });

    function calculatePrice(qty, rate) {
        if (qty != 0) {
        var price = (qty * rate);
        return price;
        } else {
        return '0';
        }
    }

    function calculateTotalQuantity() {
        var totalQuantity = 0;
        $('.quantity_item').each(function() {

        totalQuantity += parseInt($(this).val());
        });
        return totalQuantity;
    }

    function calculateTotalValue() {
        var totalQuantity = 0;
        var newTotal = 0;
        $('.cost_item').each(function() {
        totalQuantity += parseFloat($(this).val());

        });
        newTotal = totalQuantity.toFixed(2);
        return newTotal;
    }

    function calculateTotalValue2() {
        var totalQuantity = 0;
        var newTotal = 0;
        $('.total_cost_item').each(function() {
        totalQuantity += parseFloat($(this).val());

        });
        newTotal = totalQuantity.toFixed(2);
        return newTotal;
    }

    $(document).ready(function() {
        $("#ERFRequest").submit(function() {
            $("#btnSubmit").attr("disabled", true);
            return true;
        });
    });

    $("#btnSubmit").click(function(event) {
    
        event.preventDefault();
        var countRow = $('#asset-items tfoot tr').length;
        var reg = /^0/gi;
            // var value = $('.vvalue').val();
            if($("#requested_date").val() === ""){
                swal({
                    type: 'error',
                    title: 'Please select Requested Date!',
                    icon: 'error',
                    confirmButtonColor: "#367fa9",
                }); 
                event.preventDefault(); // cancel default behavior
                return false;
            }else if($("#department").val() === ""){
                swal({
                    type: 'error',
                    title: 'Please select Department!',
                    icon: 'error',
                    confirmButtonColor: "#367fa9",
                }); 
                event.preventDefault(); // cancel default behavior
                return false;
            }else if($("#date_needed").val() === ""){
                swal({
                    type: 'error',
                    title: 'Please select Needed Date!',
                    icon: 'error',
                    confirmButtonColor: "#367fa9",
                }); 
                event.preventDefault(); // cancel default behavior
                return false;
            }else if($("#position").val() === ""){
                swal({
                    type: 'error',
                    title: 'Required Position!',
                    icon: 'error',
                    confirmButtonColor: "#367fa9",
                }); 
                event.preventDefault(); // cancel default behavior
                return false;
            }else if($("#work_location").val() === ""){
                swal({
                    type: 'error',
                    title: 'Required Work Location!',
                    icon: 'error',
                    confirmButtonColor: "#367fa9",
                }); 
                event.preventDefault(); // cancel default behavior
                return false;
            }else if($("#salary_range").val() === ""){
                swal({
                    type: 'error',
                    title: 'Required Salary Range!',
                    icon: 'error',
                    confirmButtonColor: "#367fa9",
                }); 
                event.preventDefault(); // cancel default behavior
                return false;
            }else if(!$(".schedule").is(':checked')){
                    swal({
                        type: 'error',
                        title: 'Please choose Schedule!',
                        icon: 'error',
                        confirmButtonColor: "#367fa9",
                    }); 
                    event.preventDefault(); // cancel default behavior
                    return false;
            }else if(!$(".allow_wfh").is(':checked')){
                    swal({
                        type: 'error',
                        title: 'Please choose Allow Wfh!',
                        icon: 'error',
                        confirmButtonColor: "#367fa9",
                    }); 
                    event.preventDefault(); // cancel default behavior
                    return false;
            }else if(!$(".manpower").is(':checked')){
                    swal({
                        type: 'error',
                        title: 'Please choose Manpower!',
                        icon: 'error',
                        confirmButtonColor: "#367fa9",
                    }); 
                    event.preventDefault(); // cancel default behavior
                    return false;
            }else if(!$(".manpower_type").is(':checked')){
                    swal({
                        type: 'error',
                        title: 'Please choose Manpower Type!',
                        icon: 'error',
                        confirmButtonColor: "#367fa9",
                    }); 
                    event.preventDefault(); // cancel default behavior
                    return false;
            }else if(!$(".required_exams").is(':checked')){
                    swal({
                        type: 'error',
                        title: 'Please choose Required Exams!',
                        icon: 'error',
                        confirmButtonColor: "#367fa9",
                    }); 
                    event.preventDefault(); // cancel default behavior
                    return false;
            }else if(!$(".shared_files").is(':checked')){
                    swal({
                        type: 'error',
                        title: 'Please Shared Files!',
                        icon: 'error',
                        confirmButtonColor: "#367fa9",
                    }); 
                    event.preventDefault(); // cancel default behavior
                    return false;
            }else if(!$(".employee_interaction").is(':checked')){
                    swal({
                        type: 'error',
                        title: 'Please interact with!',
                        icon: 'error',
                        confirmButtonColor: "#367fa9",
                    }); 
                    event.preventDefault(); // cancel default behavior
                    return false;
            }else if(!$(".asset_usage").is(':checked')){
                    swal({
                        type: 'error',
                        title: 'Please choose what will you be using the PC for!',
                        icon: 'error',
                        confirmButtonColor: "#367fa9",
                    }); 
                    event.preventDefault(); // cancel default behavior
                    return false;
            }else if(!$(".email_domain").is(':checked')){
                    swal({
                        type: 'error',
                        title: 'Please choose Email Domain!',
                        icon: 'error',
                        confirmButtonColor: "#367fa9",
                    }); 
                    event.preventDefault(); // cancel default behavior
                    return false;
            }else if($('#documents').val() === ""){
                swal({
                    type: 'error',
                    title: 'Upload file documents required!',
                    icon: 'error',
                    customClass: 'swal-wide'
                });
                event.preventDefault();
            }else if (countRow == 1) {
            swal({
                type: 'error',
                title: 'Please add an item!',
                icon: 'error',
                confirmButtonColor: "#367fa9",
            }); 
                event.preventDefault(); // cancel default behavior
            }else{
                 //header image validation
                 for (var i = 0; i < $("#documents").get(0).files.length; ++i) {
                    var file1=$("#documents").get(0).files[i].name;
                    if(file1){                        
                        var file_size=$("#documents").get(0).files[i].size;
                            var ext = file1.split('.').pop().toLowerCase();                            
                            if($.inArray(ext,['xlsx','pdf'])===-1){
                                swal({
                                    type: 'error',
                                    title: 'Invalid File!',
                                    icon: 'error',
                                    customClass: 'swal-wide'
                                });
                                event.preventDefault();
                                return false;
                            }                                          
                    }
                }

                var item = $("input[name^='item_description']").length;
                var item_value = $("input[name^='item_description']");
                for(i=0;i<item;i++){
                    if(item_value.eq(i).val() == 0 || item_value.eq(i).val() == null){
                        swal({  
                                type: 'error',
                                title: 'Item Description cannot be empty!',
                                icon: 'error',
                                confirmButtonColor: "#367fa9",
                            });
                            event.preventDefault();
                            return false;
                    } 
            
                } 
                var sub_cat = $(".sub_category_id option").length;
                var sub_cat_value = $('.sub_category_id').find(":selected");
                for(i=0;i<sub_cat;i++){
                    if(sub_cat_value.eq(i).val() == ""){
                        swal({  
                                type: 'error',
                                title: 'Please select Sub Category!',
                                icon: 'error',
                                confirmButtonColor: "#367fa9",
                            });
                            event.preventDefault();
                            return false;
                    } 
            
                } 
                //quantity validation
                var v = $("input[name^='quantity']").length;
                var value = $("input[name^='quantity']");
                var reg = /^0/gi;
                    for(i=0;i<v;i++){
                        if(value.eq(i).val() == 0){
                            swal({  
                                    type: 'error',
                                    title: 'Quantity cannot be empty or zero!',
                                    icon: 'error',
                                    confirmButtonColor: "#367fa9",
                                });
                                event.preventDefault();
                                return false;
                        }else if(value.eq(i).val() < 0){
                            swal({
                                type: 'error',
                                title: 'Negative Value is not allowed!',
                                icon: 'error',
                                confirmButtonColor: "#367fa9",
                            }); 
                            event.preventDefault(); // cancel default behavior
                            return false;
                        }else if(value.eq(i).val().match(reg)){
                            swal({
                                type: 'error',
                                title: 'Invalid Quantity Value!',
                                icon: 'error',
                                confirmButtonColor: "#367fa9",
                            }); 
                            event.preventDefault(); // cancel default behavior
                            return false;     
                        }  
                
                    } 

                $(".sub_category_id :selected").each(function() {
                    if(app_count == 0 && $.inArray($(this).val().toLowerCase().replace(/\s/g, ''),['laptop','desktop']) > -1){
                        swal({  
                            type: 'error',
                            title: 'Please choose an Application!',
                            icon: 'error',
                            confirmButtonColor: "#367fa9",
                            
                        });
                        event.preventDefault();
                        return false;
                    }else{
                        swal({
                            title: "Are you sure?",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#41B314",
                            cancelButtonColor: "#F9354C",
                            confirmButtonText: "Yes, send it!",
                            width: 450,
                            height: 200
                            }, function () {
                                $("#ERFRequest").submit();                                                   
                        });
                    }
                }); 
            
            }
        
    });

</script>
@endpush