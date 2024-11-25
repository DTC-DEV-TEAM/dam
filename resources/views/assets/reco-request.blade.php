@extends('crudbooster::admin_template')
    @push('head')
        <style type="text/css">   
            #footer th, td {
                border: 1px solid rgba(000, 0, 0, .5);
                padding: 8px;
                border-radius: 5px 0 0 5px;
            }
            #asset-items th, td, tr {
                border: 1px solid rgba(000, 0, 0, .5);
                padding: 8px;
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
        Request Form
    </div>

    <form method='post' id="myform" action='{{CRUDBooster::mainpath('edit-save/'.$Header->requestid)}}'>
        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
       

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

            @if($Header->store_branch != null || $Header->store_branch != "")
                <div class="row">                           
                    <label class="control-label col-md-2">{{ trans('message.form-label.store_branch') }}:</label>
                    <div class="col-md-4">
                            <p>{{$Header->store_branch}}</p>
                    </div>
                </div>
            @endif
            <input type="hidden" name="from_erf" value="{{$Header->if_from_erf}}">
            @if($Header->if_from_erf != null || $Header->if_from_erf != "")
                <div class="row">                           
                    <label class="control-label col-md-2">Erf Number:</label>
                    <div class="col-md-4">
                            <p>{{$Header->if_from_erf}}</p>
                    </div>
                </div>
            @endif

            @if($Header->if_from_item_source != null || $Header->if_from_item_source != "")
                <div class="row">                           
                    <label class="control-label col-md-2">Item Sourcing Number:</label>
                    <div class="col-md-4">
                            <p>{{$Header->if_from_item_source}}</p>
                          
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
            <!--
            <hr/>

            <div class="row">                           
                <label class="control-label col-md-2">{{ trans('message.form-label.condition') }}:</label>
                <div class="col-md-4">
                        <p>{{$Header->condition_description}}</p>
                </div>

        
            </div> -->

            @if($Header->requestor_comments != null || $Header->requestor_comments != "")
                <hr/>
                <div class="row">                           
                    <label class="control-label col-md-2">{{ trans('message.table.requestor_comments') }}:</label>
                    <div class="col-md-10">
                            <p>{{$Header->requestor_comments}}</p>
                    </div>

            
                </div>
            @endif  

            @if($Header->approvedby != null || $Header->approvedby != "")
            <hr/>

            <div class="row">                           
                <label class="control-label col-md-2">{{ trans('message.form-label.approved_by') }}:</label>
                <div class="col-md-4">
                        <p>{{$Header->approvedby}} / <strong>{{$Header->approved_at}}</strong></p>
                </div>
                @if($Header->approver_comments != null || $Header->approver_comments != "")          
                    <label class="control-label col-md-2">{{ trans('message.table.approver_comments') }}:</label>
                    <div class="col-md-4">
                            <p>{{$Header->approver_comments}}</p>
                    </div>
                @endif 
            </div>
            @endif  

            <hr/>                      

            <div class="row">
                <div class="col-md-12">
                    <div class="box-header text-center">
                        <h3 class="box-title"><b>{{ trans('message.form-label.asset_reco') }}</b></h3>
                    </div>
                    <div class="box-body no-padding">
                        <div class="pic-container">
                            <div class="pic-row">
                                <table id="asset-items">
                                    <tbody id="bodyTable">
                                        <tr class="tbl_header_color dynamicRows">
                                            <th width="10%" class="text-center">{{ trans('message.table.digits_code') }}</th>
                                            <th width="20%" class="text-center">{{ trans('message.table.item_description') }}</th>
                                            <th width="9%" class="text-center">{{ trans('message.table.category_id_text') }}</th>                                                         
                                            <th width="14%" class="text-center">{{ trans('message.table.sub_category_id_text') }}</th> 
                                            <th width="5%" class="text-center">{{ trans('message.table.quantity_text') }}</th> 
                                            <th width="12%" class="text-center">{{ trans('message.table.recommendation_text') }}</th> 
                                            <th width="10%" class="text-center">{{ trans('message.table.reco_digits_code_text') }}</th> 
                                            <th width="24%" class="text-center">{{ trans('message.table.reco_item_description_text') }}</th> 
                                        </tr>
                                                       
                                        <tr id="tr-table">
                                                    <?php   $tableRow = 1; ?>
                                            <tr>

                                                @foreach($Body as $rowresult)

                                                    <?php   $tableRow++; ?>

                                                    <tr>
                                                        <td style="text-align:center">                                                                                                                       
                                                                {{$rowresult->digits_code}}
                                                        </td>
                                                        <td style="text-align:center">                                                                                
                                                                <input type="hidden"  class="form-control"  name="item_id[]" id="item_id{{$tableRow}}"  required  value="{{$rowresult->id}}">
                                                                {{$rowresult->item_description}}
                                                        </td>
                                                        <td style="text-align:center">
                                                                {{$rowresult->category_id}}
                                                        </td>
                                                        <td style="text-align:center">
                                                                
                                                                {{$rowresult->sub_category_id}}

                                                                <!--{{$rowresult->app_id}}
                                
                                                                @if($rowresult->app_id_others != null || $rowresult->app_id_others != "" )
                                                                    <br>
                                                                    {{$rowresult->app_id_others}}
                                                                @endif -->
                                                        
                                                        </td>
                                                        <td style="text-align:center">
                                                                {{$rowresult->quantity}}
                                                        </td>
                                                        
                                                        <td>
                                                            @if($rowresult->to_reco == 1)
                                                                <select class="js-example-basic-single recodropdown" style="width: 100%; height: 35px;"  name="recommendation[]" id="recommendation" data-id="{{$tableRow}}" required>
                                                                    <option value="">-- Select Recommendation --</option>
                        
                                                                    @foreach($recommendations as $datas)    
                                                                        <option  value="{{$datas->user_type}}">{{$datas->user_type}}</option>
                                                                    @endforeach
                        
                                                                </select>
                                                            @else
                                                            <input type="text" class="form-control finput" data-id="{{$tableRow}}" id="recommendation{{$tableRow}}" value="NOT APPLICABLE"  name="recommendation[]"  readonly>
                                                                <!-- <select class="js-example-basic-single Notrecodropdown" style="width: 100%; height: 35px;"  name="recommendation[]" id="recommendation" data-id="{{$tableRow}}" disabled>
                                                                    <option value="">-- Select Recommendation --</option>
                        
                                                                    @foreach($recommendations as $datas)    
                                                                        <option  value="0">{{$datas->user_type}}</option>
                                                                    @endforeach
                        
                                                                </select> -->
                                                            @endif


                                                            
                                                        <td>
                                                                <div class="form-group">
                                                                    <input class="form-control finput auto" type="text" style="width: 100%; margin-top:15px" placeholder="Search Item" id="search{{$tableRow}}" data-id="{{$tableRow}}"  name="reco_digits_code[]">
                                                                    <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" data-id="{{$tableRow}}" id="ui-id-2{{$tableRow}}" style="display: none; top: 60px; left: 15px; width: 100%;">
                                                                        <li>Loading...</li>
                                                                    </ul>
                                                                </div>
                                                        </td>

                                                        <td>
                                                            <input type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control finput itemDesc" data-id="{{$tableRow}}" id="item_description{{$tableRow}}"  name="reco_item_description[]"   maxlength="100" readonly>
                                                        </td>

                                                    </tr>

                                                @endforeach
                            
                                            </tr>
                                        </tr>
                                    
                                    </tbody>

                                    <tfoot>

                                        <tr id="tr-table1" class="bottom">

                                            <td colspan="4">
                                                <!-- <input type="button" id="add-Row" name="add-Row" class="btn btn-info add" value='Add Item' /> -->
                                            </td> 
                                            <td align="center" colspan="1">
                                                
                                                <label>{{$Header->quantity_total}}</label>

                                            </td>
                                        </tr>
                                    </tfoot>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
              
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

                <div class="col-md-12">
                    <hr/>
                    <div class="form-group">
                        <label>{{ trans('message.table.note') }}</label>
                        <textarea placeholder="{{ trans('message.table.comments') }} ..." rows="3" class="form-control finput" name="it_comments" id="it_comments"></textarea>
                    </div>
                </div>
         
            </div>

        </div>


        <div class='panel-footer'>
            <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
           
            <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> {{ trans('message.form.new') }}</button>
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

    var tableRow = <?php echo json_encode($tableRow); ?>;
    
    var tableRow1 = tableRow;

    tableRow1++;


    $('.recodropdown').change(function(){

        if( this.value.length > 1){
            
            $('#search'+$(this).attr("data-id")).attr('disabled', false);

        }else{
            $('#search'+$(this).attr("data-id")).attr('disabled', true);
            $('#search'+$(this).attr("data-id")).val('');
            $('#item_description'+$(this).attr("data-id")).val('');
        }

    });

    $(document).ready(function() {



        $(".add-row-button").click(function() {

            var buttonNo = $(this).attr("data-id");

            var itemVal = $("#item_id"+buttonNo).val();

            tableRow++;
            
            var newrow =
            '<br/>' +
 
        
            '<tr>' +
                
                '<td >' +
                '<div id="divreco'+ tableRow + '">' +  
                '<input type="hidden"  class="form-control"  name="add_item_id[]" id="add_item_id'+ tableRow + '"  required  value="'+ itemVal +'">' +
                '<input type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control Reco" data-id="'+ tableRow + '" id="recommendation_add'+ tableRow + '"  name="recommendation_add[]"  required maxlength="100">' +
                '</div >' +
                '</td>' +  

            '</tr>';
            $(newrow).insertBefore($('table tr#tr-table-reco'+ buttonNo + ':last'));
         
            var newrow1 =
            '<br/>' +
            '<tr>' +   
                '<td >' +
                '<div id="div'+ tableRow + '">' +  
          
                '<button id="delete-row-button'+ tableRow + '" name="delete-row-button" class="btn btn-danger delete-row-button'+ tableRow + ' removeRow" data-id="'+ tableRow + '" ><i class="glyphicon glyphicon-trash"></i></button>' +
                '</div >' +
                '</td>' +  
            '</tr>';
            $(newrow1).insertBefore($('table tr#tr-table-reco-delete'+ buttonNo + ':last'));

            return false;

        });

        //deleteRow
        $(document).on('click', '.removeRow', function() {

            var buttonNo = $(this).attr("data-id");
            
            
            $('#div'+buttonNo).remove();

            $('#divreco'+buttonNo).remove();

            return false;
            
        });





        var stack = [];
        var token = $("#token").val();
        var searchcount = <?php echo json_encode($tableRow); ?>;

        let countrow = 1;

            $(function(){

                for (let i = 0; i < searchcount; i++) {
                    countrow++;
                    
                    //$('#search'+countrow).attr('disabled', true);


                    $("#search"+countrow).autocomplete({

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
                                    $('#ui-id-2'+countrow).css('display', 'none');

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
                                    $("#ui-id-2"+countrow).append($("<li class='addedLi'>").text(data.message));
                                    var searchVal = $("#search"+countrow).val();
                                    if (searchVal.length > 0) {
                                        $("#ui-id-2"+countrow).css('display', 'block');
                                    } else {
                                        $("#ui-id-2"+countrow).css('display', 'none');
                                    }
                                }
                            }
                        })
                        },
                        select: function (event, ui) {
                            var e = ui.item;

                            if (e.id) {
                      
                               

                                $("#item_description"+$(this).attr("data-id")).val(e.value);

                                $("#search"+$(this).attr("data-id")).val(e.digits_code);
                                $("#search"+$(this).attr("data-id")).attr('readonly',true);
                                $('#val_item').html('');
                                return false;
    
                            }
                        },

                        minLength: 1,
                        autoFocus: true
                        });


                }


            });
       
    });


    $('#btnSubmit').click(function(event) {
        // var strconfirm = confirm("Are you sure you want to reco this request?");
        // if (strconfirm == true) {
        //     $(this).attr('disabled','disabled');
        //     $('#myform').submit(); 
        // }else{
        //     return false;
        //     window.stop();
        // }
        event.preventDefault();
        var reco = $(".recodropdown option").length;
        var reco_value = $('.recodropdown').find(":selected");
        for(i=0;i<reco;i++){
            if(reco_value.eq(i).val() == ""){
                swal({  
                        type: 'error',
                        title: 'Please select Laptop/Desktop Type!',
                        icon: 'error',
                        confirmButtonColor: "#367fa9",
                    });
                    event.preventDefault();
                    return false;
            } 
    
        } 
        if($('#it_comments').val() == ""){
            swal({  
                    type: 'error',
                    title: 'Required Additional Notes',
                    icon: 'error',
                    confirmButtonColor: "#367fa9",
                });
                event.preventDefault();
                return false;
        } 
        swal({
            title: "Are you sure you want to reco this request?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#41B314",
            cancelButtonColor: "#F9354C",
            confirmButtonText: "Yes, submit it!",
            width: 450,
            height: 200
            }, function () {
                $(this).attr('disabled','disabled');
                $("#myform").submit();                   
        });

    });

</script>
@endpush