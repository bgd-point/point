<?php

namespace App\Exports\Master;

use App\Model\Master\Customer;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\FromView;

class CustomerExport implements FromView
{
    public function view(): View
    {
      // get user 
      $branches = DB::connection('tenant')
      ->table('branch_user')
      ->where('user_id', 1)
      ->get();

      // get all user branch
      $branch_id = [];
      foreach($branches as $branch)
      {
        array_push($branch_id, $branch->branch_id);
      }

      // get date now
      $today = Carbon::now();
      
      return view('exports.customer', [
        'customers' => Customer::whereIn('branch_id', $branch_id)->get(),
        'today' => $today->toDateTimeString()
      ]);
    }

    public function collection()
    {
      $branches = DB::connection('tenant')
      ->table('branch_user')
      ->where('user_id', 1)
      ->get();

      $branch_id = [];
      foreach($branches as $branch)
      {
        array_push($branch_id, $branch->branch_id);
      }
      
      return Customer::whereIn('branch_id', $branch_id)->get();
    }
}
