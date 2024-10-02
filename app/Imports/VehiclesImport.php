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
            "Name" => $row[0],
            "Make" => $row[1],
            "Model" => $row[2],
            "Generation" => $row[3],
            "Engine" => $row[4],
            "Engine_ECU" => $row[5],
            "Engine_URL" => $row[6],
            "Brand_image_URL" => $row[7],
            "type" => $row[8],
            "BHP_standard" => $row[9],
            "BHP_tuned" => $row[10],
            "BHP_difference" => $row[11],
            "TORQUE_standard" => $row[12],
            "TORQUE_tuned" => $row[13],
            "TORQUE_difference" => $row[14],
            "Type_of_fuel" => $row[15],
        ]);
    }
}
