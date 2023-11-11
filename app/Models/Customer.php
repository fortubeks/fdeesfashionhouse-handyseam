<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Customer extends Model
{
    use HasFactory, Notifiable, SoftDeletes;
    protected $fillable = ['name', 'address', 'phone', 'email', 'user_id'];

    public function routeNotificationForWhatsApp()
    {
        return $this->phone;
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order')->orderBy('created_at','desc');
    }

    public function measurement()
    {
        return $this->hasOne('App\Models\Measurement');
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\Customer','parent_id');
    }

    public function relatives()
    {
        return $this->hasMany('App\Models\Customer','parent_id');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country','country_id');
    }

    public function phoneCode(){
        if($this->country){
            return $this->country->name;
        } 
    }

    public static function getAll(){
        //get a list of all customers
        $customers = Customer::orderBy('created_at','desc')->
        where('user_id','=', auth()->user()->user_account_id)->paginate(10);
        return $customers;
   }

   public function getTotalNumberOfOrders(){
       $orders = $this->orders;
       return count($orders);
   }

   public function getTotalAmountOnAllOrders(){
    $orders = $this->orders;
    return formatCurrency($orders->sum('total_amount'));
    } 
    
    public function getUniqueID(){
        return auth()->user()->user_account_id . '$' . $this->id;
    }

    public function whatsappNumber(){
        //if number has space remove it
        $whatsapp_number = removeSpaces($this->phone);
        //if number has 0 as first character remove it
        $whatsapp_number = ltrim($whatsapp_number, '0');
        //if number has + remove it
        if(substr($whatsapp_number, 0, 1) === '+'){
            return ltrim($whatsapp_number, '+');
        }
        if($this->country){
            return $this->country->phonecode.$whatsapp_number;
        }
        else{
            if(auth()->user()->user_account->app_settings->business_currency){
                $country = Country::where('iso',substr(auth()->user()->user_account->app_settings->business_currency, 0, -1))->first();
                if($country){
                    return $country->phonecode.$whatsapp_number;
                }
                else{
                    return $whatsapp_number;
                }
            }
            
        }
        //if customer has country code, add country tel code and return
        //if customer not have country code, use user currency to get counrty and code

    }
}
