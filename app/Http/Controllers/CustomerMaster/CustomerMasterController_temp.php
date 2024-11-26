<?php

namespace App\Http\Controllers\CustomerMaster;

use App\User;
use Auth;
use App\Http\Controllers\Controller;
use App\Models\hvl\CustomerMaster;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Imports\ImportCustomers;
use App\Imports\RemoveCustomers;
use App\Exports\ExportCustomer;

class CustomerMasterController extends Controller {

    public function __construct() {
        $this->middleware('permission:Access Customer', ['only' => ['show', 'index']]);
        $this->middleware('permission:Create Customer', ['only' => ['create']]);
        $this->middleware('permission:Read Customer', ['only' => ['read']]);
        $this->middleware('permission:Edit Customer', ['only' => ['edit']]);
        $this->middleware('permission:Delete Customer', ['only' => ['delete']]);
    }

    public function index() {
        $em_id = Auth::User()->id;
        $emp = DB::table('employees')->where('user_id', '=', $em_id)->first();
        echo "<pre>";
        print_r($emp);
        die;
        $today_date = Carbon::today()->format('Y-m-d');
        $customerDetails = "";
        $branchs = array();
        if ($em_id == 1 or $em_id == 122) {
            $customerDetails = DB::table('hvl_customer_master')
                    ->join('Branch', 'Branch.id', '=', 'hvl_customer_master.branch_name')
                    ->join('common_states AS ship_state', 'ship_state.id', '=', 'hvl_customer_master.shipping_state')
                    ->join('common_states AS bill_state', 'bill_state.id', '=', 'hvl_customer_master.billing_state')
                    ->select('hvl_customer_master.*', 'bill_state.state_name as billing_state', 'ship_state.state_name as shipping_state', 'Branch.Name as branch_name'
//                'employees.Name as sale_person'
                    )->orderBy('hvl_customer_master.id', 'DESC')
                    ->paginate(50);
//                ->get();
            $branchs = DB::table('Branch')->get();
        } else {
            $customerDetails = DB::table('hvl_customer_master')
                    ->join('hvl_customer_employees', 'hvl_customer_employees.customer_id', '=', 'hvl_customer_master.id')
                    ->join('employees', 'employees.id', '=', 'hvl_customer_employees.employee_id')
                    ->join('Branch', 'Branch.id', '=', 'hvl_customer_master.branch_name')
                    ->join('common_states AS ship_state', 'ship_state.id', '=', 'hvl_customer_master.shipping_state')
                    ->join('common_states AS bill_state', 'bill_state.id', '=', 'hvl_customer_master.billing_state')
                    // ->where('hvl_customer_employees.employee_id', $emp->id)
                    ->select('hvl_customer_master.*', 'bill_state.state_name as billing_state', 'ship_state.state_name as shipping_state', 'Branch.Name as branch_name'
                    )
                    ->orderBy('hvl_customer_master.id', 'DESC')
                    ->paginate(50);
//                    ->get();
            $branc_id = DB::table('hvl_customer_master')
                    ->join('hvl_customer_employees', 'hvl_customer_employees.customer_id', '=', 'hvl_customer_master.id')
                    ->join('Branch', 'Branch.id', '=', 'hvl_customer_master.branch_name')
                    ->where('hvl_customer_employees.employee_id', $emp->id)
                    ->select('Branch.*')
                    ->get()
                    ->groupBy('Name');
            if (!empty($branc_id)) {
                foreach ($branc_id as $key => $branch_id) {
                    $branchs[] = DB::table('Branch')->where('Name', $key)->get();
                }
            }
        }
        return view('hvl.customermaster.index', [
            'customerDetails' => $customerDetails,
            'branchs' => $branchs
//                'contracts' => $contracts
        ]);
    }

