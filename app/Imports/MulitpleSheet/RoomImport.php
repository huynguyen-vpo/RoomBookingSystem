<?php

namespace App\Imports\MulitpleSheet;

use Maatwebsite\Excel\Concerns\ToModel;

class RoomImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return Room|null
     */
    public function model(array $row)
    {
        logger($row);
    }
}
