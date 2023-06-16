<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemsUsed extends Model
{
    use HasFactory;
    protected $fillable = [
        'outfits_orders_id',
        'item_id',
        'qty',
        'unit_cost',
        'amount'
    ];

    public function outfit(){
        return $this->belongsTo('\App\Models\OutfitsOrders','outfits_orders_id');
    }

    public function item(){
        return $this->belongsTo('\App\Models\Item');
    }
}
