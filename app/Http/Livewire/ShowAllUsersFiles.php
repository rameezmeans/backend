<?php

namespace App\Http\Livewire;

use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ShowAllUsersFiles extends LivewireDatatable
{

    public function builder()
    {
        dd($this->id);
    }

    public function columns()
    {
        //
    }
}