<?php

namespace App\Imports;

use App\Models\BoschNumber;
use Maatwebsite\Excel\Concerns\ToModel;

class BoschImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    
    public function model(array $row) {
        
        return new BoschNumber([
            "manufacturer_number" => $row[0],
            "ecu" => $row[1],
        ]);
    }
}
