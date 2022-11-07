<?php

namespace Tests\Feature\Http\Master;

use App\Exports\Master\CustomerExport;
use App\Model\Master\Branch;
use App\Model\Master\Customer;
use App\Model\Master\User;
use Illuminate\Support\Facades\Artisan;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ExportCustomerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        
        $this->signIn();
    }
    
    /** @test */
    public function export_customer_test()
    {
        factory(Customer::class, 50)->create([
            'branch_id' => 1
        ]);

        $this->get('/api/v1/master/customers/export')->assertStatus(200);
    }
}
