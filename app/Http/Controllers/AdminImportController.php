<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\HeadingRowImport;
use App\Imports\ItemMasterImport;
use Illuminate\Http\Request;
use App\Imports\FulfillmentUpload;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use DB;
use CRUDBooster;
use Excel;

class AdminImportController extends \crocodicstudio\crudbooster\controllers\CBController
{
    public function fulfillmentUpload(Request $request) {
        $path_excel = $request->file('import_file')->store('temp');
        $path = storage_path('app').'/'.$path_excel;
        Excel::import(new FulfillmentUpload, $path);	
        CRUDBooster::redirect(CRUDBooster::adminpath('for_purchasing'), trans("Upload Successfully!"), 'success');
    }

    function downloadFulfillQtyTemplate() {
        $arrHeader = [
            "arf_number"         => "arf_number",
            "digits_code"        => "digits_code",
            "mo_so_num"          => "mo_so_num",
            "fulfill_qty"        => "fulfill_qty",
        ];
        $arrData = [
            "erf_number"         => "ARF-0000001",
            "digits_code"        => "40000054",
            "mo_so_num"          => "MOSO12345",
            "fulfill_qty"        => "10",
        ];
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->fromArray(array_values($arrHeader), null, 'A1');
        $spreadsheet->getActiveSheet()->fromArray($arrData, null, 'A2');
        $filename = "Fulfill-qty";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
}

?>
