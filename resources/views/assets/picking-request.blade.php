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

    <form method='post' id="myform" action='{{CRUDBooster::mainpath('edit-save/'.$HeaderID->id)}}'>
        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
        <input type="hidden" value="0" name="action" id="action">

        <div class='panel-body'>

            <div class="row">                           
                <label class="control-label col-md-2">{{ trans('message.form-label.reference_number') }}:</label>
                <div class="col-md-4">
                        <p>{{$Header->reference_number}}</p>
                </div>

                <label class="control-label col-md-2">{{ trans('message.form-label.created_at') }}:</label>
                <div class="col-md-4">
                        <p>{{$Header->created}}</p>
                </div>


            </div>

            <div class="row">                           
                <label class="control-label col-md-2">{{ trans('message.form-label.employee_name') }}:</label>
                <div class="col-md-4">
                        <p>{{$Header->employee_name}}</p>
                </div>

                <label class="control-label col-md-2">{{ trans('message.form-label.company_name') }}:</label>
                <div class="col-md-4">
                        <p>{{$Header->company_name}}</p>
                </div>
            </div>

            <div class="row">                           


                <label class="control-label col-md-2">{{ trans('message.form-label.department') }}:</label>
                <div class="col-md-4">
                        <p>{{$Header->department}}</p>
                </div>

                <label class="control-label col-md-2">{{ trans('message.form-label.position') }}:</label>
                <div class="col-md-4">
                        <p>{{$Header->position}}</p>
                </div>

            </div>

            @if($Header->store_branch != null || $Header->store_branch != "")
                <div class="row">                           
                    <label class="control-label col-md-2">{{ trans('message.form-label.store_branch') }}:</label>
                    <div class="col-md-4">
                            <p>{{$Header->store_branch}}</p>
                    </div>
                </div>
            @endif

            <hr/>

            <div class="row">                           
                <label class="control-label col-md-2">{{ trans('message.form-label.purpose') }}:</label>
                <div class="col-md-4">
                        <p>{{$Header->request_description}}</p>
                </div>

        
            </div>
  

            @if($Header->requestor_comments != null || $Header->requestor_comments != "")
                <hr/>
                <div class="row">                           
                    <label class="control-label col-md-2">{{ trans('message.table.requestor_comments') }}:</label>
                    <div class="col-md-10">
                            <p>{{$Header->requestor_comments}}</p>
                    </div>

            
                </div>
            @endif  

            <hr />

            <div class="row">
                <div class="col-md-12">
                    <div class="box-header text-center">
                        <h3 class="box-title"><b>{{ trans('message.form-label.asset_items') }}</b></h3>
                    </div>
                                <div class="box-body no-padding">
                                    <div class="table-responsive">
                                            <div class="pic-container">
                                                <div class="pic-row">
                                                    <table class="table table-bordered" id="asset-items">
                                                        <tbody id="bodyTable">
                                                            <tr class="tbl_header_color dynamicRows">
                                                                <!-- <th width="13%" class="text-center">{{ trans('message.table.action') }}</th>  -->
                                                                <th width="13%" class="text-center">Good</th> 
                                                                <th width="13%" class="text-center">Defective</th>
                                                                <th width="10%" class="text-center">{{ trans('message.table.mo_reference_number') }}</th>
                                                                <!-- <th width="13%" class="text-center">{{ trans('message.table.status_id') }}</th> -->
                                                                <th width="10%" class="text-center">{{ trans('message.table.digits_code') }}</th>
                                                                <th width="10%" class="text-center">{{ trans('message.table.asset_tag') }}</th>
                                                                <th width="26%" class="text-center">{{ trans('message.table.item_description') }}</th>
                                                                <th width="13%" class="text-center">{{ trans('message.table.serial_no') }}</th>
                                                                <th width="4%" class="text-center">{{ trans('message.table.item_quantity') }}</th>
                                                                <!-- <th width="8%" class="text-center">{{ trans('message.table.item_cost') }}</th>
                                                                <th width="16%" class="text-center">{{ trans('message.table.item_total_cost') }}</th>
                                                                -->
                                                                
                                                            </tr>
                                                            
                                                            <?php   $tableRow1 = 0; ?>

                                                            <?Php   $item_count = 0; ?>

                                                            @if( !empty($MoveOrder) )
    
                                                              
    
                                                                @foreach($MoveOrder as $rowresult)
    
                                                                    <?php   $tableRow1++; ?>

                                                                    <?Php $item_count++; ?>
    
                                                                    <tr>
                                                                        <td style="text-align:center" height="10">

                                                                            <input type="hidden" value="{{$rowresult->id}}" name="item_id[]">

                                                                            <input type="hidden" name="good_text[]" id="good_text{{$tableRow1}}" value="0" />

                                                                            <input type="checkbox" name="good[]" id="good{{$tableRow1}}" class="good" required data-id="{{$tableRow1}}" value="0"/>
                                                                        </td>

                                                                        <td style="text-align:center" height="10">

                                                                            <input type="hidden" name="defective_text[]" id="defective_text{{$tableRow1}}" value="0" />

                                                                            <input type="checkbox" name="defective[]" id="defective{{$tableRow1}}" class="defective" required data-id="{{$tableRow1}}"  value="0"/>
                                                                        </td>

                                                                        <td style="text-align:center" height="10">
                                                                            {{$rowresult->mo_reference_number}}
                                                                        </td>
    
                                                                        <!--<td style="text-align:center" height="10">
    
                                                                            <label style="color: #3c8dbc;">
                                                                                {{$rowresult->status_description}}
                                                                            </label>
                                                                           
    
                                                                        </td>-->
    
                                                                        <td style="text-align:center" height="10">
                                                                            {{$rowresult->digits_code}}
                                                                        </td>
    
                                                                        <td style="text-align:center" height="10">
                                                                            {{$rowresult->asset_code}}
                                                                        </td>
    
                                                                        <td style="text-align:center" height="10">
                                                                            {{$rowresult->item_description}}
                                                                        </td>
    
                                                                        <td style="text-align:center" height="10">
                                                                            {{$rowresult->serial_no}}
                                                                        </td>
    
                                                                        <td style="text-align:center" height="10">
                                                                            {{$rowresult->quantity}}
                                                                        </td>
    
                                                                        <!-- <td style="text-align:center" height="10">
                                                                            {{$rowresult->unit_cost}}
                                                                        </td>
    
                                                                        <td style="text-align:center" height="10">
                                                                            {{$rowresult->total_unit_cost}}
                                                                        </td> -->
    
                                                                        
    
                                                                    </tr>
    
                                                                    <?Php $cost_total = $rowresult->total_unit_cost; ?>

                                                                @endforeach
    
    
                                                            @endif
                                                        
                                                        </tbody>
                                                        
                                                        <tfoot>

                                                            <tr id="tr-table1" class="bottom">
                
                                                               <!-- <td colspan="6" align="right">
                                                                    <label>Total Qty:</label>

                                                                 
                                                                </td> 
                                                                <td align="center" colspan="1">
                                                                    
                                                                    <label>{{$Header->quantity_total}}</label>

                                                                </td> -->
                                                                        
                                                                <!-- 
                                                                <td align="right" colspan="8">
                                                                    <label>Total Cost:</label>
                                                                </td>

                                                                <td align="center">
                                                                    @if($item_count == 1)
                                                                        <label>{{$cost_total}}</label>
                                                                    @else
                                                                        <label>{{$Header->total}}</label>
                                                                    @endif
                                                                </td>
                                                                -->
                                                            </tr>
                                                        </tfoot>

                                                    </table>
                                                </div>
                                            </div>
                                    </div>
            
                                </div>
                </div>
            </div>

            <hr />

            @if($Header->application != null || $Header->application != "")
               
                <div class="form-group">                          
                    <label class="control-label col-md-2">{{ trans('message.form-label.application') }}:</label>
                    <div class="col-md-4">
                            <p>{{$Header->application}}</p>
                    </div>
                    
                    @if($Header->application_others != null || $Header->application_others != "")
                        <label class="control-label col-md-2">{{ trans('message.form-label.application_others') }}:</label>
                        <div class="col-md-4">
                                <p>{{$Header->application_others}}</p>
                        </div>
                    @endif  

                </div>

            @endif 

 
        </div>


        <div class='panel-footer'>

            <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
           
            
            <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-circle-o" ></i> {{ trans('message.form.pick') }}</button>

        
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

    $('#btnSubmit').attr("disabled", true);

    var count_pick = 0;

    $('.good').change(function() {

        var id = $(this).attr("data-id");

        var ischecked= $(this).is(':checked');

        if(ischecked == false){

            $("#good_text"+id).val("0");

            //$("#defective"+id).val("0");

            count_pick--;

            if(count_pick == 0){
                $('#btnSubmit').attr("disabled", true);
            }

                
        }else{

            $("#good_text"+id).val("1");

            //$("#defective"+id).val("1");

            count_pick++;

            $('#btnSubmit').attr("disabled", false);
        }

    });


    $('.defective').change(function() {

        var id = $(this).attr("data-id");

        var ischecked= $(this).is(':checked');

            if(ischecked == false){

                //$("#good"+id).val("0");

                $("#defective_text"+id).val("0");

                count_pick--;

                if(count_pick == 0){
                    $('#btnSubmit').attr("disabled", true);
                }

                    
            }else{

                //$("#good"+id).val("1");

                $("#defective_text"+id).val("1");

                count_pick++;

                $('#btnSubmit').attr("disabled", false);
            }

    });

    $('#btnSubmit').click(function() {

        var strconfirm = confirm("Are you sure you want to pick this request?");
        if (strconfirm == true) {

            $(this).attr('disabled','disabled');

            $('#myform').submit(); 
            
        }else{
            return false;
            window.stop();
        }

    });

</script>
@endpush