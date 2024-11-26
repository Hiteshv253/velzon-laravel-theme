<?php
namespace App\Http\Controllers\AuditManagement\AuditManagement;

// use Illuminate\Contracts\Support\Renderable;
use Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\AuditReport;
use Maatwebsite\Excel\Facades\Excel;
use Auth;


class AuditBulkUploadController extends Controller{
    public function index() {
        $user = Auth::user();
        if (!$user->can('Access Audit Bulk_Upload')) {
            abort(403, 'Access denied');
        }
        return view('hvl.audit_management.audit.bulk_upload');
    }
    public function saveBulkUpload(Request $request) {
        try {
            DB::beginTransaction();
            $ExcelArray = Excel::toArray(new Controller(), $request->file('excel_file'));

            $setdata = $this->setData("Add",$ExcelArray[0]);
            if($setdata['status'] != 'success'){
                return self::index()->withErrors("Please correct Excel data.")->with(['prepare_data' => $setdata]);
                DB::rollBack();
            }else{
                $helper = new Helper();
                foreach($setdata['response_data'] as $prepare_data){
                    $date_array = $helper->getFrequancyDateList( $prepare_data['start_date'],$prepare_data['end_date'],$prepare_data['frequency']);
                    foreach($date_array as $audit_date){
                        $audit_obj = new AuditReport();
                        $audit_obj->audit_type = $prepare_data['audit_type'];
                        $audit_obj->customer_id = $prepare_data['customer_id'];
                        $audit_obj->schedule_date = $audit_date ." ".$prepare_data['schedule_time'];
                        $audit_obj->schedule_notes = $prepare_data['remark'];
                        $audit_obj->save();        
                    }
                }
                DB::commit();
            }
        return redirect()->route('admin.audit_bulk_update.index')->with('success', 'Audit Excel data has been uploaded successfully');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            DB::rollBack();
            return self::index($request)->withErrors("Please correct Excel data# : " . ($line_no + 1) . " is " . $e->getMessage());
        }   
    }
    public function updateBulkUpload(Request $request) {    
        try {
            $ExcelArray = Excel::toArray(new Controller(), $request->file('excel_file'));
            $setdata = $this->setData("Edit",$ExcelArray[0]);
            DB::beginTransaction();
            if($setdata['status'] != 'success'){
                return self::index()->withErrors("Please correct Excel data.")->with(['prepare_data' => $setdata]);
                DB::rollBack();
            }else{
                $helper = new Helper();
                foreach($setdata['response_data'] as $prepare_data){
                    AuditReport::find($prepare_data['audit_id'])->update([
                        'audit_type'=>$prepare_data['audit_type'],
                        'customer_id'=>$prepare_data['customer_id'],
                        'schedule_notes'=>$prepare_data['remark'],
                        'schedule_date'=>date('y-m-d H:i:s',strtotime(date('y-m-d',strtotime($prepare_data['schedule_date'])) ." ".$prepare_data['schedule_time'])) ,
                    ]);
                }
                DB::commit();
                return redirect()->route('admin.audit_bulk_update.index')->with('success', 'Audit Excel data has been uploaded successfully');
            }
    
        }catch(Exception $e){
            DB::rollBack();
            return self::index($request)->withErrors("Please correct Excel data# : " . ($line_no + 1) . " is " . $e->getMessage());
        }
     }
    
