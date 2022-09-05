<?php

namespace Tests\Unit;

use Tests\TestCase;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MasterCustomerExport;
use App\User;
use App\Model\Master\Customer;
use App\Model\Master\BranchUser;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Fakes\ExcelFake;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExcelStoreTest extends TestCase
{

  /**
  * @test
  */
  public function can_fake_an_export()
  {
      Excel::fake();

      // Excel instance should be swapped to the fake now.
      $this->assertInstanceOf(ExcelFake::class, $this->app->make('excel'));
  }


  /**
     * @test
     */
    public function can_export_from_query()
    {
        $userBranch = BranchUser::where('user_id', 1)->get()->toArray();

        $export = new MasterCustomerExport($userBranch);

        $this->assertEquals(json_encode(array(
            'No',
            'Customer Code',
            'Customer Name',
            'Email',
            'Phone',
            'Address',
            'Credit Limit',
            'Pricing Group',
            'Customer Group'
        )), json_encode($export->headings()));

        $customer = factory(Customer::class)->create();
        $this->assertContains($customer->id, $export->map($customer));

    }



}
