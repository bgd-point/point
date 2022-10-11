<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use Maatwebsite\Excel\Excel;


class McustomerExport implements WithEvents
{
    protected $customers;

    function __construct($customers)
    {
        $this->customers = $customers;
    }

    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function(BeforeExport $event){
                $rightStyle = [
                    'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                        ]
                    ];
                $borderStyle = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                            ],
                        ],
                    ];
    
                $borderRightStyle = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                            ],
                        ],
                    'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                        ],
                        'numberFormat' => [
                            'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
                        ]
                    ];

                    $event->writer->reopen(new LocalTemporaryFile(storage_path('template/export_customer.xlsx')),Excel::XLSX);

                    $event->writer->getSheetByIndex(1);
                    $writeSheet = $event->getWriter()->getSheetByIndex(1);
                    $this->addDataColumn('B2', date('j F Y H:i'), $writeSheet, $rightStyle);

                    $row = 6;
                    foreach ($this->customers as $customer) {
                        $this->addDataColumn('C'. $row, ($row - 5), $writeSheet, $borderStyle);
                        $this->addDataColumn('D'. $row, $customer->code, $writeSheet, $borderStyle);
                        $this->addDataColumn('E'. $row, $customer->name, $writeSheet, $borderStyle);
                        $this->addDataColumn('F'. $row, $customer->email, $writeSheet, $borderStyle);
                        $this->addDataColumn('G'. $row, '' . $customer->phone, $writeSheet, $borderStyle);
                        $this->addDataColumn('H'. $row, $customer->address, $writeSheet, $borderStyle);
                        $this->addDataColumn('I'. $row, $customer->credit_limit, $writeSheet, $borderRightStyle);
                        $this->addDataColumn('J'. $row, (is_null($customer->pricingGroup) ? '' : $customer->pricingGroup->label), $writeSheet, $borderStyle);
                        $this->addDataColumn('K'. $row, $this->getGroups($customer->groups), $writeSheet, $borderStyle);
                        
                        $row++;
                    }
                    return $writeSheet;
            }
         ];
    }

    private function addDataColumn($celLocation, $cellValue, $writeSheet, $styleArray) {
        $writeSheet->setCellValue($celLocation, $cellValue);
        $writeSheet->getStyle($celLocation)->ApplyFromArray($styleArray);
    }

    private function getGroups($groups) {
        if (is_null($groups) || sizeof($groups) == 0) {
            return  '';
        }

        $groupList = '';
        foreach ($groups as $group) {
            $groupList .= $group->name . ',';
        }
        return substr($groupList, 0, strlen($groupList) - 1);
    }
}