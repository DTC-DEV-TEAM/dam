<!-- First you need to extend the CB layout -->
@extends('crudbooster::admin_template')
@section('content')
    <!-- Your custom  HTML goes here -->
    <div class='panel panel-default'>
    <table class='table table-hover table-striped table-bordered' id="table_dashboard">
    <thead>
        <tr class="active">
            <th width="auto">Name</th>
            <th width="auto">Description</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $row)
        <tr>
            <td>{{$row->email}}</td>
            <td>{{bcrypt($row->password)}}</td>
        </tr>
        @endforeach
    </tbody>
    </table>
</div>

@push('bottom')
<script type="text/javascript">
$("#table_dashboard").DataTable({
    pageLength:10
});

</script>
@endpush