<?php

namespace Tests\Feature\Http\Master;

use App\Model\Master\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SupplierTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->signIn();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testImportDataExcel()
    {


        $path = base_path('/tests/Import/import_master_supplier_test.xlsx');

        $file = new UploadedFile(
            $path,
            'fileImport.xlsx'
        );

        $response = $this->json('POST', route('supplier.import'), [
            'file' => $file,
        ]);

        $response->assertCreated();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testImportDataExcelEmptyRowField()
    {


        $path = base_path('/tests/Import/import_master_supplier_test_false.xlsx');

        $file = new UploadedFile(
            $path,
            'fileImport.xlsx'
        );

        $response = $this->json('POST', route('supplier.import'), [
            'file' => $file,
        ]);

        $response->assertStatus(422);
    }
}
