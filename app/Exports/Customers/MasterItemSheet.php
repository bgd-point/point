<?php

namespace App\Exports\Customers;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\Model\Master\Customer;

class MasterItemSheet implements WithTitle
{
   
    
    /**
     * @return string
     */
    public function title(): string
    {
        return 'Export Master Item';
    }
}