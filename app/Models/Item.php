<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Item extends Model
{
    use HasFactory;

    public static function getAll(){
        //get a list of all items in sinventory
        $items = Item::orderBy('description','desc')->
        where('user_id','=', auth()->user()->user_account_id)->paginate(10);
        return $items;
   }
    public function category()
    {
        return $this->belongsTo('App\Models\ItemCategory');
    }

    public function items_used()
    {
        return $this->hasMany('App\Models\ItemsUsed');
    }

    public function purchases()
    {
        return $this->hasMany('App\Models\Purchase');
    }
}
