<?php

namespace App\Models\CustomerMaster;

use Illuminate\Database\Eloquent\Model;

class CustomerMaster extends Model {

    protected $table = 'hvl_customer_master';

    const ACTIVE = 0;
    const INACTIVE = 1;
    const ACTIVE_TEXT = 'Active';
    const INACTIVE_TEXT = "Inactive";

    protected $fillable = [
        'employee_id',
        'customer_code',
        'customer_name',
        'customer_alias',
        'billing_address',
        'billing_location',
        'billing_latitude',
        'billing_longitude',
        'billing_status',
        'billing_pincode',
        'contact_person',
        'contact_person_phone',
        'billing_state',
        'billing_email',
        'billing_mobile',
        'operator',
        'operation_executive',
        'sales_person',
        'reference',
        'status',
        'create_date',
        'shipping_address',
        'shipping_state',
        'shipping_city',
        'shipping_pincode',
        'credit_limit',
        'gstin',
        'gst_reges_type',
        'payment_mode',
        'branch_name',
        'con_start_date',
        'con_end_date',
        'cust_value',
        'is_active',
        'inactive_remark',
        'inactive_date'
    ];

    public function getStatusOption() {
        return [
            self::ACTIVE => self::ACTIVE_TEXT,
            self::INACTIVE => self::INACTIVE_TEXT
        ];
    }

    public function getStatus($code) {
        $statusOption = $this->getStatusOption();
        if (isset($statusOption[$code])) {
            return $statusOption[$code];
        }
        return null;
    }
}
