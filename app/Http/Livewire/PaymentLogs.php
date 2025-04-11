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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
// use Rappasoft\LaravelLivewireTables\Views\NumberColumn;

class PaymentLogs extends DataTableComponent
{

    protected $model = Credit::where('credits', '>', 0)->where('price_payed', '>', 0);

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    // public function builder()
    // {
    //     $allPayments = Credit::where('credits', '>', 0)->where('price_payed', '>', 0);

    //     return $allPayments;
    // }

    public function columns() : array
    {
        return [

            Column::make('id')->label('Payment ID'),
            Column::make('invoice_id')->label('Invoice ID')->searchable(),
            Column::callback(['front_end_id'], function($frontEndID){
                if($frontEndID == 1){
                    return '<span class="label bg-primary text-white">'.FrontEnd::findOrFail($frontEndID)->name.'</span>';
                }
                if($frontEndID == 3){
                    return '<span class="label bg-info text-white">'.FrontEnd::findOrFail($frontEndID)->name.'</span>';
                }
                else{
                    return '<span class="label bg-warning">'.FrontEnd::findOrFail($frontEndID)->name.'</span>';
                }
            })->label('Front End')
            ->filterable(FrontEnd::get(['id', 'name']))
            ->searchable(),

            Column::callback(['user_id', 'front_end_id'], function($userId,$frontEndID){
                // if($frontEndID == 1){
                    // return '<span class="label bg-primary text-white">'.code_to_country(User::findOrFail($userId)->country).'</span>';
                    return code_to_country(User::findOrFail($userId)->country);
                // }
                // else if($frontEndID == 3){
                //     return '<span class="label bg-info text-white">'.code_to_country(User::findOrFail($userId)->country).'</span>';
                // }
                // else if($frontEndID == 2){
                //     return '<span class="label bg-warning">'.code_to_country(User::findOrFail($userId)->country).'</span>';
                // }
            })->label('Country')->sortable(),

            Column::callback(['type'], function($type){
                if($type != ''){
                    return ucfirst($type);
                }
                else{
                    return "Admin";
                }
                    
            })->label('Type'),

            DatetimeColumn::make('created_at')
                ->label('Payment Date')->sortable()->format('d/m/Y h:i A')->filterable(),
            
            
            Column::make('customer')->label('Customer')->searchable(),
            Column::make('email')->label('Email')->searchable(),
            Column::make('group')->label('Group')->searchable(),
            Column::make('credits')->label('Credits'),
            Column::callback(['price_payed'], function($pricePayed){
                
                return '$'.$pricePayed;
                    
            })->label('Price'),

            Column::callback(['id','group'], function($id){
                
                return '<a class="btn btn-warning text-black" target="_blank" href="'.route("payment-details", $id).'">Payment Details</a>';
                    
            })->label('Details'),
            Column::callback(['elorus_permalink'], function($elorusPermalink){
                if($elorusPermalink){
                    return '<a class="btn btn-warning text-black" target="_blank" href="'.$elorusPermalink.'">Go To Elorus</a>';
                }
                    
            })->label('Elorus'),
            Column::callback(['zohobooks_id'], function($zohobooksID){
                if($zohobooksID){
                    return '<a class="btn btn-warning text-black" target="_blank" href="'.'https://books.zoho.com/app/8745725#/invoices/'.$zohobooksID.'">Go To Zohobooks</a>';
                }
                    
            })->label('Zohobooks'),
        ];
    }
}