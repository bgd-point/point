<?php

namespace App\Exports\Master;

use App\Model\Master\Customer;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Events\BeforeSheet;

class CustomerExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithCustomStartCell, ShouldAutoSize
{
    protected $user_id;
    private $current_row =1;

    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    public function collection()
    {
        return Customer::with(['pricingGroup:id,label', 'customerGroup:id,name'])->where('branch_id', $this->user_id)->get();
    }

    public function startCell(): string
    {
        return 'C5';
    }

    public function headings(): array
    {
        return [
            'No',
            'Customer Code',
            'Customer Name',
            'Email',
            'Phone',
            'Address',
            'Credit Limit',
            'Pricing Group',
            'Customer Group'
        ];
    }

    public function map($row): array
    {
        return [
            $this->current_row++,
            $row->code,
            $row->name,
            $row->email,
            $row->phone,
            $row->address,
            $row->credit_limit,
            !empty($row->pricingGroup) ? $row->pricingGroup->label : 'Null',
            count($row->customerGroup) > 0 ? $row->customerGroup[0]->name : 'Null',
        ];   
    }    

    public function registerEvents(): array
   {
        $max_row = $this->current_row+5;
        return [
           AfterSheet::class => function(AfterSheet $event) use($max_row) {
                $date_now = Carbon::now()->format("d F Y H:i");
                
                $event->sheet->setCellValue('A2', "Date Export :");
                $event->sheet->setCellValue('B2', $date_now);

                $cell_bordered = [
                    'C5:K5',
                    'C6:C'.$max_row,
                    'D6:D'.$max_row,
                    'E6:E'.$max_row,
                    'F6:F'.$max_row,
                    'G6:G'.$max_row,
                    'H6:H'.$max_row,
                    'I6:I'.$max_row,
                    'J6:J'.$max_row,
                    'K6:K'.$max_row
                ];
                foreach ($cell_bordered as $cell) {
                    logger($cell);
                    $event->sheet->getStyle($cell)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '000000'],
                            ],
                        ],
                    ]);
                }
            },
       ];
   }
}
