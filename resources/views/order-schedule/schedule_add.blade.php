@extends('crudbooster::admin_template')
@section('content')

<div>
    <p><a title="Return" href="{{ CRUDBooster::mainpath() }}"><i class="fa fa-chevron-circle-left "></i>&nbsp; Back To List Data Order Schedule</a></p>

    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>Add Order Schedule</strong>
        </div>

        <div class="panel-body" style="padding:20px 0px 0px 0px">
            <form action="{{ CRUDBooster::mainpath('add-save') }}" method="POST" id="orderScheduleForm" autocomplete="off">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                
                <div class="box-body" id="parent-form-area">

                    <div class="form-group header-group-0 col-sm-12" id="form-group-schedule_name" >
                        <label class="control-label col-sm-2">
                            Schedule Name
                            <span class="text-danger" title="This field is required">*</span>
                        </label>

                        <div class="col-sm-5">
                            <input type="text" required="" maxlength="50" class="form-control" name="schedule_name" id="schedule_name" value="" title="Schedule Name">

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

                                <input type="text" title="Start Date" required class="form-control notfocus datetimepicker" name="start_date" id="start_date" value="">
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

                                <input type="text" title="End Date" required class="form-control notfocus datetimepicker" name="end_date" id="end_date" value="">
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
                            <input type="number" step="1" title="Time Unit" required class="form-control" name="time_unit" id="time_unit" value="">
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
                                <option value="DAY">DAY</option>
                                <option value="HOUR">HOUR</option>
                            </select>
                            <div class="text-danger"></div>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    
                    <br/>
                    <br/>
                    
                    
                </div><!-- /.box-body -->

                <div class="box-footer">
                    <div class='pull-right'>
                        <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-default'>Cancel</a>
                        <input type='submit' class='btn btn-success' id="btnSubmit" name='submit' value='Create'/>
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