    public function deleteBulkUpload(Request $request) {
        try {
            DB::beginTransaction();
            $ExcelArray = Excel::toArray(new Controller(), $request->file('excel_file'));
            $setdata = $this->setData("Delete",$ExcelArray[0]);
        
            if($setdata['status'] != 'success'){
                return self::index()->withErrors("Please correct Excel data.")->with(['prepare_data' => $setdata]);
                DB::rollBack();
            }else{
                foreach($setdata['response_data'] as $prepare_data){
                    AuditReport::find($prepare_data['audit_id'])->delete();
                }
                DB::commit();
                return redirect()->route('admin.audit_bulk_update.index')->with('success', 'Audit Excel data has been uploaded successfully');
            }
        }catch(Exception $e){
            DB::rollBack();
            return self::index($request)->withErrors("Please correct Excel data# : " . ($line_no + 1) . " is " . $e->getMessage());
        }
    }
    private function setData($opration,$all_row){
        $haveInvalidData = false;
        $error_data = [];
        $response=[];
        $helper = new Helper();
        if($opration =="Add"){
            $header = $this->addHeaderTitle();
            $excel_file_row_data = $this->addExcelFileData($all_row);
            foreach($all_row as $all_data_error_index=>$row){              
                $audit_type = $this->getAuditType();
                $customer = $this->getCustomerCode();
                $frequency = $this->getFrequencyList();                
                if($all_data_error_index == 0){
                    continue;
                }
                if (($row[0] == null || $row[0] == '' )&& ($row[1] == null || $row[1] == '' ) ) {
                    continue;
                }
               
                if (!isset($row[0])) {
                    $haveInvalidData = true;
                    $error_data[$all_data_error_index]['audit_type'] = 'Row ' . $all_data_error_index . ' : Audit Type Cannot be blank';
                }
                if (!isset($audit_type[strtoupper($row[0])])) {
                    $haveInvalidData = true;
                    $error_data[$all_data_error_index]['audit_type'] = 'Row ' . $all_data_error_index . ' : Audit Type Invalid';
                }
                if (!isset($row[1])) {
                    $haveInvalidData = true;
                    $error_data[$all_data_error_index]['customer_code'] = 'Row ' . $all_data_error_index . ' : Customer code Cannot be blank';
                }
                if (!isset($customer[strtoupper($row[1])])) {
                    $haveInvalidData = true;
                    $error_data[$all_data_error_index]['customer_code'] = 'Row ' . $all_data_error_index . ' : Customer code Invalid';
                }
                
                if (!isset($row[2])) {
                    $haveInvalidData = true;
                    $error_data[$all_data_error_index]['start_date'] = 'Row ' . $all_data_error_index . ' : Start Date cannot be blank';
                }
                if (!isset($row[3])) {
                    $haveInvalidData = true;
                    $error_data[$all_data_error_index]['end_date'] = 'Row ' . $all_data_error_index . ' : End Date cannot be blank';
                }

                
                if (!isset($row[4])) {
                    $haveInvalidData = true;
                    $error_data[$all_data_error_index]['frequency'] = 'Row ' . $all_data_error_index . ' : Frequency Cannot be blank';
                }
                if (!isset($frequency[strtoupper($row[4])])) {
                    $haveInvalidData = true;
                    $error_data[$all_data_error_index]['frequency'] = 'Row ' . $all_data_error_index . ' : Frequency Name Invalid';
                }
                if (!isset($row[5])) {
                    $haveInvalidData = true;
                    $error_data[$all_data_error_index]['schedule_time'] = 'Row ' . $all_data_error_index . ' : Schedule Time Cannot be blank';
                }
                $response[$all_data_error_index] = [
                    'audit_type' => $row[0],
                    'customer_code' => $row[1],
                    'customer_id' => $customer[strtoupper($row[1])],
                    'start_date' => $helper->transformDate($row[2]),
                    'end_date' => $helper->transformDate($row[3]),
                    'frequency' => trim(strtolower($row[4])),
                    'schedule_time' => Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[5]))->format('H:i'),
                    'remark' => trim($row[6]),
                ];
            }
        }
        if($opration =="Edit"){
            $audit_id = $this->getAuditId(); 
            $audit_type = $this->getAuditType();
            $customer = $this->getCustomerCode();
            $header = $this->editHeaderTitle();
            $excel_file_row_data = $this->editExcelFileData($all_row);
            foreach($all_row as $all_data_error_index=>$row){
                
                if($all_data_error_index == 0){
                    continue;
                }
                if (($row[0] == null || $row[0] == '' )&& ($row[1] == null || $row[1] == '' ) ) {
                    continue;
                }
                if(!in_array($row[0],$audit_id)){
                    $haveInvalidData = true;
                    $error_data[$all_data_error_index]['audit_transaction_no'] = 'Row ' . $all_data_error_index . ' : Audit Not Found';
                }
                if (!isset($row[1])) {
                    $haveInvalidData = true;
                    $error_data[$all_data_error_index]['audit_type'] = 'Row ' . $all_data_error_index . ' : Audit Type Cannot be blank';
                }
                if (!isset($audit_type[strtoupper($row[1])])) {
                    $haveInvalidData = true;
                    $error_data[$all_data_error_index]['audit_type'] = 'Row ' . $all_data_error_index . ' : Audit Type Invalid';
                }
                

                if (!isset($row[2])) {
                    $haveInvalidData = true;
                    $error_data[$all_data_error_index]['customer_code'] = 'Row ' . $all_data_error_index . ' : Customer code Cannot be blank';
                }

                if (!isset($customer[strtoupper($row[2])])) {
                    $haveInvalidData = true;
                    $error_data[$all_data_error_index]['customer_code'] = 'Row ' . $all_data_error_index . ' : Customer code Invalid';
                }
                if (!isset($row[3])) {
                    $haveInvalidData = true;
                    $error_data[$all_data_error_index]['schedule_time'] = 'Row ' . $all_data_error_index . ' : Schedule Time Cannot be blank';
                }
                $response[$all_data_error_index] = [
                    'audit_id'=>$row[0],
                    'audit_type' =>  $audit_type[strtoupper($row[1])],
                    'customer_code' => $row[2],
                    'customer_id' => $customer[strtoupper($row[2])],
                    'schedule_date'=>$helper->transformDate($row[3]),
                    'schedule_time' => Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[4]))->format('H:i:s'),
                    'remark' => trim($row[5]),
                ];
            }

        }
        if($opration =="Delete"){
            $header = $this->deleteHeaderTitle();
            $excel_file_row_data = $this->deleteExcelFileData($all_row);
            foreach($all_row as $all_data_error_index=>$row){
                $audit_id = $this->getAuditId(); 
                if($all_data_error_index == 0){
                    continue;
                }
                if (($row[0] == null || $row[0] == '' )&& ($row[1] == null || $row[1] == '' ) ) {
                    continue;
                }
                if(!in_array($row[0],$audit_id)){
                    $haveInvalidData = true;
                    $error_data[$all_data_error_index]['audit_transaction_no'] = 'Row ' . $all_data_error_index . ' : Audit Not Found';
                }
                $response[$all_data_error_index] = [
                    'audit_id' => $row[0],
                ];
            }
        }
        return [
            'status' => ($haveInvalidData == true) ? 'fail' : 'success',
            'response_data' => $response,
            'error_data' => $error_data,
            'header'=>$header,
            'excel_file_row_data'=>$excel_file_row_data
        ];
    }
    private function getAuditType(){
        return array_flip( [
            'adhoc'=>'ADHOC',
            'planned'=>'PLANNED',
        ]);        
    }
    private function getCustomerCode(){
        $customers_list = DB::table('hvl_customer_master')->pluck('customer_code','id')->toArray();
        $response =[];
        foreach($customers_list as $key => $customer_code) {
            $response[strtoupper($customer_code)] = $key;
        }
        return $response;
    }
  
    private function getFrequencyList(){
        return array_flip([
            'monthly'=>'MONTHLY',
            'quarterly'=>'QUARTERLY',
            'half_yearly'=>'HALF YEARLY',
            'yearly'=>'YEARLY'
        ]);
    }
    private function getAuditId(){
        return AuditReport::pluck('id')->toArray();
    }
    private function addHeaderTitle(){
        return [
            "audit_type"=>"Audit Type*",
            "customer_code"=>"Customer code*",
            "start_date"=>"Start Date (YYYY-MM-DD)*",
            "end_date"=>"End Date (YYYY-MM-DD) *",
            "frequency"=>"Frequency*",
            "schedule_time"=>"Schedule Time (HH:MM:SS AM/PM)*",
            "remark"=>"Remark"
        ];
    }
    private function addExcelFileData($excel_file_array){
        $responce =  [];
        $helper = new Helper();
        foreach($excel_file_array as $all_data_error_index=>$row ){
            if($all_data_error_index == 0){
                continue;
            }
            if (($row[0] == null || $row[0] == '' )&& ($row[1] == null || $row[1] == '' ) ) {
                continue;
            }
            $responce[$all_data_error_index]=[
                'audit_type'=>$row[0],
                'customer_code'=>$row[1],
                'start_date'=> $helper->transformDate($row[2]),
                'end_date'=>$helper->transformDate($row[3]),
                'frequency'=>$row[4],
                'schedule_time'=>Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[5]))->format('H:i:s'),
                'remark'=>$row[6],
            ];
        }
        return $responce;

    }
    private function editHeaderTitle(){
        return [
            "audit_transaction_no"=>"Audit Transaction No",
            "audit_type"=>"Audit Type*",
            "customer_code"=>"Customer Code",
            "start_date"=>"Start Date (YYYY-MM-DD)*",
            "schedule_time"=>"Schedule Time (HH:MM:SS AM/PM)*",
            "remark"=>"Remark"
        ];
    }
    private function editExcelFileData($excel_file_array){
        $responce =  [];
        $helper = new Helper();
        foreach($excel_file_array as $all_data_error_index=>$row ){
            if($all_data_error_index == 0){
                continue;
            }
            if (($row[0] == null || $row[0] == '' )&& ($row[1] == null || $row[1] == '' ) ) {
                continue;
            }
            $responce[$all_data_error_index]=[
                'audit_transaction_no'=>$row[0],
                'audit_type'=>$row[1],
                'customer_code'=>$row[2],
                'start_date'=> $helper->transformDate($row[3]),
                'schedule_time'=> Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[4]))->format('H:i:s'),$row[4],
                'remark'=>$row[5],
            ];
        }
        return $responce;
    }
    private function deleteHeaderTitle(){
        return [
            "audit_transaction_no"=>"Audit Transaction No",
        ];
    }
    
   
    private function deleteExcelFileData($excel_file_array){
        $responce =  [];

        foreach($excel_file_array as $all_data_error_index=>$row ){
            if($all_data_error_index == 0){
                continue;
            }
            if ($row[0] == null || $row[0] == '' ) {
                continue;
            }
            $responce[$all_data_error_index]=[
                'audit_transaction_no'=>$row[0],
            ];

        }
        return $responce;
    }  
}