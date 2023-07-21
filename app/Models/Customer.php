<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Customer extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

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
}
