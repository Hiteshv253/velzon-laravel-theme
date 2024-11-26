<?php

namespace App\Http\Controllers\CustomerMaster;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\hvl\CustomerMaster;
use App\Models\Employee;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportActivity;
use App\Http\Requests\ExcelBulkUploadRequest;
use App\Helpers\Helper;
use Auth;

class CustomerBulkUploadController extends Controller {
    private $customer_code;
    private $customer_ids;
    private $exsiting_customer;
    private $login_user_mail;
    private  $helper;
    
    public function index(){     
        return view('hvl.customermaster.bulk_upload');    
   
    }    
    public function saveCustomerManagementBulkUpload(ExcelBulkUploadRequest $request){
        
        $line_no = 0;
        $flag = false;
        $bulk_upload = [];
        try{
            DB::beginTransaction();
            $customer_code_id = $this->getCustomerIds();
            $ExcelArray = Excel::toArray(new Controller(), $request->file('excel_file'));
            $this->exsiting_customer = $this->getExsitingCustomer();
            $this->login_user_mail = Auth::User()->email;
            $exsiting_employee = array_change_key_case(array_flip($this->getExsitingEmployee()),CASE_UPPER);
            $this->helper = new Helper();
          
            foreach($ExcelArray[0] as $line_no=>$row){
                if($line_no == 0){
                    continue;
                }
                if($row[1] == null || (!isset($row[1])) ){
                    continue;
               }
                $row_data = $row;
                $row  = $this->setData($row);
                $customer_code = $row['customer_code'];
                $customer_occur_count = count(array_filter($this->exsiting_customer,function($a)use ($customer_code){return $a==$customer_code;}));  
              
             
                if($row['action'] == 'ADD'){
                    if( $customer_occur_count > 0){
                        throw new \ErrorException($row['customer_code'].' with Customer Already Existing');
                    }
                    $insert_data = [
                        'customer_code'         =>  $row['customer_code'],
                        'customer_name'         =>  $row['customer_name'],
                        'customer_alias'        =>  $row['customer_alias'],
                        'billing_address'       =>  $row['billing_address'],
                        'billing_state'         =>  $row['billing_state'],
                        'billing_city'          =>  $row['billing_city'],
                        'billing_pincode'       =>  $row['billing_pincode'],
                        'contact_person'        =>  $row['contact_person'],
                        'contact_person_phone'  =>  $row['contact_person_phone'],
                        'billing_email'         =>  $row['billing_email'],
                        'billing_mobile'        =>  $row['billing_mobile'],
                        'operator'              =>  $row['operator'],
                        'operation_executive'   =>  $row['operation_executive'],
                        'sales_person'          =>  $row['sales_person'],
                        'reference'             =>  $row['reference'],
                        'status'                =>  $row['status'],
                        'create_date'           =>  $row['create_date'],
                        'shipping_address'      =>  $row['shipping_address'],
                        'shipping_state'        =>  $row['shipping_state'],
                        'shipping_city'         =>  $row['shipping_city'],
                        'shipping_pincode'      =>  $row['shipping_pincode'],
                        'credit_limit'          =>  $row['credit_limit'],
                        'gst_reges_type'        =>  $row['gst_reges_type'],
                        'gstin'                 =>  $row['gstin'],
                        'branch_name'           =>  $row['branch'],
                        'payment_mode'          =>  $row['payment_mode'],
                        'con_start_date'        =>  $row['con_start_date'],
                        'con_end_date'          =>  $row['con_end_date'],
                        'cust_value'            =>  $row['cust_value'],
                        'is_active'             =>  $row['is_active'],
                    ];
                    $created_customer = CustomerMaster::create($insert_data);
                     $param =[];
                    $param['module'] = 2;
                    $param['action'] = 1;
                    $param['system_data'] = $row;
                    $param['updated_data'] = $this->userUnderstandData($row['customer_code']);
                    $helper = new Helper();
                    $helper->addSystemAddLog($param);
                    $this->customer_ids[$row['customer_code']] = $created_customer->id;
                    $bulk_upload ['Add'][] = $row_data; 
                    $this->exsiting_customer[]= $row['customer_code'];
               
                }
                else if($row['action'] == 'EDIT'){
                      if($customer_occur_count == 1){
                        $customer_data = CustomerMaster::where('customer_code',$row['customer_code'])->first();
                        if(!$customer_data){
                            throw new \ErrorException('Employee Not Found');
                        }
                        $param['old_data'] = $this->userUnderstandData($row['customer_code']);
                        $customer_data->customer_name         =  $row['customer_name'];
                        $customer_data->customer_alias        =  $row['customer_alias'];
                        $customer_data->billing_address       =  $row['billing_address'];
                        $customer_data->billing_state         =  $row['billing_state'];
                        $customer_data->billing_city          =  $row['billing_city'];
                        $customer_data->billing_pincode       =  $row['billing_pincode'];
                        $customer_data->contact_person        =  $row['contact_person'];
                        $customer_data->contact_person_phone  =  $row['contact_person_phone'];
                        $customer_data->billing_email         =  $row['billing_email'];
                        $customer_data->billing_mobile        =  $row['billing_mobile'];
                        $customer_data->sales_person          =  $row['sales_person'];
                        $customer_data->operator              =  $row['operator'];
                        $customer_data->operation_executive   =  $row['operation_executive'];
                        $customer_data->reference             =  $row['reference'];
                        $customer_data->status                =  $row['status'];
                        $customer_data->create_date           =  $row['create_date'];
                        $customer_data->shipping_address      =  $row['shipping_address'];
                        $customer_data->shipping_state        =  $row['shipping_state'];
                        $customer_data->shipping_city         =  $row['shipping_city'];
                        $customer_data->shipping_pincode      =  $row['shipping_pincode'];
                        $customer_data->credit_limit          =  $row['credit_limit'];
                        $customer_data->gst_reges_type        =  $row['gst_reges_type'];
                        $customer_data->gstin                 =  $row['gstin'];
                        $customer_data->branch_name           =  $row['branch'];
                        $customer_data->payment_mode          =  $row['payment_mode'];
                        $customer_data->con_start_date        =  $row['con_start_date'];
                        $customer_data->con_end_date          =  $row['con_end_date'];
                        $customer_data->cust_value            =  $row['cust_value'];
                        $customer_data->is_active             =  $row['is_active'];
                        $customer_data->save();
                        // echo "<pre>";
                        // print_r($customer_data);
                        // die;
                        $param['module'] = 2;
                        $param['action'] = '2';
                        $param['updated_data'] = $this->userUnderstandData($row['customer_code']);
                        $param['log_key'] = 'Customer Code';
                        $param['log_value'] = $row['customer_code'];
                        $param['system_data'] = $row;
                        $helper = new Helper();
                        $helper->addSystemUpdateLog($param);
                        
                        $bulk_upload ['Edit'][] = $row_data;
                    }
                    else if($customer_occur_count < 1){
                        throw new \ErrorException('Employee Not Found => ' .$row['customer_code']);
                    }
                    else{
                        throw new \ErrorException( $row['customer_code']." Employee Have No Unique Id Please Make It Unique First");
                    }
                    
                }
               else if($row['action'] == 'DELETE'){
                   if($this->login_user_mail == 'hvl@gmail.com'){
                        if($customer_occur_count == 1){
                            $customer_master = new CustomerMaster();
                            $customer_record = $customer_master->where('id',$customer_code_id[$row['customer_code']])->first();
                            $customer_id = $customer_record->id;
                            if(!$customer_record){
                                throw new \ErrorException('Customer Not Found');
                            }
                            $param['old_data'] = $this->userUnderstandData($customer_record->customer_code);
                            $param['module'] = 2;
                            $param['action'] = 3;
                            $param['system_data'] = $row;
                            $helper = new Helper();
                            $helper->addSystemDeleteLog($param);
                            $customer_record->delete();
                            
                            DB::table('hvl_customer_employees')
                            ->where('customer_id',$customer_id)
                            ->delete();
                             $bulk_upload ['Delete'][] = $row_data;
                        }else if($customer_occur_count < 1){
                            throw new \ErrorException( 'Customer Not Found => ' .$row['customer_code']);
                        }else{
                            throw new \ErrorException( $row['customer_code']." Customer Have No Unique Customer Code Please Make It Unique First");
                        }
                   }
                }
                
                else if($row['action'] == 'ASSIGN CUSTOMERS'){
                    $employee_email = trim(strtoupper($row['employee_email']));
                    $employee_occur_count = count(array_filter($exsiting_employee,function($a)use ($employee_email){return $a==$employee_email;}));  
                    if($employee_occur_count > 1){
                        throw new \ErrorException($employee_email .' Employee id use to many time use in " Employee Managment ", Please First make Unique');
                    }else if($employee_occur_count < 1){
                        throw new \ErrorException($employee_email .' Your given employee id not found in our record please check it in "Employee Managment"');
                    }
                        $already_assgin =  DB::table('hvl_customer_employees')
                            ->where('customer_id',$this->customer_ids[trim(strtoupper( $row['customer_code'] ))])
                            ->where('employee_id',$row['employee'])->first();
                        
                        if($already_assgin){
                            throw new \ErrorException( $row['customer_code'] .' This Customer already assgined to the '.$employee_email );
                        }
                        else{
                            DB::table('hvl_customer_employees')->insert([
                                'customer_id'=>$this->customer_ids[trim(strtoupper( $row['customer_code'] ))],
                                'employee_id'=>$row['employee']
                            ]);
                            if($row['branch_id']){
                                $customer_model = $this->getCustomerByCustomerCode($row['customer_code']);
                                $customer_model->branch_name = $row['branch_id'];
                                $customer_model->save();
                            }
                            $bulk_upload ['Assign'][] = $row_data; 
                        }    
                }
                else if($row['action'] == 'UNASSIGN CUSTOMERS'){
                    
                    $employee_email = $row['employee_email'];
                    $employee_occur_count = count(array_filter($exsiting_employee,function($a)use ($employee_email){return $a==$employee_email;}));  
                    if($employee_occur_count > 1){
                        throw new \ErrorException($employee_email .' Employee id use to many time use in " Employee Managment ", Please first make unique');
                    }else if($employee_occur_count < 1){
                        throw new \ErrorException($employee_email .' Employee id use found, Please check it');
                    }
                    
                    $ready_assgin =  DB::table('hvl_customer_employees')
                    ->where('customer_id',$this->customer_ids[trim(strtoupper($row['customer_code'] ))])
                    ->where('employee_id',$row['employee'])->first();
                    
                    if($ready_assgin){
                        DB::table('hvl_customer_employees')
                        ->where('customer_id',$this->customer_ids[trim(strtoupper( $row['customer_code'] ))])
                        ->where('employee_id',$row['employee'])
                        ->delete();
                        $bulk_upload ['Unassign'][] = $row; 
                    }else{
                        throw new \ErrorException( $row['customer_code'] .' Customer already not linked to '.$employee_email );
                    }
                }
            }
            //for log
                foreach($bulk_upload as $action=>$row_data){
                    $param =[];
                    $param['module'] = 2;
                    $param['action'] =  strtoupper($action);
                    $param['system_data'] = $row_data;
                    $param['user_understand_data'] = $row_data;
                    $this->helper->AddSystemLog($param);
                }
            //end log
           
             DB::commit();
            return redirect()->route('admin.customermaster_bulkupload.index')->with('success', 'Data has been uploaded successfully');        
        }catch (\Exception $e) {
            DB::rollBack();
             return self::index($request)->withErrors("row no = ".($line_no+1)."  Error ".$e->getMessage());
        }
    }
    private function setData($row){
        $response=[];
        $this->customer_ids = $this->getCustomerIds();
        $employee_id = $this->getEmployee();
        $employee_id_name = $this->getEmployeeWithName();
        $states = $this->getStates();
        $branch = $this->getBranch();
        $common_city = $this->getCommonCity(); 
        $common_status_citys = $this->getCommonCitywithStats();
        $payment_option    = $this->getPaymentOption();
        $customer_status = $this->getCustomerStatus();
        $Gst_reg_option = $this->getGstRegistrationOption(); 
        $helper = new Helper();
       
        $action =  trim(strtoupper($row[0]));
            if($action == 'ASSIGN CUSTOMERS'){
             
                $response = [
                    'action'    =>  $action,
                    'customer_code'  =>  $row[1],
                    'customer'  =>  $this->customer_ids[trim(strtoupper($row[1]))],
                    'employee_email'  =>  $row[2],
                    'employee'  =>  $employee_id[trim(strtoupper($row[2]))],
                    'branch'  =>  (isset($row[3]) && $row[3] != null && $row[3] != '' )?$row[3]:null,
                    'branch_id'  =>(isset($row[3]) && $row[3] != null && $row[3] != '' )?$branch[trim(strtoupper($row[3]))]:null,
                ];
                
            }
            if($action == 'UNASSIGN CUSTOMERS'){
              
                $response = [
                    'action'    =>  $action,
                    'customer_code'  =>  $row[1],
                    'customer'  =>  $this->customer_ids[trim(strtoupper($row[1]))],
                    'employee_email'  =>  $row[2],
                    'employee'  =>  $employee_id[trim(strtoupper($row[2]))],
                ];
            }
            else if($action == 'DELETE'){
                if($this->login_user_mail == 'hvl@gmail.com'){
                    $response = [
                    'action'    =>  $action,
                    'customer_code'  =>  $row[1]
                    ];
                }else{
                    throw new \ErrorException( "Customer Delete:only Super Admin Do");
                }    
            }
            else if( $action == 'ADD' || $action == 'EDIT' ){   
                if(!$row[0]){
                   throw new \ErrorException( 'Action : Not Found'); 
                }
                
                if(!$row[1]){
                   throw new \ErrorException( 'Customer Code : Not Found'); 
                }
                
                if(!$row[2]){
                   throw new \ErrorException( 'Customer Name:Not Found'); 
                }
                
                if(!$row[3]){
                   throw new \ErrorException( 'Customer Alias:Not Found'); 
                }
                
                if(!$row[4]){
                   throw new \ErrorException( 'Billing Address:Not Found'); 
                }
                
                if(!$row[5]){
                   throw new \ErrorException( 'Billing State : Not Found'); 
                }
                
                if(!isset($states[trim(strtoupper($row[5]))])){
                   throw new \ErrorException( 'Billing State : '.$row[5].' Invalild'); 
                }
                
                if(!$row[6]){
                   throw new \ErrorException('Billing City : Not Found'); 
                }
                
                if(!isset($common_city[trim(strtoupper($row[6]))])){
                   throw new \ErrorException( 'Billing City : '.$row[6].' Invalid'); 
                }
                
                // if( !isset( $common_status_citys[$states[trim(strtoupper($row[5]))]][trim(strtoupper($row[6]))] ) ){
                //   throw new \ErrorException( $row[6].': not Found in '.trim(strtoupper($row[5]))); 
                // }
                
                if(!isset($row[8])){
                   throw new \ErrorException( 'Contact Person :Not Found'); 
                }
                
                if(!isset($row[9])){
                   throw new \ErrorException( 'Contact Person Phone : Not Found'); 
                }
                if(!(isset($row[9]) && is_numeric($row[9]))){
                   throw new \ErrorException( 'Contact Person Phone : Invalid'); 
                }
                
                if(!isset($row[11])){
                   throw new \ErrorException( 'Billing Mobile : Not Found'); 
                }
                
                
                if( (isset($row[13])) && (!isset($employee_id_name[trim(strtoupper($row[13]))]))){
                   throw new \ErrorException( 'Operation Executive : '.$row[13].' Invalid'); 
                }
            
            
                if(!isset($row[14])){
                   throw new \ErrorException( 'Sales Person : Not Found'); 
                }
                
                if(!isset($employee_id_name[trim(strtoupper($row[14]))])){
                   throw new \ErrorException( 'Sales Person : '.$row[14].' Invalid'); 
                }
                
                if(!isset($row[16])){
                   throw new \ErrorException( 'Create Date : Not Found'); 
                }
                
                if(!isset($row[17])){
                   throw new \ErrorException( 'Shipping Address : Not Found'); 
                }
                
                if(!isset($row[18])){
                   throw new \ErrorException( 'Shipping State : Not Found'); 
                }
                
                
                if(!isset($states[trim(strtoupper($row[18]))])){
                   throw new \ErrorException( 'Billing State : '.$row[18].' Invalid'); 
                }
                
                
                if(!isset($row[19])){
                   throw new \ErrorException( 'Shipping City : Not Found'); 
                }
                
                // if( !isset( $common_status_citys[$states[trim(strtoupper($row[18]))]][trim(strtoupper($row[19]))] ) ){
                //   throw new \ErrorException( $row[19].': not Found in '.trim(strtoupper($row[18]))); 
                // }
                
                
                
                if(!isset($common_city[trim(strtoupper($row[19]))])){
                   throw new \ErrorException( 'Shipping City :'.$row[19] .' Invalid'); 
                }
                
                if(!isset($row[22])){
                   throw new \ErrorException( 'Gst Option : Not Found'); 
                }
                
                if(!isset($Gst_reg_option[trim(strtoupper($row[22]))])){
                   throw new \ErrorException( 'Gst Option '.$row[22] .' Invalid'); 
                }
                
                if($Gst_reg_option[trim(strtoupper($row[22]))] == 'YES' &&  $row[22] == null){
                   throw new \ErrorException( 'Gst No : Not Found'); 
                }
                
                if(!isset($row[24])){
                   throw new \ErrorException( 'Branch : Not Found'); 
                }
                
                if(!isset($branch[trim(strtoupper($row[24]))])){
                   throw new \ErrorException( 'Branch : '.$row[24].' Invalid'); 
                }
                
                
                
                if(!isset($row[25])){
                   throw new \ErrorException( 'Payment_option : Not Found'); 
                }
                
                if(!isset($payment_option[trim(strtoupper($row[25]))])){
                   throw new \ErrorException( 'Valid Payment : '.$row[25]); 
                }
                
                
                if(!isset($row[26])){
                   throw new \ErrorException( 'Contract Start Date : Not Found'); 
                }
                
                if(!isset($row[27])){
                   throw new \ErrorException( 'Contract End Date  : Not Found'); 
                }
                
                if(!isset($row[29])){
                   throw new \ErrorException( 'Status : Not Found'); 
                }
                
                if(!isset($customer_status[trim(strtoupper($row[29]))])){
                   throw new \ErrorException( 'Status :'.$row[29].' Invalid'); 
                }
                
                $response = [
                    'action'                =>  $action ,
                    'customer_code'         =>  $row[1],
                    'customer_name'         =>  $row[2],
                    'customer_alias'        =>  $row[3],
                    'billing_address'       =>  $row[4],
                    'billing_state'         =>  $states[trim(strtoupper($row[5]))],
                    'billing_city'          =>  $common_city[trim(strtoupper($row[6]))],
                    'billing_pincode'       =>  $row[7],
                    'contact_person'        =>  $row[8],
                    'contact_person_phone'  =>  $row[9],
                    'billing_email'         =>  $row[10],
                    'billing_mobile'        =>  $row[11],
                    'operator'              =>  $row[12],
                    'operation_executive'   =>  $row[13],
                    'sales_person'          =>  $employee_id_name[trim(strtoupper($row[14]))],
                    'reference'             =>  ( isset( $employee_id_name[trim( strtoupper($row[15]) ) ]) )?$employee_id_name[trim( strtoupper( $row[15]))]:null,
                    'status'                =>  $customer_status[trim(strtoupper($row[29]))],
                    'create_date'           =>  $helper->transformDate($row[16]),
                    'shipping_address'      =>  $row[17],
                    'shipping_state'        =>  $states[trim(strtoupper($row[18]))],
                    'shipping_city'         =>  $common_city[trim(strtoupper($row[19]))],
                    'shipping_pincode'      =>  $row[20],
                    'credit_limit'          =>  $row[21],
                    'gst_reges_type'        =>  $Gst_reg_option[trim(strtoupper($row[22]))],
                    'gstin'                 =>  $row[23],
                    'branch'                =>  $branch[trim(strtoupper($row[24]))],
                    'payment_mode'          =>  $payment_option[trim(strtoupper($row[25]))],
                    'con_start_date'        =>  $helper->transformDate($row[26]),
                    'con_end_date'          =>  $helper->transformDate($row[27]),
                    'cust_value'            =>  $row[28],
                    'is_active'             =>  $customer_status[trim(strtoupper($row[29]))],
                ];

            }
            
        return $response;
    }
    private function getCustomerIds(){
        if(!isset($this->customer_ids)){
            $customer_master = CustomerMaster::where('is_active',0)->pluck('customer_code','id')->toArray();
            foreach($customer_master as $id=>$row){
                $this->customer_ids[strtoupper($row)] = $id; 
            }
        }
        return $this->customer_ids;
    }
    
