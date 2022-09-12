<?php

namespace Tests\Unit;

use App\Http\Controllers\Api\Master\ExportController;
use Tests\TestCase;

class ExportCustomerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->signIn();
        $this->setRole();

    }
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function test_export_customer_if_doesnt_have_permissions(){

        $respon = (new ExportController())->export();
        $this->assertNotEmpty($respon);

    }


    public function  test_export_customer_success(){
        $this->setPermissionReadCustomer();
        $respon = (new ExportController())->export();
        $this->assertNotEmpty($respon);
    }
}
