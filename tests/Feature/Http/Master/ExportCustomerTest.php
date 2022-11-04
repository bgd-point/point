<?php

namespace Tests\Feature\Http\Master;

use App\Model\Master\User;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ExportCustomerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('tenant:seed:dummy', ['db_name' => env('DB_TENANT_DATABASE')]);
        $this->signInAdmin();
    }
    
    /** @test */
    public function export_customer_test()
    {
        $response = $this->get('/api/v1/master/customers/export');

        // Check Status Response
        $response->assertStatus(200);
    }
}
