@extends('crudbooster::admin_template')
    @push('head')
        <style type="text/css">   
 
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
        ERF for Approval Form
    </div>

    <form method='post' id="myform" action='{{CRUDBooster::mainpath('edit-save/'.$Header->requestid)}}'>
        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
        <input type="hidden" value="" name="approval_action" id="approval_action">

        <div class='panel-body'>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                    <label class="control-label">COMPANY</i></label>
                            <input type="text" class="form-control finput" value="{{$Header->company}}" aria-describedby="basic-addon1" readonly>             
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label"> REQUESTED DATE</label>
                        <input type="text" class="form-control finput" value="{{$Header->date_requested}}" aria-describedby="basic-addon1" readonly>             
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                      <label class="control-label"> DEPARTMENT</label>
                      <input type="text" class="form-control finput" value="{{$Header->department}}" aria-describedby="basic-addon1" readonly>             
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label"> DATE NEEDED</label>
                        <input type="text" class="form-control finput" value="{{$Header->date_needed}}" aria-describedby="basic-addon1" readonly>             
                    </div>
                </div>
               
            </div>
            <div class="row"> 
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label"> POSITION</label>
                        <input type="text" class="form-control finput" value="{{$Header->position}}" aria-describedby="basic-addon1" readonly>                                                      
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label"> WORK LOCATION</label>
                        <input type="text" class="form-control finput" value="{{$Header->work_location}}" aria-describedby="basic-addon1" readonly>                                                                                    
                    </div>
                </div>
                
            </div>
            <div class="row"> 
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label"> SALARY RANGE</label>
                        <input type="text" style="-webkit-text-security: square;" class="form-control finput" value="{{encrypt($Header->salary_range)}}" aria-describedby="basic-addon1" readonly>                                                                                    
                               
                    </div>
                </div>
                
            </div>
            <hr/>
            <div class="row"> 
                <div class="col-md-6">
                    <label class="require control-label"> SCHEDULE</label><br>
                    <input type="text" class="form-control finput" value="{{$Header->schedule}}" aria-describedby="basic-addon1" readonly>                                                                                    
                </div>
                <div class="col-md-6">
                    <label class="require control-label"> ALLOW WFH</label><br>
                    <input type="text" class="form-control finput" value="{{$Header->allow_wfh}}" aria-describedby="basic-addon1" readonly>                                                                                    
                </div>
            </div>
            <br>
            <div class="row"> 
                <div class="col-md-6">
                    <label class="require control-label"> MANPOWER</label><br>
                    <input type="text" class="form-control finput" value="{{$Header->manpower}}" aria-describedby="basic-addon1" readonly>                                                                                    
                </div>
                <div class="col-md-6">
                    <label class="require control-label"> MANPOWER TYPE</label><br>
                    <input type="text" class="form-control finput" value="{{$Header->manpower_type}}" aria-describedby="basic-addon1" readonly>                                                                                     
                </div>
            </div>
            <br>
            <div class="row"> 
                <div class="col-md-6">
                    <label class="require control-label"><span style="color:red">*</span> REQUIRED EXAM</label><br>
                    @foreach($required_exams as $val)
                    <input type="text" class="form-control finput" value="{{trim($val)}}" aria-describedby="basic-addon1" readonly>                                                                                       
                    @endforeach
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label> QUALIFICATIONS</label>
                        <input type="text" class="form-control finput" value="{{$Header->qualifications}}" aria-describedby="basic-addon1" readonly>                                                                                     
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label> JOB DESCRIPTIONS</label>
                        <input type="text" class="form-control finput" value="{{$Header->job_description}}" aria-describedby="basic-addon1" readonly>                                                                                      
                    </div>
                </div>
            </div>
            <div class="row">   
                <div class="col-md-6">                        
                <label class="control-label">ATTACHED DOCUMENTS</label>
                @foreach($erf_header_documents as $erf_header_document)                                    
                    <a href='{{CRUDBooster::mainpath("download/".$erf_header_document->id)."?return_url=".urlencode(Request::fullUrl())}}' class="form-control finput">{{$erf_header_document->file_name}} <i style="color:#007bff" class="fa fa-download"></i></a>                                       
                @endforeach
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
                                    <table id='table_dashboard' class="table table-hover table-striped table-bordered">
                                        <tbody>
                                            <tr class="tbl_header_color dynamicRows">
                                                <th width="10%" class="text-center">Item Description</th>
                                                <th width="10%" class="text-center">Category</th> 
                                                <th width="10%" class="text-center">Sub Category</th>  
                                                <th width="10%" class="text-center">Quantity</th>   
                                            </tr>
                                            @foreach($Body as $rowresult)
                                            <tr>
                                                <td style="text-align:center" height="10">
                                                    {{$rowresult->item_description}}
                                                </td>
                                                <td style="text-align:center" height="10">
                                                    {{$rowresult->category_id}}
                                                </td>
                                                <td style="text-align:center" height="10">
                                                    {{$rowresult->sub_category_id}}
                                                </td>
                                                <td style="text-align:center" height="10">
                                                    {{$rowresult->quantity}}
                                                </td>
                                                

                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
            <hr>
            <br>
            <div class="row">
              <div class="col-md-6" >
                        <label class="require control-label">{{ trans('message.form-label.application') }}</label>
                        @foreach($application as $val)   
                        <input type="text" class="form-control finput" value="{{trim($val)}}" aria-describedby="basic-addon1" readonly>                                                                                       
                        @endforeach  
                </div>
                @if($Header->application_others != "")
                <div class="col-md-6">
                    <div class="row">
                        <label class="require control-label">*{{ trans('message.form-label.application_others') }}</label>
                        <div class="col-md-6">
                        <p>{{$Header->application_others}}</p>   
                        </div>
                    </div>
                    <hr/>
                </div>
                @endif
            </div>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <label class="require control-label"> Does the Employee need to shared files?</label><br>
                    <input type="text" class="form-control finput" value="{{$Header->shared_files}}" aria-describedby="basic-addon1" readonly>                                                                                      
  
                </div>
           
       
                <div class="col-md-6">
                    <label class="require control-label"> Who will the employee interact with?</label><br>
                    @foreach($interaction as $val)   
                    <input type="text" class="form-control finput" value="{{trim($val)}}" aria-describedby="basic-addon1" readonly>                                                                                       
                    @endforeach
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <label class="require control-label"> What will you be using the PC for?</label><br>
                    @foreach($asset_usage as $val)   
                    <input type="text" class="form-control finput" value="{{trim($val)}}" aria-describedby="basic-addon1" readonly>                                                                                       
                    @endforeach
                </div>
           
       
                <div class="col-md-6">
                    <label class="require control-label"> Email Domain</label><br>
                    <input type="text" class="form-control finput" value="{{$Header->email_domain}}" aria-describedby="basic-addon1" readonly>                                                                                      

                </div>
            </div>
            <br>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label> Additional Notes</label>
                        <textarea placeholder="Additional Notes ..." rows="3" class="form-control finput" name="additional_notess"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class='panel-footer'>
            <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
           
            <button class="btn btn-danger pull-right" type="button" id="btnReject" style="margin-left: 5px;"><i class="fa fa-thumbs-down" ></i> Reject</button>
            <button class="btn btn-success pull-right" type="button" id="btnApprove"><i class="fa fa-thumbs-up" ></i> Approve</button>
        </div>

    </form>
</div>

@endsection
@push('bottom')
<script type="text/javascript">
 $('#btnApprove').click(function(event) {
        event.preventDefault();
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#41B314",
            cancelButtonColor: "#F9354C",
            confirmButtonText: "Yes, approve it!",
            width: 450,
            height: 200
            }, function () {
                $(this).attr('disabled','disabled');
                $('#approval_action').val('1');
                $("#myform").submit();                   
        });
    });

    $('#btnReject').click(function(event) {
        event.preventDefault();
        swal({
            title: "Are you sure?",
            type: "warning",
            text: "You won't be able to revert this!",
            showCancelButton: true,
            confirmButtonColor: "#41B314",
            cancelButtonColor: "#F9354C",
            confirmButtonText: "Yes, reject it!",
            width: 450,
            height: 200
            }, function () {
                $(this).attr('disabled','disabled');
                $('#approval_action').val('0');
                $("#myform").submit();                   
        });
        
    });
</script>
@endpush