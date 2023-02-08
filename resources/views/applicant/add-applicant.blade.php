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
            .card, .card2, .card3, .card4, .card5, .card6, .card7, .card8{
                background-color: #fff ;
                padding: 15px;
                border-radius: 3px;
                box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
                margin-bottom: 15px;
            }
            .panel-heading{
                background-color: #f5f5f5 ;
            }
        </style>
    @endpush
@section('content')
@if(g('return_url'))
	<p class="noprint"><a title='Return' href='{{g("return_url")}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@else
	<p class="noprint"><a title='Main Module' href='{{CRUDBooster::mainpath()}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@endif

        <div class='panel-heading'>
            Applicant Form
        </div>

       <form action="{{ CRUDBooster::mainpath('add-save') }}" method="POST" id="ERFRequest" enctype="multipart/form-data">
        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
        <input type="hidden" value="1" name="request_type_id" id="request_type_id">
            <div class="card">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label require"> ERF</label>
                            <select required selected data-placeholder="-- Please Select ERF --" id="erf_number" name="erf_number" class="form-select erf" style="width:100%;">
                                @foreach($erf_number as $res)
                                    <option value=""></option>
                                    <option value="{{ $res->reference_number }}">{{ $res->reference_number }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-group">
                            <label class="control-label"><span style="color:red">*</span> Screen Date</label>
                            <input class="form-control finput date" type="text" placeholder="Select Date" name="screen_date" id="screen_date">
                        </div>
                    </div>  
                </div>

                <div class="row"> 
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label"><span style="color:red">*</span> First Name</label>
                            <input type="text" class="form-control finput"  id="first_name" name="first_name"  required>                                   
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label"><span style="color:red">*</span> Last Name</label>
                            <input type="text" class="form-control finput"  id="last_name" name="last_name"  required>                                   
                        </div>
                    </div>
                </div>
                <hr>
                <a href="{{ CRUDBooster::mainpath() }}" id="btn-cancel" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
                <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> {{ trans('message.form.submit') }}</button>
            </div>
           
            
    </form>
   
                       


@endsection
@push('bottom')
<script type="text/javascript">
    function preventBack() {
        window.history.forward();
    }
    window.onunload = function() {
        null;
    };
    setTimeout("preventBack()", 0);
    $('.erf').select2({})
    $(".date").datetimepicker({
                 minDate:new Date(), // Current year from transactions
                viewMode: "days",
                format: "YYYY-MM-DD",
                dayViewHeaderFormat: "MMMM YYYY",
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

    $("#btn-cancel").click(function(event) {
       event.preventDefault();
       swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#41B314",
            cancelButtonColor: "#F9354C",
            confirmButtonText: "Yes, Go back!",
            width: 450,
            height: 200
            }, function () {
                window.history.back();                                                  
        });
    });

</script>
@endpush