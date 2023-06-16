<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }
    public function payments()
    {
        return $this->hasMany('App\Models\Payment');
    }
    public function getOutstandingBalance(){
        return $this->order->total_amount - $this->payments->sum('amount');
    }
    public function getAmountPaid(){
        //check all payments that have the invoice number
        $payments = Payment::where('invoice_id', $this->id)->sum('amount');
        return $payments;
    }
}
