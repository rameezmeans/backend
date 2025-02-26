<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'language',
        'address',
        'zip',
        'city',
        'country',
        'status',
        'company_name',
        'company_id',
        'front_end_id',
        'evc_customer_id',
        'slave_tools_flag',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function stripe_payment_account(){
        if($this->subdealer_group_id){
            $group = SubdealerGroup::findOrFail($this->subdealer_own_group_id);
            $account = PaymentAccount::findOrFail($group->stripe_payment_account_id);
        }
        else{
            $group = $this->group;
            $account = PaymentAccount::findOrFail($group->stripe_payment_account_id);
        }
        return $account;
    }

    public function paypal_payment_account(){
        if($this->subdealer_group_id){
            $group = SubdealerGroup::findOrFail($this->subdealer_own_group_id);
            $account = PaymentAccount::findOrFail($group->paypal_payment_account_id);
        }
        else{
            $group = $this->group;
            $account = PaymentAccount::findOrFail($group->paypal_payment_account_id);
        }
        return $account;
    }

    public function sum(){
        return Credit::where('user_id', $this->id)->where('is_evc', 0)->sum('credits'); 
    }

    public function purchased(){
        return Credit::where('user_id', $this->id)->whereNotNull('stripe_id')->sum('credits'); 
    }

    public function spent(){
        return Credit::where('user_id', $this->id)->where('is_evc', 0)->where('credits', '<', 0)->whereNotNull('file_id')->sum('credits'); 
    }

    public function total_credits(){
        return Credit::where('user_id', '=', $this->id)->where('is_evc', 0)->sum('credits');
    }

    public function amount(){
        return Credit::where('user_id', $this->id)->where('is_evc', 0)->sum('price_payed'); 
    }

    public function credits(){
        return $this->hasMany(Credit::class)->where('is_evc', 0)->orderby('created_at', 'desc'); 
    }
    
    public function tools_slave(){
        return $this->hasMany(UserTool::class, 'user_id', 'id')->where('type', 'slave'); 
    }

    public function is_admin(){
        
        if(Role::findOrFail($this->role_id)->name == 'admin'){
            return true;
        }
        else{
            return false;
        }
    }

    public function admin_payments(){
        return $this->hasMany(Credit::class)->where('credits', '>', 0)->where('gifted', 1)->where('price_payed', 0); 
    }

    public function all_payments(){
        return $this->hasMany(Credit::class)->orderBy('created_at', 'desc')->where('credits', '>', 0); 
    }

    public function payment_logs(){
        
        return $this->hasMany(PaymentLog::class)->orderBy('created_at', 'desc'); 
    
    }

    public function elorus_payments(){
        
        return $this->hasMany(Credit::class)
        ->where('credits', '>', 0)
        ->whereNotNull('elorus_id')
        ->where('price_payed','>', 0); 
    
    }

    public function non_elorus_payments(){
        
        return $this->hasMany(Credit::class)
        ->where('credits', '>', 0)
        ->whereNull('elorus_id')
        ->where('price_payed','>', 0); 
    
    }

    public function is_customer(){
        
        if(Role::findOrFail($this->role_id)->name == 'customer'){
            return true;
        }
        else{
            return false;
        }
    }

    public function is_head(){
        
        if(Role::findOrFail($this->role_id)->name == 'head'){
            return true;
        }
        else{
            return false;
        }
    }

    public function is_engineer(){
        
        if(Role::findOrFail($this->role_id)->name == 'engineer'){
            return true;
        }
        else{
            return false;
        }
    }

    public function tools_master(){
        return $this->hasMany(UserTool::class, 'user_id', 'id')->where('type', 'master'); 
    }
    
    public function group(){
        return $this->belongsTo(Group::class); 
    }

    public function frontend(){
        return $this->belongsTo(FrontEnd::class, 'front_end_id', 'id'); 
    }
}