    public function create() {
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true, 'isCustomizer' => true];
        $breadcrumbs = [
            ['link' => "hvl", 'name' => "Home"],
            ['link' => "/customer-master/", 'name' => "Customer Master"],
            ['link' => "/customer-master/create", 'name' => "Create"],
        ];
        $state = DB::table('common_states')->where('country_id', '=', 1)->get();
        $employees = DB::table('employees')->get();
        $branch = DB::table('Branch')->get();
        return view('hvl.customermaster.create', [
            'pageConfigs' => $pageConfigs,
            'breadcrumbs' => $breadcrumbs,
            'states' => $state,
            'employees' => $employees,
            'branchs' => $branch
        ]);
    }

    public function store(Request $request) {
        $customer_id = CustomerMaster::insertGetId([
                    'customer_code' => $request->customer_code,
                    'customer_name' => $request->customer_name,
                    'customer_alias' => $request->customer_alias,
                    'billing_address' => $request->billing_address,
                    'billing_state' => $request->billing_state,
                    'billing_city' => $request->billing_city,
                    'billing_pincode' => $request->billing_pincode,
                    'contact_person' => $request->contact_person,
                    'contact_person_phone' => $request->contact_person_phone,
                    'billing_email' => $request->billing_mail,
                    'billing_mobile' => $request->billing_mobile,
                    'sales_person' => $request->sales_person,
                    'reference' => $request->reference,
                    'status' => $request->is_active,
                    'create_date' => $request->create_date,
                    'shipping_address' => $request->shipping_adress,
                    'shipping_state' => $request->shipping_state,
                    'shipping_city' => $request->shipping_city,
                    'shipping_pincode' => $request->shipping_pincode,
                    'credit_limit' => $request->credit_limit,
                    'gst_reges_type' => $request->gst_reges_type,
                    'gstin' => $request->gstin,
                    'branch_name' => $request->branch,
                    'payment_mode' => $request->payment_mode,
                    'con_start_date' => $request->con_start_date,
                    'con_end_date' => $request->con_end_date,
                    'cust_value' => $request->cust_value,
                    'is_active' => $request->is_active
        ]);
        foreach ($request->employee_id as $employee) {
            DB::table('hvl_customer_employees')
                    ->insert([
                        'customer_id' => $customer_id,
                        'employee_id' => $employee
            ]);
        }
        return redirect('/customer-master')->with('success', 'Customer Record Has Been Inserted');
    }

    public function edit($id) {

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true, 'isCustomizer' => true];
        $breadcrumbs = [
            ['link' => "hvl", 'name' => "Home"],
            ['link' => "/customer-master/", 'name' => "Customer Master"],
            ['link' => "/customer-master/edit/" . $id, 'name' => "Update"],
        ];
        $details = CustomerMaster::whereId($id)->first();
//        $customeremp_ids = DB::table('hvl_customer_employees')
//            ->where('hvl_customer_employees.customer_id',$id)
//            ->get();
//        $customer_employees = DB::table('hvl_customer_employees')->where('customer_id',$id)->get();
        $customer_employees = DB::table('hvl_customer_employees')
