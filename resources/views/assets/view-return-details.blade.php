@extends('crudbooster::admin_template')
@push('head')
<style type="text/css">   

    #asset-items th, td {
        border: 1px solid rgba(000, 0, 0, .5);
        padding: 8px;
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
        Request Form
    </div>

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
                @if($Header->transfer_to != null)    
                    <label class="control-label col-md-2">Purpose:</label>
                    <div class="col-md-4">
                            <p>{{$Header->request_type}}</p>
                    </div>                    
                    @else
                    <label class="control-label col-md-2">Purpose:</label>
                    <div class="col-md-4">
                            <p>{{$Header->request_type}}</p>
                    </div>
                @endif
            </div>

            <hr/>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-div" style="overflow-x:scroll">
                        <table id="asset-items" style="width: 100%">
                            <thead>
                                <tr style="background-color:#3c8dbc; border: 0.5px solid #000;">
                                    <th style="text-align: center" colspan="11"><h4 class="box-title" style="color: #fff;"><b>{{ trans('message.form-label.asset_items') }}</b></h4></th>
                                </tr>
                                <tr>
                                    <th width="10%" class="text-center">Line status</th>
                                    <th width="15%" class="text-center">Reference No</th>
                                    <th width="10%" class="text-center">Asset Code</th>
                                    <th width="10%" class="text-center">Digits Code</th>
                                    <th width="25%" class="text-center">{{ trans('message.table.item_description') }}</th>
                                    <th width="15%" class="text-center">Asset Type</th>                                                         
                                    <th width="15%" class="text-center">Serial Number</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($return_body as $rowresult)
                                    <tr>
                                        @if($rowresult['body_status'] == 1)
                                            <td style="text-align:center">
                                            <label class="label label-warning" style="align:center; font-size:10px">{{$rowresult->status_description}}</label>
                                            </td>
                                        @elseif($rowresult['body_status'] == 5)
                                            <td style="text-align:center">
                                            <label class="label label-danger" style="align:center; font-size:10px">{{$rowresult->status_description}}</label>
                                            </td>
                                        @elseif($rowresult['body_status'] == 8)
                                            <td style="text-align:center">
                                            <label class="label label-danger" style="align:center; font-size:10px">{{$rowresult->status_description}}</label>
                                            </td>
                                        @elseif($rowresult['body_status'] == 24)
                                            <td style="text-align:center">
                                            <label class="label label-info" style="align:center; font-size:10px">{{$rowresult->status_description}}</label>
                                            </td>
                                        @elseif($rowresult['body_status'] == 26)
                                            <td style="text-align:center">
                                            <label class="label label-info" style="align:center; font-size:10px">{{$rowresult->status_description}}</label>
                                            </td>
                                        @elseif($rowresult['body_status'] == 27)
                                            <td style="text-align:center">
                                            <label class="label label-info" style="align:center; font-size:10px">{{$rowresult->status_description}}</label>
                                            </td>
                                        @else
                                        <td style="text-align:center">
                                            <label class="label label-success" style="align:center; font-size:10px">{{$rowresult->status_description}}</label>
                                        </td>
                                        @endif
                                        <td style="text-align:center" height="10">{{$rowresult->reference_no}}</td>
                                        <td style="text-align:center" height="10">{{$rowresult->asset_code}}</td>
                                        <td style="text-align:center" height="10">{{$rowresult->digits_code}}</td>
                                        <td style="text-align:center" height="10">{{$rowresult->description}}</td>
                                        <td style="text-align:center" height="10">{{$rowresult->asset_type}}</td>
                                        <td style="text-align:center" height="10">{{$rowresult->serial_no}}</td>
                                                            
                                    </tr>
                                @endforeach
    
                            </tbody>
                            
                        </table> 
                    </div>
                </div>
            </div>
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
            <hr>
            @if( $Header->closedby != null )
                <div class="row">                           
                    <label class="control-label col-md-2">{{ trans('message.form-label.closed_by') }}:</label>
                    <div class="col-md-4">
                            <p>{{$Header->closedby}}</p>
                    </div>
                    <label class="control-label col-md-2">{{ trans('message.form-label.closed_at') }}:</label>
                    <div class="col-md-4">
                            <p>{{$Header->close_at}}</p>
                    </div>
                </div>
            @endif
            
            </div>
        </div>

</div>

@endsection
@push('bottom')
<script type="text/javascript">


</script>
@endpush