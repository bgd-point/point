<?php

namespace Tests\Feature;

use App\Imports\SupplierImport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class SupplierImportTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function importSuppler()
    {
        Excel::fake();
    
    Excel::assertImported('filename.xlsx', 'diskName');
    
    Excel::assertImported('filename.xlsx', 'diskName', function(SupplierImport $import) {
        return true;
    });
    
    // When passing the callback as 2nd param, the disk will be the default disk.
    Excel::assertImported('filename.xlsx', function(SupplierImport $import) {
        return true;
    });
    }
}
