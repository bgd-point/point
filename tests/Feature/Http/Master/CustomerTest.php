<?php

namespace Tests\Feature\Http\Master;

use Tests\TestCase;

class CustomerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->signIn();
    }

    /** @test */
    public function create_customer_test()
    {
        $data = [
            'name' => $this->faker->name,
        ];

        // API Request
        $response = $this->json('POST', '/api/v1/master/customers', $data, $this->headers);

        // Check Status Response
        $response->assertStatus(201);

        // Check Database
        $this->assertDatabaseHas('customers', [
            'name' => $data['name'],
        ], 'tenant');
    }
    /** @test */
    public function export_customers_success_test()
    {
        $this->setProject();

        $response = $this->json('GET', '/api/v1/master/customers/export', [], $this->headers);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['url']
            ]);
    }
    /** @test */
    public function export_customers_header_not_complete_test()
    {
        $this->setProject();

        $headers = $this->headers;
        unset($headers['Tenant']);

        $response = $this->json('GET', '/api/v1/master/customers/export', [], $headers);

        $response->assertStatus(500)
            ->assertJsonStructure(['message']);
    }
}
