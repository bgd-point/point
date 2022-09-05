<?php

namespace App\Exports;

use App\Model\Master\Customer;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MasterCustomerExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * MasterCustomerExport constructor.
     *
     * @param array $branchId
     */
    public function __construct(array $branchUser)
    {
        $this->branchUser = $branchUser;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {

        return Customer::with(['priceGroup', 'customerGroup'])->whereIn('branch_id', $this->branchUser);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Customer Code',
            'Customer Name',
            'Email',
            'Phone',
            'Address',
            'Credit Limit',
            'Pricing Group',
            'Customer Group'
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {

        return [
            $row->id,
            $row->code,
            $row->name,
            $row->email,
            $row->phone,
            $row->address,
            $row->credit_limit,
            $row->priceGroup->label ?? '',
            $row->customerGroup->name ?? ''
        ];
    }
}
