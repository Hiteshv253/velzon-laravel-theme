<?php
namespace App\Http\Controllers\AuditManagement;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\AuditReport;
use App\Http\Requests\AuditManagment\StoreRequest;
use App\Http\Requests\AuditManagment\UpdateRequest;
use App\Models\hvl\CustomerMaster;
use Auth;
use App\User;
use Helper;
use Maatwebsite\Excel\Excel as BaseExcel;
use App\Exports\AuditMasterExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Mail\AuditMasterSheetMail;
use Illuminate\Support\Facades\Mail;


class AuditController extends Controller
{
    protected $auditModel;
    public function __construct(AuditReport $auditModel)
    {
        $this->auditModel = $auditModel;
    }

    public function index(Request $request)
    {
        $s_date = $request->start;
        $e_date = $request->end;
        $search_text = $request->search_text;
        $search_branch = $request->branch_id;
        $search_customer = $request->customers_id;
        $all_branche =$this->getBranch();
        $branches = $this->getCustomerBranches();
        $customer_codes = $this->getCustomerCode();
        $customers = $this->getcustomerList(null, true);
        $customers_ids = array_keys($customers);
        $search_branch_costomer = [];
        $search_param = [];
       if(isset($search_branch) && $search_branch){
            $search_branch_costomer = $this->getcustomerList($search_branch,true);
        }
        return view('hvl.audit_management.audit.index',[
                'all_branche'=>$all_branche,
                'branches'=>$branches,
                'customer_codes'=>$customer_codes,
                'customers'=>$customers,
                'search_branch'=>$search_branch,
                'search_customer'=>$search_customer,
                'search_sdate'=>$s_date,
                'search_edate'=>$e_date,
                'search_branch_costomer'=>$search_branch_costomer,
                'search_param'=>$search_param,
            ]);
    }

    public function filter(Request $request)
    {
        $user = User::where('id',$request->user_id)->first();
        Auth::login($user);
        $s_date = $request->start;
        $e_date = $request->end;
        $search_text = $request->search_text;
        $search_branch = $request->search_branch_id;
        $search_customer = $request->search_customers_id;
        $customers = $this->getcustomerList(null, true);
        $customers_ids = array_keys($customers);
        $search_branch_costomer = [];
        $search_param = [];
        if($search_text){
            $search_param['search_text'] = $search_text;
        }
        if($s_date){
            $search_param['start'] = $s_date;
        }
        if($e_date){
            $search_param['end'] = $e_date;
        }
        if(!empty($search_customer)){
            $search_param['customers_id']=$search_customer;
        }
        if(isset($search_branch)){
            $search_param['branch_id']=$search_branch;
        }

        if(isset($search_branch) && $search_branch){
            $search_branch_costomer = $this->getcustomerList($search_branch,true);
        }

        if(isset($search_text) && $search_text != null ){
            $audit_model = new AuditReport();
            $search_audit_ids = 
                    $audit_model->join('hvl_customer_master','hvl_customer_master.id','audit_report.customer_id')
                        ->join('Branch','hvl_customer_master.branch_name','Branch.id')
                        ->where('Branch.Name','LIKE','%'.$search_text.'%')
                        ->orwhere('hvl_customer_master.customer_code','LIKE','%'.$search_text.'%')
                        ->orwhere('hvl_customer_master.customer_name','LIKE','%'.$search_text.'%')
                        ->orwhere('audit_report.schedule_date','LIKE','%'.$search_text.'%')
                        ->orwhere('audit_report.audit_type','LIKE','%'.$search_text.'%')
                        ->pluck('audit_report.id');
        }
        $audit_list = $this->auditModel
                    ->join('hvl_customer_master','hvl_customer_master.id','audit_report.customer_id')
                    ->join('Branch','hvl_customer_master.branch_name','Branch.id')
                    ->select([
                                'audit_report.id as id',
                                'audit_report.customer_id as customer_id',
                                'hvl_customer_master.customer_name as customer_name',
                                'hvl_customer_master.customer_code as customer_code',
                                'audit_report.audit_type as audit_type',
                                'Branch.Name as branch',
                                'audit_report.schedule_notes as schedule_notes',
                                'audit_report.generated as generated',
                                'audit_report.schedule_date as schedule_date',
                                
                                
                            ])
                    ->whereIn('customer_id',$customers_ids)
                    ->latest('audit_report.created_at');
        if(isset($search_text) && $search_text != null ){
            $audit_list = $audit_list->whereIn('audit_report.id',$search_audit_ids);
        }
        if($s_date){
            $audit_list = $audit_list->where('schedule_date','>=',$s_date.' 00:00:00');
        }
        
        if($e_date){
            $audit_list = $audit_list->where('schedule_date','<=',$e_date.' 23:59:59');
        }
        if(!empty($search_customer)){
            $audit_list = $audit_list->whereIn('hvl_customer_master.id',$search_customer);
        }
        if(isset($search_branch)){
            $audit_list = $audit_list->where('hvl_customer_master.branch_name',$search_branch);
        }

        
        
        $audit_list = $audit_list->orderBy('audit_report.id')->paginate(10);
        $helper = new Helper();
        return response()->json([
            'status'=>'Success',
            'data'=>[
                'audit_list'=>$audit_list->toArray(),
                'page_link'=>$helper->paginateLink($audit_list),
                'per_page_limit'=>$helper->perPageLimit($audit_list)
            ],
            'message'=>'successfuly audit report get list'
        ]);
    }
    public function getPagination($model,$search_param){
        $str = '';
        $helper = new Helper();
        $str = $str. $helper->paginateLink($model);
        return  $str;

    }

