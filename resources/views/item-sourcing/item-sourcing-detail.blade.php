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
                border-bottom: 1px solid rgba(18, 17, 17, 0.5);
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

            .modal .modal-body {
                overflow-y: auto;
                padding: 10px;
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
    <form method='post' id="myform" name="myform">
        
        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
        <input type="hidden" value="0" name="action" id="action">

        <input type="hidden" value="{{$Header->requestid}}" name="headerID" id="headerID">

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
                @if($versions->version != null)
                    <label class="control-label col-md-2">Version:</label>
                    <div class="col-md-4">
                            <a type="button" value="{{$Header->requestid}}" id="getVersions">{{$versions->version}}</a>
                    </div>
                @endif
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
                                                <tr>
                                                    @foreach($Body as $rowresult)                                                                                                    
                                                        <tr>
                                                            <input type="hidden"  class="form-control"  name="id" id="id"  required  value="{{$rowresult->body_id}}" readonly>        
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
                                                                <input type="text"  class="form-control finput"  name="item_description" id="item_description" value="{{$rowresult->item_description}}" data-id="{{$tableRow1}}"  required>  
                                                            </td>
                                                            <td style="text-align:center" height="10">                                                             
                                                                <input type="text"  class="form-control finput"  name="brand" id="brand" value="{{$rowresult->brand}}" data-id="{{$tableRow1}}"  required>  
                                                            </td>
                                                            <td style="text-align:center" height="10">                                                             
                                                                <input type="text"  class="form-control finput"  name="model" id="model" value="{{$rowresult->model}}" data-id="{{$tableRow1}}"  required>  
                                                            </td>
                                                            <td style="text-align:center" height="10">                                                             
                                                                <input type="text"  class="form-control finput"  name="size" id="size" value="{{$rowresult->size}}" data-id="{{$tableRow1}}"  required>  
                                                            </td>
                                                            <td style="text-align:center" height="10">                                                             
                                                                <input type="text"  class="form-control finput"  name="actual_color" id="actual_color" value="{{$rowresult->actual_color}}" data-id="{{$tableRow1}}"  required>  
                                                            </td>
                                                            <td style="text-align:center" height="10" class="qty">
                                                                <input type="text"  class="form-control finput"  name="quantity" id="quantity" value="{{$rowresult->quantity}}" data-id="{{$tableRow1}}"  required>  
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

            <hr>
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <table class="table" id="item-sourcing-options">
                        <tr>
                            <th class="text-center">Option</th> 
                            <th class="text-center">Vendor Name</th>
                            <th class="text-center">Price</th> 
                            <th class="text-center">Quotation</th> 
                            <th width="5%" class="text-center"><i class="fa fa-trash"></i></th>
                        </tr>  
                        <tbody id="bodyTable">
                                                    
                                <?php   $tableRow = 1; ?>
                                @foreach($item_options as $res)
                                <?php   $tableRow1++; ?>
                                    @if($res->deleted_at != null || $res->deleted_at != "")
                                    <input type="hidden"  class="form-control"  name="opt_id" id="opt_id"  required  value="{{$res->optId}}" readonly>  
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
                                        
                                            <td  style="text-align:center; color:white"><i class="fa fa-times-circle"></i></td>                               
                                        
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
                                            <button id="deleteRow" name="removeRow" data-id="' + tableRow + '" class="btn btn-danger removeRow" value="{{$res->optId}}"><i class="glyphicon glyphicon-trash"></i></button>
                                        
                                        </td>
                                    </tr>
                                   @endif
                                @endforeach                              
                         
                        
                        </tbody>
                    </table>
                </div>   
            </div>
            <hr>

            <br>

                <div class="row">
                    @include('item-sourcing.comments',['comments'=>$comments])
                    @include('item-sourcing.other_detail',['Header'=>$Header])
     
                </div>

        <div class='panel-footer'>

            <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.back') }}</a>
            <button class="btn btn-primary pull-right" type="button" id="btnSubmit"><i class="fa fa-plus-circle" ></i> Edit</button>
        </div>
    </form>
</div>
            <!-- Modal Versions Details -->
            <div class="modal fade modal" id="versionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                    <div class="modal-header">
                   
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    </div>
                    <div class="modal-body">
                        <h4 class="modal-title text-center" id="event-title">Edit Logs</h4>
                        <div class="row" style="padding:20px">             
                            <table class="table table-bordered overlay" style="overflow-x: scroll;">
                                <thead>
                                    <tr>
                                        <th>From Description</th>
                                        <th>To Description</th>
                                        <th>From Brand</th>
                                        <th>To Brand</th>
                                        <th>From Model</th>
                                        <th>To Model</th>
                                        <th>From Size</th>
                                        <th>To Size</th>
                                        <th>From Actual Color</th>
                                        <th>To Actual Color</th>
                                        <th>From Quantity</th>
                                        <th>To Quantity</th>
                                        <th>Version</th>
                                        <th>Update Date</th>
                                        <th>Update By</th>
                                    </tr>
                                </thead>
                                <tbody id="appendVersions">
                        
                                </tbody>
                            </table>                           
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                    </div>
                </div>
                </div>

@endsection
@push('bottom')
<script type="text/javascript">
    $(function(){
        $('body').addClass("sidebar-collapse");
        item_source_value();
      
        var w = $("input[name^='if_arf_created']").length;
        var if_arf_created = $("input[name^='if_arf_created']");
        for(i=0;i<w;i++){
            if(if_arf_created.eq(i).val() == 1){
                $('#btnSubmit').hide();
            }else{
                $('#btnSubmit').show();
            }
        }
    });
    
    $('.chat').scrollTop($('.chat')[0].scrollHeight);
    
    
    function preventBack() {
        window.history.forward();
    }
    window.onunload = function() {
        null;
    };
    setTimeout("preventBack()", 0);
    var token = $("#token").val();
     function item_source_value(){
        var total = 0;
        $('.item_source_value').each(function(){
            total += $(this).val() === "" ? 0 : parseFloat($(this).val());
        })

        $('#item-source-value-total').text(thousands_separators(total.toFixed(2)));
    }

    //GET VERSION
    $('#getVersions').click(function(evennt) {
        event.preventDefault();
        var header_id = $('#headerID').val();
        $.ajax({
            url: "{{ route('get-versions') }}",
            type: "GET",
            dataType: 'json',

            data: {
                "_token": token,
                "header_id" : header_id
            },
            success: function (data) {
                $.each(data, function(i, item) {
                    $('#appendVersions').append('<tr>'+

                                                '<td>' + item.old_description + '</td>' +
                                                '<td>' + item.new_description + '</td>' +
                                                '<td>' + item.old_brand_value + '</td>' +
                                                '<td>' + item.new_brand_value + '</td>' +
                                                '<td>' + item.old_model_value + '</td>' +
                                                '<td>' + item.new_model_value + '</td>' +
                                                '<td>' + item.old_size_value + '</td>' +
                                                '<td>' + item.new_size_value + '</td>' +
                                                '<td>' + item.old_ac_value + '</td>' +
                                                '<td>' + item.new_ac_value + '</td>' +
                                                '<td>' + item.old_qty_value + '</td>' +
                                                '<td>' + item.new_qty_value + '</td>' +
                                                '<td>' + item.version + '</td>' +
                                                '<td>' + item.updated_at + '</td>' +
                                                '<td>' + item.name + '</td>' +

                                                '</tr>');
                });
            }
         
        });
        $('#versionModal').modal('show'); 
       
    });

    $("#message").keypress(function(event) {
        if (event.which == '13') {
            $('#btnChat').click();
        }
    });

    //Chat
    $('#btnChat').click(function(event) {
        event.preventDefault();
        var header_id = $('#headerID').val();
        var message = $('#message').val();
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
                    $('.body-comment').append('<span class="session-comment"> ' +
                                        '<p><span class="comment">'+data.message.comments +'</span> </p>'+
                                        '<p style="text-align:right; font-size:12px; font-style: italic; padding-right:5px;"> '+ new Date(data.message.created_at) +'</p></span>');
                    $('#message').val('');
                }
                var interval = setInterval(function() {
                $('.chat').scrollTop($('.chat')[0].scrollHeight);
                },200);
            }
        }); 
    });

   
    
    $('#btnSubmit').click(function() {
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#41B314",
            cancelButtonColor: "#F9354C",
            confirmButtonText: "Yes, send it!",
            width: 450,
            height: 200
            }, function () {
                $.ajax({
                    url: "{{ route('edit-item-source') }}",
                    type: "POST",
                    dataType: 'json',
                    data: $('#myform').serialize(),
                    success: function (data) {
                        if (data.status == "success") {
                            swal({
                                type: data.status,
                                title: data.message,
                            });
                            setTimeout(function(){
                                location.reload();
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
        

    });

    var tableRow = <?php echo json_encode($tableRow); ?>;
    tableRow ++;
    $(document).ready(function() {
            $(document).on('click', '.removeRow', function() {
              
                event.preventDefault();
                var id_data = $(this).val();    
   
                if ($('#asset-items1 tbody tr').length != 1) { //check if not the first row then delete the other rows
            
                // item_id = $("#ids"+id_data).val();
                // $("#bodyID").val(item_id);
                var data = $('#myform').serialize();
                var data_id = id_data;
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
                            url:  '{{ url('admin/item-sourcing-header/RemoveItemSource') }}',
                            type: "GET",
                            data: { opt_id: data_id},
                            dataType: 'json',
                            success: function(data){    
                                if (data.status == "success") {
                                    swal({
                                        type: data.status,
                                        title: data.message,
                                    });
                                    setTimeout(function(){
                                        location.reload();
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
                    $(this).closest('tr').addClass("strikeout" );
                    $(this).closest('tr').css('color','black'); 
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
    function thousands_separators(num) {
    var num_parts = num.toString().split(".");
    num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return num_parts.join(".");
    }
    
</script>
@endpush