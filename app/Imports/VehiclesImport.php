<?php

namespace App\Imports;

use App\Models\Vehicle;
use Maatwebsite\Excel\Concerns\ToModel;

class VehiclesImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Vehicle([
            "Name" => $row[0].' '. $row[1].' '.$row[3],
            "Make" => $row[0],
            "Model" => $row[1],
            "Generation" => $row[2],
            "Engine" => $row[3],
            "Engine_ECU" => $row[4],
            "Engine_URL" => $row[5],
        ]);
    }
}
