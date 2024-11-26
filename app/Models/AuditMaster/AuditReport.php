<?php

namespace App\AuditMaster;

use Illuminate\Database\Eloquent\Model;

class AuditReport extends Model {

    protected $table = 'audit_report';
    protected $fillable = [
        'audit_type',
        'customer_id',
        'schedule_date',
        'schedule_notes',
        'generated'
    ];

    const PLANNED = 'planned';
    const ADHOC = 'adhoc';
    const PLANNED_TEXT = 'Planned';
    const ADHOC_TEXT = 'Adhoc (Emergency)';
    const GENERATED = 'yes';
    const NOT_GENERATED = 'no';

    public function getAuditTypeOption() {
        return[
            self::PLANNED => self::PLANNED_TEXT,
            self::ADHOC => self::ADHOC_TEXT
        ];
    }

    public function getAuditTypeText($code) {
        $options = $this->getAuditTypeOption();
        return $options[$code];
    }
}
