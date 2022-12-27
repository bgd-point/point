<?php

namespace App\Exports\Customers;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use App\Model\Master\Customer;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Carbon\Carbon;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class MasterCustomerSheet implements FromCollection,WithTitle,WithHeadings,WithCustomStartCell, WithEvents,WithMapping
{
    private $tenant;
    private $row = 0;

    public function __construct($tenant) //int $year, int $month)
    {
        $this->tenant = $tenant;
    }

    
    public function model(array $row)
    {

        ++$this->row;

    }

    public function collection()
    {
       
        $costumers = DB::table(config('database.connections.tenant.database').'.customers as c')
            ->select('c.id','c.code','c.name','c.email'
                    ,'c.phone','c.address','c.credit_limit'
                    ,'pg.label','cg.name as group_name')
            ->join(config('database.connections.tenant.database').'.customer_groups as cg', 'cg.id', '=', 'c.branch_id')
            ->join(config('database.connections.tenant.database').'.pricing_groups as pg','pg.id','=','c.pricing_group_id')
            ->get();
        return $costumers;
    }

    public function map($customer): array
    {
        return [
            ++$this->row,
            $customer->code,
            $customer->name,
            $customer->email,
            $customer->phone,
            $customer->address,
            $customer->credit_limit,
            $customer->label,
            $customer->group_name,
        ];
    }

   
    /**
     * @return string
     */
    public function title(): string
    {
        return 'Export Master Customer';
    }

    public function headings(): array
    {
        return [
         
            [$this->tenant],
            [
            'No',
            'Customer Code',
            'Customer Name',
            'Email',
            'Phone',
            'Address',
            'Credit Limit',
            'Pricing Group',
            'Customer Group'
        ]];
    }
    
    public function startCell(): string
    {
        return 'C4';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $last_row = $event->sheet->getHighestRow();
                $event->sheet->setCellValue('A2','Date  Export :');
                $event->sheet->setCellValue('B2',date('d F Y H:i', strtotime(Carbon::now())));
                $event->sheet->getColumnDimension('A')
                            ->setAutoSize(false)
                            ->setWidth(18);
                $event->sheet->getColumnDimension('B')
                            ->setAutoSize(false)
                            ->setWidth(25);
                $event->sheet->getColumnDimension('D')
                            ->setAutoSize(false)
                            ->setWidth(18);
                $event->sheet->getColumnDimension('E')
                            ->setAutoSize(false)
                            ->setWidth(18);
                $event->sheet->getColumnDimension('F')
                            ->setAutoSize(false)
                            ->setWidth(18);
                $event->sheet->getColumnDimension('G')
                            ->setAutoSize(false)
                            ->setWidth(18);
                $event->sheet->getColumnDimension('H')
                            ->setAutoSize(false)
                            ->setWidth(18);       
                $event->sheet->getColumnDimension('I')
                            ->setAutoSize(false)
                            ->setWidth(18);
                $event->sheet->getColumnDimension('J')
                            ->setAutoSize(false)
                            ->setWidth(18);
                $event->sheet->getColumnDimension('K')
                            ->setAutoSize(false)
                            ->setWidth(18);
                $tenanName = 'C4:K4'; // All headers
                $event->sheet->mergeCells($tenanName);
                $event->sheet->getDelegate()->getStyle($tenanName)->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle($tenanName)->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle($tenanName)
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                
                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '00000000'],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                $event->getSheet()->getStyle('C5:' . sprintf('K%d',$last_row))->applyFromArray($styleArray);
            }
        ];
    }

    public function columnFormats(): array
    {
        return [
            'I' => NumberFormat::FORMAT_NUMBER,
        ];
    }
}
