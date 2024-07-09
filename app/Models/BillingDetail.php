<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingDetail extends Model
{
    use HasFactory;
    
    protected $guarded = [''];

    public function subcategory(){
        return $this->belongsTo(SubCategory::class,'category_id','id');
    }

}
