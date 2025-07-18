<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function file(){
        return $this->belongsTo(File::class);
    }

    public function my_group(){
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

    public function log(){
        return $this->hasOne(PaymentLog::class, 'payment_id', 'id');
    }

    public function running_total($user){
        return Credit::where('user_id', $user->id)->where('created_at','<=', $this->created_at)->sum('credits');
    }

    public function paypal(){
        return $this->hasOne(PaypalRecord::class, 'credit_id', 'id');
    }

    public function payment(){
        
        $paypalRecord = $this->hasOne(PaypalRecord::class, 'credit_id', 'id');
        if($paypalRecord->first()){
            return $paypalRecord;
        }
        else{
            
            return $this->hasOne(StripeRecord::class, 'credit_id', 'id');
        }
    }


    public function elorus(){
        return $this->hasOne(ElorusRecord::class, 'credit_id', 'id');
        
    }
    
    public function zoho(){
        return $this->hasOne(ZohoRecord::class, 'credit_id', 'id');
        
    }


    public function elorus_able(){

        $user = User::findOrFail($this->user_id);

        if($user->test){
            return false;
        }

        if($this->type == 'paypal'){
            return $user->paypal_payment_account()->elorus;
        }
        else{
            return $user->stripe_payment_account()->elorus;
        }

    }
}
