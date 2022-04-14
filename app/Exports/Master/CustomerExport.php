<?php

namespace App\Exports\Master;

use App\Model\Master\Customer;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

// class CustomerExport implements FromQuery, WithHeadings, WithMapping
class CustomerExport implements FromView, ShouldAutoSize, WithEvents
{
    public function __construct(String $tenant, String $exportedDate) {
        $this->tenant = $tenant;
        $this->exportedDate = $exportedDate;
    }

    public function query() {
        $tableAlias = Customer::getTableName().' as '.Customer::$alias;
        $user = tenant(auth()->user()->id);

        $customers = Customer::from($tableAlias)
            ->fields('customer.*')
            ->sortBy('customer.name')
            ->includes('groups;pricingGroup;branch')
            ->whereIn('customer.branch_id', $user->branches->pluck('id'));

        return Customer::joins($customers, 'address,phone,email')->get();
    }

    public function view(): View
    {      
        return view('exports.master.customer', [ 
            'tenant' => $this->tenant,
            'exportedDate' => $this->exportedDate,
            'data' => $this->query() 
        ]); 
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        $startRowData = 5;
        $lastRowData = $this->query()->count() + $startRowData;

        return [
            AfterSheet::class => function (AfterSheet $event) use ($startRowData, $lastRowData) {
                $tenantRowStyleArray = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                $event->getSheet()->getStyle('C4')->applyFromArray($tenantRowStyleArray);

                $dataStyleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '00000000'],
                        ],
                    ],
                ];
                $event->getSheet()->getStyle('C'.$startRowData.':K'.$lastRowData)->applyFromArray($dataStyleArray);
            },
        ];
    }
}