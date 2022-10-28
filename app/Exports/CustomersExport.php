<?php

namespace App\Exports;

use App\Model\Master\Customer;
use App\Repositories\CustomersRepository;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use \Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class CustomersExport implements FromQuery, WithHeadings, ShouldAutoSize, WithCustomStartCell, WithEvents, WithMapping, WithTitle, WithStrictNullComparison
{
    /**
    * @return \Illuminate\Support\Collection
    */

    use Exportable;
    protected $index = 0;
    
    public function forBranch(int $branch_id)
    {
        $this->branch_id = $branch_id;
        
        return $this;
    }

    public function query()
    {     
        return Customer::query()->with('pricingGroup','groups')->where('branch_id', $this->branch_id);
    }
    public function startCell(): string
    {
        return 'C5';
    }
    public function title(): string
    {
        return 'Export Master Customer';
    }

    public function map($customer): array
    {
        $groups = '';
        for ($i=0; $i < count(@$customer->groups) ; $i++) { 
            $groups .= $customer->groups[$i]->name.", ";
        }
        $no = ++$this->index;
        return [
            $no,
            $customer->code,
            $customer->name,
            $customer->email,
            $customer->address,
            $customer->phone,
            $customer->credit_limit,
            @$customer->pricingGroup->label,
            $groups,
        ];
    }


    public function registerEvents(): array {

        return [
            AfterSheet::class => function(AfterSheet $event) {
                /** @var Sheet $sheet */
                $sheet = $event->sheet;

                $sheet->setCellValue('A2', "Date Export :");
                $sheet->setCellValue('B2', date("d F Y H:m"));

                $sheet->mergeCells('C4:K4');
                $sheet->setCellValue('C4', "Nama Tenant");
                
                $styleArray = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                $styleArray2 = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '00000000'],
                        ],
                    ],
                ];
                $totalRows = $event->sheet->getHighestRow();
                $cellRange = 'C4:K4';
                $cellRange2 = 'C5:K'.$totalRows;
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle($cellRange2)->applyFromArray($styleArray2);
            },
        ];
    }


    public function headings(): array
    {
        return [
            [
                'No',
                'Customer Code',
                'Customer Name',
                'Email','Phone',
                'Address',
                'Credit Limit',
                'Pricing Group',
                'Customer Group'
            ]
        ];
    }


}
