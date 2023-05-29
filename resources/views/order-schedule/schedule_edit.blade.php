@extends('crudbooster::admin_template')
@section('content')

<div>
    <p><a title="Return" href="{{ CRUDBooster::mainpath() }}"><i class="fa fa-chevron-circle-left "></i>&nbsp; Back To List Data Order Schedule</a></p>

    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>Edit Order Schedule</strong>
        </div>

        <div class="panel-body" style="padding:20px 0px 0px 0px">
        <form action="{{ CRUDBooster::mainpath('edit-save') }}/{{ $oldData->id }}" method="POST" id="orderScheduleForm" autocomplete="off">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                
                <div class="box-body" id="parent-form-area">

                    <div class="form-group header-group-0 col-sm-12" id="form-group-schedule_name" >
                        <label class="control-label col-sm-2">
                            Schedule Name
                            <span class="text-danger" title="This field is required">*</span>
                        </label>

                        <div class="col-sm-5">
                        <input type="text" required="" maxlength="50" class="form-control" name="schedule_name" id="schedule_name" value="{{$oldData->schedule_name}}" title="Schedule Name">

                            <div class="text-danger"></div>
                            <p class="help-block"></p>

                        </div>
                    </div>
                    
                    <div class="form-group form-datepicker header-group-0 col-sm-12" id="form-group-start_date" >
                        <label class="control-label col-sm-2">Start Date
                            <span class="text-danger" title="This field is required">*</span>
                        </label>

                        <div class="col-sm-5">
                            <div class="input-group">

                                <span class="input-group-addon"><a href="javascript:void(0)" onclick="$('#start_date').data('daterangepicker').toggle()"><i class="fa fa-calendar"></i></a></span>

                            <input type="text" title="Start Date" required class="form-control notfocus datetimepicker" name="start_date" id="start_date" value="{{$oldData->start_date}}">
                            </div>
                            <div class="text-danger"></div>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    
                    <div class="form-group form-datepicker header-group-0 col-sm-12" id="form-group-end_date" >
                        <label class="control-label col-sm-2">End Date
                            <span class="text-danger" title="This field is required">*</span>
                        </label>

                        <div class="col-sm-5">
                            <div class="input-group">

                                <span class="input-group-addon"><a href="javascript:void(0)" onclick="$('#end_date').data('daterangepicker').toggle()"><i class="fa fa-calendar"></i></a></span>

                                <input type="text" title="End Date" required class="form-control notfocus datetimepicker" name="end_date" id="end_date" value="{{$oldData->end_date}}">
                            </div>
                            <div class="text-danger"></div>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    
                    <div class="form-group header-group-0 col-sm-12" id="form-group-time_unit" >
                        <label class="control-label col-sm-2">Time Unit
                            <span class="text-danger" title="This field is required">*</span>
                        </label>

                        <div class="col-sm-5">
                            <input type="number" step="1" title="Time Unit" required class="form-control" name="time_unit" id="time_unit" value="{{$oldData->time_unit}}">
                            <div class="text-danger"></div>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    
                    <div class="form-group header-group-0 col-sm-12" id="form-group-period" >
                        <label class="control-label col-sm-2">Time Period
                            <span class="text-danger" title="This field is required">*</span>
                        </label>

                        <div class="col-sm-5">
                            <select class="form-control" id="period" required name="period">
                                <option value="">** Please select a Time Period</option>
                                <option @if($oldData->period == "DAY") selected @endif value="DAY">DAY</option>
                                <option @if($oldData->period == "HOUR") selected @endif value="HOUR">HOUR</option>
                            </select>
                            <div class="text-danger"></div>
                            <p class="help-block"></p>
                        </div>
                    </div>

                    <div class="form-group header-group-0 col-sm-12" id="form-group-status" >
                        <label class="control-label col-sm-2">Status
                            <span class="text-danger" title="This field is required">*</span>
                        </label>

                        <div class="col-sm-5">
                            <select class="form-control" id="status" required name="status">
                                <option value="">** Please select a Status</option>
                                <option @if($oldData->status == "ACTIVE") selected @endif value="ACTIVE">ACTIVE</option>
                                <option @if($oldData->status == "INACTIVE") selected @endif value="INACTIVE">INACTIVE</option>
                            </select>
                            <div class="text-danger"></div>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    
                    <br/>
                    <br/>
                    
                    <div class="row">
                        <div class="col-sm-3" style="padding-left:50px;">
                            <b> RETAIL </b><br><br>
                            <input type="checkbox" id="select_all_rtl"> Select All Retail<br>
                            <input type="checkbox" id="select_all_rtldw"> Select All Retail DW<br>
                            <input type="checkbox" id="select_all_rtlbtb"> Select All Retail BTB<br>
                            <hr>
                            @foreach($retailStores as $rStore)
                                <input type="checkbox" class="rtl-stores" id="{{ $rStore->id }}" name="stores_id[]" @if(in_array($rStore->id, $oldStores)) checked @endif value="{{ $rStore->id }}"> <span>{{ $rStore->store_name }}</span><br>
                            @endforeach
                        </div>
                        <div class="col-sm-3" style="padding-left:50px;">
                            <b> FRANCHISE </b><br><br>
                            <input type="checkbox" id="select_all_fra"> Select All Franchise<br>
                            <input type="checkbox" id="select_all_fradw"> Select All Franchise DW<br>
                            <input type="checkbox" id="select_all_frabtb"> Select All Franchise BTB<br>
                            <hr>
                            @foreach($franchiseStores as $fStore)
                                <input type="checkbox" class="fra-stores" id="{{ $fStore->id }}" name="stores_id[]" @if(in_array($fStore->id, $oldStores)) checked @endif value="{{ $fStore->id }}"> <span>{{ $fStore->store_name }}</span><br>
                            @endforeach
                        </div>
                        <div class="col-sm-3" style="padding-left:50px;">
                            <b> DISTRIBUTION </b><br><br>
                            <input type="checkbox" id="select_all_dis"> Select All Distribution<br>
                            <input type="checkbox" id="select_all_discon"> Select All Distribution CON<br>
                            <input type="checkbox" id="select_all_disout"> Select All Distribution OUT<br>
                            <hr>
                            @foreach($distributionStores as $dStore)
                                <input type="checkbox" class="dis-stores" id="{{ $dStore->id }}" name="stores_id[]" @if(in_array($dStore->id, $oldStores)) checked @endif value="{{ $dStore->id }}"> <span>{{ $dStore->store_name }}</span><br>
                            @endforeach
                        </div>
                        
                        <div class="col-sm-3" style="padding-left:50px;">
                            <b> ONLINE </b><br><br>
                            <input type="checkbox" id="select_all_onl"> Select All Online<br>
                            <input type="checkbox" id="select_all_onllazada"> Select All Online LAZADA<br>
                            <input type="checkbox" id="select_all_onlshopee"> Select All Online SHOPEE<br>
                            <hr>
                            @foreach($onlineStores as $oStore)
                                <input type="checkbox" class="onl-stores" id="{{ $oStore->id }}" name="stores_id[]" @if(in_array($oStore->id, $oldStores)) checked @endif value="{{ $oStore->id }}"> <span>{{ $oStore->store_name }}</span><br>
                            @endforeach
                        </div>
                        
                        
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-3" style="padding-left:50px;">
                            <b> DIGITS </b>
                            <hr>
                            @foreach($digitsStores as $dtcStore)
                                <input type="checkbox" class="dtc-stores" id="{{ $dtcStore->id }}" name="stores_id[]" @if(in_array($oStore->id, $oldStores)) checked @endif value="{{ $dtcStore->id }}"> <span>{{ $dtcStore->store_name }}</span><br>
                            @endforeach
                        </div>
                        
                    </div>
                    
                </div><!-- /.box-body -->

                <div class="box-footer">
                    <div class='pull-right'>
                        <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-default'>Cancel</a>
                        <input type='submit' class='btn btn-success' id="btnSubmit" name='submit' value='Save'/>
                    </div>
                </div><!-- /.box-footer-->
            </form>
        </div>
    </div>
