<?php

namespace App\AuditMaster;

use Illuminate\Database\Eloquent\Model;

class AuditGenerateReportDetail extends Model
{
    protected $table = 'audit_generate_report_details';
    protected $fillable = [
        'generate_id',
        'description',
        'observation',
        'risk',
        'action',
    ];
}
