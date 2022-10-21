<?php
namespace App\Exports\Master;

use App\Model\Master\Customer;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CustomerExport implements FromView
{
    public function view(): View
    {
        $user = tenant(auth()->user()->id);
        $customers = Customer::whereIn('branch_id', $user->branches->pluck('id'))->get();

        return view('exports.master.customer', [
            'customers' => $customers
        ]);
    }
}