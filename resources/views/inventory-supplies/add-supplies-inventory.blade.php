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

       <form name="suppliesInventory" id="suppliesInventory" enctype="multipart/form-data">
        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
        <input type="hidden" value="1" name="request_type_id" id="request_type_id">
            <div class="card">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label require"> Digits Code</label>
                            <select required selected data-placeholder="-- Please Select Digits Code --" id="digits_code" name="digits_code" class="form-select digits_code" style="width:100%;">
                                @foreach($digits_code as $res)
                                    <option value=""></option>
                                    <option value="{{ $res->digits_code }}">{{ $res->digits_code }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label"><span style="color:red">*</span> Item Description</label>
                            <input class="form-control finput" type="text" placeholder="Item Description" name="description" id="description" readonly>
                        </div>
                    </div>  
                </div>

                <div class="row"> 
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label"><span style="color:red">*</span> Quantity</label>
                            <input type="text" class="form-control finput" placeholder="Quantity" id="quantity" name="quantity"  required>                                   
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
    $('.digits_code').select2({})

    $(document).ready(function() {
        $(".date").val('');
        $("#first_name").focus();
    });

    //Sub Category
    $('#digits_code').change(function(){
        var digits_code =  this.value;

        $.ajax({ 
            type: 'POST',
            url: "{{ route('get.supplies.description') }}",
            data: {
                "digits_code": digits_code
            },
            success: function(result) {
                console.log(result[0].item_description);
                $('#description').val(result[0].item_description);
            }
        });
    });

    $("#btnSubmit").click(function(event) {
    
        event.preventDefault();

            if($("#job_portal").val() === ""){
                swal({
                    type: 'error',
                    title: 'Job Portal Required!',
                    icon: 'error',
                    confirmButtonColor: "#367fa9",
                }); 
                event.preventDefault(); // cancel default behavior
                return false;
            }else{
                    swal({
                        title: "Are you sure?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#41B314",
                        cancelButtonColor: "#F9354C",
                        confirmButtonText: "Yes, send it!",
                        }, function () {
                            $.ajax({
                                data: $('#suppliesInventory').serialize(),
                                url: "{{ route('add.supplies.inventory') }}",
                                type: "POST",
                                dataType: 'json',
                                success: function (data) {
                                    if (data.status == "success") {
                                        swal({
                                            type: data.status,
                                            title: data.message,
                                        });
                                    
                                        } else if (data.status == "error") {
                                        swal({
                                            type: data.status,
                                            title: data.message,
                                        });
                                    }
                                    $('#earnForm').trigger("reset");
                                    $('#demoModal').modal('hide');
                                    location.reload();
                                
                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                }
                            });                                                 
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