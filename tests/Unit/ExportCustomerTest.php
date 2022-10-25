<?php

namespace Tests\Unit;

use Tests\TestCase;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Artisan;
use App\Exports\Master\CustomerExport;
use App\User;

class ExportCustomerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExportCustomer()
    {
        
        Excel::fake();

        $this->user = factory(User::class)->create();

        $this->actingAs($this->user, 'api')
             ->post('api/v1/master/customers/export');
        
        Excel::assertDownloaded('customer.xlsx', function(CustomerExport $export) {
            return true;
        });

    }
}
