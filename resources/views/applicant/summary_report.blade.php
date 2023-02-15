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

            #table_dashboard td.hover:hover {
                background-color:#3c8dbc;
                color: #fff !important;
                font-weight: bold;
                font-size: 14px;
                
            }

            a{
                color: #000;
            }

        </style>
    @endpush
@section('content')

<div class='panel panel-default'>
   

        <div class='panel-body'>
        <div class="row" style="margin:5px">   

        <button type="button" id="btn-export" class="btn btn-primary btn-sm btn-export" data-toggle="modal" data-target="#myModal" style="margin-bottom:10px"><i class="fa fa-download"></i>
            <span>Summary Report</span>
        </button>
        <form   method='post' target='_blank'>
            <table class='table table-hover table-striped table-bordered' id="table_dashboard">
                <thead>
                    <tr class="active">
                        <th width="auto" style="text-align:center">Erf Number</th>
                        <th width="auto" style="text-align:center">Jo Done</th>
                        <th width="auto" style="text-align:center">First Interview</th>
                        <th width="auto" style="text-align:center">Final Interview</th>
                        <th width="auto" style="text-align:center">Cancelled</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($getData as $val)
                <tr>
                    <td style="text-align:center"> {{$val->erf_number}}</td>
                    <td class="hover" id="hover" style="text-align:center"><a href='{{CRUDBooster::mainpath("summary-report/".$val->erf_number."-"."31")}}'></a>{{$val->jo_done}}</td>  
                    <td class="hover" id="hover" style="text-align:center"><a href='{{CRUDBooster::mainpath("summary-report/".$val->erf_number."-"."34")}}'></a>{{$val->first_interview}}</td>
                    <td class="hover" id="hover" style="text-align:center"><a href='{{CRUDBooster::mainpath("summary-report/".$val->erf_number."-"."35")}}'></a>{{$val->final_interview}}</td>    
                    <td class="hover" id="hover" style="text-align:center"><a href='{{CRUDBooster::mainpath("summary-report/".$val->erf_number."-"."8")}}'></a>{{$val->cancelled}}</td>     
                </tr>
                    
                @endforeach
                </tbody>
            </table>
        </form>
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
            $('#erf_number,#status').select2({})
           
            $('#table_dashboard td.hover').hover(function() {
                $(this).addClass('hover');
            }, function() {
                $(this).removeClass('hover');
            });

            $(document).on('click', '#hover', function() {
                var href = $('a', this).attr('href');
                window.location.href = href;
            });

    });
    </script>
@endpush