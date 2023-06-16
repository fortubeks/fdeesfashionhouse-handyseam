<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseCategory extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $table = "expense_categories";

    public static function getAll(){
        $expense_categories = DB::table('expense_categories')->where('user_id','=', auth()->user()->user_account_id)->get();
        return $expense_categories;
   }
   public function expenses()
    {
        return $this->hasMany('App\Models\Expense');
    }
}
