<?php

namespace App\Http\Controllers\CustomerActivityHistoryController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerActivityHistoryController extends Controller {

    public function batchsupdate(Request $request, $customer_id) {

        $batch_number = $request->batch_number;
        $update_amount = $request->update_amount;
        $customer_id = $request->customer_id;
    // echo $batch_number;
    // die;
        DB::table('hvl_activity_master')->where('batch', '=', $batch_number)->update([
            'services_value' => $update_amount
        ]);

        /**/
        $customer_name = DB::table('hvl_customer_master')->where('hvl_customer_master.id', $customer_id)->first()->customer_name;
        $activity_details = DB::table('hvl_activity_master')
                ->select([
                    'hvl_activity_master.subject' => 'subject',
                    'hvl_activity_master.frequency' => 'frequency',
                    'hvl_activity_master.start_date' => 'start_date',
                    'hvl_activity_master.end_date' => 'end_date',
                    'hvl_activity_master.services_value' => 'services_value',
                    'hvl_activity_master.batch' => 'batch',
                    'hvl_activity_master.status' => 'status',
                ])
                ->where('hvl_activity_master.customer_id', $customer_id)
                ->whereIn('hvl_activity_master.status', [1, 2, 3])
                ->orderBy('hvl_activity_master.batch', 'DESC')
                ->orderBy('hvl_activity_master.start_date', 'ASC')
                ->orderBy('hvl_activity_master.end_date', 'ASC')
                ->get();
        $result = [];
        // date_default_timezone_set("Asia/Kolkata");
        // $todayDayDate = date('Y-m-d');
        foreach ($activity_details as $activity_detail) {
            if (!($activity_detail->batch && $activity_detail->services_value))
                continue;
            $result[$activity_detail->batch]['batch'] = $activity_detail->batch;
            $result[$activity_detail->batch]['subject'] = $activity_detail->subject;
            $result[$activity_detail->batch]['frequency'] = $activity_detail->frequency;
            if (!isset($result[$activity_detail->batch]['start_date']))
                $result[$activity_detail->batch]['start_date'] = $activity_detail->start_date;
            $result[$activity_detail->batch]['end_date'] = $activity_detail->end_date;
            if ($activity_detail->status == 1) {
                if (!isset($result[$activity_detail->batch]['completed_activities'])) {
                    $result[$activity_detail->batch]['completed_activities'] = 1;
                    $result[$activity_detail->batch]['completed_service_value'] = $activity_detail->services_value;
                } else {
                    $result[$activity_detail->batch]['completed_activities'] = $result[$activity_detail->batch]['completed_activities'] + 1;
                    $result[$activity_detail->batch]['completed_service_value'] += $activity_detail->services_value;
                }
            }
            if (!isset($result[$activity_detail->batch]['total_activities'])) {
                $result[$activity_detail->batch]['total_activities'] = 1;
                $result[$activity_detail->batch]['total_service_value'] = $activity_detail->services_value;
            } else {
                $result[$activity_detail->batch]['total_activities'] = $result[$activity_detail->batch]['total_activities'] + 1;
                $result[$activity_detail->batch]['total_service_value'] += $activity_detail->services_value;
            }
        }
        /**/

        return view('hvl.customermaster.customer_history.history', [
            'result' => $result,
            'customer_id' => $customer_id
        ]);
//        return redirect('/customer-master')->with('success', 'Customer Batch Recored Has Been Updated.');
    }

    public function batchupdate($batchnumber, $customer_id, $batchname, $frequency, $total_activities) {
        
        $activity_details = DB::table('hvl_activity_master')
                ->select('*')
                ->where('hvl_activity_master.batch', '=', $batchnumber)
                ->get();

        $customer_name = DB::table('hvl_customer_master')->where('id', '=', $customer_id)->first();


        return view('hvl.customermaster.customer_history.batch', [
            'details' => $activity_details,
            'batchnumber' => $batchnumber,
            'customer_id' => $customer_id,
            'customer_name' => $customer_name,
            'batchname' => $batchname, 'frequency' => $frequency,
            'total_activities' => $total_activities
        ]);
    }

    public function index($customer_id) {

        $customer_name = DB::table('hvl_customer_master')->where('hvl_customer_master.id', $customer_id)->first()->customer_name;
        $activity_details = DB::table('hvl_activity_master')
                ->select([
                    'hvl_activity_master.subject' => 'subject',
                    'hvl_activity_master.frequency' => 'frequency',
                    'hvl_activity_master.start_date' => 'start_date',
                    'hvl_activity_master.end_date' => 'end_date',
                    'hvl_activity_master.services_value' => 'services_value',
                    'hvl_activity_master.batch' => 'batch',
                    'hvl_activity_master.status' => 'status',
                ])
                ->where('hvl_activity_master.customer_id', $customer_id)
                ->whereIn('hvl_activity_master.status', [1, 2, 3])
                ->orderBy('hvl_activity_master.batch', 'DESC')
                ->orderBy('hvl_activity_master.start_date', 'ASC')
                ->orderBy('hvl_activity_master.end_date', 'ASC')
                ->get();
        $result = [];
        // date_default_timezone_set("Asia/Kolkata");
        // $todayDayDate = date('Y-m-d');
        $subject='';
        $frequency='';
        foreach ($activity_details as $activity_detail) {
            //if (!($activity_detail->batch && $activity_detail->services_value))
           // continue;
           
           if($activity_detail->subject==''){
               $subject="blank subject";
           }else{
               $subject=$activity_detail->subject;
           }
            if($activity_detail->frequency==''){
               $frequency="blank frequency";
           }else{
               $frequency=$activity_detail->frequency;
           }
            $result[$activity_detail->batch]['batch'] = $activity_detail->batch;
            $result[$activity_detail->batch]['subject'] = $subject;
            $result[$activity_detail->batch]['frequency'] = $frequency;
            if (!isset($result[$activity_detail->batch]['start_date']))
                $result[$activity_detail->batch]['start_date'] = $activity_detail->start_date;
            $result[$activity_detail->batch]['end_date'] = $activity_detail->end_date;
            
            if ($activity_detail->status == 1) {
                if (!isset($result[$activity_detail->batch]['completed_activities'])) {
                    $result[$activity_detail->batch]['completed_activities'] = 1;
                    $result[$activity_detail->batch]['completed_service_value'] = $activity_detail->services_value;
                } else {
                    $result[$activity_detail->batch]['completed_activities'] = $result[$activity_detail->batch]['completed_activities'] + 1;
                    // echo "<pre>";
                    // echo "<br>";
                    // print_r($activity_detail);
                    // print_r( $activity_detail->services_value);
                    
                    $result[$activity_detail->batch]['completed_service_value'] += $activity_detail->services_value;
                }
            }
            if (!isset($result[$activity_detail->batch]['total_activities'])) {
                $result[$activity_detail->batch]['total_activities'] = 1;
                $result[$activity_detail->batch]['total_service_value'] = $activity_detail->services_value;
            } else {
                $result[$activity_detail->batch]['total_activities'] = $result[$activity_detail->batch]['total_activities'] + 1;
                $result[$activity_detail->batch]['total_service_value'] += $activity_detail->services_value;
            }
        }
        // die;
       //dd($result);
        return view('hvl.customermaster.customer_history.history', [
            'result' => $result,
            'customer_id' => $customer_id
        ]);
    }

}
