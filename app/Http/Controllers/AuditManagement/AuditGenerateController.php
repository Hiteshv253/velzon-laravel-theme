<?php
namespace App\Http\Controllers\AuditManagement\AuditManagement;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\AuditReport;
use App\AuditGenerateReport;
use App\AuditGenerateReportDetail;
use App\AuditGenerateReportDetailImage;
use App\Http\Requests\AuditManagment\AuditGenerateStoreRequest;
use App\Http\Requests\AuditManagment\AuditGenerateUploadRequest;
use App\Models\hvl\CustomerMaster;
use Auth;
use App\TempUploadFile;
use File;
use PDF;
use Mail;
use App\Mail\CustomerAuditMail;
use Response;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Response as HttpResponse;


class AuditGenerateController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    protected $auditModel;
    protected $auditGenerateModel;
    protected $auditGenerateDetailModel;

    public function __construct(AuditReport $auditModel,AuditGenerateReport $auditGenerateModel,AuditGenerateReportDetail $auditGenerateDetailModel)
    {
        $this->auditModel = $auditModel;
        $this->auditGenerateModel =$auditGenerateModel;
        $this->auditGenerateDetailModel = $auditGenerateDetailModel;
    }

    public function index(Request $request,$id)
    {
        $audit = $this->auditModel->find($id);
        if(!$audit){
            return redirect(route('admin.audit.index'))->with('error', 'Audit Report Not Found');
        }
        $audit_general = $this->auditGenerateModel->where('audit_id',$id)->first();
        if(!$audit_general){
            date_default_timezone_set('Asia/Kolkata');
            $audit_general = new AuditGenerateReport();
            $audit_general->audit_id = $id;
            $audit_general->in_time = date('Y-m-d H:i:s');
            $audit_general->save();
        }
        $allGenerateDetails = $this->auditGenerateDetailModel->orderBy('id','DESC')->where('generate_id',$audit_general->id)->get();
        $images = AuditGenerateReportDetailImage::select([
            'audit_generate_report_detail_images.id',
            'generate_report_id','image'
            ])
            ->join(
                'audit_generate_report_details',
                'audit_generate_report_details.id',
                '=',
                'audit_generate_report_detail_images.generate_report_id'
            )
            ->get();
            /** this for google drive */
            $helper = new Helper();
            /**end google drive */
        return view('hvl.audit_management.generate.index',[
                    'audit_general'=>$audit_general,
                    'allGenerateDetails'=>$allGenerateDetails,
                    'images'=>$images,
                    'audit_general_id'=>$id,
                    'audit'=>$audit,
                    'helper'=>$helper
                ]);
    }
    
    public function sendCustomerAuditReport(Request $request){
            $customer = $this->auditModel->where('audit_report.id',$request->audit_id)
                ->join('hvl_customer_master','hvl_customer_master.id','audit_report.customer_id')
                ->select([
                    'hvl_customer_master.id as id',
                    'hvl_customer_master.customer_name as customer',
                    'hvl_customer_master.shipping_address as address',

                ])->first();
            
            $audit_general = $this->auditGenerateModel->where('audit_id',$request->audit_id)->first();
            $allGenerateDetails = $this->auditGenerateDetailModel->orderBy('id','DESC')->where('generate_id',$audit_general->id)->get();
            $images = AuditGenerateReportDetailImage::select(['audit_generate_report_detail_images.id','generate_report_id','image'])
                ->join('audit_generate_report_details','audit_generate_report_details.id','=','audit_generate_report_detail_images.generate_report_id')->get();
            /** this for google drive */
                $helper = new Helper();
            /**  end this for google drive */
                
            $pdf = PDF::loadView('hvl.audit_management.generate.audit_pdf',[
                    'audit_general'=>$audit_general,
                    'allGenerateDetails'=>$allGenerateDetails,
                    'images'=>$images,
                    'customer'=>$customer,
                    'helper'=>$helper
            ]);
             date_default_timezone_set("Asia/Kolkata");
               
            $file_path = "temp/audit_report".date('Y_M_d_h_i_s').'.pdf';               
            $pdf->save(base_path($file_path))->stream('download.pdf');
            // if( $request->ip()=='122.171.100.85'){
            //     echo "<pre>";
            //     print_r(get_class_methods($pdf));
            //     die;
            // }
            $emailParams = new \stdClass();
            $emailParams->file = $file_path;
            $emailParams->receiverName = $customer->customer;
            $emailParams->subject = $request->subject;
            $emailParams->body = $request->body;
            $emailParams->to_mail = $request->to;
            $email =  new CustomerAuditMail($emailParams);
            Mail::to($emailParams->to_mail)
            ->send($email);
            unlink(base_path($file_path));
            return redirect(route('admin.customer.audit_list',[$customer->id]))->with('success', 'Successfully Send Audit Report !');
    }
    
    public function getPrintPdf($id){
         $customer = $this->auditModel->where('audit_report.id',$id)
                    ->join('hvl_customer_master','hvl_customer_master.id','audit_report.customer_id')
                    ->select([
                        'hvl_customer_master.customer_name as customer',
                        'hvl_customer_master.shipping_address as address'
                    ])->first();
                          
        $audit_general = $this->auditGenerateModel->where('audit_id',$id)->first();
        $allGenerateDetails = $this->auditGenerateDetailModel->orderBy('id','DESC')->where('generate_id',$audit_general->id)->get();
        $images = AuditGenerateReportDetailImage::select(['audit_generate_report_detail_images.id','generate_report_id','image'])
                ->join('audit_generate_report_details','audit_generate_report_details.id','=','audit_generate_report_detail_images.generate_report_id')->get();
        $helper = new Helper();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
            ->loadView('hvl.audit_management.generate.audit_pdf',[
                'audit_general'=>$audit_general,
                'allGenerateDetails'=>$allGenerateDetails,
                'images'=>$images,
                'customer'=>$customer,
                'helper'=>$helper
        ]);
        $headers = array('Content-Type: Application/pdf');
        $file_name = date('Y_m_d_h_i_s').'_audit.pdf';
        $file_path = '/app/public/temp';
        $file_name_path =  $file_path.'/'.$file_name;
        $pdf->save(storage_path($file_path).'/'.$file_name);
        return HttpResponse::Download(public_path('/storage/'.'/temp/'.$file_name));
  
        // return $pdf->download('audit.pdf')->header('Content-Type','application/pdf');;
        
    }

    public function store(AuditGenerateStoreRequest $request,$audit_id)
    {
        try{
            $audit_general = $this->auditGenerateModel->where('audit_id',$audit_id)->first();
            $post_data = [
                'description'=>$request->description,
                'observation'=>$request->observation,
                'risk'=>$request->risk,
                'risk'=>$request->risk,
                'action'=>$request->action,
                'generate_id'=>$audit_general->id,
            ];
            $audit_generate_detail = $this->auditGenerateDetailModel->create($post_data);
            $new_db_images = $request->new_gallary_images; 
            if(isset($new_db_images) && !(empty($new_db_images))){
                foreach($new_db_images as $image_name){
                    $generate_image = new AuditGenerateReportDetailImage();
                    $generate_image->image = $image_name;
                    $generate_image->generate_report_id = $audit_generate_detail->id;
                    $generate_image->save();
                }   
            }

            if(!$audit_generate_detail){
                return response()->json([
                    'status'=>'error',
                    'message'=>'something went to worng',
                ]);
            }
            return response()->json([
                'status'=>'success',
                'message'=>'successfully Audit Entry add',
            ]);
        }catch(\Exception $e){
            return response()->json([
                'status'=>'error',
                'message'=>'something went to worng',
            ]);
        }
    }

    public function edit($audit_id,$generate_id)
    {
        $audit = $this->auditModel
            ->join('audit_generate_reports','audit_generate_reports.audit_id','=','audit_report.id')
            ->where('audit_generate_reports.id',$audit_id)->first();
    
        if(!$audit){
            return redirect(route('admin.audit.index'))->with('error', 'Audit Report Not Found');
        }
        $audit_general_detail = $this->auditGenerateDetailModel->find($generate_id)->toArray();
        if($audit_general_detail){
            return response()->json([
                'status'=>'success',
                'message'=>'successfully get entry',
                'data'=>$audit_general_detail
            ]);
       }
        return response()->json([
            'status'=>'error',
            'message'=>'something went to wong',
        ]);
    }
    public function update(AuditGenerateUploadRequest $request, $id)
    {
        // try{
            $audit = $this->auditModel->find($id);
            if(!$audit){
                return redirect(route('admin.audit.index'))->with('error', 'Audit Report Not Found');
            }
            $audit_generate_detail = $this->auditGenerateDetailModel->find($request->generat_detail_id);
            if(!$audit_generate_detail){
                return response()->json([
                    'status'=>'error',
                    'message'=>'something went to worng',
                ]);
            }
            $audit_generate_detail->description = $request->description;
            $audit_generate_detail->observation = $request->observation;
            $audit_generate_detail->risk = $request->risk;
            $audit_generate_detail->action = $request->action;
            $audit_generate_detail->update();
            $daleted_image = $request->delete_db_images; 
            if(isset($daleted_image) && !(empty($daleted_image))){
                foreach($daleted_image as $image_id){
                    $image = AuditGenerateReportDetailImage::find($image_id);
                    if($image){    
                        $delete_image = $image->getImage($image->image);
                        $image->delete();
                        $this->removeFile($delete_image);
                    }
                }
            }
            $new_db_images = $request->new_db_images; 
            if(isset($new_db_images) && !(empty($new_db_images))){
                foreach($new_db_images as $image_name){
                    $generate_image = new AuditGenerateReportDetailImage();
                    $generate_image->image = $image_name;
                    $generate_image->generate_report_id = $audit_generate_detail->id;
                    $generate_image->save();
                }
            }
            if(isset($daleted_image) && !(empty($daleted_image))){
                foreach($daleted_image as $image_id){
                    $image = AuditGenerateReportDetailImage::find($image_id);
                    if($image){    
                        $delete_image = $image->getImage($image->image);
                        $image->delete();
                        $this->removeFile($delete_image);
                    }
                }
            }
            return response()->json([
                'status'=>'success',
                'message'=>'successfully Audit Entry Update',
            ]);
        // }catch(\Exception $e){
        //     return response()->json([
        //         'status'=>'error',
        //         'message'=>'something went to worng',
        //     ]);
        // }
            
    }

    public function destroy(Request $request,$audit_id,$audit_entry_id)
    {
        try{
            $audit = $this->auditModel
            ->join('audit_generate_reports','audit_generate_reports.audit_id','=','audit_report.id')
            ->where('audit_generate_reports.id',$audit_id)->first();
            if(!$audit){
                return redirect(route('admin.audit.index'))->with('error', 'Audit Report Not Found');
            }
            // if($audit->generated =='yes'){
            //     return redirect(route('admin.audit.index'))->with('error', 'Audit Generate Submited.');
            // }
            $flag = $this->auditGenerateDetailModel->find($audit_entry_id);
            if(!$flag){
                return response()->json([
                    'status'=>'error',
                    'message'=>'something went to worng',
                ]);
            }
            $flag->delete();
            return response()->json([
                'status'=>'success',
                'message'=>'successfully Audit Entry delete',
            ]);
        }catch(\Exception $e){
            return response()->json([
                'status'=>'error',
                'message'=>'something went to worng',
            ]);
        }
       
    }
    public function saveSignature(Request $request,$id){
    //   try{
            $audit_detail = $this->auditGenerateModel->find($id);
            $old_image = '';
            if($request->user_type == 'client_sign_image'){
                $path = $audit_detail->getClientSignaturePath();
                $filename = $this->saveImageFile('signature',$request->image_file,$path);
                $audit_detail->client_signature = $filename['data']['file_name']; 
                date_default_timezone_set("Asia/Kolkata");
                $audit_detail->out_time = date('Y-m-d  H:i:s');
                $audit_detail->client_name = $request->name;
                $audit_detail->client_mobile = $request->mobile;
                $audit_detail->client_mail = $request->mail;
                $audit_detail->save();
                $auditReport = $this->auditModel->where('id',$audit_detail->audit_id)->first();
                $auditReport->generated = 'yes';
                $auditReport->save();
                //sending mail
                $customer = $this->auditModel->where('audit_report.id',$audit_detail->audit_id)
                    ->join('hvl_customer_master','hvl_customer_master.id','audit_report.customer_id')
                    ->select([
                        'hvl_customer_master.id as id',
                        'hvl_customer_master.customer_name as customer',
                        'hvl_customer_master.shipping_address as address',

                    ])->first();

                $audit_general = $this->auditGenerateModel->where('audit_id',$audit_detail->audit_id)->first();
                $allGenerateDetails = $this->auditGenerateDetailModel->orderBy('id','DESC')->where('generate_id',$audit_general->id)->get();
                $images = AuditGenerateReportDetailImage::select(['audit_generate_report_detail_images.id','generate_report_id','image'])
                    ->join('audit_generate_report_details','audit_generate_report_details.id','=','audit_generate_report_detail_images.generate_report_id')->get();
                $helper = new Helper();
            
                $pdf = PDF::loadView('hvl.audit_management.generate.audit_pdf',[
                        'audit_general'=>$audit_general,
                        'allGenerateDetails'=>$allGenerateDetails,
                        'images'=>$images,
                        'customer'=>$customer,
                        'helper'=>$helper,
                ]);
                $file_path = "temp/audit_report".date('Y_M_d_h_i_s').'.pdf';               
                $pdf->save(base_path($file_path))->stream('download.pdf');
            
                $emailParams = new \stdClass();
                $emailParams->file = $file_path;
                $emailParams->receiverName = $customer->customer;
                $emailParams->subject = "visit for audit report";
                $emailParams->body = 'Greetings From Hvl Pest Services !! <br>
                Please check the enclosed Audit Report.';
                $emailParams->to_mail = $request->mail;
                $email =  new CustomerAuditMail($emailParams);
                Mail::to($emailParams->to_mail)
                ->send($email);
                unlink(base_path($file_path));
            
            }
            if($request->user_type == 'technican_sign_image'){
                $path = $audit_detail->getTechnicianSignaturePath();
                $filename = $this->saveImageFile('signature',$request->image_file,$path);
                $audit_detail->technical_signature = $filename['data']['file_name'];
                $audit_detail->technical_name = $request->name;
                $audit_detail->technical_mobile = $request->mobile;
                $audit_detail->save();
            }
           
            return response()->json([
                'status' => 'success',
                'message' => 'successfully signature upload',
                'data' => [
                    'user_type'=>$request->user_type,
                    'file'=>$filename['data']['file_link'],
                    'sign_time'=>date('Y-m-d  H:i:s'),
                    'name'=>$request->name,
                    'mobile'=>$request->mobile,
                ],
            ]);
        // }catch(\Exception $e){
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => $e->getMessage(),
        //     ]);
        // }

    }
   
    public function getGalleryImage($id){
        try{
            $helper = new Helper();
            $gallery_images=[];
            $images = AuditGenerateReportDetailImage::select(['id','image'])
                    ->where('generate_report_id',$id)
                    ->get();
            foreach($images as $image){
                $gallery_images[] = [
                    'id'=>$image->id,
                    'image'=>$helper->getGoogleDriveImage($image->image),
                ];
            }
            return response()->json([
                'status'=>'success',
                'message'=>"successfully geting gallery's imags ",
                'data'=>['images'=>$gallery_images]
            ]);
        }catch(\Exception $e){
            return response()->json([
                'status'=>'error',
                'message'=>'something went to worng',
            ]);
        }
    }

    public function imageSave(Request $request){
        try{
            $helper = new Helper();
       
            $tempImage = new TempUploadFile();
            $path = $tempImage->getImagePath();
            $res = $this->saveImageFile('gallery',$request->image_file,$path);
      
            $filename = $res['data']['file_name'];
            $tempImage->path = $path;
            $tempImage->file = $filename;
            $tempImage->save();
            
            return response()->json([
                'status'=>'success',
                'message'=>"successfully gallery's imags uploaded",
                'data'=>[
                        'image'=>$helper->getGoogleDriveImage($res['data']['file_name']),
                        'image_name'=>$res['data']['file_name'],
                        'temp_id'=>$tempImage->id
                    ]
            ]);
        }catch(\Exception $e){
            return response()->json([
                'status'=>'error',
                'message'=> $e->getMessage(),
            ]);
        }
    }
    public function tempDelete(Request $request){
        try{
            $tempImage = TempUploadFile::where('id',$request->id)->first();
            if($tempImage){
                if($this->removeFile($tempImage->path."".$tempImage->file)){
                    $tempImage->delete();
                     return response()->json([
                        'status'=>'success',
                        'message'=>"successfully temp gallery's imags remove",
                    ]);
                }
            }
            return response()->json([
                'status'=>'error',
                'message'=>'something went to worng',
            ]);
        }catch(\Exception $e){
            return response()->json([
                'status'=>'error',
                'message'=>'something went to worng',
            ]);
        }
    }
    public function removeFile($file_path){
         if(file_exists($file_path)){
            unlink($file_path);
            return true;  
        }else{
            if(file_exists(public_path($file_path))){
                unlink(public_path($file_path));
                return true; 
            }
        }
        return false;
    }
    function saveImageFile($name,$file,$path){
        date_default_timezone_set("Asia/Kolkata");
        $filename = $name."_".date('Y_m_d_h_i_s_A').".jpg";
        $response = $this->saveImageFileToDrive($filename,$file);
        // $file->move($path, $filename); *|30-01-2024|*
        // return $filename;
        return $response;
    }
    function saveImageFileToDrive($filename,$file){
        $file_param = [
            'file_name'=>$filename,    
        ];
        $helper = new Helper();
        $file_result = $helper->uploadGoogleFile($file,$file_param);
        if($file_result['status']=='success'){
            $response = [
                'status'=>'success',
                'message'=>'successfully file uploaded on google drive',
                'data'=>[
                        'file_link' =>$helper->getGoogleDriveImage($file_result['data']['file_google_filename']), //"https://drive.google.com/thumbnail?id=".$file_result['data']['file_google_path'],
                        'file_path'=>$file_result['data']['file_google_path'],
                        'file_size'=>$file_result['data']['file_size'],
                        'file_name'=>$file_result['data']['file_google_filename'],
                ]
            ];
        }else{
            $response = [
                'status'=>'fail',
                'message'=>$file_result['message'],
            ];
        }
        return $response; 
    }

}
