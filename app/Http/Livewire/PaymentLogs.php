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
        $allPayments = Credit::orderBy('created_at', 'desc')->where('price_payed', '>', 0)->orWhere('gifted', 1)->where('credits', '>', 0);

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

            Column::callback(['user_id, front_end_id'], function($userid, $frontEndID){
                if($frontEndID == 1){
                    return '<span class="label bg-primary text-white">'.code_to_country(User::findOrFail($userid)->country).'</span>';
                }
                else if($frontEndID == 3){
                    return '<span class="label bg-info text-white">'.code_to_country(User::findOrFail($userid)->country).'</span>';
                }
                else if($frontEndID == 2){
                    return '<span class="label bg-warning">'.code_to_country(User::findOrFail($userid)->country).'</span>';
                }
            })->label('Country'),
            
        ];
    }
}