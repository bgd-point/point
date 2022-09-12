<?php

namespace App\Exports;

use App\Model\Master\BranchUser;
use App\Model\Master\Customer;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomerExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * CustomerExport constructor.
     *
     * @param array $branchId
     */
    public function __construct()
    {
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $userBranch = BranchUser::where('user_id', auth()->user()->id)->pluck('branch_id');
        // return Customer::get();
        return Customer::query()->whereIn('branch_id',$userBranch);
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
            'Credit Limit'/* ,
            'Pricing Group',
            'Customer Group' */
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
            $row->credit_limit/* ,
            $row->priceGroup->label ?? '',
            $row->customerGroup->name ?? '' */
        ];
    }
}