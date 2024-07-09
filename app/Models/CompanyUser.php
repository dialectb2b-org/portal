<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class CompanyUser extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = [''];

    public function company(){
        return $this->belongsTo(Company::class,'company_id','id');
    }
    
    public function paid_activities()
    {
        return $this->belongsToMany(SubCategory::class, 'company_activities', 'user_id', 'activity_id')->where('is_previlaged',0)->withPivot('expiry_date');
    }
}
