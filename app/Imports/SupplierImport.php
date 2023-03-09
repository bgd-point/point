<?php

namespace App\Imports;

use App\Model\Master\Supplier;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SupplierImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;
    private $rows = 0;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        ++$this->rows;
        return new Supplier([
            'code' => $row['code'],
            'name' => $row['name'],
            'email' => $row['email'],
            'address' => $row['address'],
            'phone' => $row['phone'],
        ]);
    }

    public function rules(): array
    {
        return [
            'code' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'phone' => 'required',
        ];
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }
}
