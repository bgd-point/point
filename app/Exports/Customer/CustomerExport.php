<?php

namespace App\Exports\Customer;

use App\Model\Master\Customer;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;

class CustomerExport implements FromCollection, WithEvents, WithHeadings,WithCustomStartCell,ShouldAutoSize,WithMapping
{
    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        return Customer::from(Customer::getTableName().' as '.Customer::$alias)->with(['groups','pricingGroup'])->whereIn('branch_id',tenant(auth()->user()->id)->branches->pluck('id'))->get();
    }
    /**
     * All headings
     * @return string[]
     */
    public function headings(): array
    {
        return [
            'no',
            'customer code',
            'customer name',
            'email',
            'phone',
            'address',
            'credit limit',
            'pricing group',
            'customer group'
        ];
    }

    /**
     * Events to add custom format and style
     * @return Closure[]
     */
    public function registerEvents(): array {

        return [
            BeforeSheet::class=>function(BeforeSheet $event){
                $sheet = $event->sheet;
                $sheet->append( array(
                    'Date Export :', Carbon::now()->toDayDateTimeString(),
                ),'A2');
            },
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;

                $sheet->mergeCells('C4:K4');
                $sheet->setCellValue('C4', "Nama Tenant");

                $styleArray = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                $cellRange = 'C4:K4'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
            },
        ];
    }

    /**
     * Adds custom colum mapping
     * @param $row
     * @return array
     */
    public function map($row): array
    {
        return [
           $row->id ?? "",
            $row->code ?? "",
            $row->name ?? "",
            $row->email ?? "",
            $row->phone ?? "",
            $row->address ?? "",
            $row->credit_limit ?? "",
            implode(',',$row->groups->pluck('name')->toArray()) ?? "",
            $row->pricingGroup->label ?? "",
        ];
    }

    /**
     * Defines custom start cell
     * @return string
     */
    public function startCell(): string
    {
        return 'C5';
    }
}
