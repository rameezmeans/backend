<?php

namespace App\Http\Livewire;

use App\Models\OriginalFile;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class OriginalFilesTable extends LivewireDatatable
{
    public function builder()
    {
        $originalFiles = OriginalFile::OrderBy('created_at', 'asc');

        return $originalFiles;
    }

    public function columns()
    {
        return [

            Column::name('Producer')
            ->label('Producer'),
            
            Column::name('Series')
            ->label('Series'),

            Column::name('Model')
            ->label('Model'),

        ];
    }
}