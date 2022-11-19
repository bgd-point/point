<?php

namespace App\Exports;

use App\Model\Master\Customer;
use App\Model\Master\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CustomerExport implements FromView
{

    protected $tenant;

    public function __construct($tenant) {
        $this->tenant = $tenant;
    }

    public function view(): View
    {
        $user = User::with('branches')->findOrFail(auth()->user()->id);
        $data['tenant'] = $this->tenant;
        $data['customers'] = Customer::with('pricingGroup', 'customerGroup')->whereIn('branch_id', $user->branches->pluck('id'))->get();
        return view('exports.master.customer', $data);
    }
}