    public function getCustomerAuditDownload(Request $request){
        $file_name = date('Y_m_d_h_i_s') . "_audit_master.xlsx";
        Excel::store(new AuditMasterExport($request->all()), '/public/temp/' . $file_name);
        return redirect()->to(asset('public/storage/temp/' . $file_name));
    }
    public function sendCustomerAuditReport(Request $request) {
        
        $attachment = Excel::raw(new AuditMasterExport($request->all()),BaseExcel::CSV);
        $message = Mail::to($request->to);
        if (isset($request->cc)) {
            $message->cc($request->cc);
        }
        if (isset($request->bcc)) {
            $message->bcc($request->bcc);
        }
        $message->send(
                new AuditMasterSheetMail(
                    $attachment, $request->subject, $request->body
                )
            );
        return redirect(route('admin.audit.index'))->with('success', 'Email Sent successfully.');
    }

    public function getCustomerAudit(Request $request,$customer_id){
        $s_date = $request->start;
        $e_date = $request->end;
        $customer_model = new CustomerMaster();
        $customer = $customer_model->where('id',$customer_id)
                    ->select([  'id',
                                'customer_name as name',
                                'billing_address as address'
                            ])
                    ->first();
        

                            
        $audit_list = $this->auditModel
                    ->select([
                            'audit_report.id as id',
                            'audit_report.customer_id as customer_id',
                            'audit_report.audit_type as audit_type',
                            'audit_report.schedule_notes as schedule_notes',
                            'audit_report.generated as generated',
                            'audit_report.schedule_date as schedule_date'
                            ])
                    ->where('customer_id',$customer_id)
                    ->where('audit_report.generated',AuditReport::GENERATED);
            if($s_date){
                $audit_list = $audit_list->where('schedule_date','>=',$s_date.' 00:00:00');
            }
            if($e_date){
                $audit_list = $audit_list->where('schedule_date','<=',$e_date.' 23:59:59');
            }
            $audit_list = $audit_list->latest('audit_report.created_at');

            $audit_list = $audit_list->paginate(config('per_page_audit_list'));
       
       return view('hvl.audit_management.audit.customer_audit_list',[
                'audit_list'=>$audit_list,
                'customer'=>$customer,
                'search_sdate'=>$s_date,
                'search_edate'=>$e_date
            ]);
    }

    public function create()
    {
        
        $branches =$this->getBranch();
        $audit_options = $this->auditModel->getAuditTypeOption();
        $fequancy = [
                'monthly'       =>'Monthly',
                'quarterly'     =>'Quarterly',
                'half_yearly'   =>'Halfyearly',
                'yearly'        => 'Yearly',

        ];
        return view('hvl.audit_management.audit.create',[
                'branches'=>$branches,
                'audit_options'=>$audit_options,
                'fequancies'=>$fequancy,
            ]);
    }
    public function getBranchCustomers(Request $request){
        $customers = $this->getcustomerList($request->branch_id);
        return $customers;

    }

