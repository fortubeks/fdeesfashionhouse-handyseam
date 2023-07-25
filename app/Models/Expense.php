<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Expense extends Model
{
    use HasFactory;
    public function category()
    {
        return $this->belongsTo('App\Models\ExpenseCategory')->withTrashed();
    }

    public function category_details()
    {
        $expense_category = ExpenseCategory::where('id','=', $this->expense_category_id)->first();
        return $expense_category;
    }
    
    public static function getAll(){
        //get a list of all items in sinventory
        $expenses = Expense::orderBy('created_at','desc')->
        where('user_id','=', auth()->user()->user_account_id)->paginate(15);
        return $expenses;
   }
}
