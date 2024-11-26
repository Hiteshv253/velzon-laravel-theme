<?php

namespace App\AuditMaster;

use Illuminate\Database\Eloquent\Model;

class AuditGenerateReportDetailImage extends Model
{
    const IMAGEPATH = 'img/audit_gallery/';
    protected $table='audit_generate_report_detail_images';
    protected $fillable=[
        'generate_report_id',
        'image'
    ];
    public function getImage($image){
        return $this->getImagePath().$image;
    }
    public function getImagePath(){
        return self::IMAGEPATH;
    }
    
}
