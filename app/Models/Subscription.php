<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','expires_at','package_id'];
    public function package(){
        return $this->belongsTo('App\Models\Package');
    }
}
