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

    public function payment_account(){
        $group = $this->group;
        $account = PaymentAccount::findOrFail($group->payment_account_id);
        return $account;
    }

    public function sum(){
        return Credit::where('user_id', $this->id)->sum('credits'); 
    }

    public function purchased(){
        return Credit::where('user_id', $this->id)->whereNotNull('stripe_id')->sum('credits'); 
    }

    public function spent(){
        return Credit::where('user_id', $this->id)->where('credits', '<', 0)->whereNotNull('file_id')->sum('credits'); 
    }

    public function total_credits(){
        return Credit::where('user_id', '=', $this->id)->sum('credits');
    }

    public function amount(){
        return Credit::where('user_id', $this->id)->sum('price_payed'); 
    }

    public function credits(){
        return $this->hasMany(Credit::class)->orderby('created_at', 'desc'); 
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
