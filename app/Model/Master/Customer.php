<?php

namespace App\Model\Master;

use App\Model\Accounting\ChartOfAccount;
use App\Model\Accounting\ChartOfAccountType;
use App\Model\Accounting\Journal;
use App\Model\MasterModel;
use App\Model\Master\PricingGroup;
use App\Model\Master\CustomerCustomerGroup;
use App\Traits\Model\Master\CustomerJoin;
use App\Traits\Model\Master\CustomerRelation;

class Customer extends MasterModel
{
    use CustomerJoin, CustomerRelation;

    protected $connection = 'tenant';

    protected $appends = ['label'];

    protected $casts = ['credit_limit' => 'double'];

    protected $fillable = [
        'code',
        'name',
        'tax_identification_number',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'latitude',
        'longitude',
        'phone',
        'phone_cc',
        'email',
        'notes',
        'credit_limit',
        'pricing_group_id',
        'disabled',
    ];

    public static $morphName = 'Customer';

    public static $alias = 'customer';

    public function getLabelAttribute()
    {
        $label = $this->code ? '['.$this->code.'] ' : '';

        return $label.$this->name;
    }

    /**
     * Get the customer's total payable.
     */
    public function totalAccountPayable()
    {
        $payables = $this->journals()
            ->join(ChartOfAccount::getTableName(), ChartOfAccount::getTableName('id'), '=', Journal::getTableName('chart_of_account_id'))
            ->join(ChartOfAccountType::getTableName(), ChartOfAccountType::getTableName('id'), '=', ChartOfAccount::getTableName('type_id'))
            ->where(function ($query) {
                $query->where(ChartOfAccountType::getTableName('name'), '=', 'current liability')
                    ->orWhere(ChartOfAccountType::getTableName('name'), '=', 'long term liability')
                    ->orWhere(ChartOfAccountType::getTableName('name'), '=', 'other current liability');
            })
            ->selectRaw('SUM(`credit`) AS credit, SUM(`debit`) AS debit')
            ->first();

        return $payables->credit - $payables->debit;
    }

    /**
     * Get the customer's total receivable.
     */
    public function totalAccountReceivable()
    {
        $receivables = $this->journals()
            ->join(ChartOfAccount::getTableName(), ChartOfAccount::getTableName('id'), '=', Journal::getTableName('chart_of_account_id'))
            ->join(ChartOfAccountType::getTableName(), ChartOfAccountType::getTableName('id'), '=', ChartOfAccount::getTableName('type_id'))
            ->where(function ($query) {
                $query->where(ChartOfAccountType::getTableName('name'), '=', 'account receivable')
                    ->orWhere(ChartOfAccountType::getTableName('name'), '=', 'other account receivable');
            })
            ->selectRaw('SUM(`credit`) AS credit, SUM(`debit`) AS debit')
            ->first();

        return $receivables->debit - $receivables->credit;
    }

    /**
     * Get the ancestor customer group.
     */
    public function customerGroup()
    {

        return $this->hasOneThrough(CustomerGroup::class, CustomerCustomerGroup::class,
            'customer_id', // Foreign key on the environments table...
            'id', // Foreign key on the customer group table...
            'id', // Local key on the customer table...
            'customer_group_id'); // Local key on the environments table...);
    }

    /**
     * Get the ancestor price group.
     */
    public function priceGroup()
    {
        return $this->belongsTo(PricingGroup::class, 'pricing_group_id', 'id');
    }
}