</div>

@endsection

@push('bottom')
<script type="text/javascript">
$(document).ready(function() {

    $('#select_all_rtl').change(function() {
        if(this.checked) {
            $(".rtl-stores").prop("checked", true);
        }
        else{
            $(".rtl-stores").prop("checked", false);
        }
    });

    $('#select_all_rtldw').change(function() {
        if(this.checked) {
            checkAll("rtl-dw");
        }
        else{
            $(".rtl-stores").prop("checked", false);
        }  
    });

    $('#select_all_rtlbtb').change(function() {
        if(this.checked) {
            checkAll("rtl-btb");
        }
        else{
            $(".rtl-stores").prop("checked", false);
        }     
    });

    $('#select_all_fra').change(function() {
        if(this.checked) {
            $(".fra-stores").prop("checked", true);
        }
        else{
            $(".fra-stores").prop("checked", false);
        }     
    });

    $('#select_all_fradw').change(function() {
        if(this.checked) {
            checkAll("fra-dw");
        }
        else{
            $(".fra-stores").prop("checked", false);
        }     
    });

    $('#select_all_frabtb').change(function() {
        if(this.checked) {
            checkAll("fra-btb");
        }
        else{
            $(".fra-stores").prop("checked", false);
        }    
    });

    $('#select_all_dis').change(function() {
        if(this.checked) {
            $(".dis-stores").prop("checked", true);
        }
        else{
            $(".dis-stores").prop("checked", false);
        }    
    });

    $('#select_all_discon').change(function() {
        if(this.checked) {
            checkAll("dis-con");
        }
        else{
            $(".dis-stores").prop("checked", false);
        }    
    });

    $('#select_all_disout').change(function() {
        if(this.checked) {
            checkAll("dis-out");
        }
        else{
            $(".dis-stores").prop("checked", false);
        }    
    });

    $('#select_all_onl').change(function() {
        if(this.checked) {
            $(".onl-stores").prop("checked", true);
        }
        else{
            $(".onl-stores").prop("checked", false);
        }    
    });

    $('#select_all_onllazada').change(function() {
        if(this.checked) {
            checkAll("onl-lazada");
        }
        else{
            $(".onl-stores").prop("checked", false);
        }   
    });

    $('#select_all_onlshopee').change(function() {
        if(this.checked) {
            checkAll("onl-shopee");
        }
        else{
            $(".onl-stores").prop("checked", false);
        }    
    });
});