//            ->join('hvl_customer_master','hvl_customer_master.customer_name','=','hvl_customer_employees.customer_id')
                ->where('hvl_customer_employees.customer_id', '=', $id)
                ->pluck('hvl_customer_employees.employee_id')
                ->all();
        $state = DB::table('common_states')->where('country_id', '=', 1)->get();
        $billing_citys = DB::table('common_cities')->where('state_id', '=', $details->billing_state)->get();
        $shipping_citys = DB::table('common_cities')->where('state_id', '=', $details->shipping_state)->get();
        $employees = DB::table('employees')->get();
        $branch = DB::table('Branch')->get();
        return view('hvl.customermaster.edit', [
            'pageConfigs' => $pageConfigs,
            'breadcrumbs' => $breadcrumbs,
            'details' => $details,
            'customer_employees' => $customer_employees,
            'states' => $state,
            'employees' => $employees,
            'branchs' => $branch,
            'billing_cities' => $billing_citys,
            'shipping_cities' => $shipping_citys,
        ]);
    }

    public function update(Request $request, $id) {
        CustomerMaster::whereId($id)
                ->update([
                    'employee_id' => $request->employee_id,
                    'customer_code' => $request->customer_code,
                    'customer_name' => $request->customer_name,
                    'customer_alias' => $request->customer_alias,
                    'billing_address' => $request->billing_address,
                    'billing_state' => $request->billing_state,
                    'billing_city' => $request->billing_city,
                    'billing_pincode' => $request->billing_pincode,
                    'contact_person' => $request->contact_person,
                    'contact_person_phone' => $request->contact_person_phone,
                    'billing_email' => $request->billing_mail,
                    'billing_mobile' => $request->billing_mobile,
                    'sales_person' => $request->sales_person,
                    'reference' => $request->reference,
                    'status' => $request->is_active,
                    'create_date' => $request->create_date,
                    'shipping_address' => $request->shipping_adress,
                    'shipping_state' => $request->shipping_state,
                    'shipping_city' => $request->shipping_city,
                    'shipping_pincode' => $request->shipping_pincode,
                    'credit_limit' => $request->credit_limit,
                    'gst_reges_type' => $request->gst_reges_type,
                    'gstin' => $request->gstin,
                    'branch_name' => $request->branch,
                    'payment_mode' => $request->payment_mode,
                    'con_start_date' => $request->con_start_date,
                    'con_end_date' => $request->con_end_date,
                    'cust_value' => $request->cust_value,
                    'is_active' => $request->is_active
        ]);

        if ($request->is_active) {
            DB::table('hvl_activity_master')
                    ->where('customer_id', $id)
                    ->where('status', 3)
                    ->whereRaw("`start_date` >= '" . date('Y-m-d H:i:s') . "'")
                    ->update([
                        'status' => 5
            ]);
        }
        DB::table('hvl_customer_employees')->where('customer_id', $id)->delete();
        foreach ($request->employee_id as $employee) {
            DB::table('hvl_customer_employees')
                    ->insert([
                        'customer_id' => $id,
                        'employee_id' => $employee
            ]);
        }
        return redirect('/customer-master')->with('success', 'Customer Record Has Been Updated');
    }

    function removedata(Request $request) {
        $Lead_delete = CustomerMaster::whereId($request->input('id'))->first();
        $data = DB::table('hvl_activity_master')->where('customer_id', $Lead_delete->id)->get();
        if (count($data) > 0) {
            return response('error');
        } else {
            $Lead_delete->forceDelete();
        }
    }

    function massremove(Request $request) {
        $Status_ids = $request->input('ids');
        foreach ($Status_ids as $id) {
            $Status_Multi_Delete = CustomerMaster::whereId($id)->first();
            $data = DB::table('hvl_activity_master')->where('customer_id', $Status_Multi_Delete->id)->get();
            if (count($data) > 0) {
                return response('error');
            } else {
                $Status_Multi_Delete->forceDelete();
            }
        }
    }

    public function import_customer(Request $request) {
        $request->validate([
            'import_file' => 'required'
        ]);
        Excel::import(new ImportCustomers, request()->file('import_file'));
        return redirect('/customer-master')->with('success', 'Data imported successfully.');
    }

    public function add_contract(Request $request) {
        $round = rand(00001, 99999);
        $customer_id = $request->customer_id;
        if (!empty($request->file('contract'))) {
            foreach ($request->file('contract') as $before) {
                $path = 'public/uploads/customercontract/';
                $before_pic = $before->getClientOriginalName();
                $before_file = $before_pic;
                $before->move($path, $before_file);
                DB::table('hvl_customer_contract')->insert([
                    'customer_id' => $customer_id,
                    'contract' => $before_file,
                    'type' => $before->getClientOriginalExtension(),
                    'path' => $path
                ]);
            }
            DB::table('hvl_customer_master')
                    ->whereId($customer_id)
                    ->update(['contract' => 1]);
        }
        return redirect('/customer-master')->with('success', 'Customer Contract Added Successfully');
    }

    public function edit_contract(Request $request) {
        $round = rand(00001, 99999);
        $customer_id = $request->customer_id;
        if (!empty($request->file('contract'))) {
            foreach ($request->file('contract') as $before) {
                $path = 'public/uploads/customercontract/';
                $before_pic = $before->getClientOriginalName();
                $before_file = $before_pic;
                $before->move($path, $before_file);
                DB::table('hvl_customer_contract')->insert([
                    'customer_id' => $customer_id,
                    'contract' => $before_file,
                    'type' => $before->getClientOriginalExtension(),
                    'path' => $path
                ]);
            }
            DB::table('hvl_customer_master')
                    ->whereId($customer_id)
                    ->update(['contract' => 1]);
        }
        return redirect('/customer-master/show/' . $customer_id)->with('success', 'Customer Contract Added Successfully');
    }

    public function delete_contract(Request $request) {
        DB::table('hvl_customer_contract')->whereId($request->id)->delete();
    }

    public function show($id) {
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true, 'isCustomizer' => true];
        $breadcrumbs = [
            ['link' => "hvl", 'name' => "Home"],
            ['link' => "/customer-master/", 'name' => "Customer Master"],
            ['link' => "/customer-master/edit/" . $id, 'name' => "Update"],
        ];
        $details = CustomerMaster::whereId($id)->first();
        $customer_employees = DB::table('hvl_customer_employees')
                ->join('hvl_customer_master', 'hvl_customer_master.id', '=', 'hvl_customer_employees.customer_id')
                ->where('hvl_customer_master.id', '=', $id)
                ->pluck('hvl_customer_employees.employee_id')
                ->all();
        $contracts = DB::table('hvl_customer_contract')->where('customer_id', $id)->get();
        $state = DB::table('common_states')->where('country_id', '=', 1)->get();
        $billing_citys = DB::table('common_cities')->where('state_id', '=', $details->billing_state)->get();
        $shipping_citys = DB::table('common_cities')->where('state_id', '=', $details->shipping_state)->get();
        $employees = DB::table('employees')->get();
        $branch = DB::table('Branch')->get();
        return view('hvl.customermaster.view', [
            'pageConfigs' => $pageConfigs,
            'breadcrumbs' => $breadcrumbs,
            'details' => $details,
            'states' => $state,
            'customer_employees' => $customer_employees,
            'employees' => $employees,
            'branchs' => $branch,
            'billing_cities' => $billing_citys,
            'shipping_cities' => $shipping_citys,
            'contracts' => $contracts
        ]);
    }

    public function get_customer(Request $request) {
        $id = $request->eids;
        $em_id = Auth::User()->id;
        $emp = DB::table('employees')->where('user_id', '=', $em_id)->first();
        if ($em_id == 1 or $em_id == 122) {
            $custdetails = DB::table('hvl_customer_master')
                    ->where('branch_name', $id)
                    ->select('hvl_customer_master.customer_name')
                    ->orderby('hvl_customer_master.customer_name')
                    ->get()
                    ->groupBy('customer_name');
        } else {
            $custdetails = DB::table('hvl_customer_master')
                    ->join('hvl_customer_employees', 'hvl_customer_employees.customer_id', '=', 'hvl_customer_master.id')
                    ->where('hvl_customer_master.branch_name', $id)
                    ->where('hvl_customer_employees.employee_id', $emp->id)
                    ->select('hvl_customer_master.customer_name')
                    ->get()
                    ->groupBy('customer_name');
        }
        return response()->json($custdetails);
    }

