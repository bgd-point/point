<?php

namespace Tests\Unit\Customer\Export;

use App\Model\Master\User;
use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CustomerExport extends TestCase
{
    use DatabaseMigrations;
    use Authenticatable;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        \Maatwebsite\Excel\Facades\Excel::fake();
        $user = User::where('email','admin@point')->first();
        $this->actingAs($user)
            ->post('/api/v1/master/customers/export');

        \Maatwebsite\Excel\Facades\Excel::assertDownloaded('customer.xlsx', function(\App\Exports\Customer\CustomerExport $export) {
            return $export->collection()->contains(Carbon::now()->toDayDateTimeString());
        });
    }
}
