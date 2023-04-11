<?php

namespace App\DataTables;

use Carbon\Carbon;
use Mediconesystems\LivewireDatatables\Column;

class DatetimeColumn extends Column
{
    public $type = 'datetime';
    public $callback;

    // this must be done.
    public function __construct()
    {
        $this->format();
    }

    public function format($format = null)
    {
        $this->callback = function ($value) use ($format) {
            return $value ? Carbon::parse($value)->format($format ?? config('livewire-datatables.default_datetime_format')) : null;
        };

        return $this;
    }
}