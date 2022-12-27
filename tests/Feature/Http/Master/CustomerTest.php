<?php

namespace Tests\Feature\Http\Master;

use App\Exports\Master\CustomerExport;
use App\Model\Master\Branch;
use App\Model\Master\Customer;
use App\Model\Master\User;
use Illuminate\Support\Facades\Artisan;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('tenant:seed:dummy', ['db_name' => env('DB_TENANT_DATABASE')]);
        $this->signIn();
    }

    /** @test */
    public function create_customer_test()
    {
        $data = [
            'name' => $this->faker->name,
        ];

        // API Request
        $response = $this->json('POST', '/api/v1/master/customers', $data, [$this->headers]);

        // Check Status Response
        $response->assertStatus(201);

        // Check Database
        $this->assertDatabaseHas('customers', [
            'name' => $data['name'],
        ], 'tenant');
    }

     /** @test */
    public function export_customer_test(){

        factory(Customer::class, 50)->create([
            'branch_id' => 1
        ]);

        //  API Request
         $response = $this->get('/api/v1/master/customers/export', [$this->headers]);
         //'GET', 
         
        //  Check Status Response
         $response->assertStatus(200);

    }
}
