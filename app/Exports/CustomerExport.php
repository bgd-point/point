<?php

namespace App\Exports;

use App\Model\Master\Branch;
use App\Model\Master\Customer;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;

// class CustomerExport implements FromCollection, WithHeadings, WithCustomStartCell
class CustomerExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
      $branch = DB::connection('tenant')
      ->table('branch_user')
      ->where('user_id', 1)
      // ->where('is_default', 1)
      ->get();

      $branch_id = [];
      foreach($branch as $dt)
      {
        array_push($branch_id, $dt->branch_id);
      }

      $today = Carbon::now();
        return view('exports.customer', [
            'customer' => Customer::whereIn('branch_id', $branch_id)->get(),
            // 'customer' => Customer::whereIn('branch_id', ["1", "2"])->get(),
            'today' => $today->toDateTimeString()
        ]);
    }

    public function collection()
    {
      $branch = DB::connection('tenant')
      ->table('branch_user')
      ->where('user_id', 1)
      // ->where('is_default', 1)
      ->get();

      $branch_id = [];
      foreach($branch as $dt)
      {
        array_push($branch_id, $dt->branch_id);
      }
      
      return Customer::whereIn('branch_id', $branch_id)->get();
    }

    // public function headings(): array
    // {
    //     return [
    //       [
    //         'Nama Tenant'
    //       ],
    //       [
    //         // 'No',
    //         'Customer Code',
    //         'Customer Name',
    //         'Email',
    //         'Phone',
    //         'Address',
    //         'Credit Limit',
    //         'Pricing Group',
    //         // 'Customer Group'
    //       ]
    //     ];
    // }

    // public function startCell(): string
    // {
    //     return 'B2';
    // }
}
