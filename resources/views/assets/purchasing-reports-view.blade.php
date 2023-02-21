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
       
        </style>
    @endpush
@section('content')

<div class='panel panel-default'>
    <div class='panel-heading'>
    Request Assets Reports
    </div>

        <div class='panel-body' style="overflow-x: scroll">
            <div class="row">
           
        <div class="row" style="margin:5px">   

            <a href="javascript:showApplicantItemExport()" style="margin:5px" id="export-sales-item-report" class="btn btn-primary btn-sm">
            <i class="fa fa-download"></i> Export 
            </a>
            <table class='table table-hover table-striped table-bordered' id="table_dashboard">
            
                <thead>
                    <tr class="active">
                        <th width="auto">Status</th>
                        <th width="auto">Reference No.</th>
                        <th width="auto">Description</th>
                        <th width="auto">Request Quantity</th>
                        <th width="auto">Request Type</th>
                        <th width="auto">Requested By</th>
                        <th width="auto">Department</th>
                        <th width="auto">Store Branch</th>
                        <th width="auto">MO Reference</th>
                        <th width="auto">MO Item Code</th>
                        <th width="auto">MO Item Description</th>
                        <th width="auto">Requested Date</th>
                        <th width="auto">Transacted By</th>
                        <th width="auto">Transacted Date</th>
               
                    </tr>
                </thead>
                <tbody>
                @foreach($result as $val)
                    <tr>
                    @if($val['mo_statuses_description'] == "FOR APPROVAL")
                    <td style="text-align:center">
                     <label class="label label-warning" style="align:center">{{$val['mo_statuses_description']}}</label>
                    </td>
                    @elseif($val['mo_statuses_description'] == "CLOSED")
                    <td style="text-align:center">
                     <label class="label label-success" style="align:center">{{$val['mo_statuses_description']}}</label>
                    </td>
                    @elseif($val['mo_statuses_description'] == "CANCELLED" || $val['mo_statuses_description'] == "REJECTED")
                    <td style="text-align:center">
                     <label class="label label-danger" style="align:center">{{$val['mo_statuses_description']}}</label>
                    </td>
                    @else
                    <td style="text-align:center">
                     <label class="label label-info" style="align:center">{{$val['mo_statuses_description']}}</label>
                    </td>
                    @endif
                    <td>{{$val['reference_number']}}</td>
                    <td>{{$val['body_description']}}</td>  
                    <td>{{$val['quantity']}}</td>
                    <td>{{$val['category_id']}}</td>
                    <td>{{$val['requestedby']}}</td>     
                    <td>{{$val['department']}}</td>                                                                
                    <td>{{$val['store_branch']}}</td>  
                    <td>{{$val['mo_reference_number']}}</td>  
                    <td>{{$val['digits_code']}}</td>  
                    <td>{{$val['mo_item_description']}}</td>  
                    <td>{{$val['created_at']}}</td> 
                    <td>{{$val['taggedby']}}</td>  
                    <td>{{$val['transacted_date']}}</td>  
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>                 
        </div>

        <div class='modal fade' tabindex='-1' role='dialog' id='modal-applicant-export'>
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <button class='close' aria-label='Close' type='button' data-dismiss='modal'>
                            <span aria-hidden='true'>×</span></button>
                        <h4 class='modal-title'><i class='fa fa-download'></i> Export Request Assets</h4>
                    </div>

                    <form method='post' target='_blank' action="{{ CRUDBooster::mainpath('export-request') }}">
                    <input type='hidden' name='_token' value="{{ csrf_token() }}">
                    {{ CRUDBooster::getUrlParameters() }}
                    @if(!empty($filters))
                        @foreach ($filters as $keyfilter => $valuefilter )
                            <input type="hidden" name="{{ $keyfilter }}" value="{{ $valuefilter }}">
                        
                        @endforeach

                    @endif
                    <input type="hidden" name="from" value="{{ $from }}">
                    <input type="hidden" name="to" value="{{ $to }}">
                    <input type="hidden" name="category" value="{{ $category }}">
                    <div class='modal-body'>
                        <div class='form-group'>
                            <label>File Name</label>
                            <input type='text' name='filename' class='form-control' required value='Export Request Assets {{ CRUDBooster::getCurrentModule()->name }} - {{ date('Y-m-d H:i:s') }}'/>
                        </div>
                    </div>
                    <div class='modal-footer' align='right'>
                        <button class='btn btn-default' type='button' data-dismiss='modal'>Close</button>
                        <button class='btn btn-primary btn-submit' type='submit'>Download</button>
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

            $('#erf_number,#status').select2({})
            $(".date").datetimepicker({
                    viewMode: "days",
                    format: "YYYY-MM-DD",
                    dayViewHeaderFormat: "MMMM YYYY",
            });
        });

    function showApplicantItemExport() {
        $('#modal-applicant-export').modal('show');
    }
    </script>
@endpush