function checkAll(parameter){
    switch (parameter) {
        case "rtl-dw": {
            $('.rtl-stores').each(function () {
                if ($(this).next('span').text().indexOf("DIGITAL WALKER") >= 0) {
                    $(this).prop("checked", true);
                }
            });
        }break;
        case "rtl-btb": {
            $('.rtl-stores').each(function () {
                if ($(this).next('span').text().indexOf("BEYOND THE BOX") >= 0) {
                    $(this).prop("checked", true);
                }
            });
        }break;

        case "fra-dw": {
            $('.fra-stores').each(function () {
                if ($(this).next('span').text().indexOf("DIGITAL WALKER") >= 0) {
                    $(this).prop("checked", true);
                }
            });
        }break;
        case "fra-btb": {
            $('.fra-stores').each(function () {
                if ($(this).next('span').text().indexOf("BEYOND THE BOX") >= 0) {
                    $(this).prop("checked", true);
                }
            });
        }break;

        case "onl-lazada": {
            $('.onl-stores').each(function () {
                if ($(this).next('span').text().indexOf("LAZADA") >= 0) {
                    $(this).prop("checked", true);
                }
            });
        }break;
        case "onl-shopee": {
            $('.onl-stores').each(function () {
                if ($(this).next('span').text().indexOf("SHOPEE") >= 0) {
                    $(this).prop("checked", true);
                }
            });
        }break;

        case "dis-con": {
            $('.dis-stores').each(function () {
                if ($(this).next('span').text().indexOf(".CON") >= 0) {
                    $(this).prop("checked", true);
                }
            });
        }break;
        case "dis-out": {
            $('.dis-stores').each(function () {
                if ($(this).next('span').text().indexOf(".OUT") >= 0 || $(this).next('span').text().indexOf(".CRP") >= 0) {
                    $(this).prop("checked", true);
                }
            });
        }break;
    
        default:
            break;
    }
}

</script>

@endpush