<?php

namespace App\Exports\Customers;

use App\Model\Form;
// use App\Model\Sales\DeliveryNote\DeliveryNote;
use App\Model\Master\Customer;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CustomerExport implements WithMultipleSheets 
{

    use Exportable;

    protected $tenant;
    
    public function __construct($tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $month = 1;

        $sheets = [
            new MasterItemSheet(),
            new MasterCustomerSheet($this->tenant)];


        return $sheets;
    }
}

