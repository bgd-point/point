<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use app\Model\Master\Customer;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class CustomerExports implements FromArray, WithHeadings, WithCustomStartCell, WithTitle, WithEvents, WithColumnFormatting
{
    public function __construct(array $branchId)
    {
        $this->branchId = $branchId;
    }

    public function array(): array
    {
        $data = array();
        $customer = Customer::all()->whereIn('id',$this->branchId);
        $numb = 1;

        foreach ($customer as $key => $cust) {
            $data[] = [
                'no' => $numb,
                'code' => $cust->code,
                'name' => $cust->name,
                'email' => $cust->email,
                'phone' => $cust->phone,
                'address' => $cust->address,
                'credit_limit' => $cust->credit_limit,
                'pricing_group' => '',
                'customer_group' => '',
            ];
            $numb++;
        };


        return $data;
    }
    public function headings(): array
    {

        return  [
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
    public function startCell(): string
    {
        return 'C4';
    }

    public function title(): string
    {
        return "Tenant";
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $event->sheet->getColumnDimension('B')
                    ->setAutoSize(false)
                    ->setWidth(30);
                $event->sheet->getColumnDimension('A')
                    ->setAutoSize(false)
                    ->setWidth(30);
                $event->sheet->getColumnDimension('C')
                    ->setAutoSize(false)
                    ->setWidth(15);
                $event->sheet->getColumnDimension('D')
                    ->setAutoSize(false)
                    ->setWidth(15);
                $event->sheet->getColumnDimension('E')
                    ->setAutoSize(false)
                    ->setWidth(15);
                $event->sheet->getColumnDimension('F')
                    ->setAutoSize(false)
                    ->setWidth(15);
                $event->sheet->getColumnDimension('G')
                    ->setAutoSize(false)
                    ->setWidth(15);
                $event->sheet->getColumnDimension('H')
                    ->setAutoSize(false)
                    ->setWidth(15);
                $event->sheet->getColumnDimension('I')
                    ->setAutoSize(false)
                    ->setWidth(15);
                $event->sheet->getColumnDimension('J')
                    ->setAutoSize(false)
                    ->setWidth(15);
                $event->sheet->getColumnDimension('K')
                    ->setAutoSize(false)
                    ->setWidth(15);

                $event->sheet->getDelegate()->mergeCells('C4:K4')->setCellValue('C4', 'Tenant');
                $event->sheet->getDelegate()->getStyle('C4:K4')->getAlignment()->setHorizontal('center');
                $event->sheet->getDelegate()->setCellValue('A2', 'Date Export :');
                $event->sheet->getDelegate()->setCellValue('B2', Carbon::now()->toDateTimeString());
                $event->sheet->getDelegate()->getStyle('B2')->getAlignment()->setHorizontal('right');
            },
            AfterSheet::class => function (AfterSheet $event) {
                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '00000000'],
                        ],
                    ],
                ];
                $event->getSheet()->getDelegate()->getStyle('C5:K44')->applyFromArray($styleArray);
            }
        ];
    }
}