    public function store(StoreRequest $request){
      try{
        DB::beginTransaction();
         $flag = false;
            $helper = new Helper();
            $data_list = $helper->getFrequancyDateList($request->schedule_start_date,$request->schedule_end_date,$request->frequancy);
            foreach($data_list as $schedule_date){
                $auditModel = new AuditReport();
                $auditModel->audit_type = $request->audit_type;
                $auditModel->customer_id = $request->customer_id;
                $auditModel->schedule_date = $schedule_date.' '.$request->schedule_time;
                $auditModel->schedule_notes = $request->schedule_notes;
                $auditModel->save();
                if(!$auditModel){
                    $flag = true;      
                }
            }
            DB::commit();
            return redirect(route('admin.audit.index'))->with('success', 'Successfully Audit Schedule Added !');   
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect(route('admin.audit.index'))->with('error', 'Something Went To Wong');      
        }
    }

    public function show($id){ 
        $audit = $this->auditModel->find($id);
        if(!$audit){
            return redirect(route('admin.audit.index'))->with('error', 'audit not found !');
        }
        $selected_branch = CustomerMaster::where('id',$audit->customer_id)->first()->branch_name;
        $selected_branch_customer = $this->getcustomerList($selected_branch,true);
        
        $audit_options = $this->auditModel->getAuditTypeOption();
        $branches =$this->getBranch();
        
        return view('hvl.audit_management.audit.view',[
                        'branches'=>$branches,
                        'audit'=>$audit,
                        'audit_options'=>$audit_options,
                        'selected_branch'=>$selected_branch,
                        'selected_branch_customer'=>$selected_branch_customer,    
                    ]);
    }

    public function edit($id)
    {
        $audit = $this->auditModel->find($id);
        if(!$audit){
            return redirect(route('admin.audit.index'))->with('error', 'audit not found !');
        }
        $selected_branch = CustomerMaster::where('id',$audit->customer_id)->first()->branch_name;
        $selected_branch_customer = $this->getcustomerList($selected_branch,true);
        
        $audit_options = $this->auditModel->getAuditTypeOption();
        $branches =$this->getBranch();
        
        return view('hvl.audit_management.audit.edit',[
                        'branches'=>$branches,
                        'audit'=>$audit,
                        'audit_options'=>$audit_options,
                        'selected_branch'=>$selected_branch,
                        'selected_branch_customer'=>$selected_branch_customer,    
                    ]);
                        
    }

    public function update(UpdateRequest $request, $id)
    {
        $customers = $this->getcustomerList($request->branch_id,true);
        $customers_ids = array_keys($customers); 
        $audit_report = $this->auditModel->whereIn('customer_id',$customers_ids)->latest('created_at')->find($id);
        if(!$audit_report){
            return redirect(route('admin.audit.index'))->with('error', 'Audit Schedule Not Found !');
        }
        // if($audit_report->generated == 'yes'){
        //     return redirect(route('admin.audit.index'))->with('error', 'Audit Generate Submited.');
        // }
        
        $auditModel = AuditReport::where('id',$id);
        
        $flag = $auditModel->update([
         'audit_type'=>$request->audit_type,
         'customer_id'=>$request->customer_id,
         'schedule_date'=>$request->schedule_date.' '.$request->schedule_time,
         'schedule_notes'=>$request->schedule_notes,
        ]);
        if(!$flag){
            return redirect(route('admin.audit.index'))->with('error', 'Something Went To Wong');   
        } 
        return redirect(route('admin.audit.edit',['id'=>$id]))->with('success', 'Successfully Audit Updated !');
    }

