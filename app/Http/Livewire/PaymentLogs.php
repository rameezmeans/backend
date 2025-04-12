<?php

namespace App\Http\Livewire;

use App\Models\Credit;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use App\DataTables\DatetimeColumn;
use App\Models\AlientechFile;
use App\Models\File;
use App\Models\FrontEnd;
use App\Models\Key;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
// use Illuminate\Contracts\Database\Eloquent\Builder;
// use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
// use Rappasoft\LaravelLivewireTables\DataTableComponent;
// use Rappasoft\LaravelLivewireTables\Views\Column;
// use Rappasoft\LaravelLivewireTables\Views\DateColumn;
// use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectFilter;
// use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

// use Rappasoft\LaravelLivewireTables\Views\NumberColumn;

use Filament\Tables;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class PaymentLogs extends Component implements Tables\Contracts\HasTable
{

    use Tables\Concerns\InteractsWithTable;

    protected function getTableQuery(): Builder
    {
        return Credit::query()->where('credits', '>', 0)->where('price_payed', '>', 0);
    }

    public function render(): View
    {
        return view('payment_logs.all_payments_live');
    }

    // protected $model = Credit::class;

    // public function builder(): Builder
    // {
    //     return Credit::query()->where('credits', '>', 0)->where('price_payed', '>', 0);
    // }

    // public function configure(): void
    // {
    //     $this->setPrimaryKey('id');
    // }

    // public function builder()
    // {
    //     $allPayments = Credit::where('credits', '>', 0)->where('price_payed', '>', 0);

    //     return $allPayments;
    // }

    // public function columns() : array
    // {
    //     return [

    //         Column::make('Payment ID', 'id'),
    //         Column::make('Invoice ID', 'invoice_id'),

    //         Column::make('Frontend', 'front_end_id'),
                
    //         // Column::callback(['front_end_id'], function($frontEndID){
    //         //     if($frontEndID == 1){
    //         //         return '<span class="label bg-primary text-white">'.FrontEnd::findOrFail($frontEndID)->name.'</span>';
    //         //     }
    //         //     if($frontEndID == 3){
    //         //         return '<span class="label bg-info text-white">'.FrontEnd::findOrFail($frontEndID)->name.'</span>';
    //         //     }
    //         //     else{
    //         //         return '<span class="label bg-warning">'.FrontEnd::findOrFail($frontEndID)->name.'</span>';
    //         //     }
    //         // }),
            

    //         // Column::callback(['user_id', 'front_end_id'], function($userId,$frontEndID){
    //         //     // if($frontEndID == 1){
    //         //         // return '<span class="label bg-primary text-white">'.code_to_country(User::findOrFail($userId)->country).'</span>';
    //         //         return code_to_country(User::findOrFail($userId)->country);
    //         //     // }
    //         //     // else if($frontEndID == 3){
    //         //     //     return '<span class="label bg-info text-white">'.code_to_country(User::findOrFail($userId)->country).'</span>';
    //         //     // }
    //         //     // else if($frontEndID == 2){
    //         //     //     return '<span class="label bg-warning">'.code_to_country(User::findOrFail($userId)->country).'</span>';
    //         //     // }
    //         // })->label('Country')->sortable(),

    //         // Column::callback(['type'], function($type){
    //         //     if($type != ''){
    //         //         return ucfirst($type);
    //         //     }
    //         //     else{
    //         //         return "Admin";
    //         //     }
                    
    //         // })->label('Type'),

    //         // DateColumn::make('Payment Date', 'created_at'),
            
            
    //         // Column::make('customer')->label('Customer')->searchable(),
    //         // Column::make('email')->label('Email')->searchable(),
    //         // Column::make('group')->label('Group')->searchable(),
    //         // Column::make('credits')->label('Credits'),
    //         // Column::callback(['price_payed'], function($pricePayed){
                
    //         //     return '$'.$pricePayed;
                    
    //         // })->label('Price'),

    //         // Column::callback(['id','group'], function($id){
                
    //         //     return '<a class="btn btn-warning text-black" target="_blank" href="'.route("payment-details", $id).'">Payment Details</a>';
                    
    //         // })->label('Details'),
    //         // Column::callback(['elorus_permalink'], function($elorusPermalink){
    //         //     if($elorusPermalink){
    //         //         return '<a class="btn btn-warning text-black" target="_blank" href="'.$elorusPermalink.'">Go To Elorus</a>';
    //         //     }
                    
    //         // })->label('Elorus'),
    //         // Column::callback(['zohobooks_id'], function($zohobooksID){
    //         //     if($zohobooksID){
    //         //         return '<a class="btn btn-warning text-black" target="_blank" href="'.'https://books.zoho.com/app/8745725#/invoices/'.$zohobooksID.'">Go To Zohobooks</a>';
    //         //     }
                    
    //         // })->label('Zohobooks'),
    //     ];
    // }

    // public function filters(): array
    // {
    //     return [
    //         // SelectFilter::make('Frontend')
    //         //     ->options([
    
    //         //         Frontend::query()
                        
    //         //             ->get()
                        
    //         //             ->map(fn ($frontend) => $frontend->pluck('name', 'id')->filter())
    //         //             ->toArray(),
    //         //     ])
    //         //     ->filter(function(Builder $builder, string $value) {
    //         //         $builder->where('frontend.id', $value);
    //         //     }),
    //     ];
    // }
}