<?php

namespace App\Http\Controllers;

use Session;
use App\Brand;
use Illuminate\Http\Request;
use DB;
use CRUDBooster;
use App\BodyRequest;
use App\HeaderRequest;
use App\MoveOrder;
use App\Models\ReturnTransferAssets;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Contracts\Cache\LockTimeoutException;
use App\Exports\ExportMultipleByApprover;
use DataTables;

class AdminReportsv2Controller extends Controller {
    public function __construct() {
        // Register ENUM type
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex(Request $request) {
        //First, Add an auth
        //dd($request->ajax());
        if(!CRUDBooster::isView()) CRUDBooster::redirect(CRUDBooster::adminPath(),trans('crudbooster.denied_access'));
        
        //Create your own query 
        $data = [];
        $data['page_title'] = 'Request Assets Status Reports';

        $result_one = BodyRequest::arrayone();
        $result_two = ReturnTransferAssets::arraytwo();
        $suppliesMarketing = [];
        $suppliesMarketingCon = [];

        foreach($result_one as $smVal){
            $suppliesMarketingCon['id'] = $smVal['requestid'];
            $suppliesMarketingCon['reference_number'] = $smVal['reference_number'];
            $suppliesMarketingCon['requested_by'] = $smVal['requestedby'];
            $suppliesMarketingCon['department'] = $smVal['department'] ? $smVal['department'] : $smVal['store_branch'];
            $suppliesMarketingCon['store_branch'] = $smVal['store_branch'] ? $smVal['store_branch'] : $smVal['department'];
            $suppliesMarketingCon['transaction_type'] = "REQUEST";
            $bodyStatus = $smVal['body_statuses_description'] ? $smVal['body_statuses_description'] : $smVal['status_description'];
            if(in_array($smVal['request_type_id'], [6,7])){
                $suppliesMarketingCon['status'] = $smVal['status_description'];
                $suppliesMarketingCon['description'] = $smVal['body_description'];
                $suppliesMarketingCon['request_quantity'] = $smVal['body_quantity'];
                $suppliesMarketingCon['request_type'] = $smVal['body_category_id'];
                $suppliesMarketingCon['mo_reference'] = $smVal['body_mo_so_num'];
                $suppliesMarketingCon['mo_item_code'] = $smVal['body_digits_code'];
                $suppliesMarketingCon['mo_item_description'] = $smVal['body_description'];
                $suppliesMarketingCon['mo_qty_serve_qty'] = $smVal['serve_qty'];
            }else{
                $suppliesMarketingCon['status'] = isset($smVal['mo_reference_number']) ? $smVal['mo_statuses_description'] : $bodyStatus;
                $suppliesMarketingCon['description'] = $smVal['body_description'];
                $suppliesMarketingCon['request_quantity'] = $smVal['body_quantity'];
                $suppliesMarketingCon['request_type'] = $smVal['body_category_id'];
                $suppliesMarketingCon['mo_reference'] = $smVal['mo_reference_number'];
                $suppliesMarketingCon['mo_item_code'] = $smVal['digits_code'];
                $suppliesMarketingCon['mo_item_description'] = $smVal['item_description'];
                $suppliesMarketingCon['mo_qty_serve_qty'] = $smVal['quantity'];
            }
            $suppliesMarketingCon['requested_date'] = $smVal['created_at'];
            $suppliesMarketingCon['transacted_by'] = $smVal['taggedby'];
            $suppliesMarketingCon['transacted_date'] = $smVal['transacted_date'];
            $suppliesMarketing[] = $suppliesMarketingCon;
        }

        $returnTransfer = [];
        $returnTransferCon = [];
        foreach($result_two as $rtVal){
            $returnTransferCon['id'] = $rtVal['requestid'];
            $returnTransferCon['reference_number'] = $rtVal['reference_no'];
            $returnTransferCon['requested_by'] = $rtVal['employee_name'];
            $returnTransferCon['department'] = $rtVal['department_name'] ? $rtVal['department_name'] : $rtVal['store_branch'];
            $returnTransferCon['store_branch'] = $rtVal['store_branch'] ? $rtVal['store_branch'] : $rtVal['department_name'];
            $returnTransferCon['status'] = $rtVal['status_description'];
            $returnTransferCon['description'] = $rtVal['description'];
            $returnTransferCon['request_quantity'] = $rtVal['quantity'];
            $returnTransferCon['transaction_type'] = $rtVal['request_type'];
            $returnTransferCon['request_type'] = $rtVal['request_name'];
            $returnTransferCon['mo_reference'] = $rtVal['reference_no'];
            $returnTransferCon['mo_item_code'] = $rtVal['digits_code'];
            $returnTransferCon['mo_item_description'] = $rtVal['description'];
            $returnTransferCon['mo_qty_serve_qty'] = $rtVal['quantity'];
            $returnTransferCon['requested_date'] = $rtVal['requested_date'];
            $returnTransferCon['transacted_by'] = $rtVal['receivedby'];
            $returnTransferCon['transacted_date'] = $rtVal['transacted_date'];
            $returnTransfer[] = $returnTransferCon;
        }
        //dd($returnTransfer);
        $data['finalData'] = array_merge($suppliesMarketing, $returnTransfer);

        $data['categories'] = DB::table('requests')->whereIn('id', [1,2,3,5,6,7,8])->where('status', 'ACTIVE')
                                                   ->orderby('request_name', 'asc')
                                                   ->get();
        if ($request->ajax()) {                                           
           return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
    
                        $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';

                        return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return $this->view('assets.purchasing-reports',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        //
    }
}
