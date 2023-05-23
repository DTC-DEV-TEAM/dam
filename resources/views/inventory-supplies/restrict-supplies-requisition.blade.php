@extends('crudbooster::admin_template')
    @push('head')
        <style type="text/css">   

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
            Restrict Supplies Requisition Form
        </div>
        <form name="suppliesInventory" id="suppliesInventory" enctype="multipart/form-data">
        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
            <div class="panel-body">
                

                <div class="row"> 
                    <div class="col-md-6">
                        <button class="btn btn-danger" type="submit" value="1" id="btnRestrict"><i class="fa fa-ban"></i> Restrict Supplies Requisition</button>
                    </div>
                    <div class="col-md-6">
                        <label for="">Status</label>
                        @if($privileges->isEmpty())
                            <span class="label label-success">Active</span>
                        @else
                            <span class="label label-danger">In Active</span>
                        @endif
                    </div>
                </div>
                <br>
                <div class="row"> 
                    <div class="col-md-6">
                        <button class="btn btn-success" type="submit" value="0" id="btnUnRestrict"><i class="fa fa-check"></i> Unrestrict Supplies Requisition</button>
                    </div>
                    <div class="col-md-6">
                        <label for="">Status</label>
                        @if($privileges->isNotEmpty())
                         <span class="label label-success">Active</span>
                        @else
                         <span class="label label-danger">In Active</span>
                        @endif
                    </div>
                </div>

            </div>
        </form>

        <div class='panel-footer'>
        <a href="{{ CRUDBooster::mainpath() }}" id="btn-cancel" class="btn btn-default">back</a>
        </div>
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
    $('.digits_code').select2({})

    $("#btnRestrict").click(function(event) {
        event.preventDefault();
        var btn_value = $(this).val();
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dd4b39",
            cancelButtonColor: "#F9354C",
            confirmButtonText: "Yes, Restrict it!",
            width: 450,
            height: 200
            }, function () {
                $.ajax({
                    url: "{{ route('restrict-request-asset') }}",
                    type: "POST",
                    dataType: 'json',
                    data: { value: btn_value},
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

    $("#btnUnRestrict").click(function(event) {
        event.preventDefault();
        var btn_value = $(this).val();
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#41B314",
            cancelButtonColor: "#F9354C",
            confirmButtonText: "Yes, UnRestrict it!",
            width: 450,
            height: 200
            }, function () {
                $.ajax({
                    url: "{{ route('restrict-request-asset') }}",
                    type: "POST",
                    dataType: 'json',
                    data: { value: btn_value},
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

</script>
@endpush