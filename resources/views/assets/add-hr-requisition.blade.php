@extends('crudbooster::admin_template')
    @push('head')
        <style type="text/css">   

            .select2-selection__choice{
                    font-size:14px !important;
                    color:black !important;
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
        Asset Form
    </div>

    <form action="{{ CRUDBooster::mainpath('add-save') }}" method="POST" id="AssetRequest" enctype="multipart/form-data">
        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
        <input type="hidden" value="1" name="request_type_id" id="request_type_id">

        <div class='panel-body'>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label"><span style="color:red">*</span> {{ trans('message.form-label.employee_name') }}</label>
                        <input class="form-control auto" placeholder="Search Employee" id="search_employee">
                        <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="ui-id-2" style="display: none; top: 60px; left: 15px; width: 520px;">
                            <li>Loading...</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label require">{{ trans('message.form-label.company_name') }}</label>
                        <input type="text" class="form-control"  id="company" name="company"  required readonly>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label require">{{ trans('message.form-label.department') }}</label>
                        <input type="text" class="form-control"  id="department" name="department"  required readonly>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label require">{{ trans('message.form-label.position') }}</label>
                        <input type="text" class="form-control"  id="position" name="position"  required readonly>                                   
                    </div>
                </div>
            </div>

           
                <div class="row" id="div_store_branch">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label require">*{{ trans('message.form-label.store_branch') }}</label>
                            <select class="js-example-basic-single" style="width: 100%;" name="store_branch" id="store_branch">
                                <option value="">-- Select Store/Branch --</option>

                                @foreach($stores as $datas)    
                                    <option  value="{{$datas->id}}">{{$datas->bea_mo_store_name}}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                </div>
            <hr/>

            <div class="row"> 
                <label class="require control-label col-md-2">*{{ trans('message.form-label.purpose') }}</label>
                    @foreach($purposes as $data)
                        @if($data->id == 1)
                                    <div class="col-md-5">
                                        <label class="radio-inline control-label col-md-5" ><input type="radio" required   class="purpose" name="purpose" value="{{$data->id}}" >{{$data->request_description}}</label>
                                        <br>
                                    </div>
                            @else
                                    <div class="col-md-5">
                                        <label class="radio-inline control-label col-md-5"><input type="radio" required  class="purpose" name="purpose" value="{{$data->id}}" >{{$data->request_description}}</label>
                                        <br>
                                    </div>
                        @endif
                    @endforeach
            </div>
            <hr/>

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
                                                            <th width="30%" class="text-center">{{ trans('message.table.item_description') }}</th>
                                                            <th width="25%" class="text-center">{{ trans('message.table.category_id_text') }}</th>                                                         
                                                            <th width="20%" class="text-center">{{ trans('message.table.application_id_text') }}</th> 
                                                            <th width="7%" class="text-center">{{ trans('message.table.quantity_text') }}</th> 
                                                           <!-- <th width="8%" class="text-center">{{ trans('message.table.image') }}</th>  -->
                                                            <th width="5%" class="text-center">{{ trans('message.table.action') }}</th>
                                                        </tr>
                                                        
                                                        <tr id="tr-table">
                                                            <tr>
                                            
                                                            </tr>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr id="tr-table1" class="bottom">
            
                                                            <td colspan="3">
                                                                <input type="button" id="add-Row" name="add-Row" class="btn btn-info add" value='Add Item' />
                                                            </td>
                                                            <td align="left" colspan="1">
                                                                <input type='number' name="quantity_total" class="form-control text-center" id="quantity_total" readonly>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                
                                    </div>
                                    <br>
                                </div>
                </div>
          
                <div class="col-md-12">
                    <div class="form-group">
                        <label>{{ trans('message.table.note') }}</label>
                        <textarea placeholder="{{ trans('message.table.comments') }} ..." rows="3" class="form-control" name="requestor_comments"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class='panel-footer'>
            <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
            <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> {{ trans('message.form.save') }}</button>
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

        var tableRow = 1;

        $(document).ready(function() {
            $('.js-example-basic-single').select2();
            $('#div_store_branch').hide();
            $('#store_branch').removeAttr('required');

            $("#add-Row").click(function() {
                var description = "";
                var count_fail = 0;
                $('.itemDesc').each(function() {
                    description = $(this).val();
                    if (description == null) {
                        alert("Please fill Item Description !");
                        count_fail++;
                    } else if (description == "") {
                        alert("Please fill Item Description !");
                        count_fail++;
                    }else{
                        count_fail = 0;
                    }
                });

                tableRow++;

                if(count_fail == 0){
                    var newrow =
                    '<tr>' +
                        '<td >' +
                        '  <input type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control itemDesc" data-id="' + tableRow + '" id="itemDesc' + tableRow + '"  name="item_description[]"  required maxlength="100">' +
                        '</td>' +  

                        '<td>'+
                            '<select class="form-control drop'+ tableRow + '" name="category_id[]" data-id="' + tableRow + '" id="category_id' + tableRow + '" required>' +
                            //'  <option value="">- Select Category -</option>' +
                            '        @foreach($categories as $data)'+
                            '        <option value="{{$data->category_description}}">{{$data->category_description}}</option>'+
                            '         @endforeach'+
                            '</select></td>' +
                        '<td>'+
                            '<select class="js-example-basic-multiple" multiple="multiple" name="app_id' + tableRow + '[]" data-id="' + tableRow + '" id="app_id' + tableRow + '" required style="width:100%;">' +
                        
                            '        @foreach($applications as $data)'+
                            '        <option value="{{$data->app_name}}">{{$data->app_name}}</option>'+
                            '         @endforeach'+
                            '</select>'+
                            '<br/><br/><input type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control AppOthers" data-id="' + tableRow + '" id="AppOthers' + tableRow + '"  name="app_id_others[]" maxlength="100">' +
                        '</td>' +           
                        
                        '<td><input class="form-control text-center quantity_item" type="number" required name="quantity[]" id="quantity' + tableRow + '" data-id="' + tableRow  + '"  value="1" min="0" max="9999999999" step="any" onKeyPress="if(this.value.length==4) return false;" oninput="validity.valid||(value=0);"></td>' +
                        
                        /*'<td><input type="file" name="image[]" id="image' + tableRow + '" accept="image/*"></td>' + */
                        
                        '<td>' +
                            '<button id="deleteRow" name="removeRow" class="btn btn-danger removeRow"><i class="glyphicon glyphicon-trash"></i></button>' +
                        '</td>' +

                    '</tr>';
                    $(newrow).insertBefore($('table tr#tr-table1:last'));

                    $('#app_id'+tableRow).attr('disabled', true);

                    $('.js-example-basic-multiple').select2();

                    $('#app_id'+tableRow).change(function(){

                            /*var appothers = this.value;

                            alert(appothers);
                            if(appothers.includes("OTHERS")){
                                alert('');

                                $('#AppOthers'+$(this).attr("data-id")).show();
                                $('#AppOthers'+$(this).attr("data-id")).attr('required', 'required');

                            }else{

                                $('#AppOthers'+$(this).attr("data-id")).hide();
                                $('#AppOthers'+$(this).attr("data-id")).removeAttr('required');

                            }*/

                            if($('#app_id'+$(this).attr("data-id")).val() != null){
                                var arrx = $(this).val();
                                execute = 0;
                            }else{
                                var arrx = "";
                                execute++;
                            }
                            var s = arrx;

                            if(s.includes("OTHERS")){
                    
                                $('#AppOthers'+$(this).attr("data-id")).show();
                                $('#AppOthers'+$(this).attr("data-id")).attr('required', 'required');

                            }else{

                                $('#AppOthers'+$(this).attr("data-id")).hide();
                                $('#AppOthers'+$(this).attr("data-id")).removeAttr('required');

                            }

                    });

                    $(document).on('keyup', '#itemDesc'+tableRow, function(ev) {

                        var category =  $('#category_id'+$(this).attr("data-id")).val();
                        var description = this.value;

                        if(description.includes("LAPTOP") && category == "IT ASSETS"){
                        
                            // alert(description);

                            $('#app_id'+$(this).attr("data-id")).attr('disabled', false);

                        }else{
                        
                            $('#app_id'+$(this).attr("data-id")).attr('disabled', true);
                        }


                    });


                    $('#AppOthers'+tableRow).hide();
                    $('#AppOthers'+tableRow).removeAttr('required');

                    $('#category_id'+tableRow).change(function(){

                        var category = this.value;
                        var description = $('#itemDesc'+$(this).attr("data-id")).val();



                        if(description.includes("LAPTOP") && category == "IT ASSETS"){
                        
                        // alert(description);

                            $('#app_id'+$(this).attr("data-id")).attr('disabled', false);

                        }else{
                        
                            $('#app_id'+$(this).attr("data-id")).attr('disabled', true);
                        }

                    });


                    $("#quantity_total").val(calculateTotalQuantity());
                    
                }

            });
            
            //deleteRow
            $(document).on('click', '.removeRow', function() {
                if ($('#asset-items tbody tr').length != 1) { //check if not the first row then delete the other rows
               
                    $(this).closest('tr').remove();
                    $("#quantity_total").val(calculateTotalQuantity());
                    
                    //$("#tQuantity").val(calculateTotalQuantity());
                    //$("#tValue2").val(calculateTotalValue2());
                    return false;
                }
            });

        });

        var stack = [];
        var token = $("#token").val();

        $(document).ready(function(){
            $(function(){

            $('#search_employee').autocomplete({
                source: function (request, response) {
                $.ajax({
                    url: "{{ route('hr.search.user') }}",
                    dataType: "json",
                    type: "POST",
                    data: {
                        "_token": token,
                        "search": request.term
                    },
                    success: function (data) { 
                        if (data.status_no == 1) {
                            $("#val_item").html();
                            var data = data.items;
                            $('#ui-id-2'+tableRow).css('display', 'none');
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
                            $("#ui-id-2"+tableRow).append($("<li class='addedLi'>").text(data.message));
                            var searchVal = $('#itemDesc'+tableRow).val();
                            if (searchVal.length > 0) {
                                $("#ui-id-2"+tableRow).css('display', 'block');
                            } else {
                                $("#ui-id-2"+tableRow).css('display', 'none');
                            }
                        }
                    }
                })
                },
                select: function (event, ui) {
                    var e = ui.item;
                    if (e.id) {
                    
                        $("#digits_code"+$(this).attr("data-id")).val(e.digits_code);
                        $('#itemDesc'+$(this).attr("data-id")).val(e.value);
                        $('#val_item').html('');
                        return false;

                    }
                },
                minLength: 1,
                autoFocus: true
                });

            });
        });
        
    
        $(document).on('keyup', '.quantity_item', function(ev) {
            $("#quantity_total").val(calculateTotalQuantity());
        });

        $(document).on('keyup', '.cost_item', function(ev) {

                var id = $(this).attr("data-id");
                var rate = parseFloat($(this).val());
                var qty = $("#quantity" + id).val();
                var price = calculatePrice(qty, rate).toFixed(2); // this is for total Value in row

                $("#total_unit_cost" + id).val(price);
                $("#quantity_total").val(calculateTotalQuantity());
                $("#cost_total").val(calculateTotalValue());
                $("#total").val(calculateTotalValue2());

                var total_checker = $("#total").val();

        });

        function calculatePrice(qty, rate) {
            if (qty != 0) {
            var price = (qty * rate);
            return price;
            } else {
            return '0';
            }
        }

        function calculateTotalQuantity() {
            var totalQuantity = 0;
            $('.quantity_item').each(function() {

            totalQuantity += parseInt($(this).val());
            });
            return totalQuantity;
        }
   
        function calculateTotalValue() {
            var totalQuantity = 0;
            var newTotal = 0;
            $('.cost_item').each(function() {
            totalQuantity += parseFloat($(this).val());

            });
            newTotal = totalQuantity.toFixed(2);
            return newTotal;
        }

        function calculateTotalValue2() {
            var totalQuantity = 0;
            var newTotal = 0;
            $('.total_cost_item').each(function() {
            totalQuantity += parseFloat($(this).val());

            });
            newTotal = totalQuantity.toFixed(2);
            return newTotal;
        }

        $(document).ready(function() {
            $("#AssetRequest").submit(function() {
                $("#btnSubmit").attr("disabled", true);
                return true;
            });
        });

        $("#btnSubmit").click(function(event) {

            var countRow = $('#asset-items tfoot tr').length;
            // var value = $('.vvalue').val();

            if (countRow == 1) {

                alert("Please add an item!");
                event.preventDefault(); // cancel default behavior

            }

            var qty = 0;
            $('.quantity_item').each(function() {

                qty = $(this).val();
                if (qty == 0) {
                    alert("Quantity cannot be empty or zero!");
                    event.preventDefault(); // cancel default behavior
                } else if (qty < 0) {
                    alert("Negative Value is not allowed!");
                    event.preventDefault(); // cancel default behavior
                }

            });


            var description = "test";
            $('.itemDesc').each(function() {

                description = $(this).val();
                if (description == null) {
                    alert("Item Description cannot be empty!");
                    event.preventDefault(); // cancel default behavior
                } else if (description == "") {
                    alert("Item Description cannot be empty!");
                    event.preventDefault(); // cancel default behavior
                }
                
            });

        });

    </script>
@endpush