<?php

namespace App\Http\Livewire;

use App\Models\Credit;
use Livewire\Component;

use Illuminate\Database\Eloquent\Builder;

use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;

class NewPaymentLog extends Component
{

    use Tables\Concerns\InteractsWithTable;

    public function render()
    {
        return view('livewire.new-payment-log');
    }

    // protected function getTableQuery(): Builder
    // {
    //     return Credit::query()->where('credits', '>', 0)->where('price_payed', '>', 0);
    // }

    public function table(Table $table): Table
    {
        return $table
        ->query(Credit::query()->where('credits', '>', 0)->where('price_payed', '>', 0))
        ->columns([
           
        ]);
    }
}
