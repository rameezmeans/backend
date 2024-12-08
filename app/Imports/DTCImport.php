<?php

namespace App\Imports;

use App\Models\DTCLookup;
use Maatwebsite\Excel\Concerns\ToModel;

class DTCImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    
    public function model(array $row) {
        
        return new DTCLookup([
            "code" => $row[0],
            "desc" => $row[1],
        ]);
    }
}
