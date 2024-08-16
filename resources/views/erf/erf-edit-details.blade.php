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

            table, th, td {
            border: 1px solid rgba(000, 0, 0, .5);
            padding: 8px;
            border-radius: 5px 0 0 5px;
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
        ERF Edit Form 
    </div>

    <form method='post' id="myform" action="{{route('save-edit-erf')}}">
        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
        <input type="hidden" value="{{$Header->header_id}}" name="header_id" id="header_id">

            <div class="card">
                <div class="row"> 
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label"> ERF Number</label>
                            <input type="text" class="form-control" value="{{$Header->reference_number}}" aria-describedby="basic-addon1" disabled>                                                                                         
   
                        </div>
                    </div> 
                    <div class="col-md-6">
                        <div class="form-group">
                        <label class="control-label">Company</i></label>
                            <select required selected data-placeholder="-- Please choose company --" id="company" name="company" class="form-select select2" style="width:100%;">
                                @foreach($companies as $company)
                                    <option value="{{ $company->company_name }}"
                                        {{ isset($Header->company) && $Header->company == $company->company_name ? 'selected' : '' }}>
                                        {{ $company->company_name}} 
                                    </option>>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label"> Status</label>
                            <select required id="status_id" name="status_id" class="form-select select2" style="width:100%;">
                                @foreach($statuses as $res)
                                    <option value="{{ $res->id }}"
                                        {{ isset($Header->status_id) && $Header->status_id == $res->id ? 'selected' : '' }}>
                                        {{ $res->status_description }} 
                                    </option>>
                                @endforeach
                            </select>             
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                        <label class="control-label"> Department</label>
                            <select required id="department" name="department" class="form-select select2" style="width:100%;">
                                @foreach($departments as $res)
                                    <option value="{{ $res->id }}"
                                        {{ isset($Header->department) && $Header->department == $res->id ? 'selected' : '' }}>
                                        {{ $res->department_name }} 
                                    </option>>
                                @endforeach
                            </select>         
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label"> Position</label>
                            <select required id="position" name="position" class="form-select select2 white" style="width:100%;">
                                @foreach($positions as $res)
                                    <option value="{{ $res->position_description }}"
                                        {{ isset($Header->position) && $Header->position == $res->position_description ? 'selected' : '' }}>
                                        {{ $res->position_description }} 
                                    </option>>
                                @endforeach
                            </select>                                                      
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label"> Work Location</label>
                            <input type="text" class="form-control finput" value="{{$Header->work_location}}" aria-describedby="basic-addon1" name="work_location">                                                                                    
                        </div>
                    </div>
                    
                </div>
               
                <hr>
                <div class="footer">
                    <button class="btn btn-default" type="button" id="btnCancel"><i class="fa fa-times-circle"></i> Cancel</button>
                    <button class="btn btn-success pull-right" type="button" id="btnEdit"><i class="fa fa-thumbs-up"></i> Update</button>
                </div>
            </div>

    </form>

@endsection
@push('bottom')
<script type="text/javascript">
    $(function(){
        $('body').addClass("sidebar-collapse");
    });
    $("input:checkbox").click(function() { return false; });

    function preventBack() {
        window.history.forward();
    }
    setTimeout("preventBack()", 0);

    $('.select2').select2({})
    $('#btnEdit').click(function(event) {
        event.preventDefault();
        
            swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#41B314",
            cancelButtonColor: "#F9354C",
            confirmButtonText: "Yes, update it!",
            width: 450,
            height: 200
            }, function () {
                $(this).attr('disabled','disabled');
                $("#myform").submit();                   
        });

    });

    $("#btnCancel").click(function(event) {
       event.preventDefault();
       swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dd4b39",
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