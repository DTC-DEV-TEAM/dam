@extends('crudbooster::admin_template')
    @push('head')
    
        <style type="text/css">   
            /* loading spinner */
            .loading {
                z-index: 20;
                position: absolute;
                top: 0;
                bottom:0;
                left:0;
                width: 100%;
                height: 1500px;
                background-color: rgba(0,0,0,0.4);
            }
            .loading-content {
                position: absolute;
                border: 16px solid #f3f3f3; /* Light grey */
                border-top: 16px solid #3498db; /* Blue */
                border-radius: 50%;
                width: 50px;
                height: 50px;
                top: 40%;
                left:50%;
                bottom:0;
                /* margin-left: -4em; */
                animation: spin 2s linear infinite;
                }
                
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
            .comment_div {
                box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
                background: #f5f5f5;
                height: 300px;
                padding: 10px;
                overflow-y: scroll;
                word-wrap: break-word;
            }
            .text-comment{
                display: block;
                background: #fff;
                padding:5px;
                border-radius: 2px;
                box-shadow: rgba(0, 0, 0, 0.05) 0px 6px 24px 0px, rgba(0, 0, 0, 0.08) 0px 0px 0px 1px;
                margin-bottom:0;
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
    Edit Asset Inventory Form
    </div>

    <form action='{{CRUDBooster::mainpath('edit-save/'.$Body->id)}}' method="POST" id="EditInventoryForm" enctype="multipart/form-data">
        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
        <input type="hidden" value="{{$Body->id}}" name="request_type_id" id="request_type_id">
        <input type="hidden" value="{{$Body->digits_code}}" name="digits_code" id="digits_code">
        <input type="hidden" value="{{$Body->asset_code}}" name="asset_code" id="asset_code">

        <div class='panel-body'>
        <div class="row">     
         <div class="col-md-12">                      
            <label class="control-label col-md-2">Asset Code:</label>
            <div class="col-md-4">
                    <p>{{$Body->asset_code}}</p>
            </div>
            <label class="control-label col-md-2">Serial No:</label>
            <div class="col-md-4">
                    <p>{{$Body->serial_no}}</p>
            </div>
            <label class="control-label col-md-2">Digits Code:</label>
            <div class="col-md-4">
                    <p>{{$Body->digits_code}}</p>
            </div>
            <label class="control-label col-md-2">Item Description:</label>
            <div class="col-md-4">
                    <p>{{$Body->item_description}}</p>
            </div>
         </div>
        </div>
           <div class="row">
             <div class="col-md-12">
                     <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Item Condition</label>
                            <select required selected data-placeholder="-- Please Select Item Condition --" id="item_condition" name="item_condition" class="form-select select2" style="width:100%;">
                           
                            <option <?php if ($Body->item_condition == 'Good') echo 'selected'; ?>>Good</option>
                            <option <?php if ($Body->item_condition == 'Defective') echo 'selected'; ?>>Defective</option>
                                <!-- <option value="{{ $key }}" {{$suggestions_datas[0]->categories_id == $key ? "selected" : "" }}>{{ $value->item_condition }}</option> -->
                         
                            </select>
                        </div>
                       </div>
             </div>
            </div>
            <br>
            <!-- Comment Section -->
            <div class="row">  
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Comments:</label>
                        <div class="comment_div">
                            <span class="text-comment">
                            @foreach($comments as $comment)
                            <p style="margin-top:5px"><strong>{{ $comment->name }}:</strong>  {{ $comment->comments }} </p>
                            <p style="text-align:right; font-size:10px; font-style: italic; border-bottom:1px solid #d2d6de"> {{ $comment->created_at }} </p>
                            @endforeach
                            </span>
                        </div>
                        <br>
                        <select required selected data-placeholder="-- Please Select Defect Comments --" id="comments" name="comments" class="form-select select2" style="width:100%;">
                            <option value=""></option>
                            <option value="Screen light fails. ..">Screen light fails. ..</option>
                            <option value="Your computer does not turn on. ...">Your computer does not turn on. ...</option>
                            <option value="The screen is blank. ...">The screen is blank. ...</option>
                            <option value="Laptop shuts down or freezes. ...">Laptop shuts down or freezes. ...</option>
                            <option value="The battery does not charge properly. ...">The battery does not charge properly. ...</option>
                            <option value="Screen light fails. ...">Screen light fails. ...</option>
                            <option value="Freezing Of Mouse Cursor">Freezing Of Mouse Cursor</option>
                            <option value="Faulty Batteries">Faulty Batteries</option>
                        </select>
                        <!-- <textarea placeholder="Comment ..." rows="3" class="form-control" name="comments"></textarea> -->
                    </div>
                </div>
            </div>  
        </div>
        

        <div class='panel-footer'>

            <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>

            <button class="btn btn-primary pull-right" type="submit" id="btnEditSubmit"> <i class="fa fa-save" ></i>  Edit</button>

        </div>

    </form>


</div>

@endsection

@push('bottom')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.select2').select2({placeholder_text_single : "-- Select --"})
            var item_condition = $('#item_condition').val().toLowerCase().replace(/\s/g, '');
            console.log(item_condition);
                if(item_condition == "defective"){
                    $("#item_condition").prop('disabled', true);
                }else{
                    $("#item_condition").prop('disabled', false);
                }
            $("#btnEditSubmit").click(function(event) {
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
                        $("#EditInventoryForm").submit();  
                        showLoading();                      
                });
            });
        });
    </script>
@endpush