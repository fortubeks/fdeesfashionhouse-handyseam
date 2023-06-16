<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class Payment extends Model
{
    use HasFactory;

    static function getPayments($order_id){
        $invoice_id = Order::find($order_id)->invoice->id;
        $payments = Payment::where('invoice_id',"{$invoice_id}")->paginate(10);
        return $payments;
    }
    public function invoice()
    {
        return $this->belongsTo('App\Models\Invoice');
    }
}
