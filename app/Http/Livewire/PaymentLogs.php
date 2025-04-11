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
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;

class PaymentLogs extends LivewireDatatable
{
    public function builder()
    {
        $allPayments = Credit::where('credits', '>', 0)->where('price_payed', '>', 0);

        return $allPayments;
    }

    public function columns()
    {
        return [

            NumberColumn::name('id')->label('Payment ID'),
            Column::name('invoice_id')->label('Invoice ID')->searchable(),
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
               
                    return code_to_country(User::findOrFail($userId)->country);
                
            })->label('Country'),

            Column::callback(['type'], function($type){
                if($type != ''){
                    return ucfirst($type);
                }
                else{
                    return "Admin";
                }
                    
            })->label('Type'),

            DatetimeColumn::name('created_at')
                ->label('Payment Date')->sortable()->format('d/m/Y h:i A')->filterable(),
            
            
            Column::name('customer')->label('Customer')->searchable(),
            Column::name('email')->label('Email')->searchable(),
            Column::name('group')->label('Group')->searchable(),
            Column::name('credits')->label('Credits'),
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