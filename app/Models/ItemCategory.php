<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    use HasFactory;
    public $table = "item_categories";

    public static function getAll(){
        //get a list of all items in inventory
        $item_categories = ItemCategory::orderBy('name','desc')->
        where('user_id','=', auth()->user()->user_account_id)->paginate(10);
        return $item_categories;
   }
   public function items()
    {
        return $this->hasMany('App\Models\Item');
    }
}