    public function destroy(Request $request)
    {
        $customers = $this->getcustomerList(null,true);
        $customers_ids = array_keys($customers); 
        $audit_schedule =  $this->auditModel->whereIn('customer_id',$customers_ids)->where('id',$request->id)->first();
        if($audit_schedule->generated == 'yes' && (!in_array(Auth::User()->id ,[1,122,184]))){
            return response()->json([
                'status'=>'error',
                'message'=>'Generated audit report  not able to delete. Please contact admin'
            ]);
            
        }
        if(!$audit_schedule){
            return response()->json([
                'status'=>'error',
                'message'=>'something went to worng'
            ]);
        }
        $audit_schedule->delete();
        return response()->json([
            'status'=>'success',
            'message'=>'your audit schecdule has been remove'
        ]);
    }
    public function destroyMultiple(Request $request){
        
        $audit_ids = $request->input('ids');
        $customers = $this->getcustomerList(null,true);
        $customers_ids = array_keys($customers); 
        $flag = $this->auditModel->whereIn('customer_id',$customers_ids)->where('generated','no')->whereIn('id',$audit_ids)->delete();
        if($flag){
            return response()->json([
                'status'=>'success',
                'message'=>'your audit schecdule has been remove'
            ]);
        }
        return response()->json([
            'status'=>'error',
            'message'=>'something went to worng'
        ]);
    }

    public function getcustomerList($branch_id = null, $flag = false ){
        $em_id = Auth::User()->id;
        $emp = DB::table('employees')->where('user_id', '=', $em_id)->first();
        if ($em_id == 1 or $em_id == 122 or $em_id == 184) {
            $customers = new CustomerMaster();
            if($branch_id){
                $customers = $customers->where('branch_name',$branch_id);
            }
            $customers = $customers->pluck('customer_name','id')->toArray();
        }
        else{
            if ($emp) 
            {
                $customers =  new CustomerMaster();
                $customers =  $customers->join('hvl_customer_employees', 'hvl_customer_employees.customer_id', '=', 'hvl_customer_master.id')
                    ->where('hvl_customer_employees.employee_id', $emp->id);
                    if($branch_id){
                        $customers = $customers->where('branch_name',$branch_id);
                    }
                    $customers = $customers->pluck('hvl_customer_master.customer_name', 'hvl_customer_master.id')
                    ->toArray();
            }else{
                $db_customersIds = [];
                $customers_admin = CustomersAdmin::where('user_id', $em_id)->first();
                if ($customers_admin) {
                    $db_customersIds = json_decode($customers_admin->customers_id, true);
                }
                $customers = new CustomerMaster();
                    if($branch_id){
                        $customers =  $customers->where('branch_name',$branch_id);
                    }
                $customers =$customers->whereIn('hvl_customer_master.id', $db_customersIds)
                    ->pluck('customer_name', 'id')->toArray();
            }
        }
        if($flag){
            return $customers;
        }
        return response()->Json([
                'status'=>'success',
                'data' =>[
                    'customer_list'=>$customers]
                ]);
        
    }
    
    public function getBranch(){
        $branchs = array();
        $em_id = Auth::User()->id;
        $emp = DB::table('employees')->where('user_id', '=', $em_id)->first();
        if ($em_id == 1 or $em_id == 122 or $em_id == 184) {
            $branchs = DB::table('Branch')->pluck('Name', 'id')->toArray();
        }
        else{
            if ($emp) {
                $branchs = DB::table('hvl_customer_master')
                        ->join('hvl_customer_employees', 'hvl_customer_employees.customer_id', '=', 'hvl_customer_master.id')
                        ->join('Branch', 'Branch.id', '=', 'hvl_customer_master.branch_name')
                        ->where('hvl_customer_employees.employee_id', $emp->id)
                        ->groupBy('Name')
                        ->pluck('Name', 'Branch.id')
                        ->toArray();
            }else{
                $branchs = DB::table('hvl_customer_master')
                    ->join('Branch', 'Branch.id', '=', 'hvl_customer_master.branch_name')
                    ->whereIn('hvl_customer_master.id', $db_customersIds)
                    ->groupBy('Branch.id')
                    ->pluck('Name', 'Branch.id')
                    ->toArray();
            }
        }
        return $branchs;
    }
    
    public function getCustomerBranches($customer_id = null){
        $customer_ids = array_keys($this->getcustomerList(null,true));
        $branches = CustomerMaster::whereIn('hvl_customer_master.id',$customer_ids)
        ->join('Branch','hvl_customer_master.branch_name','=','Branch.id');
        if($customer_id){
                return $branches->where('hvl_customer_master.id', $customer_id)->pluck('Branch.Name')->first()->Name;
        }
        return $branches->pluck('Branch.Name as branch_name','hvl_customer_master.id as customer_id')
        ->toArray();
    }

