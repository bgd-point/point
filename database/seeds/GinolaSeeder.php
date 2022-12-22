<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GinolaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection('tenant')->table('branches')->insert([
          "name" => "jambi"
        ]);
        
        DB::connection('tenant')->table('branch_user')->insert([
          [
            "user_id" => "1",
            "branch_id" => "1",
            "is_default" => "1"
          ],
          [
            "user_id" => "1",
            "branch_id" => "2",
            "is_default" => "0"
          ]
        ]);

        
        DB::connection('tenant')->table('customers')->insert([
          [
            "code" => "1",
            "name" => "jambi 1",
            "branch_id" => "1"
          ],
          [
            "code" => "2",
            "name" => "jambi 2",
            "branch_id" => "1"
          ],
          [
            "code" => "3",
            "name" => "central 1",
            "branch_id" => "2"
          ],
          [
            "code" => "4",
            "name" => "central 2",
            "branch_id" => "2"
          ]
        ]);

    }
}
