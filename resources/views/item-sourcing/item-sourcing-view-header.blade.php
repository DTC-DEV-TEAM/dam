<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label require">{{ trans('message.form-label.employee_name') }}</label>
             
            <input type="text" class="form-control finput"  id="employee_name" name="employee_name"  required readonly value="{{$employeeinfos->bill_to}}"> 
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label require">{{ trans('message.form-label.company_name') }}</label>
            <input type="text" class="form-control finput"  id="company_name" name="company_name"  required readonly value="{{$employeeinfos->company_name_id}}">                                   
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label require">{{ trans('message.form-label.department') }}</label>
            <input type="text" class="form-control finput"  id="department" name="department"  required readonly value="{{$employeeinfos->department_name}}">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label require">{{ trans('message.form-label.position') }}</label>
            <input type="text" class="form-control finput"  id="position" name="position"  required readonly value="{{$employeeinfos->position_id}}">                                   
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label"><span style="color:red">*</span> Date Needed</label>
            <input class="form-control finput date" type="text" placeholder="Select Needed Date" name="date_needed" id="date_needed">
        </div>
    </div> 
</div>