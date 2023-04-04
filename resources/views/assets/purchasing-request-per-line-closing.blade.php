@extends('crudbooster::admin_template')
@push('head')
    <style type="text/css">   
    .mo_so_num {
    border-top-style: hidden;
    border-right-style: hidden;
    border-left-style: hidden;
    border-bottom-style: hidden;
    background-color: #eee;
    
    }

    .no-outline:focus {
    outline: none;
    }
    input.mo_so_num:read-only {
        background-color: #fff;
        text-align: center; 
    }

    table, th, td {
        border: 1px solid rgba(000, 0, 0, .5);
        padding: 8px;
        border-radius: 5px 0 0 5px;
    }
    #asset-items1 th, td, tr {
        border: 1px solid rgba(000, 0, 0, .5);
        padding: 8px;
    }
    /* .finput {
        border:none;
        border-bottom: 1px solid rgba(18, 17, 17, 0.5);
    } */
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

    <form method='post' id="myform" action='{{CRUDBooster::mainpath('add-save/'.$Header->requestid)}}'>
        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
        <input type="hidden" value="0" name="action" id="action">

        <!-- Modal -->
        <div class="modal fade" id="search-items" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Item Search</h4>
                </div>
                
                    <div class="modal-body">
                            <div class='callout callout-info'>
                                    <h5>SEARCH FOR <label id="item_search"></label></h5>
                            </div>
                
            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">{{ trans('message.form-label.add_item1') }}</label>
                                        <input class="form-control auto" style="width:100%;" placeholder="Search Item" id="search">
                                        <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="ui-id-2" style="display: none; top: 60px; left: 15px; width: 420px;">
                                            <li>Loading...</li>
                                        </ul>
                                    </div>
                                </div>
                            </div> 
                            
                    </div>
                    <div class="modal-footer">
                        
                        <!-- <input type="submit" class="btn btn-success" id="upload-excel1" value="Upload Excel"> -->
                        <button type="button" class="btn btn-default" id="upload-close1" data-dismiss="modal">Close</button>
                    </div>
        
                
                </div>
            </div>
        </div>

        <div class='panel-body'>

            <div class="row">

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label require">{{ trans('message.form-label.po_number') }}</label>
                        <input type="text" class="form-control finput"  id="po_number" name="po_number"   value="{{$Header->po_number}}" readonly>      
         
                        <p style="font: italic bold 12px/30px arial, arial;">Type N/A if not applicable</p>                         
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">

                   

                        <label class="control-label require">{{ trans('message.form-label.po_date') }}</label>
                        <div class="input-group date">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <!-- <input type='input' name='po_date' id="po_date" value="{{$Header->po_date}}"  onkeydown="return false"   autocomplete="off"  class='form-control' placeholder="yyyy-mm-dd" />    -->
                            <input type="text" class="form-control finput date" name="po_date" id="po_date" value="{{$Header->po_date}}" readonly>
                        </div>
                        <p style="font: italic bold 12px/30px arial, arial;">Type N/A if not applicable</p> 
                    </div>

                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label require">{{ trans('message.form-label.quote_date') }}</label>
                        <div class="input-group date">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            
                            <!-- <input type='input' name='quote_date' id="quote_date" value="{{$Header->quote_date}}" onkeydown="return false"   autocomplete="off"  class='form-control' placeholder="yyyy-mm-dd" /> --> 
                            
                            <input type="text" class="form-control finput date" name="quote_date" id="quote_date" value="{{$Header->quote_date}}" readonly>

                          </div>
                          <p style="font: italic bold 12px/30px arial, arial;">Type N/A if not applicable</p> 
                    </div>

                </div>

            </div>
 
            <hr/>

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
                        <p>{{$Header->employee_name}}</p>
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

            @if($Header->if_from_erf != null || $Header->if_from_erf != "")
                <div class="row">                           
                    <label class="control-label col-md-2">Erf Number:</label>
                    <div class="col-md-4">
                            <p>{{$Header->if_from_erf}}</p>
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
                                <table id="asset-items1">
                                    <tbody id="bodyTable">
                                        <tr class="tbl_header_color dynamicRows">

                                            <!--<th width="5%" class="text-center">{{ trans('message.table.action') }}</th>-->
                                            <th width="15%" class="text-center">Digits Code</th>
                                            <th width="15%" class="text-center">{{ trans('message.table.item_description') }}</th>
                                            <th width="9%" class="text-center">{{ trans('message.table.category_id_text') }}</th>                                                         
                                            <th width="10%" class="text-center">{{ trans('message.table.sub_category_id_text') }}</th> 
                                            <th width="5%" class="text-center">{{ trans('message.table.quantity_text') }}</th> 
                                           
                                            {{-- <th width="5%" class="text-center">For Replenish Qty</th> 
                                            <th width="5%" class="text-center">For Re Order Qty</th> 
                                            <th width="5%" class="text-center">UnServe Qty</th>  --}}

                                            <th width="7%" class="text-center">Item Cost</th> 
                                            <th width="7%" class="text-center">Total Cost</th>                                                                                                                                            
                                            <th width="10%" class="text-center">MO/SO</th>    
                                            <th width="5%" class="text-center">Serve Qty</th> 
                                            @if($Header->recommendedby != null || $Header->recommendedby != "")
                                                <th width="13%" class="text-center">{{ trans('message.table.recommendation_text') }}</th> 
                                                <th width="14%" class="text-center">{{ trans('message.table.reco_digits_code_text_mo') }}</th> 
                                                <th width="24%" class="text-center">{{ trans('message.table.reco_item_description_text_mo') }}</th>
                                            @endif 
                                        </tr>
                                        

                                        <tr id="tr-table">
                                                    <?php   $tableRow = 1; ?>
                                            <tr>
                                            <input type="hidden"  class="form-control"  name="header_id" id="header_id" value="{{$Header->requestid}}">
                                                @foreach($Body as $rowresult)

                                                    <?php   $tableRow++; ?>

                                                    <tr>
                                                        <td style="text-align:center" height="10">                                    
                                                             {{$rowresult->digits_code}} 
                                                        </td>
                                                        <td style="text-align:center" height="10">
                                                                <input type="hidden"  class="form-control"  name="ids[]" id="ids{{$tableRow}}"  required  value="{{$rowresult->id}}">
                                                                {{-- <input type="text"  class="form-control mo_so_num" value="{{$rowresult->item_description}}" readonly> --}}
                                                                 {{$rowresult->item_description}} 
                                                        </td>

                                                        <td style="text-align:center" height="10">
                                                        <input type="text"  class="form-control mo_so_num" value="{{$rowresult->category_id}}" readonly>
                                                                <!-- {{$rowresult->category_id}} -->
                                                        </td>

                                                        <td style="text-align:center" height="10">
                                                        <input type="text"  class="form-control mo_so_num" value="{{$rowresult->sub_category_id}}" readonly>
                                                            <!-- {{$rowresult->sub_category_id}} -->
                                                            
                                                        </td>

                                                        <td style="text-align:center" height="10" class="qty">
                                                            <input type="text"  class="form-control mo_so_num" name="quantity{{$tableRow}}" value="{{$rowresult->quantity}}" id="quantity{{$tableRow}}" readonly>
                                                                    <!-- {{$rowresult->quantity}} -->
                                                        </td>

                                                        {{-- <td style="text-align:center">{{$rowresult->replenish_qty ? $rowresult->replenish_qty : 0}}</td>  
                                                        <td style="text-align:center">{{$rowresult->reorder_qty ? $rowresult->reorder_qty : 0}}</td>                                                              
                                                        <td style="text-align:center">{{$rowresult->unserved_qty ? $rowresult->unserved_qty : 0}}</td> --}}

                                                        <td style="text-align:center" height="10">{{$rowresult->unit_cost}}</td>
                                                        <td style="text-align:center" height="10" class="cost">{{$rowresult->unit_cost * $rowresult->serve_qty}}</td>
                                                      
                                                        <td style="text-align:center" height="10">
                                                            <input type="text"  class="form-control mo_so_num"  name="mo_so_num[]" id="mo_so_num{{$tableRow}}" value="{{$rowresult->mo_so_num}}" readonly>
                                                            <input type="hidden"  class="form-control"  name="default_val[]" id="default_val{{$tableRow}}" value="{{$rowresult->mo_so_num}}" readonly>
                                                        </td>
                                                        <td style="text-align:center" height="10">
                                                            <input type="text" style="text-align:center" class="form-control finput reserve_qty"  name="reserve_qty[]" id="reserve_qty{{$tableRow}}" value="{{$rowresult->quantity}}" data-id="{{$tableRow}}">
                                                            <div id="display_error{{$tableRow}}" style="text-align:left"></div>
                                                        </td>                       
                                                                                                                
                                                        @if($Header->recommendedby != null || $Header->recommendedby != "")
                                                        
                                                            <td>
                                                                @if($rowresult->to_reco == 1)
                                                                    <select class="js-example-basic-single recodropdown" style="width: 100%; height: 35px;" required name="recommendation[]" id="recommendation" data-id="{{$tableRow}}">
                                                                        <option value="">-- Select Recommendation --</option>
                            
                                                                        @foreach($recommendations as $datas)    
                                                                            @if($rowresult->recommendation == $datas->user_type)
                                                                                <option  value="{{$datas->user_type}}" selected>{{$datas->user_type}}</option>
                                                                            @else
                                                                                <option  value="{{$datas->user_type}}">{{$datas->user_type}}</option>
                                                                            @endif
                                                                        @endforeach
                            
                                                                    </select>
                                                                @else
                                                                    <select class="js-example-basic-single recodropdown" style="width: 100%; height: 35px;"  name="recommendation[]" id="recommendation" data-id="{{$tableRow}}" disabled>
                                                                        <option value="">-- Select Recommendation --</option>
                            
                                                                        @foreach($recommendations as $datas)    
                                                                            @if($rowresult->recommendation == $datas->user_type)
                                                                                <option  value="{{$datas->user_type}}" selected>{{$datas->user_type}}</option>
                                                                            @else
                                                                                <option  value="{{$datas->user_type}}">{{$datas->user_type}}</option>
                                                                            @endif
                                                                        @endforeach
                            
                                                                    </select>
                                                                @endif

                                                            </td>
                                                            
                                                            <td>
                                                                    <div class="form-group">
                                                                        <input class="form-control auto" type="text" style="width: 100%;" placeholder="Search Item" id="search{{$tableRow}}" data-id="{{$tableRow}}"  name="reco_digits_code[]" value="{{$rowresult->reco_digits_code}}">
                                                                        <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" data-id="{{$tableRow}}" id="ui-id-2{{$tableRow}}" style="display: none; top: 60px; left: 15px; width: 100%;">
                                                                            <li>Loading...</li>
                                                                        </ul>
                                                                    </div>
                                                            </td>

                                                            <td>
                                                                <input type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control itemDesc" data-id="{{$tableRow}}" id="item_description{{$tableRow}}"  name="reco_item_description[]" maxlength="100" readonly value="{{$rowresult->reco_item_description}}">
                                                            </td>

                                                        @endif

                                                    </tr>

                                                @endforeach
                            
                                            </tr>
                                        </tr>
                                    
                                    </tbody>

                                    <tfoot>
                                    <tr>
                                        <td colspan='4' style='text-align:right'>
                                        <strong>TOTAL</strong>
                                        </td>
                                        <td style='text-align:center'>
                                        <strong>
                                        @foreach($bodyTotal as $total_qty)       
                                                {{$total_qty->quantity}}
                                            @endforeach
                                        </strong>
                                        </td>
                                        </tr>
                                    </tfoot>

                                </table>
                                
                            </div>
                        </div>                  
                    </div>
                </div>
            </div>

            <hr>
            @if( $Header->processedby != null )
                <div class="row">
                    <div class="col-md-6">
                        <table style="width:100%">
                            <tbody>
                                <tr>
                                    <th class="control-label col-md-2">{{ trans('message.form-label.po_number') }}:</th>
                                    <td class="col-md-4">{{$Header->po_number}}</td>     
                                </tr>

                                <tr>
                                    <th class="control-label col-md-2">{{ trans('message.form-label.po_date') }}:</th>
                                    <td class="col-md-4">{{$Header->po_date}}</td>
                                </tr>

                                <tr>
                                    <th class="control-label col-md-2">{{ trans('message.form-label.quote_date') }}:</th>
                                    <td class="col-md-4">{{$Header->quote_date}}</td>
                                </tr>
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
            @endif
        </div>

        <div class='panel-footer'>
            <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
            <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> Close</button>
            <!-- <button class="btn btn-warning pull-right" type="submit" id="btnPrint" style="margin-right: 10px;"> <i class="fa fa-print" ></i> {{ trans('message.form.print') }}</button> -->
             <!-- <button class="btn btn-warning pull-right" type="submit" id="btnUpdate" style="margin-right: 10px;"> <i class="fa fa-circle-o" ></i> {{ trans('message.form.update') }}</button>  -->
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


    $("#btnSubmit").click(function(event) {
       event.preventDefault();
        //Each Warranty Coverage Validation
        var w = $("input[name^='mo_so_num']").length;
        var mo_so_num = $("input[name^='mo_so_num']");
        for(i=0;i<w;i++){
            if(mo_so_num.eq(i).val() === ""){
            swal({
                    type: 'error',
                    title: 'MO/SO required!',
                    icon: 'error',
                    customClass: 'swal-wide'
                });
                event.preventDefault();
                return false;
            }else{
                swal({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#41B314",
                    cancelButtonColor: "#F9354C",
                    confirmButtonText: "Yes, close it!",
                    width: 450,
                    height: 200
                    }, function () {
                        $("#myform").submit();                      
                });
            }
        
        }

    });
    var count_pick = 0;
    //reserve quantity
     //fill current and redeem field on amount change
     var searchcount = <?php echo json_encode($tableRow); ?>;

        let countrow = 1;

        $(function(){

        for (let i = 0; i < searchcount; i++) {
            countrow++;
        $('#reserve_qty'+countrow).on("keyup", function() {
            var other_id = $(this).attr("data-id");
            var value =  this.value;
            var text = "OUT OF STOCK";
            var orig_val = $("#default_val"+$(this).attr("data-id")).val();
            var quantity = parseFloat($("#quantity"+$(this).attr("data-id")).val());
            var reserve_qty = parseFloat($("#reserve_qty"+$(this).attr("data-id")).val());
        
            if(value <= 0){
                $("#mo_so_num"+$(this).attr("data-id")).val(text);
                //$("#mo_so_num"+countrow).val(text).trigger('change');
            }else{
                $("#mo_so_num"+$(this).attr("data-id")).val(orig_val);
            }

            if(value > quantity){
                $('#btnSubmit').attr('disabled','disabled');
                $('#display_error'+$(this).attr("data-id")).html("<span id='notif' class='label label-danger'> Serve Quantity Exceed!</span>")
            }else{
                $('#btnSubmit').removeAttr('disabled');
                $('#display_error'+$(this).attr("data-id")).html('')
            }

        });
        }
    });

        // var tds = document
        // .getElementById("asset-items1")
        // .getElementsByTagName("td");
        // var sumqty = 0;
     
        // for (var i = 0; i < tds.length; i++) {
        //     console.log(tds[i].innerHTML);
        // if (tds[i].className == "qty") {
        //     sumqty += isNaN(tds[i].innerHTML) ? 0 : parseFloat(tds[i].innerHTML);
        // }
        // }
        // document.getElementById("asset-items1").innerHTML +=
        // "<tr><td colspan='4' style='text-align:right'><strong>TOTAL</strong></td><td style='text-align:center'><strong>" +
        // sumqty +
        // "</strong></td></tr>";
</script>
@endpush