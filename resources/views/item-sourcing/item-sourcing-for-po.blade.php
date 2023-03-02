@extends('crudbooster::admin_template')
    @push('head')
        <style type="text/css">   
            table, th, td {
            border: 1px solid rgba(000, 0, 0, .5);
            padding: 8px;
            border-radius: 5px 0 0 5px;
            }
            .finput {
                border:none;
                border-bottom: 1px solid rgba(18, 17, 17, 0.5);
            }

            input.finput:read-only {
                background-color: #fff;
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
        Detail Form
    </div>

    <form method='post' id="myform" action='{{CRUDBooster::mainpath('edit-save/'.$Header->requestid)}}'>
        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
        <input type="hidden" value="0" name="action" id="action">
        <input type="hidden" value="" name="approval_action" id="approval_action">
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

            <div class="row">                           
                <label class="control-label col-md-2">Date Needed:</label>
                <div class="col-md-4">
                        <p>{{$Header->date_needed}}</p>
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

            @if($Header->requestor_comments != null || $Header->requestor_comments != "")
                <hr/>
                <div class="row">                           
                    <label class="control-label col-md-2">{{ trans('message.table.requestor_comments') }}:</label>
                    <div class="col-md-10">
                            <p>{{$Header->requestor_comments}}</p>
                    </div>
                </div>
            @endif  

            
            @if($Header->suggested_supplier != null || $Header->suggested_supplier != "")
                <hr/>
                <div class="row">                           
                    <label class="control-label col-md-2">Suggested Supplier:</label>
                    <div class="col-md-10">
                            <p>{{$Header->suggested_supplier}}</p>
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
                                    <table class="table table-bordered" id="item-sourcing">
                                        <tbody id="bodyTable">
                                            <tr class="tbl_header_color dynamicRows">
                                                <th width="10%" class="text-center">Item Code</th> 
                                                <th width="10%" class="text-center">PO Number</th>
                                                <th width="10%" class="text-center">PO Date</th> 
                                                <th width="10%" class="text-center">Quote Date</th> 
                                                <th width="10%" class="text-center">Supplier</th> 
                                                <th width="10%" class="text-center">{{ trans('message.table.item_description') }}</th>
                                                <th width="9%" class="text-center">{{ trans('message.table.category_id_text') }}</th>                                                         
                                                <th width="10%" class="text-center">{{ trans('message.table.sub_category_id_text') }}</th> 
                                                <th width="5%" class="text-center">Budget</th> 
                                                <th width="5%" class="text-center">Quantity</th> 
                                                <th width="10%" class="text-center">Value</th> 
                                            </tr>
                                            <tr id="tr-table">
                                                <?php   $tableRow = 1; ?>
                                                <tr>
                                                    @foreach($Body as $rowresult)
                                                        <?php   $tableRow++; ?>
                                                                                            
                                                        <tr>
                                                            <input type="hidden"  class="form-control"  name="ids[]" id="ids{{$tableRow}}"  required  value="{{$rowresult->id}}">        
                                                            <td style="text-align:center" height="10">
                                                                    <input type="text"  class="form-control finput digits_code"  name="item_code[]" id="digits_code{{$tableRow}}" data-id="{{$tableRow1}}" value="{{$rowresult->digits_code}}" required >                                
                                                            </td>
                                                            <td style="text-align:center" height="10">
                                                                    <input type="text"  class="form-control finput"  name="po_number[]" id="po_number{{$tableRow}}" data-id="{{$tableRow1}}" value="{{$rowresult->po_number}}" required >                                
                                                            </td>
                                                            <td style="text-align:center" height="10">
                                                                    <input type="text"  class="form-control finput po_date{{$tableRow}}"  name="po_date[]" id="po_date{{$tableRow}}" value="{{$rowresult->po_date}}" data-id="{{$tableRow1}}"  required >                                
                                                            </td>
                                                            <td style="text-align:center" height="10">
                                                                    <input type="text"  class="form-control finput qoute_date"  name="qoute_date[]" id="qoute_date{{$tableRow}}" data-id="{{$tableRow1}}" value="{{$rowresult->qoute_date}}" required >                                
                                                            </td>
                                                            <td style="text-align:center" height="10">
                                                                    <input type="text"  class="form-control finput"  name="supplier[]" id="supplier{{$tableRow}}" value="{{$rowresult->supplier}}" required >                                
                                                            </td>
                                                            <td style="text-align:center" height="10">                                                             
                                                                    {{$rowresult->item_description}}
                                                            </td>
                                                            <td style="text-align:center" height="10">
                                                                    {{$rowresult->category_id}}
                                                            </td>
                                                            <td style="text-align:center" height="10">
                                                                    {{$rowresult->sub_category_id}}
                                                            </td>
                                                            <td style="text-align:center" height="10" class="cost">
                                                                    {{$rowresult->budget}}
                                                            </td>
                                                            <td style="text-align:center" height="10" class="qty">
                                                                    {{$rowresult->quantity}}
                                                          
                                                            </td>
                                                            <td style="text-align:center" height="10">
                                                                    <input type="text"  class="form-control finput"  name="value[]" id="value{{$tableRow}}" value="{{$rowresult->value}}" required >                                
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

            <div class="row">
                    <div class="col-md-6">
                        <table style="width:100%">
                            <tbody>
                            @if($Header->approvedby != null)
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

            <a href="{{ CRUDBooster::mainpath() }}" id="btn-cancel" class="btn btn-default">{{ trans('message.form.back') }}</a>
            <button class="btn btn-success pull-right" type="button" id="btnClose" style="margin-left: 5px;"><i class="fa fa-times-circle" ></i> Close</button>
            <button class="btn btn-primary pull-right" type="button" id="btnUpdate"><i class="fa fa-refresh" ></i> Update</button>
        </div>

    </form>



</div>

@endsection
@push('bottom')
<script type="text/javascript">
    $(function(){
        $('body').addClass("sidebar-collapse");
    });
    function preventBack() {
        window.history.forward();
    }
    window.onunload = function() {
        null;
    };
    setTimeout("preventBack()", 0);
    var searchcount = <?php echo json_encode($tableRow); ?>;

    let countrow = 1;

    $(function(){

        for (let i = 0; i < searchcount; i++) {
            countrow++;
            $('#po_date'+countrow).datetimepicker({
                minDate:new Date(), // Current year from transactions
                viewMode: "days",
                format: "YYYY-MM-DD",
                dayViewHeaderFormat: "MMMM YYYY",
            });
            $('#qoute_date'+countrow).datetimepicker({
                minDate:new Date(), // Current year from transactions
                viewMode: "days",
                format: "YYYY-MM-DD",
                dayViewHeaderFormat: "MMMM YYYY",
            });

            $('#digits_code'+countrow).each(function() {
                description = $(this).val();
                if(description !== "") {
                    $('#digits_code'+countrow).attr('readonly', true);

                }else{
                    $('#digits_code'+countrow).removeAttr('readonly');
                }
            });

            $('#po_number'+countrow).each(function() {
                description = $(this).val();
                if(description !== "") {
                    $('#po_number'+countrow).attr('readonly', true);

                }else{
                    $('#po_number'+countrow).removeAttr('readonly');
                }
            });

            $('#po_date'+countrow).each(function() {
                description = $(this).val();
                if(description !== "") {
                    $('#po_date'+countrow).attr('readonly', true);

                }else{
                    $('#po_date'+countrow).removeAttr('readonly');
                }
            });

            $('#qoute_date'+countrow).each(function() {
                description = $(this).val();
                if(description !== "") {
                    $('#qoute_date'+countrow).attr('readonly', true);

                }else{
                    $('#qoute_date'+countrow).removeAttr('readonly');
                }
            });

            $('#supplier'+countrow).each(function() {
                description = $(this).val();
                if(description !== "") {
                    $('#supplier'+countrow).attr('readonly', true);

                }else{
                    $('#supplier'+countrow).removeAttr('readonly');
                }
            });

            $('#value'+countrow).each(function() {
                description = $(this).val();
                if(description !== "") {
                    $('#value'+countrow).attr('readonly', true);

                }else{
                    $('#value'+countrow).removeAttr('readonly');
                }
            });
        }
    });

   

    $('#btnUpdate').click(function(event) {
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
                $('#approval_action').val('1');
                $("#myform").submit();                   
        });
    });

    $('#btnClose').click(function(event) {
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
                $('#approval_action').val('0');
                $("#myform").submit();                   
        });
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

    function thousands_separators(num) {
    var num_parts = num.toString().split(".");
    num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return num_parts.join(".");
    }

    var tds = document
    .getElementById("item-sourcing")
    .getElementsByTagName("td");
    var sumqty = 0;
    var sumcost = 0;
    for (var i = 0; i < tds.length; i++) {
    if (tds[i].className == "qty") {
        sumqty += isNaN(tds[i].innerHTML) ? 0 : parseFloat(tds[i].innerHTML);
    }else if(tds[i].className == "cost"){
        sumcost += isNaN(tds[i].innerHTML) ? 0 : parseFloat(tds[i].innerHTML);
    }
    }
    document.getElementById("item-sourcing").innerHTML +=
    "<tr style='text-align:center'><td colspan=8><strong>TOTAL</strong></td><td><strong>" +
    thousands_separators(sumcost.toFixed(2)) +
    "</strong></td><td><strong>" +
                         sumqty +
    "</strong></td></tr>";
    
</script>
@endpush