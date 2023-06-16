<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutfitsOrders extends Model
{
    use HasFactory;
    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }
    public function tailor(){
        return $this->belongsTo('App\Models\Staff', 'staff_id');
    }
    public function customer(){
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }
    public function items_used(){
        return $this->hasMany('App\Models\ItemsUsed');
    }
    public function getTotalAmount(){
        return $this->qty * $this->price;
    }

}
