<?php

namespace App\AuditMaster;

use Illuminate\Database\Eloquent\Model;

class AuditGenerateReport extends Model
{
    const CLIENT_SIGNATURE_PATH = 'public/uploads/audit_report/signature/client';
    const TECHNICIAN_SIGNATURE_PATH = 'public/uploads/audit_report/signature/technician';
    
    protected $table='audit_generate_reports';
    protected $fillable=[
        'audit_id',
        'in_time',
        'out_time',
        'client_signature',
        'client_name',
        'client_mobile',
        'client_mail',
        'technical_name',
        'technical_mobile',
        'technical_signature'
    ];
    public function getClientSignaturePath(){
        return self::CLIENT_SIGNATURE_PATH;
    }
    public function getTechnicianSignaturePath(){
        return self::TECHNICIAN_SIGNATURE_PATH;
    }
    
    public function getClientSignature($signature_name){
        // if (file_exists(public_path($this->getClientSignaturePath().'/'.$signature_name))){
            return $this->getClientSignaturePath().'/'.$signature_name;
        // }
        // return '';
    }
    public function getTechnicianSignature($signature_name){
        // if (file_exists(public_path($this->getTechnicianSignaturePath().'/'.$signature_name))){
            return $this->getTechnicianSignaturePath().'/'.$signature_name;
        // }
        // return '';
    }
}
