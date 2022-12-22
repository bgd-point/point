<?php

namespace Tests\Feature\Http\Master;

use App\Exports\CustomerExport;
use Illuminate\Foundation\Testing\DatabaseTransactions;
// use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\DatabaseMigrations;
use Maatwebsite\Excel\Facades\Excel;

use Tests\TestCase;

class CustomerTest extends TestCase
{
  use DatabaseTransactions;

    public function test_central_branch()
    {
      Excel::fake();
      $this->withoutMiddleware()->get('api/v1/master/customers/export');
      
      Excel::assertDownloaded('customer.xlsx', function(CustomerExport $export) {
        // dd($export->collection());
        $res = $export->collection()->toArray();
        // dd($res);
        $sv = [];
        foreach($res as $dt)
        {
          $ob = (object) $dt;
          array_push($sv, $ob->branch_id);
        }
        // dd($sv);
        return $this->assertContains("2", $sv, "2(Central) tidak ada") === null;
        // return true;
      });
    }

    public function test_jambi_branch()
    {
      Excel::fake();
      $this->withoutMiddleware()->get('api/v1/master/customers/export');
      
      Excel::assertDownloaded('customer.xlsx', function(CustomerExport $export) {
        // dd($export->collection());
        $res = $export->collection()->toArray();
        // dd($res);
        $sv = [];
        foreach($res as $dt)
        {
          $ob = (object) $dt;
          array_push($sv, $ob->branch_id);
        }
        // dd($sv);
        $check = self::assertContains("1", $sv, "1(Jambi) tidak ada");
        if($check===null){
          return true;
        }
        // return $this->assertContains("2", $sv, "2(Jambi) tidak ada") === null;
        // return true;
      });
    }
}