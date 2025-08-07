<?php
namespace App\Http\Livewire\Traits;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArrayExport;

trait HasExports {

public $exportExcel = false;
public $objectName = "Export";

public function exportExcel()
{
    return Excel::download(new ArrayExport(
        array_map( array($this, 'collectionToArray'), $this->getQuery()->get()->toArray() )
        ), $this->objectName . '.xlsx');
    }

    protected function collectionToArray($i) {
        return json_decode(json_encode($i),true);
    }

}
