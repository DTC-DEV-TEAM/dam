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
                                                <th width="5%" class="text-center">Quantity</th> 
                                                <th width="10%" class="text-center">Value</th> 
                                            </tr>
                                            <tr id="tr-table">
                                                <?php   $tableRow = 1; ?>
                                                <tr> 
                                                    @foreach($Body as $rowresult)
                                                        <?php   $tableRow++; ?>
                                                        @if($rowresult->deleted_at != null || $rowresult->deleted_at != "")                                        
                                                        <tr class="strikeout">
                                                            <td style="text-align:center" height="10">
                                                                @if($rowresult->if_arf_created != NULL)
                                                                <i class="fa fa-check-circle green-color fa-lg" aria-hidden="true"></i>
                                                                @endif
                                                            </td>
                                                          
                                                            <td style="text-align:center" height="10">
                                                                <input type="text"  class="form-control finput"value="{{$rowresult->digits_code}}"  readonly>                                                                                                        
                                                            </td>
                                                           
                                                            <td style="text-align:center" height="10">
                                                                    <input type="text"  class="form-control finput" value="{{$rowresult->po_number}}" readonly>                                
                                                            </td>
                                                            <td style="text-align:center" height="10">
                                                                    <input type="text"  class="form-control finput" value="{{$rowresult->po_date}}" readonly>                                
                                                            </td>
                                                            <td style="text-align:center" height="10">
                                                                    <input type="text"  class="form-control finput" value="{{$rowresult->qoute_date}}" readonly>                                
                                                            </td>
                                                            <td style="text-align:center" height="10">
                                                                    <input type="text"  class="form-control finput" value="{{$rowresult->supplier}}" readonly>                                
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
                                                            <td  style="text-align:center; color:#dd4b39"><i class="fa fa-times-circle"></i></td>                                                   
                                                      </tr>
                                                      @else
                                                        <tr>
                                                            <td style="text-align:center" height="10">
                                                                @if($rowresult->if_arf_created != NULL)
                                                                <i class="fa fa-check-circle green-color fa-lg" aria-hidden="true"></i>
                                                                @endif
                                                            </td>
                                                            <input type="hidden"  class="form-control"  name="ids[]" id="ids{{$tableRow}}"  required  value="{{$rowresult->id}}">        
                                                            <td style="text-align:center" height="10">
                                                                <input type="text"  class="form-control finput digits_code"  name="item_code[]" id="digits_code{{$tableRow}}" data-id="{{$tableRow}}" value="{{$rowresult->digits_code}}" required >                                
                                                                <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" data-id="{{$tableRow}}" id="ui-id-2{{$tableRow}}" style="display: none; top: 60px; left: 15px; width: 100%;">
                                                                    <li>Loading...</li>
                                                                </ul>
                                                                <input type="hidden"  name="reco_item_description[]" id="reco_item_description{{$tableRow}}" data-id="{{$tableRow}}" value="{{$rowresult->reco_item_description}}">  
                                                            </td>
                                                        
                                                            <td style="text-align:center" height="10">
                                                                    <input type="text"  class="form-control finput"  name="po_number[]" id="po_number{{$tableRow}}" data-id="{{$tableRow}}" value="{{$rowresult->po_number}}" required >                                
                                                            </td>
                                                            <td style="text-align:center" height="10">
                                                                    <input type="text"  class="form-control finput po_date{{$tableRow}}"  name="po_date[]" id="po_date{{$tableRow}}" value="{{$rowresult->po_date}}" data-id="{{$tableRow}}"  required >                                
                                                            </td>
                                                            <td style="text-align:center" height="10">
                                                                    <input type="text"  class="form-control finput qoute_date"  name="qoute_date[]" id="qoute_date{{$tableRow}}" data-id="{{$tableRow}}" value="{{$rowresult->qoute_date}}" required >                                
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
                                                            <td style="text-align:center" height="10" class="value">
                                                                    <input type="text" style="text-align:center"  class="form-control finput item_source_value"  name="value[]" id="value{{$tableRow}}" value="{{$rowresult->value}}" onkeyup="item_source_value();" required >                                
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
<script src=
    "https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" >
        </script>
        
        <script src=
    "https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" >
        </script>
