@extends('crudbooster::admin_template')
    @push('head')
    
        <style type="text/css">   
            table.dataTable td.dataTables_empty {
                text-align: center;    
            }
            /* .active{
                font-weight: bold;
                font-size: 13px;
                color:#3c8dbc
            } */
            .modal-content  {
                -webkit-border-radius: 3px !important;
                -moz-border-radius: 3px !important;
                border-radius: 3px !important; 
            }

            #table_dashboards th, td {
                border: 1px solid rgba(000, 0, 0, .4);
                padding: 8px;
            }
            ::-webkit-scrollbar-track
            {
                /* -webkit-box-shadow: inset 0 0 6px rgba(32, 83, 178, 0.3); */
                background-color: #F5F5F5;
            }

            ::-webkit-scrollbar
            {
                width: 10px;
                background-color: #F5F5F5;
            }

            ::-webkit-scrollbar-thumb
            {
                background-color: #3c8dbc;
                /* border: px solid #367fa9; */
            }
        </style>
    @endpush
@section('content')

<div class='panel panel-default'>

        <div class='panel-body'>
            <div class="row">   
                <div class="col-md-12" style="overflow-x: scroll">
                    <!-- <button type="button" id="btn-export" class="btn btn-primary btn-sm btn-export" data-toggle="modal" data-target="#myModal" style="margin-bottom:10px"><i class="fa fa-download"></i>
                        <span>Export Filter</span>
                    </button> -->
                    <button type="button" id="btn-export" class="btn btn-primary btn-sm btn-export" style="margin-bottom:10px"><i class="fa fa-download"></i>
                        <span>Export Data</span>
                    </button>
                    <table width="150%" id="table_dashboards" style="overflow-x: scroll">
                        <thead style="background-color: #F5F5F5; color:#3c8dbc">
                            <tr class="active">
                                <th style="width:2%">Action</th>
                                <th style="width:6%">Status</th>
                                <th style="width:8%">Erf Number</th>
                                <th style="width:8%">Verified Date</th>
                                <th style="width:8%">First Name</th>
                                <th style="width:8%">Last Name</th>
                                <th style="width:10%">Screen Date</th>
                                <th style="width:8%">Created By</th>
                                <th style="width:10%">Created At</th>
                                <th style="width:8%">Updated By</th>
                                <th style="width:8%">Updated At</th>
                                <th style="width:8%">First Interview</th>
                                <th style="width:8%">Final Interview</th>
                                <th style="width:8%">Job Offer</th>
                                <th style="width:8%">For Comparison</th>
                                <th style="width:8%">Cancelled</th>
                                <th style="width:10%">Rejected</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($getData as $val)
                        <tr>
                            <td style="text-align:center">   
                            @if(!in_array($val->status, [5,8,31]))     
                            <a data-toggle="tooltip" data-placement="right" title="Edit" class='btn btn-xs btn-success' href='{{CRUDBooster::mainpath("edit-applicant/".$val->apid)."?return_url=".urlencode(Request::fullUrl())}}'><i class='fa fa-pencil'></i></a>                                         
                            @else
                            <a data-toggle="tooltip" data-placement="right" title="View" class='btn btn-xs btn-primary' href='{{CRUDBooster::mainpath("detail-applicant/".$val->apid)."?return_url=".urlencode(Request::fullUrl())}}'><i class='fa fa-eye'></i></a>   
                            @endif
                            </td>  
                            @if($val['status'] == 8)
                            <td style="text-align:center">
                            <label class="label label-danger" style="align:center; font-size:10px">{{$val['status_description']}}</label>
                            </td>
                            @elseif($val['status'] == 5)
                            <td style="text-align:center">
                            <label class="label label-danger" style="align:center; font-size:10px">{{$val['status_description']}}</label>
                            </td>
                            @elseif($val['status'] == 34)
                            <td style="text-align:center">
                            <label class="label label-info" style="align:center; font-size:10px">{{$val['status_description']}}</label>
                            </td>
                            @elseif($val['status'] == 35)
                            <td style="text-align:center">
                            <label class="label label-info" style="align:center; font-size:10px">{{$val['status_description']}}</label>
                            </td>
                            @elseif($val['status'] == 42)
                            <td style="text-align:center">
                            <label class="label label-info" style="align:center; font-size:10px">{{$val['status_description']}}</label>
                            </td>
                            @elseif($val['status'] == 47)
                            <td style="text-align:center">
                            <label class="label label-info" style="align:center; font-size:10px">{{$val['status_description']}}</label>
                            </td>
                            @else
                            <td style="text-align:center">
                            <label class="label label-success" style="align:center; font-size:10px">{{$val['status_description']}}</label>
                            </td>
                            @endif 
                            <td>{{$val->erf_number}}</td>
                            <td>{{$val->approved_hr_at}}</td>
                            <td>{{$val->first_name}}</td>  
                            <td>{{$val->last_name}}</td>
                            <td>{{$val->screen_date}}</td>     
                            <td>{{$val->created_name}}</td>   
                            <td>{{$val->created_at}}</td>                                                                
                            <td>{{$val->updated_by}}</td>  
                            <td>{{$val->updated_at}}</td>  
                            <td>{{$val->first_interview}}</td>  
                            <td>{{$val->final_interview}}</td>  
                            <td>{{$val->job_offer}}</td>  
                            <td>{{$val->for_comparison}}</td> 
                            <td>{{$val->Cancelled}}</td>   
                            <td>{{$val->rejected}}</td>  
                        </tr>
                            
                        @endforeach
                        </tbody>
                    </table>
                </div>
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
                        <form  id="filterForm" method='post' target='_blank' name="filterForm" action="{{route('erf-search')}}">
                            <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label require"> Erf No.</label>
                                        <select selected data-placeholder="-- Please Select ERF --" id="erf_number" name="erf_number" class="form-select erf" style="width:100%;">
                                            @foreach($erf_number as $res)
                                                <option value=""></option>
                                                <option value="{{ $res->reference_number }}">{{ $res->reference_number }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>  
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label require"> Status.</label>
                                        <select selected data-placeholder="-- Please Select Status --" id="status" name="status" class="form-select erf" style="width:100%;">
                                            @foreach($statuses as $res)
                                                <option value=""></option>
                                                <option value="{{ $res->id }}">{{ $res->status_description }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>  
                            </div>
                            <div class="row">
                            <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label require"> Screen Date</label>
                                        <input type="text" class="form-control date" name="screen_date"  id="screen_date" placeholder="Please Select Date">
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
       var table;
       $(document).ready(function() {
            table = $("#table_dashboards").DataTable({
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
                        title: "Applicant",
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
            $("#btn-export").on("click", function () {
                table.button(".buttons-excel").trigger();
            });
            $('#erf_number,#status').select2({})
            $(".date").datetimepicker({
                    viewMode: "days",
                    format: "YYYY-MM-DD",
                    dayViewHeaderFormat: "MMMM YYYY",
            });
           
            // $('#btnExport').click(function(event) {
            //     event.preventDefault();
            //     $.ajax({
            //         data: $('#filterForm').serialize(),
            //         url: "{{ route('export-applicant') }}",
            //         type: "POST",
            //         dataType: 'json',
            //         success: function (data) {
            //             if (data.status == 200) {
                         
            //             }                            
            //         },
            //         error: function (data) {
            //             console.log('Error:', data);
            //         }
            //     });      
            // });
        
    });
    </script>
@endpush