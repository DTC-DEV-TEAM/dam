<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\HeadingRowImport;
use App\Imports\ItemMasterImport;
use Illuminate\Http\Request;
use App\Imports\FulfillmentUpload;
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
}

?>
