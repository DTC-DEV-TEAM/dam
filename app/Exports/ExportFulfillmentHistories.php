<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\FulfillmentHistories;

class ExportFulfillmentHistories implements FromCollection, WithHeadings
{
    //use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){
        return FulfillmentHistories::select('*')->get();
    }

    public function headings(): array
    {
        return [
                "Arf Number",
                "Digits Code", 
                "DR No",
                "PO No",
                "DR Qty",
                "DR Type",
                "PO Qty",
               ];
    }
}
