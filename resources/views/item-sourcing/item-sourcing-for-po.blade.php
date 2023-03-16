@extends('crudbooster::admin_template')
    @push('head')
        <style type="text/css">   
            #other-detail th, td {
            border: 1px solid rgba(000, 0, 0, .5);
            padding: 8px;
            }
            #item-sourcing-options th, td {
            border: 1px solid rgba(000, 0, 0, .5);
            padding: 8px;
            }
         
            .finput {
                border:none;
                /* border-bottom: 1px solid rgba(18, 17, 17, 0.5); */
            }
            .alink {
                border:none;
                /* border-bottom: 1px solid rgba(18, 17, 17, 0.5); */
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
            #add-Row{
                border:none;
                background-color: #fff;
            }
            .icon{
                background-color: #3c8dbc: 
            }
            
            .icon:before {
                content: '';
                display: flex;
                justify-content: center;
                align-items: center;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                /* border: 1px solid rgb(194, 193, 193); */
                font-size: 30px;
                color: white;
                background-color: #3c8dbc;
       
            }
            #bigplus:before {
                content: '\FF0B';
                background-color: #3c8dbc: 
                font-size: 30px;
            }
            #bigplus:hover{
                cursor: default;
                transform: rotate(180deg);
                transition: all 0.3s ease-in-out 0s;
               
            }

            table { border-collapse: collapse; empty-cells: show; }

            td { position: relative; }

            tr.strikeout td:before {
            content: " ";
            position: absolute;
            top: 50%;
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

    <form method='post' id="myform" action='{{CRUDBooster::mainpath('edit-save/'.$Header->requestid)}}' enctype="multipart/form-data">
        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
        <input type="hidden" value="0" name="action" id="action">
        <input type="hidden" value="" name="button_action" id="button_action">
        <input type="hidden" value="{{$Header->requestid}}" name="headerID" id="headerID">

        <input type="hidden" value="{{$countOptions}}" name="countRow" id="countRow">

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
                    <table class="table" id="item-sourcing-options">
                        <tbody id="bodyTable">    
                            <tr>
                                <th class="text-center">Option</th> 
                                <th class="text-center">Vendor Name</th>
                                <th class="text-center">Price</th> 
                                <th class="text-center">File</th> 
                                <th width="5%" class="text-center"><i class="fa fa-trash"></i></th>
                            </tr>       
                                <?php   $tableRow = 1; ?>
                                @foreach($item_options as $res)
                                <?php   $tableRow1++; ?>
                                    @if($res->deleted_at != null || $res->deleted_at != "")
                                      <tr class="strikeout" style="background-color: #dd4b39; color:#fff">                                    
                                        <td style="text-align:center" height="10">
                                            {{$res->options}}                               
                                        </td>
                                        <td style="text-align:center" height="10">
                                            {{$res->vendor_name}}                               
                                        </td>
                                        <td style="text-align:center" height="10">
                                            {{$res->price}}                               
                                        </td>
                                        <td style="text-align:center" height="10">
                                            {{$res->file_name}}                               
                                        </td>
                                        
                                            <td  style="text-align:center; color:#fff"><i class="fa fa-times-circle"></i></td>                               
                                        
                                    </tr>
                                   @else
                                    <tr id="tr-tableOption">                                    
                                        <td style="text-align:center" height="10">
                                            <input type="hidden"  class="form-control"  name="opt_id" id="opt_id"  required  value="{{$res->optId}}" readonly>  
                                            {{$res->options}}                               
                                        </td>
                                        <td style="text-align:center" height="10">
                                            {{$res->vendor_name}}                               
                                        </td>
                                        <td style="text-align:center" height="10">
                                            {{$res->price}}                               
                                        </td>
                                        <td style="text-align:center" height="10">
                                            <a  href='{{CRUDBooster::adminpath("item_sourcing_for_quotation/download/".$res->file_id)."?return_url=".urlencode(Request::fullUrl())}}' class="form-control alink">{{$res->file_name}}   <i style="color:#007bff" class="fa fa-download"></i></a>                             
                                        </td>
                                        <td>
                                            {{-- <button id="deleteRow" name="removeRow" data-id="' + tableRow + '" class="btn btn-danger removeRow"><i class="glyphicon glyphicon-trash"></i></button> --}}
                                        
                                        </td>
                                    </tr>
                                   @endif
                                @endforeach                             
                         
                        
                        </tbody>
                        <tfoot>
                            <tr id="tr-tableOption1" class="bottom">
                                <td colspan="5">
                                    <button id="add-Row" name="add-Row"><div class="icon" id="bigplus"></div></button>
                                    <div id="display_error" style="text-align:left"></div>
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

        <div id="myModal" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><strong>Input PO</strong></h4>
                       
                    </div>
                    <div class="modal-body">
                       <div class='row'>
                         <div class='col-md-12'>
                          <input type"text" class="form-control" name="po_no"  id="po_no" placeholder="Please input PO">
                         </div>
                         <br>	
                       </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type='button' id="closed" class="btn btn-primary btn-sm">
                         <i class="fa fa-save"></i> Close
                         </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class='panel-footer'>

            <a href="{{ CRUDBooster::mainpath() }}" id="btn-cancel" class="btn btn-default">{{ trans('message.form.back') }}</a>
            {{-- <button class="btn btn-success pull-right" type="button" id="btnClose" style="margin-left: 5px;"><i class="fa fa-times-circle" ></i> Close</button> --}}
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
        var deleteRow = $('#countRow').val();
        var rowCount = $('#item-sourcing-options tr').length-2-deleteRow;
        if(rowCount > 3){
              $('#add-Row').prop("disabled", true)
        }else{
            $('#add-Row').prop("disabled", false)
        }
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

    $('#status').change(function(){
        var status =  this.value;
       if(status == 13){
         $("#myModal").modal('show');	
       }else{
        $("#myModal").modal('hide');
       }
    });
    $('#myModal').on('hidden.bs.modal', function () {
      location.reload();
    });

    //Add Row
    $("#add-Row").click(function() {
        var count_fail = 0;
        tableRow++;
        var deleteRow = $('#countRow').val();
        var rowCount = $('#item-sourcing-options tr').length - 1 - deleteRow;
    
        if(count_fail == 0){
            if(rowCount > 3){
              $('#add-Row').prop("disabled", true);
              $('#display_error').html("<span id='notif' class='label label-danger'> More than 3 options not allowed!</span>")
            }else{
                $('#add-Row').prop("disabled", false);
                $('#display_error').html("");
                var newrow =
                '<tr>' +

                    '<td >' +
                    '<input type="text" placeholder="Option..." onkeyup="this.value = this.value.toUpperCase();" class="form-control finput" data-id="' + tableRow + '" id="option' + tableRow + '"  name="option[]"  required maxlength="100" style="width:100%">' +
                    '</td>' +  

                    '<td>' +
                    '<input class="form-control finput" type="text" onkeyup="this.value = this.value.toUpperCase();" placeholder="Vendor Name..." name="vendor_name[]" id="vendor_name' + tableRow + '" data-id="' + tableRow  + '" style="width:100%">' + 
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
                $('#item-sourcing-options tbody').append(newrow);
        }

      
    }

    });

    //deleteRow
    $(document).on('click', '.removeRow', function() {

        if ($('#asset-items tbody tr').length != 1) { //check if not the first row then delete the other rows
            tableRow--;

            $(this).closest('tr').remove();
            var deleteRow = $('#countRow').val();
            var rowCount = $('#item-sourcing-options tr').length-1-deleteRow;
            if(rowCount > 3){
            $('#add-Row').prop("disabled", true)
            }else{
            $('#add-Row').prop("disabled", false)
            $('#display_error').html("");
            }
            return false;
        }
    });
  
    var stack = [];
    var token = $("#token").val();
   
    $('#btnUpdate').click(function(event) {
        event.preventDefault(); // cancel default behavior
        var rowCount = $('#item-sourcing-options tr').length-1;
        if(rowCount == 1) {
        swal({
            type: 'error',
            title: 'Please add an item!',
            icon: 'error',
            confirmButtonColor: "#367fa9",
        }); 
        event.preventDefault(); // cancel default behavior
        return false;
        }else{
            var opt = $("input[name^='option']").length;
            var opt_value = $("input[name^='option']");
            for(i=0;i<opt;i++){
                if(opt_value.eq(i).val() == 0 || opt_value.eq(i).val() == null){
                    swal({  
                            type: 'error',
                            title: 'Option Fields cannot be empty!(put N/A if not available)',
                            icon: 'error',
                            confirmButtonColor: "#367fa9",
                        });
                        event.preventDefault();
                        return false;
                } 
        
            } 
            var vendor = $("input[name^='vendor_name']").length;
            var vendor_value = $("input[name^='vendor_name']");
            for(i=0;i<vendor;i++){
                if(vendor_value.eq(i).val() == 0 || vendor_value.eq(i).val() == null){
                    swal({  
                            type: 'error',
                            title: 'Vendor Name Fields cannot be empty!(put N/A if not available)',
                            icon: 'error',
                            confirmButtonColor: "#367fa9",
                        });
                        event.preventDefault();
                        return false;
                } 
        
            } 
            var price = $("input[name^='price']").length;
            var price_value = $("input[name^='price']");
            for(i=0;i<price;i++){
                if(price_value.eq(i).val() == 0 || price_value.eq(i).val() == null){
                    swal({  
                            type: 'error',
                            title: 'Price Fields cannot be empty!(put N/A if not available)',
                            icon: 'error',
                            confirmButtonColor: "#367fa9",
                        });
                        event.preventDefault();
                        return false;
                } 
        
            } 
            var optionFile = $("input[name^='optionFile']").length;
            var optionFile_value = $("input[name^='optionFile']");
            for(i=0;i<optionFile;i++){
                if(optionFile_value.eq(i).val() == 0 || optionFile_value.eq(i).val() == null){
                    swal({  
                            type: 'error',
                            title: 'File Fields cannot be empty!',
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
                confirmButtonText: "Yes, update it!",
                width: 450,
                height: 200
                }, function () {
                    $(this).attr('disabled','disabled');
                    $('#button_action').val('1');
                    $("#myform").submit();                   
            });
        }
    });


    $('#closed').click(function(event) {
      
        event.preventDefault();
        var rowCount = $('#item-sourcing-options tr').length-1;

        if(rowCount == 1) {
            swal({
                type: 'error',
                title: 'Please add an item!',
                icon: 'error',
                confirmButtonColor: "#367fa9",
            }); 
            event.preventDefault(); // cancel default behavior
        }else if($('#po_no').val() == "") {
            swal({
                type: 'error',
                title: 'PO No required!',
                icon: 'error',
                confirmButtonColor: "#367fa9",
            }); 
            event.preventDefault(); // cancel default behavior
        }else{
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
                    $('#button_action').val('0');
                    $("#myform").submit();                   
            });
        }
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