<?php

namespace App\Model\Accounting;

use App\Model\HumanResource\Employee\Employee;
use App\Model\Master\Customer;
use App\Model\Master\Expedition;
use App\Model\Master\Supplier;
use App\Model\PointModel;

class CutOffDownPayment extends PointModel
{
    protected $connection = 'tenant';

    public static $alias = 'cutoff_down_payment';

    protected $table = 'cutoff_down_payments';

    public static $morphName = 'CutoffDownPayment';

    protected $fillable = [
        'date',
        'amount',
        'notes',
    ];

    public function cutoff_downpaymentable()
    {
        return $this->morphTo();
    }

    public static function getCutOffDownPaymentableType($subLedger)
    {
        $morphName = null;
        if ($subLedger === 'CUSTOMER') {
            $morphName = Customer::$morphName;
        } elseif ($subLedger === 'SUPPLIER') {
            $morphName = Supplier::$morphName;
        } elseif ($subLedger === 'EXPEDITION') {
            $morphName = Expedition::$morphName;
        } elseif ($subLedger === 'EMPLOYEE') {
            $morphName = Employee::$morphName;
        } 
        
        return $morphName;
    }
}
