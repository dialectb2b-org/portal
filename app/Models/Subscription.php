<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $guarded = [''];
    
    public function company(){
        return $this->belongsTo(Company::class,'company_id','id');
    }
    
    public function package(){
        return $this->belongsTo(Package::class,'package_id','id');
    }
}
