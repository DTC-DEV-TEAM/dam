@extends('crudbooster::admin_template')
    @push('head')
    
        <style type="text/css">   
            table.dataTable td.dataTables_empty {
                text-align: center;    
            }
            .active{
                font-weight: bold;
                font-size: 13px;
                color:#3c8dbc
            }
            .modal-content  {
                -webkit-border-radius: 3px !important;
                -moz-border-radius: 3px !important;
                border-radius: 3px !important; 
            }
            .select2-selection__choice{
                    font-size:14px !important;
                    color:black !important;
            }
            .select2-selection__rendered {
                line-height: 31px !important;
            }
            .select2-container .select2-selection--single {
                height: 35px !important;
            }
            .select2-selection__arrow {
                height: 34px !important;
            }
        </style>
    @endpush
@section('content')

<div class='panel panel-default'>
    <div class='panel-heading'>
    Request Assets Reports
    </div>

        <div class='panel-body' style="overflow-x: scroll">
            <div class="row">
            <!-- <div class="col-md-3">
                    <h3>Select Date Start</h3>
                    <input type="text" class="form-control date" name="start_date"  id="start_date" placeholder="Please Select Start Date">
                </div>  
                <div class="col-md-3">
                    <h3>Select Date End</h3>
                    <input type="text" class="form-control date" name="end_date"  id="end_date" placeholder="Please Select End Date">
                </div>  
                <div class="col-md-3">
                    <h3>Asset Code</h3>
                    <select class="form-control select2" name="asset_code" id="asset_code">
                        <option value="">-- Select Asset Code --</option> 
                       
                    </select>
                </div>  
                <div class="col-md-3">
                    <h3>Item Description</h3>
                    <input type="text" class="form-control" name="item_description"  id="item_description" readonly>
                </div>  
                
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top:15px; margin-bottom:10px"
                                    id="btn-generate"><i class="fa fa-search"></i> Search History</button>
            <br> -->
           
        <div class="row" style="margin:5px">   
            <!-- <button type="button" id="btn-export" class="btn btn-primary btn-sm btn-export" style="margin-bottom:10px"><i class="fa fa-download"></i>
                <span>Export Data</span>
            </button> -->
            <button type="button" id="btn-export" class="btn btn-primary btn-sm btn-export" data-toggle="modal" data-target="#myModal" style="margin-bottom:10px"><i class="fa fa-search"></i>
             <span>Export Filter</span>
            </button>
            <table class='table table-hover table-striped table-bordered' id="table_dashboard">
            
                <thead>
                    <tr class="active">
                        <th width="auto">Action</th>
                        <th width="auto">Status</th>
                        <th width="auto">Reference No.</th>
                        <th width="auto">Description</th>
                        <th width="auto">Request Quantity</th>
                        <th width="auto">Transaction Type</th>
                        <th width="auto">Request Type</th>
                        <th width="auto">Requested By</th>
                        <th width="auto">Department</th>
                        <th width="auto">Store Branch</th>
                        <th width="auto">MO Reference</th>
                        <th width="auto">MO Item Code</th>
                        <th width="auto">MO Item Description</th>
                        <th width="auto">MO QTY/Serve QTY</th>
                        <th width="auto">Requested Date</th>
                        <th width="auto">Transacted By</th>
                        <th width="auto">Transacted Date</th>
               
                    </tr>
                </thead>
                <tbody>
                @foreach($finalData as $val)
                    <tr>
                    <td style="text-align:center">   
                     <a class='btn btn-primary btn-xs' href='{{CRUDBooster::adminpath("request_history/detail/".$val["id"])."?return_url=".urlencode(Request::fullUrl())}}'><i class='fa fa-eye'></i></a>                                         
                    </td> 
                    @if($val['status'] == "FOR APPROVAL")
                    <td style="text-align:center">
                     <label class="label label-warning" style="align:center">{{$val['status']}}</label>
                    </td>
                    @elseif($val['status'] == "CLOSED")
                    <td style="text-align:center">
                     <label class="label label-success" style="align:center">{{$val['status']}}</label>
                    </td>
                    @elseif($val['status'] == "CANCELLED" || $val['status'] == "REJECTED")
                    <td style="text-align:center">
                     <label class="label label-danger" style="align:center">{{$val['status']}}</label>
                    </td>
                    @else
                    <td style="text-align:center">
                     <label class="label label-info" style="align:center">{{$val['status']}}</label>
                    </td>
                    @endif
                    <td>{{$val['reference_number']}}</td>
                    <td>{{$val['description']}}</td>  
                    <td>{{$val['request_quantity']}}</td>
                    <td>{{$val['transaction_type']}}</td>  
                    <td>{{$val['request_type']}}</td>
                    <td>{{$val['requested_by']}}</td>     
                    <td>{{$val['department']}}</td>                                                                
                    <td>{{$val['store_branch']}}</td>  
                    <td>{{$val['mo_reference']}}</td>  
                    <td>{{$val['mo_item_code']}}</td>  
                    <td>{{$val['mo_item_description']}}</td>  
                    <td>{{$val['mo_qty_serve_qty']}}</td>  
                    <td>{{$val['requested_date']}}</td> 
                    <td>{{$val['transacted_by']}}</td>  
                    <td>{{$val['transacted_date']}}</td>  
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>                 
        </div>

        <!-- Modal Edit Start-->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria- 
            labelledby="exportModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria- 
                            label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h3 class="modal-title text-center" id="exportModalLabel">Filter Export</h3>
                    </div>
                    <div class="modal-body">
                        <form  id="filterForm" method='post' target='_blank' name="filterForm" action="{{route('request-search')}}">
                            <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label require"> Approved Date From</label>
                                        <input type="text" class="form-control date" name="from"  id="from" placeholder="Please Select Date">
                                    </div>
                                </div>  
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label require"> Approved Date To</label>
                                        <input type="text" class="form-control date" name="to"  id="to" placeholder="Please Select Date">
                                    </div>
                                </div>  
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label require">Category</label>
                                        <select selected data-placeholder="-- Select Category --" id="category" name="category" class="form-select erf" style="width:100%;">
                                            @foreach($categories as $res)
                                                <option value=""></option>
                                                <option value="{{ $res->id }}">{{ $res->request_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>  
                            </div>
                    </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="btnExport">Export</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal Edit End-->


</div>

@endsection

@push('bottom')
<script src=
"https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js" >
    </script>
    <script src=
"https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" >
    </script>
        <script src=
"https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js" >
    </script>
    <script type="text/javascript">
        $(function(){
            $('body').addClass("sidebar-collapse");
        });
       var table;
       $(document).ready(function() {
           table = $("#table_dashboard").DataTable({
                ordering:false,
                pageLength:25,
                language: {
                    searchPlaceholder: "Search"
                },
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"],
                    ],
                buttons: [
                    {
                        extend: "excel",
                        title: "Request Assets Report",
                        exportOptions: {
                        columns: ":not(.not-export-column)",
                        columns: ":gt(0)",
                            modifier: {
                            page: "current",
                        }
                        },
                    },
                    ],
            });
            // $("#btn-export").on("click", function () {
            //     table.button(".buttons-excel").trigger();
            // });

            $('#erf_number,#status, #category').select2({})
            $(".date").datetimepicker({
                    viewMode: "days",
                    format: "YYYY-MM-DD",
                    dayViewHeaderFormat: "MMMM YYYY",
            });

            $('#btnExport').click(function(event) {
                event.preventDefault();
                var from = $('#from').val();
                var to = $('#to').val();
                if(from > to){
                    swal({
                        type: 'error',
                        title: 'Invalid Date of Range',
                        icon: 'error',
                        confirmButtonColor: "#367fa9",
                    }); 
                    event.preventDefault(); // cancel default behavior
                    return false;
                }else{
                    $('#filterForm').submit(); 
                }
               
            });
        });
 
    </script>
@endpush