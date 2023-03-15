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
        
            .plus{
                font-size:20px;
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
                <label class="control-label col-md-2">Status:</label>
                <div class="col-md-4">
                    <select required selected data-placeholder="-- Please Select ERF --" id="status" name="status" class="form-select erf" style="width:100%;">
                        @foreach($statuses as $res)
                        <option value="{{ $res->id }}"
                            {{ isset($Header->status_id) && $Header->status_id == $res->id ? 'selected' : '' }}>
                            {{ $res->status_description }} 
                        </option>>
                        @endforeach
                    </select>
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
                                                <th width="12%" class="text-center">Category</th> 
                                                <th width="12%" class="text-center">Sub Category</th>
                                                <th width="12%" class="text-center">Class</th> 
                                                <th width="12%" class="text-center">Sub Class</th> 
                                                <th width="12%" class="text-center">{{ trans('message.table.item_description') }}</th>   
                                                <th width="7%" class="text-center">Brand</th> 
                                                <th width="7%" class="text-center">Model</th>  
                                                <th width="7%" class="text-center">Size</th> 
                                                <th width="7%" class="text-center">Actual Color</th>     
                                                <th width="2%" class="text-center">Quantity</th>                                                                                                                
                                                <th width="10%" class="text-center">Budget</th> 
                                            </tr>
                                            <tr id="tr-table">
                                                <?php   $tableRow = 1; ?>
                                                <tr> 
                                                    @foreach($Body as $rowresult)
                                                    <tr>
                                                            <input type="hidden"  class="form-control"  name="ids[]" id="ids{{$tableRow}}"  required  value="{{$rowresult->id}}" readonly>        
                                                            <td style="text-align:center" height="10">
                                                                {{$rowresult->category_description}}                               
                                                            </td>
                                                            <td style="text-align:center" height="10">
                                                                {{$rowresult->sub_category_description}}                              
                                                            </td>
                                                            <td style="text-align:center" height="10">
                                                                {{$rowresult->class_description}}                               
                                                            </td>
                                                            <td style="text-align:center" height="10">
                                                                {{$rowresult->sub_class_description}}                               
                                                            </td>
                                                                                                
                                                            <td style="text-align:center" height="10">                                                             
                                                                {{$rowresult->item_description}} 
                                                            </td>
                                                            <td style="text-align:center" height="10">                                                             
                                                                {{$rowresult->brand}} 
                                                            </td>
                                                            <td style="text-align:center" height="10">                                                             
                                                                {{$rowresult->model}} 
                                                            </td>
                                                            <td style="text-align:center" height="10">                                                             
                                                                {{$rowresult->size}} 
                                                            </td>
                                                            <td style="text-align:center" height="10">                                                             
                                                                {{$rowresult->actual_color}}  
                                                            </td>
                                                            <td style="text-align:center" height="10" class="qty">
                                                                {{$rowresult->quantity}} 
                                                            </td>     
                                                            <td style="text-align:center" height="10" class="cost">
                                                                    {{$rowresult->budget}}
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
                <div class="col-md-8 col-md-offset-2">
                    <table class="table table-bordered" id="item-sourcing-options">
                        <tbody id="bodyTable">
                            <tr id="tr-tableOption">
                                <tr>
                
                                </tr>
                            </tr>
                        
                        </tbody>
                        <tfoot>
                            <tr id="tr-tableOption1" class="bottom">
                                <td colspan="4">
                                    <button type="button" id="add-Row" name="add-Row" class="btn btn-success"><i class="fa fa-plus-circle plus"></i></button>
                                </td>
                            </tr>
                        </tfoot>

                    </table>
                </div>   
            </div>
            <hr>

            <div class="row">
                @include('item-sourcing.comments',['comments'=>$comments])
                @include('item-sourcing.other_detail',['Header'=>$Header])
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
        item_source_value();
    });
    function preventBack() {
        window.history.forward();
    }
    window.onunload = function() {
        null;
    };
    $('.chat').scrollTop($('.chat')[0].scrollHeight);
    $('#status').select2({})
    setTimeout("preventBack()", 0);
    var searchcount = <?php echo json_encode($tableRow); ?>;
    let countrow = 1;
    var tableRow = 1;
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

    //Chat
    $('#btnChat').click(function() {
        event.preventDefault();
        var header_id = $('#headerID').val();
        var message = $('#message').val();
        if ($('#message').val() === "") {
            swal({
                type: 'error',
                title: 'Message Required',
                icon: 'error',
                confirmButtonColor: "#367fa9",
            }); 
            event.preventDefault(); // cancel default behavior
        }else{
            $.ajax({
                url: "{{ route('save-message') }}",
                type: "POST",
                dataType: 'json',

                data: {
                    "_token": token,
                    "header_id" : header_id,
                    "message": message,
                },
                success: function (data) {
                    if (data.status == "success") {
                        
                        $('.new-body-comment').append('<strong style="margin-left:10px"> '+ data.comment_by + '</strong><span class="text-comment"> ' +
                                            '<p><span class="comment">'+data.message.comments +'</span> </p>'+
                                            '<p style="text-align:right; font-size:12px; font-style: italic; padding-right:5px;"> '+ new Date(data.message.created_at) +'</p></span>');
                        $('#message').val('');
                    }
                }
                 
            });
           
        }
       
        
    });

    //Add Row
    $("#add-Row").click(function() {
        var count_fail = 0;
        tableRow++;
        if(count_fail == 0){
            var newrow =
            '<tr>' +

                '<td >' +
                '<input type="text" placeholder="Option..." onkeyup="this.value = this.value.toUpperCase();" class="form-control finput" data-id="' + tableRow + '" id="option' + tableRow + '"  name="option[]"  required maxlength="100" style="width:100%">' +
                '</td>' +  

                '<td>' +
                '<input class="form-control finput" type="text" placeholder="Vendor Name..." name="vendor_name[]" id="vendor_name' + tableRow + '" data-id="' + tableRow  + '" style="width:100%">' + 
                '</td>' +

                '<td>' +
                '<input class="form-control text-center finput" type="text" placeholder="Price..." name="price[]" id="price' + tableRow + '" data-id="' + tableRow  + '"  max="9999999999" step="any" onkeypress="return event.charCode <= 57" style="width:100%">' +
                '</td>' +

                '<td>' +
                '<input class="form-control finput" type="file" placeholder="File..." name="optionFile[]" id="optionFile' + tableRow + '" data-id="' + tableRow  + '" style="width:100%">' + 
                '</td>' +

                '<td>' +
                    '<button id="deleteRow" name="removeRow" data-id="' + tableRow + '" class="btn btn-danger removeRow"><i class="glyphicon glyphicon-trash"></i></button>' +
                '</td>' +

            '</tr>';
            

        $(newrow).insertBefore($('table tr#tr-tableOption:last'));
        var total_rows = $('#item-sourcing-options > tr').length;
        alert(total_rows);
        if(total_rows > 3){
            $('#add-Row').attr('disabled', true);
            
        }else{
            $('#add-Row').attr('disabled', false);
        }
    }

    });

    //deleteRow
    $(document).on('click', '.removeRow', function() {

        if ($('#asset-items tbody tr').length != 1) { //check if not the first row then delete the other rows
            tableRow--;

            $(this).closest('tr').remove();

            return false;
        }
    });
  
    var stack = [];
    var token = $("#token").val();
   
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
    // var tds = document
    // .getElementById("item-sourcing")
    // .getElementsByTagName("td");
    // var sumqty = 0;
    // var sumcost = 0;
    // for (var i = 0; i < tds.length; i++) {
    //     if (tds[i].className == "qty") {
    //         sumqty += isNaN(tds[i].innerHTML) ? 0 : parseFloat(tds[i].innerHTML);
    //     }else if(tds[i].className == "cost"){
    //         sumcost += isNaN(tds[i].innerHTML) ? 0 : parseFloat(tds[i].innerHTML);
    //     }
    // }
    // document.getElementById("item-sourcing").innerHTML +=
    // "<tr style='text-align:center'>"+
    // "<td colspan=10><strong>TOTAL</strong></td>"+
    // "<td><strong>" +
    //     sumqty + 
    // "</strong></td>"+
                                      
    // "<td style='text-align:center'><strong><span id='item-source-value-total'>" + 
   
    // "</span></strong></td>"+
    // "</tr>";
</script>
@endpush