//    public function get_customer_details(Request $request)
//    {
//        $ids = $request->customer_id;
//
//        $custdetails = array();
//        foreach ($ids as $id)
//        {
//            $custdetails[] = DB::table('hvl_customer_master')
//                ->where('hvl_customer_master.customer_name',$id)
//                ->orderBy('hvl_customer_master.id','DESC')
//                ->get();
//        }
//
//        $branchs = DB::table('Branch')->get();
//
//        return view('hvl.customermaster.index_details', [
//            'customerDetails' => $custdetails,
//            'branchs' => $branchs,
//
//        ]);
//    }
    public function delete_customer() {
        return view('hvl.customermaster.bulk_remove');
    }

    public function bulk_remove_customer(Request $request) {
        $request->validate([
            'upload_file' => 'required'
        ]);
        Excel::import(new RemoveCustomers, request()->file('upload_file'));
        return redirect('/customer-master')->with('success', 'Data Removed successfully.');
    }

    public function get_date_data(Request $request) {
        $mindate = $request->contract_start;
        $maxdate = $request->contract_end;
        $branch_id = $request->branch_id;
        $customer_id = $request->customer_id;

//
        $em_id = Auth::User()->id;
        $emp = DB::table('employees')
                ->where('user_id', '=', $em_id)
                ->first();
        $customerDetails = "";
        if ($em_id == 1 or $em_id == 122) {
//            $custdetails = array();
//            foreach ($ids as $id)
//            {
            if (!empty($mindate) and !empty($maxdate)) {
                $custdetails = DB::table('hvl_customer_master')
                        ->join('Branch', 'Branch.id', '=', 'hvl_customer_master.branch_name')
                        ->join('common_states AS ship_state', 'ship_state.id', '=', 'hvl_customer_master.shipping_state')
                        ->join('common_states AS bill_state', 'bill_state.id', '=', 'hvl_customer_master.billing_state')
                        ->whereBetween('create_date', [$mindate, $maxdate])
                        ->where('hvl_customer_master.branch_name', $branch_id)
                        ->whereIn('hvl_customer_master.id', $customer_id)
                        ->orderBy('hvl_customer_master.id', 'DESC')
                        ->select(
                                'hvl_customer_master.*', 'bill_state.state_name as billing_state', 'ship_state.state_name as shipping_state', 'Branch.Name as branch_name')
                        ->get();
//                        ->groupBy('customer_name');
            } else {
                $custdetails = DB::table('hvl_customer_master')
                        ->join('Branch', 'Branch.id', '=', 'hvl_customer_master.branch_name')
                        ->join('common_states AS ship_state', 'ship_state.id', '=', 'hvl_customer_master.shipping_state')
                        ->join('common_states AS bill_state', 'bill_state.id', '=', 'hvl_customer_master.billing_state')
                        ->where('hvl_customer_master.branch_name', $branch_id)
                        ->whereIn('hvl_customer_master.id', $customer_id)
                        ->orderBy('hvl_customer_master.id', 'DESC')
                        ->select(
                                'hvl_customer_master.*', 'bill_state.state_name as billing_state', 'ship_state.state_name as shipping_state', 'Branch.Name as branch_name')
                        ->get();
            }
//            }
        } else {
            if (!empty($mindate) and !empty($maxdate)) {
                $custdetails = DB::table('hvl_customer_master')
                        ->join('hvl_customer_employees', 'hvl_customer_employees.customer_id', '=', 'hvl_customer_master.id')
                        ->join('employees', 'employees.id', '=', 'hvl_customer_employees.employee_id')
                        ->join('Branch', 'Branch.id', '=', 'hvl_customer_master.branch_name')
                        ->join('common_states AS ship_state', 'ship_state.id', '=', 'hvl_customer_master.shipping_state')
                        ->join('common_states AS bill_state', 'bill_state.id', '=', 'hvl_customer_master.billing_state')
                        ->where('hvl_customer_employees.employee_id', $emp->id)
                        ->whereBetween('create_date', [$mindate, $maxdate])
                        ->where('hvl_customer_master.branch_name', $branch_id)
                        ->whereIn('hvl_customer_master.id', $customer_id)
                        ->orderBy('hvl_customer_master.id', 'DESC')
                        ->select(
                                'hvl_customer_master.*', 'bill_state.state_name as billing_state', 'ship_state.state_name as shipping_state', 'Branch.Name as branch_name')
                        ->get();
//                        ->groupBy('customer_name');
            } else {
                $custdetails = DB::table('hvl_customer_master')
                        ->join('hvl_customer_employees', 'hvl_customer_employees.customer_id', '=', 'hvl_customer_master.id')
                        ->join('employees', 'employees.id', '=', 'hvl_customer_employees.employee_id')
                        ->join('Branch', 'Branch.id', '=', 'hvl_customer_master.branch_name')
                        ->join('common_states AS ship_state', 'ship_state.id', '=', 'hvl_customer_master.shipping_state')
                        ->join('common_states AS bill_state', 'bill_state.id', '=', 'hvl_customer_master.billing_state')
                        ->where('hvl_customer_employees.employee_id', $emp->id)
                        ->where('hvl_customer_master.branch_name', $branch_id)
                        ->whereIn('hvl_customer_master.id', $customer_id)
                        ->orderBy('hvl_customer_master.id', 'DESC')
                        ->select(
                                'hvl_customer_master.*', 'bill_state.state_name as billing_state', 'ship_state.state_name as shipping_state', 'Branch.Name as branch_name')
                        ->get();
            }
        }
        if ($em_id == 1 or $em_id == 122) {
            $branchs = DB::table('Branch')->get();
        } else {
            $branc_id = DB::table('hvl_customer_master')
                    ->join('hvl_customer_employees', 'hvl_customer_employees.customer_id', '=', 'hvl_customer_master.id')
                    ->join('Branch', 'Branch.id', '=', 'hvl_customer_master.branch_name')
                    ->where('hvl_customer_employees.employee_id', $emp->id)
                    ->select('Branch.*')
                    ->get()
                    ->groupBy('Name');
            foreach ($branc_id as $key => $branch_id) {
                $branchs[] = DB::table('Branch')->where('Name', $key)->get();
            }
        }
        return view('hvl.customermaster.index', [
            'customerDetails' => $custdetails,
            'branchs' => $branchs
        ]);
    }

    public function view_activity($id) {
        $customer_options = array();
        $activity_details = DB::table('hvl_activity_master')
                ->select('hvl_activity_master.*', 'hvl_customer_master.branch_name')
                ->join('hvl_customer_master', 'hvl_customer_master.id', '=', 'hvl_activity_master.customer_id');

        $activity_details = $activity_details->where('hvl_activity_master.customer_id', $id);
        $activity_details = $activity_details->orderBy('hvl_activity_master.id', 'DESC');
        $activity_details = $activity_details->get();

        $em_id = Auth::User()->id;
        $emp = DB::table('employees')
                ->where('user_id', '=', $em_id)
                ->first();
        $branchs = DB::table('hvl_customer_master')
                ->join('Branch', 'Branch.id', '=', 'hvl_customer_master.branch_name')
                ->where('hvl_customer_master.id', $id)
                ->groupBy('Branch.id')
                ->pluck('Name', 'Branch.id')
                ->toArray();

        $customers = DB::table('hvl_customer_master')
                        ->where('hvl_customer_master.id', $id)
                        ->pluck('customer_name', 'id')->toArray();

        $hvl_job_cards = DB::table('hvl_job_cards')
                ->groupBy('hvl_job_cards.activity_id')
                ->orderBy('id', 'DESC')
                ->pluck('hvl_job_cards.added', 'hvl_job_cards.activity_id')
                ->toArray();

        $hvl_audit_reports = DB::table('hvl_audit_reports')
                ->groupBy('hvl_audit_reports.activity_id')
                ->orderBy('id', 'DESC')
                ->pluck('hvl_audit_reports.added', 'hvl_audit_reports.activity_id')
                ->toArray();

        $activity_status = DB::table('activitystatus')->pluck('Name', 'id')->toArray();

        return view('hvl.activitymaster.index', [
            'em_id' => $em_id,
            'details' => $activity_details,
            'customers' => $customers,
            'customer_options' => $customer_options,
            'branchs' => $branchs,
            'status' => $activity_status,
            'hvl_job_cards' => $hvl_job_cards,
            'hvl_audit_reports' => $hvl_audit_reports,
            'search_branch' => null,
            'search_sdate' => null,
            'search_edate' => null,
            'search_status_id' => [],
            'search_customer_ids' => [$id]
        ]);
    }

    public function export_all_customer() {
        return (new ExportCustomer)->download('AllCustomer.xlsx');
    }
}
