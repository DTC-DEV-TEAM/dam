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
   

        <div class='panel-body'>
        <div class="row" style="margin:5px">   

        <button type="button" id="btn-export" class="btn btn-primary btn-sm btn-export" data-toggle="modal" data-target="#myModal" style="margin-bottom:10px"><i class="fa fa-download"></i>
            <span>Export Filter</span>
        </button>
            <table class='table table-hover table-striped table-bordered' id="table_dashboard">
                <thead>
                    <tr class="active">
                        <th style="text-align:center" width="auto">Status</th>
                        <th width="auto">Erf Number</th>
                        <th width="auto">First Name</th>
                        <th width="auto">Last Name</th>
                        <th width="auto">Screen Date</th>
                        <th width="auto">Created By</th>
                        <th width="auto">Created At</th>
                        <th width="auto">Updated By</th>
                        <th width="auto">Updated At</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($summary_report as $val)
                <tr>
                    @if($val['status'] == 8)
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
                    @else
                    <td style="text-align:center">
                     <label class="label label-success" style="align:center; font-size:10px">{{$val['status_description']}}</label>
                    </td>
                    @endif 
                    <td>{{$val->erf_number}}</td>
                    <td>{{$val->first_name}}</td>  
                    <td>{{$val->last_name}}</td>
                    <td>{{$val->screen_date}}</td>     
                    <td>{{$val->created_name}}</td>   
                    <td>{{$val->created_at}}</td>                                                                
                    <td>{{$val->updated_by}}</td>  
                    <td>{{$val->updated_at}}</td>  
                </tr>
                    
                @endforeach
                </tbody>
            </table>
        </div>   
        </div>

</div>

@endsection

@push('bottom')
    <script type="text/javascript">
       var table;
       $(document).ready(function() {
            $("#table_dashboard").DataTable({
                ordering:false,
                pageLength:25,
                language: {
                    searchPlaceholder: "Search"
                },
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"],
                    ],
            });
        
    });
    </script>
@endpush