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
            .green-color {
                color:green;
                margin-top:12px;
            }

            table { border-collapse: collapse; empty-cells: show; }

            td { position: relative; }

            tr.strikeout td:before {
            content: " ";
            position: absolute;
            top: 25%;
            left: 0;
            border-bottom: 1px solid #111;
            width: 100%;
            }

            tr.strikeout td:after {
            content: "\00B7";
            font-size: 1px;
            }

            /* Extra styling */
            td { width: 100px; }
            th { text-align: left; }
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
        History Detail View History
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
            <div class="row">                          
                <label class="control-label col-md-2">Date Needed:</label>
                <div class="col-md-4">
                        <p>{{$Header->date_needed}}</p>
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
                                                <th width="2%" class="text-center"></th> 
                                                <th width="10%" class="text-center">Item Code</th> 
                                                <th width="10%" class="text-center">PO Number</th>
                                                <th width="10%" class="text-center">PO Date</th> 
                                                <th width="10%" class="text-center">Quote Date</th> 
                                                <th width="10%" class="text-center">Supplier</th> 
                                                <th width="10%" class="text-center">{{ trans('message.table.item_description') }}</th>
                                                <th width="9%" class="text-center">{{ trans('message.table.category_id_text') }}</th>                                                         
                                                <th width="10%" class="text-center">{{ trans('message.table.sub_category_id_text') }}</th> 
                                                <th width="5%" class="text-center">Budget</th> 
                                                <th width="10%" class="text-center">Value</th> 
                                                <th width="5%" class="text-center">Quantity</th>  
                                            </tr>
                                            <tr id="tr-table">
                                                <?php   $tableRow = 1; ?>
                                                <tr>
                                                    @foreach($Body as $rowresult)
                                                        <?php   $tableRow++; ?>
                                                        @if($rowresult->deleted_at != null || $rowresult->deleted_at != "")                                    
                                                        <tr class="strikeout">
                                                            <td >
                                                            @if($rowresult->digits_code != NULL && $rowresult->po_number != NULL && $rowresult->po_date != NULL && $rowresult->qoute_date != NULL && $rowresult->supplier != NULL && $rowresult->value != NULL && $rowresult->if_arf_created == NULL)
                                                                <input style="margin-top:10px" type="checkbox" name="body_id[]" id="body_id{{$tableRow}}" class="id" required data-id="{{$tableRow}}" value="{{$rowresult->id}}"/>
                                                            @elseif($rowresult->if_arf_created != NULL)
                                                            <i class="fa fa-check-circle green-color fa-lg" aria-hidden="true"></i>
                                                            @endif
                                                                <input type="hidden" name="request_type_id[]" id="request_type_id{{$tableRow}}" class="id" required data-id="{{$tableRow}}" value="{{$rowresult->request_type_id}}"/>
                                                                <input type="hidden" name="if_arf_created[]" id="if_arf_created" class="id" required data-id="{{$tableRow}}" value="{{$rowresult->if_arf_created}}"/>
                                                            </td>
                                                          
                                                            <input type="hidden"  class="form-control"  name="ids[]" id="ids{{$tableRow}}"  required  value="{{$rowresult->id}}" readonly>        
                                                            <td style="text-align:center" height="10">
                                                                    <input type="text"  class="form-control finput"  name="item_code[]" id="ids{{$tableRow}}" value="{{$rowresult->digits_code}}"  required readonly>                                
                                                            </td>
                                                            <td style="text-align:center" height="10">
                                                                    <input type="text"  class="form-control finput"  name="po_number[]" id="ids{{$tableRow}}" value="{{$rowresult->po_number}}" required readonly>                                
                                                            </td>
                                                            <td style="text-align:center" height="10">
                                                                    <input type="text"  class="form-control finput po_date{{$tableRow}}"  name="po_date[]" id="po_date{{$tableRow}}" value="{{$rowresult->po_date}}" data-id="{{$tableRow1}}"  required readonly>                                
                                                            </td>
                                                            <td style="text-align:center" height="10">
                                                                    <input type="text"  class="form-control finput qoute_date"  name="qoute_date[]" id="qoute_date{{$tableRow}}" value="{{$rowresult->qoute_date}}" data-id="{{$tableRow1}}"  required readonly>                                
                                                            </td>
                                                            <td style="text-align:center" height="10">
                                                                    <input type="text"  class="form-control finput"  name="supplier[]" id="ids{{$tableRow}}" value="{{$rowresult->supplier}}" required readonly>                                
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
                                                            <td style="text-align:center" height="10">
                                                                    {{$rowresult->budget}}
                                                            </td>
                                                            <td style="text-align:center" height="10">
                                                                    <input type="text" style="text-align:center" class="form-control finput item_source_value"  name="value[]" id="ids{{$tableRow}}" value="{{$rowresult->value}}" required readonly>                                
                                                            </td>
                                                            <td style="text-align:center" height="10">
                                                                    {{$rowresult->quantity}}
                                                          
                                                            </td>
                                                          
                                                            <td  style="text-align:center; color:#dd4b39"><i class="fa fa-times-circle"></i></td>                                                          
                                                        </tr>
                                                      @else                                    
                                                        <tr>                                                                     
                                                            <input type="hidden"  class="form-control"  name="ids[]" id="ids{{$tableRow}}"  required  value="{{$rowresult->id}}" readonly>        
                                                            <td style="text-align:center" height="10">
                                                                @if($rowresult->if_arf_created != NULL)
                                                                <i class="fa fa-check-circle green-color fa-lg" aria-hidden="true"></i>
                                                                @endif
                                                            </td>
                                                            <td style="text-align:center" height="10">
                                                                    <input type="text"  class="form-control finput"  name="item_code[]" id="ids{{$tableRow}}" value="{{$rowresult->digits_code}}"  required readonly>                                
                                                            </td>
                                                            <td style="text-align:center" height="10">
                                                                    <input type="text"  class="form-control finput"  name="po_number[]" id="ids{{$tableRow}}" value="{{$rowresult->po_number}}" required readonly>                                
                                                            </td>
                                                            <td style="text-align:center" height="10">
                                                                    <input type="text"  class="form-control finput po_date{{$tableRow}}"  name="po_date[]" id="po_date{{$tableRow}}" value="{{$rowresult->po_date}}" data-id="{{$tableRow1}}"  required readonly>                                
                                                            </td>
                                                            <td style="text-align:center" height="10">
                                                                    <input type="text"  class="form-control finput qoute_date"  name="qoute_date[]" id="qoute_date{{$tableRow}}" value="{{$rowresult->qoute_date}}" data-id="{{$tableRow1}}"  required readonly>                                
                                                            </td>
                                                            <td style="text-align:center" height="10">
                                                                    <input type="text"  class="form-control finput"  name="supplier[]" id="ids{{$tableRow}}" value="{{$rowresult->supplier}}" required readonly>                                
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
                                                            <td style="text-align:center" height="10">
                                                                    <input type="text"  class="form-control finput item_source_value"  name="value[]" id="ids{{$tableRow}}" value="{{$rowresult->value}}" required readonly>                                
                                                            </td>   
                                                            <td style="text-align:center" height="10" class="qty">
                                                                    {{$rowresult->quantity}}
                                                          
                                                            </td>      
                                                                                                               
                                                      </tr>
                                                      @endif                                                                   
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
                            @if( $Header->processedby != null )
                                <tr>
                                    <th class="control-label col-md-2">{{ trans('message.form-label.processed_by') }}:</th>
                                    <td class="col-md-4">{{$Header->processedby}} / {{$Header->purchased2_at}}</td>
                                </tr>
                            @endif
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

    $(function(){
        item_source_value();
    });
    
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


    function item_source_value(){
        var total = 0;
        $('.item_source_value').each(function(){
            total += $(this).val() === "" ? 0 : parseFloat($(this).val().trim().replace(/,/g, ''));
        })
    
        $('#item-source-value-total').text(thousands_separators(total.toFixed(2)));
    }


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
        "<tr style='text-align:center'>"+
        "<td colspan=10><strong>TOTAL</strong></td>"+
        "<td style='text-align:center'><strong><span id='item-source-value-total'>" + 
        "<td><strong>" +
            sumqty + 
        "</strong></td>"+
                                        
       
    
        "</span></strong></td>"+
        "</tr>";
    
</script>
@endpush