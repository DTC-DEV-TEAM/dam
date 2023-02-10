@extends('crudbooster::admin_template')
    @push('head')
        <style type="text/css">   
 
            .firstRow {
                border: 1px solid rgba(39, 38, 38, 0.5);
                padding: 10px;
                margin-left: 10px;
                border-radius: 3px;
                opacity: 2;
            }

            .firstRow {
                padding: 10px;
                margin-left: 10px;
            }

            .finput {
                border:none;
                border-bottom: 1px solid rgba(18, 17, 17, 0.5);
            }

            input.finput:read-only {
                background-color: #fff;
            }

            input.sinput:read-only {
                background-color: #fff;
            }

            input.addinput:read-only {
                background-color: #f5f5f5;
            }

            .input-group-addon {
                background-color: #f5f5f5 !important;
            }

            .card, .card2, .card3, .card4, .card5, .card6, .card7, .card8{
                background-color: #fff ;
                padding: 15px;
                border-radius: 3px;
                box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
                margin-bottom: 15px;
            }
            .panel-heading{
                background-color: #f5f5f5 ;
            }

            table, th, td {
            border: 1px solid rgba(000, 0, 0, .5);
            padding: 8px;
            border-radius: 5px 0 0 5px;
            }
           
        </style>
    @endpush
@section('content')
@if(g('return_url'))
	<p class="noprint"><a title='Return' href='{{g("return_url")}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@else
	<p class="noprint"><a title='Main Module' href='{{CRUDBooster::mainpath()}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@endif

    <div class='panel-heading'>
        Crreation of Account Form
    </div>

    <form method='post' id="myform" action='{{CRUDBooster::mainpath('edit-save/'.$Header->requestid)}}'>
        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
        <input type="hidden" value="" name="approval_action" id="approval_action">
     
            <div class="card">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label"> Email</label>
                            <input type="text" class="form-control finput" name="email" id="email" aria-describedby="basic-addon1">             
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                        <label class="control-label">Company</i></label>
                                <input type="text" class="form-control finput" name="company" id="company" value="{{$Header->company}}" aria-describedby="basic-addon1" readonly>             
                        </div>
                    </div>
                </div>
                <div class="row"> 
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label"> First Name</label>
                            <input type="text" class="form-control finput" name="first_name" id="first_name" value="{{$Header->first_name}}" aria-describedby="basic-addon1" readonly>             
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label"> Last Name</label>
                            <input type="text" class="form-control finput" name="last_name" id="last_name" value="{{$Header->last_name}}" aria-describedby="basic-addon1" readonly>                                                      
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                        <label class="control-label"> Department</label>
                        <input type="text" class="form-control finput" name="department" id="department" value="{{$Header->department}}" aria-describedby="basic-addon1" readonly>             
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label"> Position</label>
                            <input type="text" class="form-control finput" name="position" id="position" value="{{$Header->position}}" aria-describedby="basic-addon1" readonly>                                                      
                        </div>
                    </div>
                </div>
                
                <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
                <button class="btn btn-success pull-right" type="button" id="btnCreateAccount"> Create Account</button>
            </div>
        
    </form>

@endsection
@push('bottom')
<script type="text/javascript">
 $('#btnCreateAccount').click(function(event) {
        event.preventDefault();
        if($('#email').val() === ""){
            swal({
                    type: 'error',
                    title: 'Required Email!',
                    icon: 'error',
                    confirmButtonColor: "#367fa9",
                }); 
                event.preventDefault(); // cancel default behavior
                return false;
        }else if(IsEmail($('#email').val())==false){
                            swal({
                    type: 'error',
                    title: 'Invalid Email Format!',
                    icon: 'error',
                    confirmButtonColor: "#367fa9",
                });
                event.preventDefault();
        }else{
            swal({
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#41B314",
                cancelButtonColor: "#F9354C",
                confirmButtonText: "Yes, create it!",
                width: 450,
                height: 200
                }, function () {
                    $(this).attr('disabled','disabled');
                    $("#myform").submit();                   
            });
        }
            
    });

     //email validation
     function IsEmail(email) {
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if(!regex.test(email)) {
            return false;
        }else{
            return true;
        }
    }

    var tds = document
    .getElementById("table_dashboard")
    .getElementsByTagName("td");
    var sumqty = 0;
    var sumcost = 0;
    for (var i = 0; i < tds.length; i++) {
    if (tds[i].className == "qty") {
        sumcost += isNaN(tds[i].innerHTML) ? 0 : parseFloat(tds[i].innerHTML);
    }
    }
    document.getElementById("table_dashboard").innerHTML +=
    "<tr><td colspan='3' style='text-align:right'><strong>TOTAL</strong></td><td style='text-align:center'><strong>" +
    sumcost +
    "</strong></td></td></tr>";
    
</script>
@endpush