<?php

namespace App\Imports;

use App\Model\Master\Supplier;
use Maatwebsite\Excel\Concerns\ToModel;

class SupplierImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Supplier([
            'code' => $row[1],
            'name' => $row[2],
            'email' => $row[3],
            'address' => $row[4],
            'phone' => $row[5]  
        ]);
    }
}
