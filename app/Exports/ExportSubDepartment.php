<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\SubDepartment;

class ExportSubDepartment implements FromQuery, WithHeadings, WithMapping
{
    //use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $filter_column;

    public function __construct($fields){
        $this->filter_column  = $fields;
     
    }
    public function headings(): array
    {
        return [
                "Status",
                "Erf Number",
                "First Name",
                "Last Name", 
                "Screen Date",
                "Created By",
                "Created At",
               ];
    }
    public function map($data): array {
        return [
            $data->department,
            $data->sub_department,
            $data->coa,
            $data->status,
            $data->created_name,
            $data->created_at
        ];
    }

    public function query(){

        $data = SubDepartment::query()
        ->leftjoin('departments', 'sub_department.department_id', '=', 'departments.id')
        ->leftjoin('cms_users as created', 'sub_department.created_by', '=', 'created.id')
        ->select(
                'departments.department_name as department',
                'sub_department.sub_department_name as sub_department',
                'sub_department.coa_id as coa',
                'sub_department.status as status',
                'created.name as created_name',
                'sub_department.created_at'
                );
                if ($this->filter_column) {
                    $filter_column = $this->filter_column;
        
                    $data->where(function($w) use ($filter_column) {
                        foreach($filter_column as $key=>$fc) {
        
                            $value = @$fc['value'];
                            $type  = @$fc['type'];
        
                            if($type == 'empty') {
                                $w->whereNull($key)->orWhere($key,'');
                                continue;
                            }
        
                            if($value=='' || $type=='') continue;
        
                            if($type == 'between') continue;
        
                            switch($type) {
                                default:
                                    if($key && $type && $value) $w->where($key,$type,$value);
                                break;
                                case 'like':
                                case 'not like':
                                    $value = '%'.$value.'%';
                                    if($key && $type && $value) $w->where($key,$type,$value);
                                break;
                                case 'in':
                                case 'not in':
                                    if($value) {
                                        if($key && $value) $w->whereIn($key,$value);
                                    }
                                break;
                            }
                        }
                    });
        
                    foreach($filter_column as $key=>$fc) {
                        $value = @$fc['value'];
                        $type  = @$fc['type'];
                        $sorting = @$fc['sorting'];
        
                        if($sorting!='') {
                            if($key) {
                                $data->orderby($key,$sorting);
                                $filter_is_orderby = true;
                            }
                        }
        
                        if ($type=='between') {
                            if($key && $value) $data->whereBetween($key,$value);
                        }
        
                        else {
                            continue;
                        }
                    }
                }
                return $data;
    }

  

    public function title(): string
    {
        return 'Sub Department';
    }
}
