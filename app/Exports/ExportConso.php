<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\BodyRequest;

class ExportConso implements FromCollection, WithHeadings
{
    //use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){
        return BodyRequest::leftjoin('header_request', 'body_request.header_request_id', '=', 'header_request.id')
        ->leftjoin('request_type', 'header_request.purpose', '=', 'request_type.id')
        ->leftjoin('companies', 'header_request.company_name', '=', 'companies.id')
        ->leftjoin('departments', 'header_request.department', '=', 'departments.id')
        ->leftjoin('positions', 'header_request.position', '=', 'positions.id')
        ->leftjoin('locations', 'header_request.store_branch', '=', 'locations.id')
        ->leftjoin('cms_users as requested', 'header_request.created_by','=', 'requested.id')
        ->leftjoin('cms_users as approved', 'header_request.approved_by','=', 'approved.id')
        ->leftjoin('cms_users as recommended', 'header_request.recommended_by','=', 'recommended.id')
        ->leftjoin('cms_users as tagged', 'header_request.purchased2_by','=', 'tagged.id')
        ->leftjoin('statuses', 'header_request.status_id', '=', 'statuses.id')
        ->select(
          'statuses.status_description',
          'header_request.reference_number',
          'body_request.digits_code',
          'body_request.item_description',
          'body_request.category_id',
          'body_request.sub_category_id',
          'body_request.quantity',
          'body_request.replenish_qty',
          'body_request.reorder_qty',
          'body_request.serve_qty',
          'body_request.unserved_qty'
        )->get();
    }

    public function headings(): array
    {
        return [
                "Status",
                "Reference Number", 
                "Digits Code",
                "Item Description",
                "Category", 
                "Sub Category",
                "Quantity",
                "Replenish Qty",
                "Re Order Qty",
                "Served Qty",
                "Unserved Qty"
               ];
    }
}
