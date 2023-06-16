<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Staff extends Model
{
    use HasFactory;

    public $table = "staffs";

    public static function getAll(){
        //get a list of all items in sinventory
        $staffs = Staff::orderBy('created_at','desc')->
        where('user_account_id','=', auth()->user()->user_account_id)->paginate(10);
        return $staffs;
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function getFullName(){
        return $this->first_name . " " . $this->last_name;
    }
}
