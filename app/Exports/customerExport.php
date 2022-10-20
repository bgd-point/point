<?php

namespace App\Exports;

use App\Model\Master\Customer;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class customerExport implements FromCollection, WithHeadings, WithCustomStartCell, WithColumnFormatting, WithEvents, ShouldAutoSize, WithMapping {
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $rowCount;
    protected $tenantName;

    public function __construct() {
        $this->rowCount = 0;
        $this->tenantName = tenant(auth()->user()->id)->full_name;
    }

    public function collection() {
        $customers =  Customer::from(Customer::getTableName() . ' as ' . Customer::$alias)->with(['groups', 'pricingGroup'])->whereIn('branch_id', tenant(auth()->user()->id)->branches->pluck('id'))->get();
        $this->rowCount = $customers->count();
        return $customers;
    }

    public function headings(): array {
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

    public function map($row): array {
        return [
            $row->id ?? "",
            $row->code ?? "",
            $row->name ?? "",
            $row->email ?? "",
            $row->phone ?? "",
            $row->address ?? "",
            $row->credit_limit ?? "",
            $row->pricingGroup->label ?? "",
            implode(',', $row->groups->pluck('name')->toArray()) ?? "",
        ];
    }

    public function startCell(): string {
        return 'C5';
    }

    public function columnFormats(): array {
        return [
            'C' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    public function registerEvents(): array {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {

                $event->sheet->getDelegate()->mergeCells('C4:K4')->setCellValue('C4', $this->tenantName);
                $event->sheet->getDelegate()->getStyle('C4:K4')->getAlignment()->setHorizontal('center');
                $event->sheet->getDelegate()->setCellValue('A2', 'Date Export :');
                $event->sheet->getDelegate()->setCellValue('B2', Carbon::now()->format("d F Y H:i"));
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
                $event->getSheet()->getDelegate()->getStyle('C5:K' . (5 + $this->rowCount))->applyFromArray($styleArray);
            }
        ];
    }
}
