@extends('crudbooster::admin_template')
    @push('head')
        <style type="text/css">   
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
<div class='panel panel-default'>
    <div class='panel-heading'>
        History Detail View
    </div>

        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
        <input type="hidden" value="0" name="action" id="action">

        <input type="hidden" value="{{$Header->requestid}}" name="headerID" id="headerID">

        <input type="hidden" value="" name="bodyID" id="bodyID">

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
                   @if($Header->header_created_by != null || $Header->header_created_by != "")
                        <p>{{$Header->employee_name}}</p>
                    @else
                    <p>{{$Header->header_emp_name}}</p>
                    @endif
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

            @if(CRUDBooster::myPrivilegeId() == 8 || CRUDBooster::isSuperadmin())
                <div class="row">                           
                    <label class="control-label col-md-2">{{ trans('message.form-label.store_branch') }}:</label>
                    <div class="col-md-4">
                            <p>{{$Header->store_branch}}</p>
                    </div>
                </div>
            @endif

            @if($Header->if_from_erf != null || $Header->if_from_erf != "")
                <div class="row">                           
                    <label class="control-label col-md-2">Erf Number:</label>
                    <div class="col-md-4">
                            <p>{{$Header->if_from_erf}}</p>
                    </div>
                </div>
            @endif

            @if($Header->requestor_comments != null || $Header->requestor_comments != "")
                <hr/>
                <div class="row">                           
                    <label class="control-label col-md-2">{{ trans('message.table.requestor_comments') }}:</label>
                    <div class="col-md-10">
                            <p>{{$Header->requestor_comments}}</p>
                    </div>

            
                </div>
            @endif  
            <hr/>                
            <div class="row">
                <div class="col-md-12">
                    <div class="box-header text-center">
                        <h3 class="box-title"><b>Item Source</b></h3>
                    </div>
                    <div class="box-body no-padding">
                        <div class="table-responsive">
                            <div class="pic-container">
                                <div class="pic-row">
                                    <table class="table table-bordered" id="asset-items1">
                                        <tbody id="bodyTable">
                                            <tr class="tbl_header_color dynamicRows">
                                                <th width="20%" class="text-center">{{ trans('message.table.item_description') }}</th>
                                                <th width="9%" class="text-center">{{ trans('message.table.category_id_text') }}</th>                                                         
                                                <th width="15%" class="text-center">{{ trans('message.table.sub_category_id_text') }}</th> 
                                                <th width="15%" class="text-center">Quantity</th> 
                                            </tr>
                                            <tr id="tr-table">
                                                <?php   $tableRow = 1; ?>
                                                <tr>
                                                    @foreach($Body as $rowresult)
                                                        <?php   $tableRow++; ?>
                                                                                            
                                                        <tr>
                                                
                                                            <td style="text-align:center" height="10">
                                                                    <input type="hidden"  class="form-control"  name="ids[]" id="ids{{$tableRow}}"  required  value="{{$rowresult->id}}">                               
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
                                                                    <input type='hidden' name="quantity" class="form-control text-center quantity_item" id="quantity" readonly value="{{$rowresult->quantity}}">
                                                                    <input type='hidden' name="quantity_body" id="quantity{{$tableRow}}" readonly value="{{$rowresult->quantity}}">
                                                            </td>
                                                              
                                                      </tr>
                                                                                                                         
                                                    @endforeach     
                                                    
                                                    <input type='hidden' name="quantity_total" class="form-control text-center" id="quantity_total" readonly value="{{$Header->quantity_total}}">
                                                </tr>
                                            </tr>          
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <br><br>
           
                <div class="row">
                    <div class="col-md-6">
                        <table style="width:100%">
                            <tbody>
                               @if($Header->rejected_at == null)
                                <tr>
                                    <th class="control-label col-md-2">{{ trans('message.form-label.approved_by') }}:</th>
                                    <td class="col-md-4">{{$Header->approvedby}} / {{$Header->approved_at}}</td>   
                                </tr>
                                @else
                                <tr>
                                    <th class="control-label col-md-2">Rejected By:</th>
                                    <td class="col-md-4">{{$Header->approvedby}} / {{$Header->rejected_at}}</td>   
                                </tr>
                                @endif
                                @if($Header->approver_comments != null)
                                    <tr>
                                        <th class="control-label col-md-2">{{ trans('message.table.approver_comments') }}:</th>
                                        <td class="col-md-4">{{$Header->approver_comments}}</td>
                                    </tr>
                                @endif
                                @if($Header->po_number != null)
                                <tr>
                                    <th class="control-label col-md-2">{{ trans('message.form-label.po_number') }}:</th>
                                    <td class="col-md-4">{{$Header->po_number}}</td>     
                                </tr>
                                @endif
                                @if($Header->po_date != null)
                                <tr>
                                    <th class="control-label col-md-2">{{ trans('message.form-label.po_date') }}:</th>
                                    <td class="col-md-4">{{$Header->po_date}}</td>
                                </tr>
                                @endif
                                @if($Header->quote_date != null)
                                <tr>
                                    <th class="control-label col-md-2">{{ trans('message.form-label.quote_date') }}:</th>
                                    <td class="col-md-4">{{$Header->quote_date}}</td>
                                </tr>
                                @endif
                                @if( $Header->processedby != null )
                                    <tr>
                                        <th class="control-label col-md-2">{{ trans('message.form-label.processed_by') }}:</th>
                                        <td class="col-md-4">{{$Header->processedby}} / {{$Header->purchased2_at}}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <table style="width:100%">
                            <tbody>
                                @if($Header->ac_comments != null)
                                    <tr>
                                        <th class="control-label col-md-2">{{ trans('message.table.ac_comments') }}:</th>
                                        <td class="col-md-4">{{$Header->ac_comments}}</td>
                                    </tr>
                                @endif
                                @if( $Header->pickedby != null )
                                    <tr>
                                        <th class="control-label col-md-2">{{ trans('message.form-label.picked_by') }}:</th>
                                        <td class="col-md-4">{{$Header->pickedby}} / {{$Header->picked_at}}</td>
                                    </tr>
                                @endif
                                @if( $Header->receivedby != null )
                                    <tr>
                                        <th class="control-label col-md-2">{{ trans('message.form-label.received_by') }}:</th>
                                        <td class="col-md-4">{{$Header->receivedby}} / {{$Header->received_at}}</td>
                                    </tr>
                                @endif
                                @if( $Header->closedby != null )
                                    <tr>
                                        <th class="control-label col-md-2">{{ trans('message.form-label.closed_by') }}:</th>
                                        <td class="col-md-4">{{$Header->closedby}} / {{$Header->closed_at}}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
     
        </div>

        <div class='panel-footer'>

            <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.back') }}</a>

        </div>

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

    $('#btnSubmit').click(function() {

        var strconfirm = confirm("Are you sure you want to close this request?");
        if (strconfirm == true) {

            $(this).attr('disabled','disabled');

            $('#myform').submit(); 
            
        }else{
            return false;
            window.stop();
        }

    });

    var tableRow = <?php echo json_encode($tableRow); ?>;

    $(document).ready(function() {
            $(document).on('click', '.removeRow', function() {
                
                event.preventDefault();
                if ($('#asset-items1 tbody tr').length != 1) { //check if not the first row then delete the other rows
                var id_data = $(this).attr("data-id");    
                $("#quantity_total").val(calculateTotalQuantity($("#quantity"+id_data).val()));
                item_id = $("#ids"+id_data).val();
                $("#bodyID").val(item_id);
                var data = $('#myform').serialize();
                swal({
                    title: "Are you sure?",
                    type: "warning",
                    text: "You won't be able to revert this!",
                    showCancelButton: true,
                    confirmButtonColor: "#41B314",
                    cancelButtonColor: "#F9354C",
                    confirmButtonText: "Yes, cancel it!"
                    }, function () {
                    $.ajax
                        ({ 
                            url:  '{{ url('admin/header_request/RemoveItem') }}',
                            type: "GET",
                            data: data,
                            success: function(data){    
                                if (data.status == "success") {
                                    swal({
                                        type: data.status,
                                        title: data.message,
                                    });
                                    setTimeout(function(){
                                        window.location.replace(document.referrer);
                                    }, 1000); 
                                    } else if (data.status == "error") {
                                    swal({
                                        type: data.status,
                                        title: data.message,
                                    });
                                }
                            }
                        });                            
                    });
                    $("#deleteRow"+id_data).attr('disabled', true);
                    tableRow--;
                    $(this).closest('tr').css('background-color','#d9534f');
                    $(this).closest('tr').css('color','white'); 
                    return false;   
               }
            });
    });

        function calculateTotalQuantity(...body_qty) {
            var totalQuantity = 0;  
            $('.quantity_item').each(function() {
             totalQuantity = parseInt($("#quantity_total").val()) - parseInt(body_qty);
            });
            return totalQuantity;
    
        }
    
</script>
@endpush