<script type="text/javascript">
    
    $(function(){
        $('body').addClass("sidebar-collapse");
        item_source_value();
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
            $('#po_date'+countrow).datepicker({
                constrainInput: false,  
                dateFormat: 'yy-mm-dd'
              
            });
            $('#qoute_date'+countrow).datepicker({
                constrainInput: false,  
                dateFormat: 'yy-mm-dd'
               
            });
            $('#po_date'+countrow).keyup(function() {
                    this.value = this.value.toLocaleUpperCase();
            });
            $('#qoute_date'+countrow).keyup(function() {
                    this.value = this.value.toLocaleUpperCase();
            });
             //cost fields validation
             $(document).on("keyup","#value"+countrow, function (e) {
                if (e.which >= 37 && e.which <= 40) return;
                        if (this.value.charAt(0) == ".") {
                            this.value = this.value.replace(
                            /\.(.*?)(\.+)/,
                            function (match, g1, g2) {
                                return "." + g1;
                            }
                            );
                        }
                        if (e.key == "." && this.value.split(".").length > 2) {
                            this.value =
                            this.value.replace(/([\d,]+)([\.]+.+)/, "$1") +
                            "." +
                            this.value.replace(/([\d,]+)([\.]+.+)/, "$2").replace(/\./g, "");
                            return;
                        }
                    $(this).val(function (index, value) {
                        value = value.replace(/[^-0-9.]+/g, "");
                        let parts = value.toString().split(".");
                        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        return parts.join(".");
                    });
            });  
            
        }
        
    });
  
    var stack = [];
    var token = $("#token").val();
    var tableRow1 = <?php echo json_encode($tableRow); ?>;
    let countrow1 = 1;
    $(function(){
            for (let j = 0; j < tableRow1; j++) {
                countrow1++;
                $("#digits_code"+countrow1).autocomplete({
                    source: function (request, response) {
                    $.ajax({
                        url: "{{ route('it.item.search') }}",
                        dataType: "json",
                        type: "POST",
                        data: {
                            "_token": token,
                            "search": request.term
                        },
                        success: function (data) {
                            //var rowCount = $('#asset-items tr').length;
                            //myStr = data.sample;   
                            if (data.status_no == 1) {
                                $("#val_item").html();
                                var data = data.items;
                                $('#ui-id-2'+countrow1).css('display', 'none');
                                response($.map(data, function (item) {
                                    return {
                                        id:                         item.id,
                                        asset_code:                 item.asset_code,
                                        digits_code:                item.digits_code,
                                        asset_tag:                  item.asset_tag,
                                        serial_no:                  item.serial_no,
                                        value:                      item.item_description,
                                        category_description:       item.category_description,
                                        item_cost:                  item.item_cost,
                                    
                                    }
                                }));
                            } else {
                                $('.ui-menu-item').remove();
                                $('.addedLi').remove();
                                $("#ui-id-2"+countrow1).append($("<li class='addedLi'>").text(data.message));
                                var searchVal = $("#search"+countrow1).val();
                                if (searchVal.length > 0) {
                                    $("#ui-id-2"+countrow1).css('display', 'block');
                                } else {
                                    $("#ui-id-2"+countrow1).css('display', 'none');
                                }
                            }
                        }
                    })
                    },
                    select: function (event, ui) {
                        var e = ui.item;
                        if (e.id) {
                            $("#digits_code"+$(this).attr("data-id")).val(e.digits_code);
                            $("#reco_item_description"+$(this).attr("data-id")).val(e.value);
                            return false;
                        }
                    },
                    minLength: 1,
                    autoFocus: true
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
        var item = $("input[name^='item_code']").length;
        var item_value = $("input[name^='item_code']");
        for(i=0;i<item;i++){
            if(item_value.eq(i).val() == 0 || item_value.eq(i).val() == null){
                swal({  
                        type: 'error',
                        title: 'Item Code Fields cannot be empty!(put N/A if not available)',
                        icon: 'error',
                        confirmButtonColor: "#367fa9",
                    });
                    event.preventDefault();
                    return false;
            } 
    
        } 
        var po = $("input[name^='po_number']").length;
        var po_value = $("input[name^='po_number']");
        for(i=0;i<po;i++){
            if(po_value.eq(i).val() == 0 || po_value.eq(i).val() == null){
                swal({  
                        type: 'error',
                        title: 'PO Number Fields cannot be empty!(put N/A if not available)',
                        icon: 'error',
                        confirmButtonColor: "#367fa9",
                    });
                    event.preventDefault();
                    return false;
            } 
    
        } 
        var po_date = $("input[name^='po_date']").length;
        var po_date_value = $("input[name^='po_date']");
        for(i=0;i<po_date;i++){
            if(po_date_value.eq(i).val() == 0 || po_date_value.eq(i).val() == null){
                swal({  
                        type: 'error',
                        title: 'PO Date Fields cannot be empty!(put N/A if not available)',
                        icon: 'error',
                        confirmButtonColor: "#367fa9",
                    });
                    event.preventDefault();
                    return false;
            } 
    
        } 
        var qoute_date = $("input[name^='qoute_date']").length;
        var qoute_date_value = $("input[name^='qoute_date']");
        for(i=0;i<qoute_date;i++){
            if(qoute_date_value.eq(i).val() == 0 || qoute_date_value.eq(i).val() == null){
                swal({  
                        type: 'error',
                        title: 'Quote Date Fields cannot be empty!(put N/A if not available)',
                        icon: 'error',
                        confirmButtonColor: "#367fa9",
                    });
                    event.preventDefault();
                    return false;
            } 
    
        } 
        var supplier = $("input[name^='supplier']").length;
        var supplier_value = $("input[name^='supplier']");
        for(i=0;i<supplier;i++){
            if(supplier_value.eq(i).val() == 0 || supplier_value.eq(i).val() == null){
                swal({  
                        type: 'error',
                        title: 'Supplier Fields cannot be empty!(put N/A if not available)',
                        icon: 'error',
                        confirmButtonColor: "#367fa9",
                    });
                    event.preventDefault();
                    return false;
            } 
    
        } 
        var value = $("input[name^='value']").length;
        var value_value = $("input[name^='value']");
        for(i=0;i<value;i++){
            if(value_value.eq(i).val() == null || value_value.eq(i).val() == 0 ){
                swal({  
                        type: 'error',
                        title: 'Value Fields cannot be empty',
                        icon: 'error',
                        confirmButtonColor: "#367fa9",
                    });
                    event.preventDefault();
                    return false;
            } 
    
        } 
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
    "<td><strong>" +
        sumqty + 
    "</strong></td>"+
                                      
    "<td style='text-align:center'><strong><span id='item-source-value-total'>" + 
   
    "</span></strong></td>"+
    "</tr>";
</script>
@endpush