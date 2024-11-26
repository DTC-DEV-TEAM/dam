@extends('crudbooster::admin_template')
@section('content')

@if(g('return_url'))
	<p class="noprint"><a title='Return' href='{{g("return_url")}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@else
	<p class="noprint"><a title='Main Module' href='{{CRUDBooster::mainpath()}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@endif
<div class='panel panel-default'>
    <div class='panel-heading'>
        Request Form
    </div>

    <form method='post' id="myform" action='{{CRUDBooster::mainpath('edit-save/'.$Header->requestid)}}'>
        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
        <input type="hidden" value="" name="approval_action" id="approval_action">

        <div class='panel-body'>

            <div class="row">                           
                <label class="control-label col-md-2">{{ trans('message.form-label.employee_name') }}:</label>
                <div class="col-md-4">
                        <p>{{$Header->employee_name}}</p>
                </div>
                <label class="control-label col-md-2">{{ trans('message.form-label.created_at') }}:</label>
                <div class="col-md-4">
                        <p>{{$Header->requested_date}}</p>
                </div>
            </div>


            <div class="row">                           
                <label class="control-label col-md-2">{{ trans('message.form-label.company_name') }}:</label>
                <div class="col-md-4">
                        <p>{{$Header->company}}</p>
                </div>
                <label class="control-label col-md-2">{{ trans('message.form-label.department') }}:</label>
                <div class="col-md-4">
                        <p>{{$Header->department_name}}</p>
                </div>
            </div>

            <div class="row">                           
                <label class="control-label col-md-2">{{ trans('message.form-label.position') }}:</label>
                <div class="col-md-4">
                        <p>{{$Header->position}}</p>
                </div>
                @if($Header->store_branch != null || $Header->store_branch != "")
                <label class="control-label col-md-2">{{ trans('message.form-label.store_branch') }}:</label>
                    <div class="col-md-4">
                            <p>{{$Header->store_branch}}</p>
                    </div>
                @endif
            </div>

            <hr>
            <div class="row">
                <label class="control-label col-md-2">Purpose:</label>
                <div class="col-md-4">
                        <p>{{$Header->request_type}}</p>
                </div>                    
            </div>

            <hr/>
        
            <div class="box-header text-center">
                <h3 class="box-title"><b>{{ trans('message.form-label.asset_items') }}</b></h3>
            </div>

            <table id="returnClose"  class='table table-striped table-bordered'>
                <thead>
                    <tr>
                        <th width="20%" class="text-center">Reference No</th>
                        <th width="10%" class="text-center">Asset Code</th>
                        <th width="10%" class="text-center">Digits Code</th>
                        <th width="10%" class="text-center">Serial No</th>
                        <th width="30%" class="text-center">{{ trans('message.table.item_description') }}</th>
                        <th width="25%" class="text-center">Asset Type</th> 
                        <th width="25%" class="text-center">Cost</th>                                                         
                    <!-- <th width="13%" class="text-center">{{ trans('message.table.image') }}</th> -->
                    </tr>
                </thead>
                <tbody>
                    @foreach($return_body as $rowresult)
                        <tr>
                            <td style="text-align:center" height="10">{{$rowresult->reference_no}}</td>
                            <td style="text-align:center" height="10">{{$rowresult->asset_code}}</td>
                            <td style="text-align:center" height="10">{{$rowresult->digits_code}}</td>
                            <td style="text-align:center" height="10">{{$rowresult->serial_no}}</td>
                            <td style="text-align:center" height="10">{{$rowresult->description}}</td>
                            <td style="text-align:center" height="10">{{$rowresult->asset_type}}</td>
                            <td style="text-align:center" height="10" class="cost">{{$rowresult->unit_cost}}</td>                     
                        </tr>
                    @endforeach

                </tbody>
                
            </table> 
            <hr/>
            @if($Header->approvedby != null || $Header->approvedby != "")
            <div class="row">                           
                <label class="control-label col-md-2">{{ trans('message.form-label.approved_by') }}:</label>
                <div class="col-md-4">
                        <p>{{$Header->approvedby}}</p>
                </div>
                <label class="control-label col-md-2">{{ trans('message.form-label.approved_at') }}:</label>
                <div class="col-md-4">
                        <p>{{$Header->approved_date}}</p>
                </div>
            </div>
            @endif
            @if($Header->approver_comments != null || $Header->approver_comments != "")
                <div class="row">                           
                    <label class="control-label col-md-2">{{ trans('message.table.approver_comments') }}:</label>
                    <div class="col-md-10">
                            <p>{{$Header->approver_comments}}</p>
                    </div>
                </div>
            @endif 
            <hr>

            @if( $Header->receivedby != null )
                <div class="row">   
                    @if($Header->transfer_to == null)                        
                        <label class="control-label col-md-2">Transacted By:</label>
                        <div class="col-md-4">
                                <p>{{$Header->receivedby}}</p>
                        </div>
                        <label class="control-label col-md-2">Transacted Date:</label>
                        <div class="col-md-4">
                                <p>{{$Header->transacted_date}}</p>
                        </div>
                    @else
                        <label class="control-label col-md-2">Transferred To:</label>
                        <div class="col-md-4">
                                <p>{{$Header->receivedby}}</p>
                        </div>
                        <label class="control-label col-md-2">Transferred Date:</label>
                        <div class="col-md-4">
                                <p>{{$Header->transacted_date}}</p>
                        </div>
                    @endif
                </div>
            @endif
    
        </div>


        <div class='panel-footer'>
            <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
           
            <button class="btn btn-success pull-right" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> {{ trans('message.form.closing') }}</button>
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
    setTimeout("preventBack()", 0);

    $('#btnSubmit').click(function(event) {
        event.preventDefault();
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#41B314",
            cancelButtonColor: "#F9354C",
            confirmButtonText: "Yes, close it!",
            width: 450,
            height: 200
            }, function () {
                $(this).attr('disabled','disabled');
                $('#myform').submit();                                                  
        });

    });
     var tds = document
    .getElementById("returnClose")
    .getElementsByTagName("td");
    var sumqty = 0;
    
    for (var i = 0; i < tds.length; i++) {
        console.log(tds[i].innerHTML);
    if (tds[i].className == "cost") {
        sumqty += isNaN(tds[i].innerHTML) ? 0 : parseFloat(tds[i].innerHTML);
    }
    }
    document.getElementById("returnClose").innerHTML +=
    "<tr><td colspan='6' style='text-align:right'><strong>TOTAL</strong></td><td style='text-align:center'><strong>" +
    sumqty +
    "</strong></td></tr>";

</script>
@endpush