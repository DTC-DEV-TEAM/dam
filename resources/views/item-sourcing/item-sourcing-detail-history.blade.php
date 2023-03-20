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
    <form method="post">
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
                @if($versions->version != null)
                <label class="control-label col-md-2">Version:</label>
                <div class="col-md-4">
                        <a type="button" value="{{$Header->requestid}}" id="getVersions"><strong>{{$versions->version}}</strong></a>
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
                    <table class="table" id="item-sourcing-options">
                        <tbody id="bodyTable">
                        <tr>
                            <th class="text-center">Option</th> 
                            <th class="text-center">Vendor Name</th>
                            <th class="text-center">Price</th> 
                            <th class="text-center">Quotation</th> 
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
                                             {{number_format($res->price, 2, '.', ',')}}                               
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
                                             {{number_format($res->price, 2, '.', ',')}}                               
                                        </td>
                                        <td style="text-align:center" height="10">
                                            <a  href='{{CRUDBooster::adminpath("item_sourcing_for_quotation/download/".$res->file_id)."?return_url=".urlencode(Request::fullUrl())}}' class="form-control alink">{{$res->file_name}}   <i style="color:#007bff" class="fa fa-download"></i></a>                             
                                        </td>
                                        <td>
                                           
                                        
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
     
        </div>

        <div class='panel-footer'>

            <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.back') }}</a>

        </div>
</form>
</div>

  {{-- Modal Edi Version --}}
  @include('item-sourcing.modal-edit-version')

@endsection
@push('bottom')
<script type="text/javascript">
    $(function(){
        item_source_value();
        $('body').addClass("sidebar-collapse");
    });
    function preventBack() {
        window.history.forward();
    }
    window.onunload = function() {
        null;
    };
    setTimeout("preventBack()", 0);

    //Get Edit Verions
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
                    $('#appendVersions').append(
                        '<tr>' +
                            '<tr>' +
                    
                                '<td colspan="4" style="background-color:#3c8dbc; color:white; font-weight:bold">' + item.version + '</td>' +
                            '</tr>' +

                            '<tr>' +
                                '<th style="padding-top:25px" rowspan="2">Description</th>' +
                                '<th colspan="2">' + 'From' + '</th>' +
                                '<th colspan="2">' + 'To' + '</th>' +
                            '</tr>' +

                            '<tr>'  +
                                '<td colspan="2">' + item.old_description + '</td>' +
                                '<td colspan="2">' + item.new_description + '</td>' +
                            '</tr>' +

                            '<tr>' +
                                '<th style="padding-top:25px" rowspan="2">Brand</th>' +
                                '<th colspan="2">' + 'From' + '</th>' +
                                '<th colspan="2">' + 'To' + '</th>' +
                            '</tr>' +
                            '<tr>'  +
                                '<td colspan="2">' + item.old_brand_value + '</td>' +
                                '<td colspan="2">' + item.new_brand_value + '</td>' +
                            '</tr>' +

                            
                            '<tr>' +
                                '<th style="padding-top:25px" rowspan="2">Model</th>' +
                                '<th colspan="2">' + 'From' + '</th>' +
                                '<th colspan="2">' + 'To' + '</th>' +
                            '</tr>' +
                            '<tr>'  +
                                '<td colspan="2">' + item.old_model_value + '</td>' +
                                '<td colspan="2">' + item.new_model_value + '</td>' +
                            '</tr>' +

                            '<tr>' +
                                '<th style="padding-top:25px" rowspan="2">Size</th>' +
                                '<th colspan="2">' + 'From' + '</th>' +
                                '<th colspan="2">' + 'To' + '</th>' +
                            '</tr>' +
                            '<tr>'  +
                                '<td colspan="2">' + item.old_size_value + '</td>' +
                                '<td colspan="2">' + item.new_size_value + '</td>' +
                            '</tr>' +

                            
                            '<tr>' +
                                '<th style="padding-top:25px" rowspan="2">Actual Color</th>' +
                                '<th colspan="2">' + 'From' + '</th>' +
                                '<th colspan="2">' + 'To' + '</th>' +
                            '</tr>' +
                            '<tr>'  +
                                '<td colspan="2">' + item.old_ac_value + '</td>' +
                                '<td colspan="2">' + item.new_ac_value + '</td>' +
                            '</tr>' +

                            '<tr>' +
                                '<th style="padding-top:25px" rowspan="2">Quantity</th>' +
                                '<th colspan="2">' + 'From' + '</th>' +
                                '<th colspan="2">' + 'To' + '</th>' +
                            '</tr>' +
                            '<tr>'  +
                                '<td colspan="2">' + item.old_qty_value + '</td>' +
                                '<td colspan="2">' + item.new_qty_value + '</td>' +
                            '</tr>' +

                            '<tr>' +
                                '<th>Updated Date</th>' +
                                '<td colspan="3">' + item.updated_at + '</td>' +
                            '</tr>' +

                            '<tr>' +
                                '<th>Updated By</th>' +
                                '<td colspan="3">' + item.name + '</td>' +
                            '</tr>' +
                        '</tr>'
                        );
                });
            }
         
        });
        $('#versionModal').modal('show'); 
       
    });

    $('#versionModal').on('hidden.bs.modal', function () {
      location.reload();
    });

   
    var token = $("#token").val();
    $('.chat').scrollTop($('.chat')[0].scrollHeight);
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
        "<td colspan=9><strong>TOTAL</strong></td>"+
        "<td><strong>" +
            sumqty + 
        "</strong></td>"+
                                        
        "</span></strong></td>"+
        "</tr>";
    
</script>
@endpush