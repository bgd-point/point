<?php

namespace App\Imports\Master;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\HeadingRowImport;

class MasterItem implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public $data;
    public function collection(Collection $collection)
    {

        $created_at = date('Y-m-d H:i:s');
        foreach ($collection as $index => $row) {
            $date=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['expired_date'])->format('Y-m-d') ?? null;
            if ($row->filter()->isNotEmpty()) {
                $row['expired_date']=$date;
                $this->data[]=$row;
            }
        }
    }

    public function getData(){
        return $this->data;
    }
}