    public function getCustomerCode(){
        $customer_ids = array_keys($this->getcustomerList(null,true));
        return CustomerMaster::whereIn('hvl_customer_master.id',$customer_ids)
        ->pluck('hvl_customer_master.customer_code','hvl_customer_master.id as customer_id')
        ->toArray();
    }
    public function dashboard(Request $request){
        $sdate = $request->start;
        $edate = $request->end;
        
        
        $genarated_inprocess =[];
        $adhoc_planed =[];
        $day_wise_audit=[];
        if(!isset($sdate)){
            $sdate = date('Y-m-d');
        }
        if(!isset($edate)){
            $edate = date('Y-m-d');
        }
        $s_date  = $sdate.' 00:00:00';
        $e_date  = $edate.' 23:59:59';
        $em_id = Auth::User()->id;
        if ($em_id != 1 && $em_id != 122) {
            $customer_id = $this->getAuthUsersCustomer($em_id);
        }
        
        $genarated = $this->auditModel
                ->select(['generated',DB::raw('count(*) as audit_count')])
                ->whereBetween('schedule_date',[$s_date,$e_date]);
            if(isset($customer_id)){
                $genarated = $genarated->whereIn('customer_id',$customer_id);
            }
        $genarated = $genarated->groupBy('generated')->get();
        foreach($genarated as $row){
            if($row->generated=='yes'){
                $genarated_inprocess['Genarated'] = $row->audit_count;
            }else{
                $genarated_inprocess['In Process']= $row->audit_count;
            }
        }
        $auditModel = new AuditReport();
        $adhocPlannetRow = $auditModel
                ->select(['audit_type',DB::raw('count(*) as audit_count')])
                ->whereBetween('schedule_date',[$s_date,$e_date]);
                if(isset($customer_id)){
                    $adhocPlannetRow = $adhocPlannetRow->whereIn('customer_id',$customer_id);
                }
                $adhocPlannetRow = $adhocPlannetRow->groupBy('audit_type')
                ->get();
        foreach($adhocPlannetRow as $row){
            if($row->audit_type=='adhoc'){
                $adhoc_planed[$row->getAuditTypeText($row->audit_type)] = $row->audit_count;
            }else{
                $adhoc_planed[$row->getAuditTypeText($row->audit_type)]= $row->audit_count;
            }
        }
        
        $auditModel = new AuditReport();
        $day_wise_audit_row = $auditModel
                ->select([DB::raw("DATE_FORMAT(schedule_date, '%d %M %Y')as date"),DB::raw('count(*) as audit_count')])
                ->whereBetween('schedule_date',[$s_date,$e_date]);
                if(isset($customer_id)){
                    $day_wise_audit_row = $day_wise_audit_row->whereIn('customer_id',$customer_id);
                }
        $day_wise_audit_row = $day_wise_audit_row->groupBy('date')->get();
        
        foreach($day_wise_audit_row as $row){
                $day_wise_audit[$row->date] = $row->audit_count;
        }
        return view('hvl.audit_management.audit.dashboard',['genarated_inprocess'=>$genarated_inprocess,'adhoc_planed'=>$adhoc_planed,'day_wise_audit'=>$day_wise_audit,'sdate'=>$sdate,'edate'=>$edate]);

    }
    public function getAuthUsersCustomer($em_id){
        $emp = DB::table('employees')->where('user_id', '=', $em_id)->first();
        if ($em_id != 1 && $em_id != 122) {
            if ($emp) {
                $customer_ids = DB::table('hvl_customer_master')
                        ->join('hvl_customer_employees', 'hvl_customer_employees.customer_id', '=', 'hvl_customer_master.id')
                         ->where('hvl_customer_employees.employee_id', $emp->id)
                         ->pluck('hvl_customer_master.id')->toArray();
            }else{
             $customers_admin = CustomersAdmin::where('user_id', Auth::User()->id)->first();
                if ($customers_admin) {
                    $customer_ids = json_decode($customers_admin->customers_id, true);
                }
            }
            return $customer_ids ;
        }
         
        
    }
 
}
