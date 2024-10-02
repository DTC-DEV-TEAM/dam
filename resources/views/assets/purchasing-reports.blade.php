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

              /* Custom loading spinner */
            .spinner {
                display: none;
                margin: 0 auto;
                width: 40px;
                height: 40px;
                border: 6px solid #ccc;
                border-top: 6px solid #1d72b8;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }

            .span_processing{
                font-size: 1em;
                font-weight: bold;
            }

            .dataTables_wrapper .dataTables_processing {
                height: 100px !important;
                opacity: 5 !important;
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
            <div class="clearfix" style="margin-bottom:8px">
                <button type="button" id="btn-export" class="btn btn-primary btn-sm btn-export" data-toggle="modal" data-target="#myModal" style="margin-bottom:10px"><i class="fa fa-search"></i>
                <span>Export Filter</span>
                </button>
                <a href="javascript:showSalesFilteredReportExport()" style="display: none" id="export-filtered-sales" class="btn btn-primary btn-sm" style="margin-bottom:10px">
                    <i class="fa fa-download"></i> <span>Export Filtered Sales</span>
                </a>
            </div>
            <table class='table table-hover table-striped table-bordered' id="table-dashboard">
            
                <thead>
                    <tr class="active">
                        <th width="auto" style="text-align:center">Action</th>
                        <th width="auto">Status</th>
                        <th width="auto">Reference No.</th>
                        <th width="auto">Digits Code</th>
                        <th width="auto">Description</th>
                        <th width="auto">Request Quantity</th>
                        <th width="auto">Transaction Type</th>
                        <th width="auto">Request Type</th>
                        <th width="auto">Requested By</th>
                        <th width="auto">Department</th>
                        <th width="auto">Coa</th>
                        <th width="auto">Store Branch</th>
                        <th width="auto">Replenish Qty</th>
                        <th width="auto">Re Order Qty</th>
                        <th width="auto">Fulfilled Qty</th>
                        <th width="auto">MO Reference</th>
                        <th width="auto">MO Item Code</th>
                        <th width="auto">MO Item Description</th>
                        <th width="auto">MO QTY/Serve QTY</th>
                        <th width="auto">Requested Date</th>
                        <th width="auto">Transacted By</th>
                        <th width="auto">Transacted Date</th>
                        <th width="auto">Received By</th>
                        <th width="auto">Received Date</th>
               
                    </tr>
                </thead>
                <tbody>
                
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
                        <form  method='post'>
                            <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
                            <input type="hidden" value="1" name="overwrite" id="overwrite">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label require"> Date From</label>
                                        <input type="text" class="form-control date" name="from"  id="from" placeholder="Please Select Date">
                                    </div>
                                </div>  
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label require"> Date To</label>
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

        <div class='modal fade' tabindex='-1' role='dialog' id='export-filtered-report'>
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <button class='close' aria-label='Close' type='button' data-dismiss='modal'>
                            <span aria-hidden='true'>×</span></button>
                        <h4 class='modal-title'><i class='fa fa-download'></i> Download Report</h4>
                    </div>
        
                    {{-- <form method='post' target='_blank' id="exportForm"> --}}
                <form method='post' target='_blank' action="{{ route('report-filter-export') }}">
                    <input type='hidden' name='_token' value="{{ csrf_token()}}">
                    <input type='hidden' name='datefrom' id="date_from">
                    <input type='hidden' name='dateto' id="date_to">
                    <input type='hidden' name='category' id="category_id">
                    {!! CRUDBooster::getUrlParameters() !!}
                    <div class='modal-body'>
                        <div class='form-group'>
                            <label>File Name</label>
                            <input type='text' name='filename' class='form-control' required value="Export {{ CRUDBooster::getCurrentModule()->name }} - {{ date('Y-m-d H:i:s')}}"/>
                        </div>
                    </div>
                    <div class='modal-footer' align='right'>
                        <button class='btn btn-default' type='button' data-dismiss='modal'>Close</button>
                        {{-- <button class='btn btn-primary btn-submit' type='submit' id="exportBtn">Submit</button> --}}
                        <button class='btn btn-primary btn-submit' type='submit'><i class='fa fa-download'></i> Download</button>
                    </div>
                </form>
                </div>
            </div>
        </div>


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

    $(document).ready(function() {
        load_data();

        function load_data(start_date = '', end_date = '', category = '') {
            $("#table-dashboard").DataTable({
                    ordering:false,
                    processing: true,
                    serverSide: true,
                    language: {
                        processing: '<div class="spinner" id="spinner"></div> <span class="span_processing">Processing... Please wait...</span>' // Custom processing text with spinner
                    },
                    order: [[0, 'desc']],
                    pageLength: 10,
                    lengthMenu: [10, 25, 50, 100],

                    ajax: {
                        url: '{{ route("api.reports.index") }}',
                        data: {
                            datefrom: start_date,
                            dateto: end_date,
                            category: category
                        },
                        beforeSend: function() {
                            $('#spinner').show(); // Show spinner before the request
                        },
                        complete: function() {
                            $('#spinner').hide(); // Hide spinner after the request completes
                        }
                    },
                    columns : [
                            {data: 'action'},
                            {data: 'status', name:'status'},
                            {data: 'reference_number', name: 'reference_number'},
                            {data: 'digits_code', name: 'digits_code',}, 
                            {data: 'description', name: 'description'},  
                            {data: 'request_quantity', name: 'request_quantity'},
                            {data: 'transaction_type', name: 'transaction_type'},  
                            {data: 'request_type', name: 'request_type'},
                            {data: 'requested_by', name: 'requested_by'},     
                            {data: 'department', name: 'department'}, 
                            {data: 'coa', name: 'coa'},                                                                
                            {data: 'store_branch', name: 'store_branch'},  
                            {data: 'replenish_qty', name: 'replenish_qty'},  
                            {data: 'reorder_qty', name: 'reorder_qty'},  
                            {data: 'fulfill_qty', name: 'fulfill_qty'},  
                            {data: 'mo_reference', name: 'mo_reference'},  
                            {data: 'mo_item_code', name: 'mo_item_code'},  
                            {data: 'mo_item_description', name: 'mo_item_description'},  
                            {data: 'mo_qty_serve_qty', name: 'mo_qty_serve_qty'},  
                            {
                                data: 'requested_date',
                                name: 'requested_date',
                            }, 
                        
                            {data: 'transacted_by', name: 'transacted_by'},  
                            {data: 'transacted_date', name: 'transacted_date'},  
                            {data: 'received_by', name: 'received_by'},  
                            {data: 'received_at', name: 'received_at'}
                    ],
                    columnDefs: [{
                        targets: [1],
                                render : function (data, type, row) {
                                    if(row.status == "FOR APPROVAL"){
                                        return '<label class="label label-warning" style="align:center">'+row.status+'</label>';
                                    }else if(row.status == "CLOSED"){
                                        return '<label class="label label-success" style="align:center">'+row.status+'</label';
                                    }else if(row.status == "CANCELLED" || row.status == "REJECTED"){
                                        return '<label class="label label-danger" style="align:center">'+row.status+'</label';
                                    }else{
                                        return '<label class="label label-info" style="align:center">'+row.status+'</label';
                                    }
                        
                        },
                        // targets : targetColumns,
                        // render(v){
                        //     return (+v).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})
                        // }
            
                    }],
                        
                    // footerCallback : function ( row, data, start, end, display ) {
                    //     var api = this.api();
                    //     api.columns(targetColumns, {
                    //         page: 'current'
                    //     }).every(function() {
                    //         var sum = this.data().reduce(function(a, b) {
                    //             var x = parseFloat(a) || 0;
                    //             var y = parseFloat(b) || 0;
                    //             return x + y;
                    //         }, 0);
                    //         $(this.footer()).html(sum.toLocaleString(undefined,{
                    //             minimumFractionDigits: 2,
                    //             maximumFractionDigits: 2
                    //         }));
                    //     });
                    // }
        
                    
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

                // $('#btnExport').click(function(event) {
                //     event.preventDefault();
                //     var from = $('#from').val();
                //     var to = $('#to').val();
                //     if(from > to){
                //         swal({
                //             type: 'error',
                //             title: 'Invalid Date of Range',
                //             icon: 'error',
                //             confirmButtonColor: "#367fa9",
                //         }); 
                //         event.preventDefault(); // cancel default behavior
                //         return false;
                //     }else{
                //         $('#filterForm').submit(); 
                //     }
                
                // });
        }

        $('#btnExport').off('click').on('click', function(e) {
            e.preventDefault();
            var start_date = $('#from').val();
            var end_date = $('#to').val();
            var category_id = $('#category').val();
            $('#date_from').val(start_date);
            $('#date_to').val(end_date);
            $('#category_id').val(category_id);
            $('#myModal').modal('hide');
            if (start_date > end_date) {
                swal({
                    type: 'error',
                    title: 'Invalid Date of Range',
                    icon: 'error',
                    confirmButtonColor: "#367fa9"
                }); 
            } else {
                $('#btn-export').hide();
                $('#export-filtered-sales').show();
                if ($.fn.DataTable.isDataTable('#table-dashboard')) {
                    $('#table-dashboard').DataTable().destroy();
                }
                load_data(start_date, end_date, category_id); 
            }
        });

    });

    function showSalesFilteredReportExport() {
        $('#export-filtered-report').modal('show');
    }
 
    </script>
@endpush