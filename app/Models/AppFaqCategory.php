<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppFaqCategory extends Model
{
    use HasFactory;

    protected $guarded = [''];
    
    public function faqs(){
         return $this->hasMany(AppFaq::class,'category_id','id')->where('status',1);
    }
}
