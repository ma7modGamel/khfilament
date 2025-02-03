<?php

namespace App\Imports;

use App\Models\Item;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ItemsImport implements ToModel, WithHeadingRow, WithCalculatedFormulas,SkipsEmptyRows
{
    use Importable ;
    protected int $rowNumber = 0;

    /**
     * @param array $row
     *
     * @return Model|null
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */

    public function model(array $row)
    {
        $this->rowNumber++;
        if ($this->rowNumber == 1) {
            return null; // Skip the first row
        }
        return new Item([
            'number' => $row['num'],
            'description' => $row['des'],
            'unit' => $row['unit'],
            'quantity' => $row['quntity'],
            'unit_price' => $row['singleprice'],
            'total_price' => $row['totalprice'],
            'discount_percentage' => $row['profitspercentatge'],
            'real_price_item' => $row['totalpricewithoutprofits'],
            'project_id' => session()->get('project_id'),
        ]);
    }

}
