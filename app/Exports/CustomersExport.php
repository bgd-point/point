<?php

namespace App\Exports;

use App\Model\Master\Customer;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class CustomersExport implements FromQuery, WithHeadings, WithMapping, WithCustomStartCell, WithEvents
{
    public $user, $customers;
    /**
     * ScaleWeightItemExport constructor.
     *
     * @param string $dateFrom
     * @param string $dateTo
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $this->customers = Customer::whereIn('branch_id', $this->user->branches->pluck('id'));
        return $this->customers;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            ['Date Export', ': ' . date('d F Y', strtotime(Carbon::now()))],
            [],
            [ '' , '', 'Nama Tenant'],
            ['','',
            'No',
            'Customer Code',
            'Customer Name',
            'Email',
            'Phone',
            'Address',
            'Credit Limit',
            'Pricing Group',
            'Customer Group']
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        return ['','',
            $row->id,
            $row->code,
            $row->name,
            $row->email,
            $row->phone,
            $row->address,
            $row->credit_limit,
            $row->pricingGroup->label,
            $row->groups->pluck('name'),
        ];
    }

    public function startCell(): string
    {
        return 'A2';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->mergeCells('c4:K4');
                $event->sheet->getDelegate()->getStyle('c4:K4')
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $title = 'C5:K5'; // All headers
                $event->sheet->getDelegate()->getStyle($title)
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()->getStyle('C5:K'.($this->customers->count() + 5))->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                $event->sheet->getStyle('C5:K'.($this->customers->count() + 5))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);
            },

        ];
    }    
}