    private function getEmployee(){
        if(!isset($this->company_employee)){
            $company_employee =  DB::table('employees')->pluck('email','id')->toArray();
            foreach($company_employee as $id=>$row){
                $this->company_employee[strtoupper($row)] = $id; 
            }
        }
        return $this->company_employee;
    }
    private function getEmployeeWithName(){
        if(!isset($this->company_employee_with_name)){
            $company_employee_with_name =  DB::table('employees')->pluck('Name')->toArray();
            foreach($company_employee_with_name as $id=>$row){
                $this->company_employee_with_name[trim(strtoupper($row))] = $row; 
            }
        }
        return $this->company_employee_with_name;
    }
    public function getExsitingCustomer(){
         if(!isset($this->customer_master)){
            $this->customer_master = CustomerMaster::pluck('customer_code')->toArray();
            }
        return $this->customer_master;
    }
    public function getExsitingEmployee(){
         if(!isset($this->employee)){
            $this->employee = Employee::pluck('email')->toArray();
         }
        return $this->employee;
    }
    private function getStates(){
        if(!isset($this->states)){
            $this->states = DB::table('common_states')->where('country_id', '=', 1)->pluck('state_name','id')->toArray();
        }
        return array_change_key_case(array_flip($this->states),CASE_UPPER);
    }
    private function getBranch(){
        if(!isset($this->branch)){
            $this->branch = DB::table('Branch')->pluck('Name','id')->toArray();
        }
        return array_change_key_case(array_flip($this->branch),CASE_UPPER);
    
    } 
    private function getCommonCity(){
         if(!isset($this->cities)){
            $this->cities = DB::table('common_cities')->pluck('Name','id')->toArray();
         }
        return array_change_key_case(array_flip($this->cities),CASE_UPPER);
        
    }
    
    
    private function getCommonCitywithStats(){
         
            $response_data = [];
            $city_datas = DB::table('common_cities')->select('id','Name','state_id')->get();
            foreach($city_datas as $city_data){
                $response_data[$city_data->state_id][ trim(strtoupper($city_data->Name))] = $city_data->id;
            }
            return $response_data;
    }
    private function getCustomerIdWithCode(){
        $customeridCode = CustomerMaster::pluck('customer_code','id')->toArray();
        $customer_code_response=[];
        foreach($customeridCode as $id_key=>$code){
            $customer_code_response[trim(strtoupper($code))] = $id_key ;
        }
        return $customer_code_response;
    }
    private function getCustomerByCustomerCode($code){
        $customer_code = $this->getCustomerIdWithCode();
        if(!isset($customer_code[trim(strtoupper($code))])){
               throw new \ErrorException( 'Contract Code : Not Found'); 
        }
        $customer = CustomerMaster::where('id',$customer_code[trim(strtoupper($code))])->first();
       if($customer){
           return $customer;
       }
        throw new \ErrorException( 'Customer code : invalid ('.$code.')'); 
    }
    private function getGstRegistrationOption(){
        return [
            'YES'=>'Yes',
            'NO'=>'No'
        ];
    }
    private function getPaymentOption(){
        return [
            'CASH'  =>  'Cash',
            'ONLINE'=>  'Online'
        ];
    }
    private function getCustomerStatus(){
        return [
            'ACTIVE'=>0,
            'INACTIVE'=>1,
        ];
    }
    private function userUnderstandData($customer_code){

        $customers = DB::table('hvl_customer_master')
                ->join('Branch', 'Branch.id', '=', 'hvl_customer_master.branch_name')
                ->join('common_cities AS bill_city', 'bill_city.id', '=', 'hvl_customer_master.billing_city')
                ->join('common_cities AS ship_city', 'ship_city.id', '=', 'hvl_customer_master.shipping_city')
                ->join('common_states AS ship_state', 'ship_state.id', '=', 'hvl_customer_master.shipping_state')
                ->join('common_states AS bill_state', 'bill_state.id', '=', 'hvl_customer_master.billing_state')
                ->where('hvl_customer_master.customer_code',$customer_code) 
                ->select([
                    'hvl_customer_master.*', 
                    'bill_city.Name as billing_city_name',
                    'ship_city.Name as ship_city_name',
                    'bill_state.state_name as billing_state_name', 
                    'ship_state.state_name as shipping_state_name', 
                    'Branch.Name as customer_branch_name'

                ])->first();
               return [
                    'Customer Code'             => $customers->customer_code,
                    'Customer Name'             => $customers->customer_name,
                    'Customer Alias'            => $customers->customer_alias,
                    'Customer Billing Address'  => $customers->billing_address,
                    'Customer Billing State'    => $customers->billing_state_name,
                    'Customer Billing City'     => $customers->billing_city_name,
                    'Customer Billing Pincode'  => $customers->billing_pincode,
                    'Customer Billing Location' => $customers->billing_location,
                    'Customer Billing Latitude' => $customers->billing_latitude,
                    'Customer Billing Longitude'=> $customers->billing_longitude,
                    'Customer Contact Person'   => $customers->contact_person,
                    'Customer Contact phone'    => $customers->contact_person_phone,
                    'Customer Billing Email'    => $customers->billing_email,
                    'Customer Billing Mobile'   => $customers->billing_mobile,
                    'Operator'                  => $customers->operator,
                    'Executive'                 => $customers->operation_executive,
                    'Sales Person'              => $customers->sales_person,
                    'Reference'                 => $customers->reference,
                    'Status'                    => $customers->status,
                    'Created Date'              => $customers->create_date,
                    'Shipping Address'          => $customers->shipping_address,
                    'Shipping State'            => $customers->shipping_state_name,
                    'Shipping City'             => $customers->ship_city_name,
                    'Shipping Pincode'          => $customers->shipping_pincode,
                    'Shipping Limite'           => $customers->credit_limit,
                    'GST Type'                  => $customers->gst_reges_type,
                    'GST No'                    => $customers->gstin,
                    'Customer Branch'           => $customers->customer_branch_name,
                    'Payment Mode'              => $customers->payment_mode,
                    'Contract Start Date'       => $customers->con_start_date,
                    'Contract End Date'         => $customers->con_end_date,
                    'Customer Value'            => $customers->cust_value,
                    'Customer is Active'        => ($customers->is_active == 0)?'Active':'Inactive', 
                ];

    }

}
